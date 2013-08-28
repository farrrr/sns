<?php

tsload(APPS_PATH.'/admin/Lib/Action/AdministratorAction.class.php');
/**
 * 後臺部門管理
 *
 * @author jason
 */
class DepartmentAction extends AdministratorAction
{
    public $pageTitle = array(
        'index'  => '部門設定',
    );


    public function _initialize(){
        $this->pageTitle['index'] = L('PUBLIC_DEPARTMENT_SETTING');
        parent::_initialize();
    }
    public function index(){
        if(!empty($_POST)){
            if(model('Department')->addDepart($_POST)){
                //TODO 記錄日誌
                $this->setSearchPost();
            }
        }

        $department = model('Department')->getDepartment();

        //顯示分類HTML需要知道的對應欄位
        $this->assign('field',array('id'=>'department_id','name'=>'title','sort'=>'display_order'));
        $this->assign('_func','department');	//JS操作函數字首

        $this->pageKeyList = array('department_id','title','parent_dept_id','display_order','ctime','DOACTION');

        $this->savePostUrl = U('admin/Department/index');	//添加部門的資料提交地址

        //獲取1級部門
        $this->opt['parent_dept_id'] = model('Department')->getHashDepartment(0);

        $this->notEmpty = array('title');
        $this->onsubmit =  "admin.checkDepartment(this)";

        $this->displayCateTree($department);

    }
    //修改名稱
    public function editDepartment(){
        $this->delDepartment();
    }
    public function doeditDepartment(){
        $id = intval($_POST['id']);
        $return = array('status'=>1,'data'=>L('PUBLIC_DEPARTMENT_MODIFY_SUCCESS'));
        if(empty($id)){
            $return['status'] = 0;
            $return['data'] = L('PUBLIC_SELECT_DEPARTMENT');
            echo json_encode($return);exit();
        }
        $map = $save = array();
        $map['department_id'] 	   = $id;
        $save['title'] 		   = t($_POST['title']);
        $save['display_order'] = intval($_POST['display_order']);
        $old = $new  = model('Department')->getTreeName($id);

        if(!model('Department')->where($map)->save($save)){
            $return['status'] = 0;
            $return['data'] = L('PUBLIC_DEPARTMENT_MODIFY_FAIL');
        }else{
            model('Department')->cleancache();
            //格式化資料
            //$old = $new = explode('|',str_replace(' - ', "|", $oldTreeName));
            $new[count($new)-1] = $save['title'];
            model('Department')->editUserProfile($old, $new);
            //TODO 日誌記錄
        }
        echo json_encode($return);exit();
    }
    //移動部門
    public function moveDepartment(){
        $this->delDepartment();
    }
    public function domoveDepartment(){
        $id = intval($_POST['id']);
        $pid= intval($_POST['topid']);
        $return = array('status'=>1,'data'=>L('PUBLIC_MOVE_DEPARTMENT_SUCCESS'));
        if(empty($id)){
            $return['status'] = 0;
            $return['data'] = L('PUBLIC_SELECT_DEPARTMENT');
            echo json_encode($return);exit();
        }
        if($id == $pid){
            $return['status'] = 0;
            $return['data'] = L('PUBLIC_TRANSFER_DEPARTMENT_FORBIDDEN');
            echo json_encode($return);exit();
        }
        if(!model('Department')->moveDepart($id,$pid)){
            $return['status'] = 0;
            $return['data']   = L('PUBLIC_TRANSFER_DEPARTMENT_FAIL');
        }else{
            //TODO 記錄日誌
        }
        echo json_encode($return);exit();
    }
    //刪除部門
    public function delDepartment(){
        $id = intval($_GET['id']);
        if(empty($id)){
            echo L('PUBLIC_RELATED_DEPARTMENT_NOEXIST');exit();
        }
        $info = model('Department')->getDepartment($id);
        $this->assign('info',$info);
        $this->display();
    }
    //刪除部門操作
    public function dodelDepartment(){
        $id = intval($_POST['id']);
        $pid= intval($_POST['topid']);
        $return = array('status'=>1,'data'=>L('PUBLIC_DELETE_SUCCESS'));
        if(empty($id)){
            $return['status'] = 0;
            $return['data'] = L('PUBLIC_SELECT_DEPARTMENT');
            echo json_encode($return);exit();
        }
        if($id == $pid){
            $return['status'] = 0;
            $return['data'] = L('PUBLIC_TRANSFER_SUBDEPARTMENT_FORBIDDEN');
            echo json_encode($return);exit();
        }
        if(!model('Department')->delDepart($id,$pid)){
            $return['status'] = 0;
            $return['data']   = L('PUBLIC_DELETE_FAIL');
        }else{
            //TODO 記錄日誌
        }
        echo json_encode($return);exit();
    }
}
