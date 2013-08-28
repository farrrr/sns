<?php
/**
 * //TODO  以後統一優化成分類的多級選擇widget
 * 部門選擇
 * @example W('Department',array('tpl'=>'input','inputName'=>'depart','canChange'=>1,'sid'=>1,'defaultName'=>'無','defaultId'=>'0','callback'=>'contactBack'))
 * @author jason
 * @version TS3.0
 */
class DepartmentWidget extends Widget{
	
	private static $rand = 1;
	public static $userDepartHash = array();

	/**
	 * @param string tpl 部門選擇類型 admin:下拉形式  input:表單輸入形式   menu:選單形式
	 * @param string inputName 表單輸入的值，只針對tpl=input
	 * @param integer sid 當前選擇的部門ID
	 * @param integer canChange 是否可修改，只針對tpl=input有效 
	 * @param srting defaultName 默認的部門名稱，只針對tpl=input有效
	 * @param string defaultId 默認的部門ID，只針對tpl=input有效
	 * @param string callback 選擇部門之後的回撥函數，只針對tpl=menu有效
	 */
	public function render($data){
		self::$rand++;
		$var['rand'] = self::$rand;
		$var['tpl'] = 'admin';

		$var = is_array($data) ? array_merge($var,$data) : $var;

		$var['defaultId'] = intval($var['defaultId']);

		if($var['select']==1){	//下拉形式
			//部門選擇wigdet
			$var['pid'] = !empty($data['pid']) ? $data['pid'] : 0;
			$var['parentList'] = model('Department')->getHashDepartment($var['pid'],$var['sid'],$var['nosid'],intval($var['notop']));

			$content = $this->renderFile(dirname(__FILE__)."/{$var['tpl']}.html",$var);
			return $content;
		}else{
			//顯示使用者wigdet
			switch($var['tpl']){
				case 'input':
					$departHash = model('Department')->getAllHash();
					$var['defaultName'] = $departHash[$var['defaultId']]['title'];
					break;	//input 輸入框
				case 'menu':		//選單展示
				//看看有沒有子節點資料
				$var['pid'] = !empty($data['pid']) ? intval($data['pid']) : 0;
				//
				$var['sid'] = !empty($data['sid']) ? intval($data['sid']) : 0;
				

				//全部部門
				$pInfo[] = array('sid'=>0,'pid'=>0,'name'=>L('PUBLIC_DEPARTMENT_ALL'));	

				$list = $this->_getList($var['sid']);

				$childInfo = array();

				
				foreach($list['_child'] as $v){
					$pInfo[] = array('sid'=>$v['department_id'],'pid'=>$v['parent_dept_id'],'name'=>$v['title']);
					
					if($v['department_id'] == $var['sid'] || $v['department_id'] == $var['pid']){
						foreach($v['_child'] as $vv){
							$childInfo[] = array('sid'=>$vv['department_id'],'pid'=>$vv['parent_dept_id'],'name'=>$vv['title']);
						}
					}
				}
				if(!empty($var['pid'])){
					$ppid = model('Department')->getDepartment($sid);
					$var['ppid'] = $ppid['parent_dept_id'];
				}else{
					$var['ppid'] = 0;
				}

				$var['pInfo'] = $pInfo;
				$var['childInfo'] = $childInfo;

				break;
			}
			return $this->renderFile(dirname(__FILE__)."/{$var['tpl']}.html",$var);
		}
    }

    /**
    * 獲取部門列表
    * @return array 部門列表
    */
    private function _getList($sid){
   		//判斷是否有子節點
   	
   		$data = model('Department')->getDepartment($sid);
   		if(!empty($sid)){
   			$list['_child'][0] = $data;
   		}else{
   			$list = $data;
   		}

		//取父資料
		if(!empty($sid) && empty($data['_child'])){
			$list = $this->_getList($data['parent_dept_id']);
		}
   		
   		return $list;
   }
    
   /**
    * 修改部門
    */
   public function change(){
   		$var = $_REQUEST;
   		$var['parentList'] = model('Department')->getHashDepartment(intval($var['pid']),$var['sid'],$var['nosid'],intval($var['notop']));
   		return $this->renderFile(dirname(__FILE__)."/change.html",$var);
   } 

   /**
    * 選擇部門
    * @return array 已選擇的部門
    */
   public function selectDepartment(){
    	$return = array('status'=>1,'data'=>'');
    	
    	$return['data'] = model('Department')->getHashDepartment(t($_REQUEST['pid']), t($_REQUEST['sid']), t($_REQUEST['nosid']), t($_REQUEST['notop']));
    	
    	
    	if(empty($return['data'])) $return['data'] = array();
    	echo json_encode($return);exit();
    }
    

}