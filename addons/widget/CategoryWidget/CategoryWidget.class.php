<?php
/**
 * 分類 widget
 * @example W('Category',array('app_name'=>'support','model_name'=>'SupportCategory','method'=>'getEnCate','tpl'=>'select','id'=>4,'inputname'=>'category_en'))
 * @author Jason 
 * @version TS3.0
 */
class CategoryWidget extends Widget {
	
	/**
	  * @param app_name 此分類所在的應用名稱
      * @param model_name 分類model名稱
      * @param method 分類model中根據某個id獲取對應分類資訊的函數
      * @param tpl 顯示模版：分select和menu兩種，select為選擇分類形式，menu為選單形式
      * @param id 當前分類ID
      * @param inputname 分類存儲的input欄位名稱，只針對tpl=select 有效
      * @param title 自定義點選項的名稱，只針對tpl=select有效
      * @param cate_url 分類的URL字首，針對tpl=menu或者tpl=two有效
      * @param pid 當前分類的父級ID，針對tpl=menu有效
	 */
	public function render($data) {
		$var['tpl'] = 'select';
		$var['id'] = $var['pid'] = 0;
		$var['inputname'] = 'category';
		is_array($data) && $var = array_merge($var,$data);

		switch($var['tpl']){
			case 'select':
				return $this->select($var);
				break;
			case 'menu':
				return $this->menu($var);
				break;	
			case 'two':
				return $this->two($var);
				break;	
			case 'twochecked':
				return $this->twochecked($var);
				break;
			case 'twonums':
				return $this->twonums($var);
				break;
			case 'twofloat':
				return $this->twofloat($var);
				break;
		}
	}

	/**
	 * 選擇分類
	 */
	private function select($data){
		$model = D(ucfirst($data['model_name']),$data['app_name']);

		$data['catePath'] = $model->getCatePathById($data['id'],$data['method']);
		
		$content = $this->renderFile (dirname(__FILE__)."/".$data['tpl'].'.html', $data );
		return $content;	 
	}

	/**
	 * 收藏分類
	 */
	private function menu($data){
		!isset($data['title']) && $data['title'] = L('PUBLIC_ALL_CATEGORIES');
		$model = D(ucfirst($data['model_name']),$data['app_name']);

		$data['id'] = !empty($data['id']) ? intval($data['id']) : 0;

		$list  = $model->$data['method']($data['id'],true);

		$data['pid'] = $pid = $list['pid'];

		$pInfo[] = array('id'=>0,'name'=>$data['title'],'pid'=>0);	
		$childInfo = array();

		if($pid == 0){
			if(!empty($list['child']) && $data['id']!=0){
				$pInfo[] = array('id'=>$list['id'],'name'=>$list['name'],'pid'=>0);
				foreach($list['child'] as $k=>$v){
					$childInfo[] = array('id'=>$k,'name'=>$v,'pid'=>$data['id']);
				}
			}else{
				$list = $model->$data['method'](0,true);
				foreach($list['child'] as $k=>$v){
					$childInfo[] = array('id'=>$k,'name'=>$v,'pid'=>0);	
				}
			}
		}else{
			$pdepart =  $model->$data['method']($pid,true);
			if(!empty($list['child'])){
				$pInfo[] = array('id'=>$list['id'],'name'=>$list['name'],'pid'=>$pid);
				foreach($list['child'] as $k=>$v){
					$childInfo[] = array('id'=>$k,'name'=>$v,'pid'=>$data['id']);
				}
			}else{
				$pInfo[] = array('id'=>$pdepart['id'],'name'=>$pdepart['name'],'pid'=>$pdepart['pid']);
				foreach($pdepart['child'] as $k=>$v){
					$childInfo[] = array('id'=>$k,'name'=>$v,'pid'=>$pdepart['pid']);
				}
			}
			
		}

		$data['pInfo'] = $pInfo;
		$data['childInfo'] = $childInfo;

		$content = $this->renderFile (dirname(__FILE__)."/".$data['tpl'].'.html', $data );
		return $content;
	}

	/**
	 * 兩級分類
	 */
	private function two($data){
		$model = D(ucfirst($data['model_name']),$data['app_name']);
		$data['cateList'] = $model->$data['method'](0);
		$content = $this->renderFile (dirname(__FILE__)."/".$data['tpl'].'.html', $data );
		return $content;	 
	}

	/**
	 * 彈出窗
	 */
	public function selectBox(){
		$data = $_GET;
		$model = D(ucfirst($data['model_name']),$data['app_name']);
		$pids = $model->getPidsById($data['id']);
		if(empty($pids)) $pids = array(0);
		foreach($pids as $v){
			$select = $model->$data['method']($v,true);
			$data['select'][$v] = $select['child'];
		}
		$data['pids'] = $pids;
		$content = $this->renderFile (dirname(__FILE__).'/selectbox.html', $data );
		return $content;
	}

	/**
	 * 獲取某級下面的子集
	 */
	public function getChild(){
		$data = $_GET;
		$model = D(ucfirst($data['model_name']),$data['app_name']);
		$select = $model->$data['method']($data['id'],true);
		if(!empty($select['child'])){
			echo json_encode(array('data'=>$select['child'],'status'=>1));//表示沒有資料
		}else{
			echo json_encode(array('data'=>'','status'=>0));//表示沒有資料
		}
		exit();
	}

	/**
	 * getCatePathById方法不存在？？？
	 * @return 分類地址
	 */
	public function getCatePath(){
		$data =$_GET;
		$model = D(ucfirst($data['model_name']),$data['app_name']);
		$catePath = $model->getCatePathById($data['id'],$data['method']);
		echo  implode($catePath,' <span class="symbol-next">»</span> ');
		die();
	}

	/**
	 * 兩級分類選擇模板
	 */ 
	public function twochecked($data) {
		$model = D(ucfirst($data['model_name']),$data['app_name']);
		$data['cateList'] = $model->$data['method'](0);
		$content = $this->renderFile(dirname(__FILE__)."/".$data['tpl'].'.html', $data);
		return $content;
	}

	/**
	 * 兩級分類帶統計數目
	 */ 
	public function twonums($data) {
		$cid = empty($data['cid']) ? 0 : intval($data['cid']);
		$model = D(ucfirst($data['model_name']),$data['app_name']);
		$data['cateList'] = $model->$data['method']($cid);

		$content = $this->renderFile(dirname(__FILE__)."/".$data['tpl'].'.html', $data);
		return $content;		
	}

	/**
	 * 浮動兩級分類别範本
	 */ 
	public function twofloat($data) {
		$model = D(ucfirst($data['model_name']),$data['app_name']);
		$data['cateList'] = $model->$data['method'](0);
		$content = $this->renderFile(dirname(__FILE__)."/".$data['tpl'].'.html', $data);
		return $content;
	}
}
