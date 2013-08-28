<?php
/**
 * 應用管理控制器
 * @author liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.0
 */
class AppAction extends Action {

    /**
     * 初始化控制器，載入相關樣式表
     */
    protected function _initialize() {
        $this->appCssList[] = 'app.css';
    }

    /**
     * 應用列表頁面，默認為所有應用
     */
    public function index() {
        $map['status'] = 1;
        $list = model('App')->getAppByPage($map, 10);
        $installIds = model('UserApp')->getUserAppIds($this->uid);
        $this->assign('installIds', $installIds);
        $this->assign('list', $list);
        $this->setTitle(L('PUBLIC_APP_INEX'));              // 添加應用
        $this->display();
    }

    /**
     * 我的應用列表頁面，登入使用者已經安裝的應用
     */
    public function myApp() {
        $list = model('App')->getUserAppByPage($this->uid, 10);
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 登入使用者解除安裝應用操作
     * @return json 返回操作後的JSON資訊資料
     */
    public function uninstall() {
        $return = array('status'=>1,'data'=>L('PUBLIC_SYSTEM_MOVE_SUCCESS'));           // 移除成功
        $appId = intval($_POST['app_id']);
        if(empty($appId)) {
            $return = array('status'=>1,'data'=>L('PUBLIC_SYSTEM_MOVE_FAIL'));          // 移除失敗
            exit(json_encode($return));
        }
        if(!model('UserApp')->uninstall($this->uid, $appId)) {
            $return['status'] = 0;
            $return['data'] = model('UserApp')->getError();
        }
        exit(json_encode($return));
    }

    /**
     * 登入使用者安裝應用操作
     * @return json 返回操作後的JSON資訊資料
     */
    public function install() {
        $return = array('status'=>1,'data'=>L('PUBLIC_ADD_SUCCESS'));                   // 添加成功
        $appId = intval($_POST['app_id']);
        if(empty($appId)) {
            $return = array('status'=>1,'data'=>L('PUBLIC_ADD_FAIL'));                  // 添加失敗
            exit(json_encode($return));
        }
        if(!model('UserApp')->install($this->uid, $appId)) {
            $return['status'] = 0;
            $return['data'] = model('UserApp')->getError();
        }
        exit(json_encode($return));
    }

    // 添加更多應用

    /**  前臺 應用管理  **/

    public function addapp() {
        $dao = model('App');
        $all_apps  = $dao->getAppByPage('add_front_applist=1',$limit=10);
        $installed = isset($_SESSION['installed_app_user_'.$this->mid]) ? $_SESSION['installed_app_user_'.$this->mid] :M('user_app')->where('`uid`='.$this->mid)->field('app_id')->findAll();
        $installed = getSubByKey($installed, 'app_id');
        $this->assign($all_apps);
        $this->assign('installed', $installed);
        $this->setTitle('更多應用');
        $this->display();
    }

    public function editapp() {
        // 重置使用者的漫遊應用的快取
        global $ts;
        if ($ts['site']['my_status'])
            model('Myop')->unsetAllInstalledByUser($this->mid);

        $this->assign('has_order', array('local_app', 'myop_app'));
        $this->setTitle(L('manage_apps'));
        $this->display();
    }


}
