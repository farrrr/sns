<?php
/**
 * 標籤模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class TagModel extends Model
{
	protected $tableName = 'tag';
	protected $fields = array(0=>'tag_id',1=>'name','_pk'=>'tag_id');

	private $_app = null;					// 所屬應用
	private $_app_table = null;				// 所屬資源表

	/**
	 * 設定所屬應用
	 * @param string $app 應用名稱
	 * @return object 標籤物件
	 */
	public function setAppName($app)
	{
		$this->_app = $app;
		return $this;
	}

	/**
	 * 設定相關內容所存儲的表格
	 * @param string $app_table 資料表名
	 */
	public function setAppTable($app_table)
	{
		$this->_app_table = $app_table;
		return $this;
	}

	/**
	 * 通過指定的應用資源ID，獲取應用的內容標籤
	 * @param array|integer $row_ids 應用內容編號
	 * @return array 標籤內容列表
	 */
	public function getAppTags($row_ids)
	{
		$map['row_id'] = array('IN', $row_ids);
		$map['table'] = $this->_app_table;
		$app_tags = D('app_tag')->where($map)->findAll();
		// 獲取標簽名稱
		$names = $this->getTagNames(getSubByKey($app_tags, 'tag_id'));
		// 重組結果
		foreach($app_tags as $a_t_k => $a_t_v) {
			$result[$a_t_v['row_id']][$a_t_v['tag_id']] = $names[$a_t_v['tag_id']];
			unset($app_tags[$a_t_k]);
		}
		if(is_numeric($row_ids)) {
			return $result[$row_ids];
		} else {
			return $result;
		}
	}

	/**
	 * 設定指定應用下的應用內容標籤
	 * @param integer $row_id 應用內容編號
	 * @param array $tags 標籤
	 * @param integer $max 最多標籤數量
	 * @return boolean 是否設定成功
	 */
	public function setAppTags($row_id, $tags, $max = 9) {
		$row_id = intval($row_id);
		if(!$this->_app || !$this->_app_table) {
			$this->error = L('PUBLIC_WRONG_DATA');			// 錯誤的參數
			return false;
		} else if(!$row_id) {
			$this->error = L('PUBLIC_WRONG_DATA');			// 錯誤的參數
			return false;
		}
		// 標籤
		if(!$tags) {
			$tags = array();
		} else if(is_string($tags)) {
			$tags = explode(',', preg_replace('/[，,]+/u', ',', $tags));
		} else if(!is_array($tags)) {
			$this->error = L('PUBLIC_WRONG_DATA');			// 錯誤的參數
			return false;
		}
		// 限制最大標籤數
		$tags = array_slice($tags, 0, intval($max));
		// 刪除歷史設定
		$del_map['table'] = $this->_app_table;
		$del_map['row_id'] = $row_id;
		$res = D('app_tag')->where($del_map)->delete();
		// 添加新設定
		$data = array();
		foreach($tags as $t_i_v) {
			$tag_id = $this->getTagId($t_i_v);
			$data[] = "('{$this->_app}', '{$this->_app_table}', {$row_id}, {$tag_id})";
		}
		$data = implode(',', $data);

		$sql = 'INSERT INTO '.$this->tablePrefix.'app_tag("app", "table", "row_id", "tag_id") VALUES'.$data;
		(false !== $res) && $res = $this->query($sql);
		return false !== $res;
	}

	/**
	 * 一次添加多個應用內容的標籤
	 * @param integer $row_id 應用內容編號
	 * @param string $tags 標籤
	 * @return boolean 是否添加成功
	 */
	public function addAppTags($row_id, $tags)
	{
		if(!is_array($tags)) {
			$tags = str_replace('，', ',', $tags);
			$tags = explode(',', $tags);
		}
		// 獲取結果集
		$r = array();
		foreach($tags as $t) {
			if(empty($t)) {
				continue;
			}
			if($id = $this->addAppTag($row_id, $t)) {
				$r[] = array('tag_id'=>$id, 'name'=>$t);
			}
		}

		return $r;
	}

	/**
	 * 添加應用內容的標籤
	 * @param integer $row_id 應用內容編號
	 * @param string $tag 標籤
	 * @return boolean 是否添加成功
	 */
	public function addAppTag($row_id, $tag)
	{
		$data['app'] = $this->_app;
		$data['table'] = $this->_app_table;
		$data['row_id'] = intval($row_id);
		$data['tag_id'] = $this->getTagId($tag);
		if(empty($row_id)) {
			$ids = model('Cache')->get('temp_'.$data['table'].$GLOBALS['ts']['mid']);

			if(!empty($ids)) {
				if(in_array($data['tag_id'],$ids)){
					$this->error = L('PUBLIC_TAG_EXIST');			// 標籤已經存在
					return false;
				} else {
					$ids[] = $data['tag_id'];
				}
			} else {
				$ids = array($data['tag_id']);
			}
			$this->error = L('PUBLIC_TAG').L('PUBLIC_ADD_SUCCESS');			// 標籤，添加成功
			model('Cache')->set('temp_'.$data['table'].$GLOBALS['ts']['mid'], $ids, 60);
			return $data['tag_id'];
		}

		if($data['tag_id'] && 0 == $result = D('app_tag')->where($data)->count()) {
			$result = D('app_tag')->add($data);
			if ($result){
				$this->error = L('PUBLIC_TAG').L('PUBLIC_ADD_SUCCESS');		// 標籤，添加成功
				return $data['tag_id'];
			} else {
				$this->error =  L('PUBLIC_TAG').L('PUBLIC_ADD_FAIL');		// 標籤，添加失敗
				return false;
			}
		} else {
			$this->error = L('PUBLIC_TAG_EXIST');			// 標籤已經存在
			return false;
		}
	}

	/**
	 * 刪除應用內容的標籤
	 * @param integer $row_id 應用內容編號
	 * @param integer $tag_id 標籤編號
	 * @return boolean 是否刪除成功
	 */
	public function deleteAppTag($row_id, $tag_id) {
		$map['table'] = $this->_app_table;
		$map['row_id'] = intval($row_id);
		$map['tag_id'] = intval($tag_id);
		if(empty($map['row_id'])) {
			$ids = model('Cache')->get('temp_'.$map['table'].$GLOBALS['ts']['mid']);
			if(!empty($ids)){
				if(in_array($map['tag_id'],$ids)){
					unset($ids[array_search($map['tag_id'], $ids)]);
					model('Cache')->set('temp_'.$map['table'].$GLOBALS['ts']['mid'],$ids);
				}
			}
			$this->error = L('PUBLIC_DELETE_FAIL');				// 刪除失敗
			return  true;

		}
		if(false !== D('app_tag')->where($map)->delete()) {
			$this->error = L('PUBLIC_TAG').L('PUBLIC_DELETE_SUCCESS');			// 刪除成功
			return true;
		} else {
			$this->error = L('PUBLIC_TAG').L('PUBLIC_DELETE_FAIL');				// 刪除失敗
			return false;
		}
	}

	/**
	 * 通過標簽名稱，獲取標籤編號
	 * @param string $name 標籤
	 * @return integer 標籤編號
	 */
	public function getTagId($name) {
		// $name = t(getShort(preg_replace('/^[\s　]+|[\s　]+$/', '', $name), 15));
		$name = getShort(t($name), 15);
		if(!$name || !is_string($name)) {
			$this->error = 'Tag_Empty';
			return false;
		}
		$result = $this->getField('`tag_id`', "`name` = '{$name}'");
		if (!$result){
			$result = $this->add(array('name' => $name));
		}
		return $result;
	}

	/**
	 * 通過標籤編號，獲取標籤內容
	 * @param array $tag_ids 標籤ID陣列
	 * @return array 標籤內容列表
	 */
	public function getTagNames($tag_ids) {
		if(is_array($tag_ids)) {
			$tag_ids = implode(',', $tag_ids);
		}
		if(strpos($tag_ids, ',')) {
			$where = "`tag_id` IN ({$tag_ids})";
		} else if(intval($tag_ids) > 0) {
			$where = "`tag_id`={$tag_ids} ";
		} else {
			return '';
		}
		$sql = "SELECT `tag_id`,`name` FROM `{$this->tablePrefix}tag` WHERE {$where}";
		$result = $this->query($sql);
		$_result = array();
		foreach($result as $k => $v) {
			$_result[$v['tag_id']] = $v['name'];
			unset($result[$k]);
		}

		return $_result;
	}

	/**
	 * 獲取全局標籤列表 - 分頁型
	 * @param array $map 查詢條件
	 * @param string $field 顯示欄位名稱，多個用“,”分割
	 * @param string $order 排序條件，默認tag_id DESC
	 * @param integer $limit 結果集數目，默認為20
	 * @return array 全局標籤列表
	 */
	public function getTagList($map = null, $field = null, $order = 'tag_id DESC', $limit = 20) {
		$result = $this->field($field)->where($map)->order($order)->findPage($limit);
		return $result;
	}

	/**
	 * 獲取應用標籤列表 - 分頁型
	 * @param array $map 查詢條件
	 * @param integer $limit 結果集數目，默認為20
	 * @return array 應用列表標籤列表
	 */
	public function getAppTagList($map, $limit = 20) {
		$table = $this->tablePrefix.'app_tag AS a LEFT JOIN '.$this->tablePrefix.'tag AS b ON a.tag_id = b.tag_id';
		$result = $this->Table($table)->where($map)->findPage($limit);
		return $result;
	}

	/**
	 * 獲取應用標籤的Hash陣列
	 * @return array 應用標籤的Hash陣列
	 */
	public function getTableHash() {
		$list = $this->table($this->tablePrefix.'app_tag')->field('DISTINCT(`table`) AS t')->getAsFieldArray('t');
		$r = array();
		foreach($list as $v){
			$r[$v] = $v;
		}
		return $r;
	}

	/**
	 * 獲取熱門標籤
	 * @param integer $limit 結果集數目，默認為15
	 * @param integer $expire 快取時間，默認為3600
	 * @return array 熱門標籤列表
	 */
	public function getHotTags($limit = 15, $expire = 3600) {
		$hot_tag_list = array();
		$cache_id = $this->_app.$this->_app_table.'_hot_tag';
		if(($hot_tag_list = S($cache_id)) === false) {
			$limit = is_numeric($limit) ? $limit : 20;
			$hot_tag_ids = D('app_tag')->field("`tag_id`, COUNT(`tag_id`) AS `count`")
						   ->group("`tag_id`")->order('`count` DESC')
						   ->limit($limit)->findAll();
			// 獲得標籤文字
			$hot_names  = $this->getTagNames(getSubByKey($hot_tag_ids, 'tag_id'));
			$hot_tag_list = array();
			// 指針引用
			foreach($hot_tag_ids as $h_v) {
				$hot_tag_list[$h_v['tag_id']] = array(
					'name' => $hot_names[$h_v['tag_id']],
					'count' => $h_v['count'],
				);
			}
			unset($hot_tag_ids, $hot_tag_list);
			// 快取結果
			S($cache_id, $hot_tag_list, $expire);
		}
		return $hot_tag_list;
	}

	/**
	 * 添加全局標籤
	 * @param string $tags 標籤
	 * @return boolean 是否添加成功
	 */
	public function addTags($tags) {
		if(empty($tags)) {
			$this->error = L('PUBLIC_TAG_NOEMPTY');			// 標籤不能為空
			return false;
		}
		!is_array($tags) && $tags = explode(",", $tags);
		$tags = array_filter($tags);
		foreach($tags as $k => $v) {
			$tags[$k] = mysql_escape_string(t(preg_replace('/^[\s　]+|[\s　]+$/', '', $tags[$k])));
			if (!$tags[$k]) {
				unset($tags[$k]);
			}
		}

		if(empty($tags)) {
			$this->error = L('PUBLIC_TAG_NOEMPTY');			// 標籤不能為空
			return false;
		}
		// 檢測已有標籤
		$sql = 'SELECT `name` FROM '.$this->tablePrefix.'tag WHERE `name` IN (\''.implode("','", $tags).'\')';
		$existing_tags = $this->query($sql);
		$existing_tags = getSubByKey($existing_tags, 'name');
		$existing_tags = array_map('mysql_escape_string', $existing_tags);  // 資料安全轉義

		// 過濾已有標籤
		$tags = array_diff($tags, $existing_tags);
		if(empty($tags)) {
			$this->error = L('PUBLIC_TAG_EXIST');			// 標籤已經存在
			return false;
		}

		$sql = 'INSERT INTO '.$this->tablePrefix.'tag(`name`) VALUES (\''.implode("'),('", $tags).'\')';
		if(false !== $this->execute($sql)) {
			$this->error = L('PUBLIC_TAG').L('PUBLIC_SAVE_SUCCESS');		// 標籤，儲存成功
			return true;
		} else {
			$this->error = L('PUBLIC_TAG').L('PUBLIC_SAVE_FAIL');			// 標籤，儲存失敗
			return false;
		}
	}

	/**
	 * 刪除應用指定資源的標籤資訊
	 * @param integer $row_id 應用內容編號
	 * @return integer 0表示刪除失敗，1表示刪除成功
	 */
	public function deleteSourceTag($row_id) {
		$map['app'] = $this->_app;
		$map['table'] = $this->_app_table;
		$map['row_id'] = intval($row_id);
		$res = D('app_tag')->where($map)->delete();
		return $res;
	}

	/**
	 * @param integer $row_id 資源ID
	 * @param array $tagIds 標籤ID陣列
	 * @return void
	 */
	public function updateTagData($row_id, $tagIds)
	{
		// 刪除原有資料
		$row_id = intval($row_id);
		$map['row_id'] = $row_id;
		D('app_tag')->where($map)->delete();
		if(!empty($tagIds)) {
			// 添加新的標籤資訊
			$data['app'] = $this->_app;
			$data['table'] = $this->_app_table;
			$data['row_id'] = $row_id;
			foreach($tagIds as $value) {
				$data['tag_id'] = intval($value);
				D('app_tag')->add($data);
			}
		}
	}
}
