<?php
/**
 * 收藏
 * @example W('Collection',array('sid'=>1,'stable'=>'feed','sapp'=>'public','tpl'=>'simple'))
 * @author Jason
 * @version TS3.0
 */
class CollectionWidget extends Widget {
	
    /**
     * @param integer sid 資源ID
     * @param string stable 資源所在的表
     * @param string sapp 資源所在的應用
     * @param string tpl 渲染的模板，可分為simple(有統計數) 和 btn(無統計數)
     */
	public function render($data) {
		
		$var['tpl'] = 'btn';
		$var['type'] = 'btn';
		
		is_array($data) && $var = array_merge($var,$data);
		
		$var['coll'] = model('Collection')->getCollection($var['sid'],$var['stable']);
		$var['count'] = model('Collection')->getCollectionCount($var['sid'],$var['stable']);
		
		$content = $this->renderFile (dirname(__FILE__)."/".$var['tpl'].'.html', $var );
		
		return $content;
	}	

    /**
	 * 添加收藏記錄
	 * @return array 收藏狀態和成功提示
	 */
	public function addColl(){
		$return  = array('status'=>0,'data'=>L('PUBLIC_FAVORITE_FAIL'));
		if(empty($_POST['sid']) || empty($_POST['stable'])){
			$return['data'] = L('PUBLIC_RESOURCE_ERROR');
			echo json_encode($return);exit();
		}
		$data['source_table_name'] = t($_POST['stable']);
		$data['source_id'] 	= intval($_POST['sid']);
		$data['source_app'] = t($_POST['sapp']);

		// 驗證資源是否已經被刪除
		$key = $data['source_table_name'].'_id';
		$map[$key] = $data['source_id'];
		$map['is_del'] = 0;
		$isExist = model(ucfirst($data['source_table_name']))->where($map)->count();
		if(empty($isExist)) {
			$return = array('status'=>0, 'data'=>'內容已被刪除，收藏失敗');
			exit(json_encode($return));
		}
				
		if(model('Collection')->addCollection($data)) {
			$return = array('status'=>1,'data'=>L('PUBLIC_FAVORITE_SUCCESS'));
		} else {
			$return['data'] = model('Collection')->getError();
			empty($return['data']) && $return['data'] = L('PUBLIC_FAVORITE_FAIL');
		}
		exit(json_encode($return));
	}
	
	/**
	 * 取消收藏
	 * @return array 成功取消的狀態及錯誤提示
	 */
	public function delColl(){
		$return  = array('status'=>0,'data'=>L('PUBLIC_EDLFAVORITE_ERROR'));
		if(empty($_POST['sid']) || empty($_POST['stable'])){
			$return['data'] = L('PUBLIC_RESOURCE_ERROR');
			echo json_encode($return);exit();
		}
		if( model('Collection')->delCollection(intval($_POST['sid']), t($_POST['stable']))){
			$return = array('status'=>1,'data'=> L('PUBLIC_CANCEL_ERROR'));
		}else{
			$return['data'] = model('Collection')->getError();
			empty($return['data']) && $return['data'] = L('PUBLIC_EDLFAVORITE_ERROR');
		}
		exit(json_encode($return));
	}
	

}	