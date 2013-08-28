<?php
/**
 * 找人首頁控制器
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class IndexAction extends Action
{
    public function _initialize()
    {
        $this->appCssList[] = 'people.css';
    }

    public function index()
    {
        $conf = model('Xdata')->get('admin_User:findPeopleConfig');
        if(!$conf['findPeople']){
            $this->error('找人功能已關閉');
        }
        $this->assign('findPeopleConfig',$conf['findPeople']);
        if(!isset($_GET['type']) || empty($_GET['type']) ){
            $_GET['type'] = $conf['findPeople'][0];
        }else{
            if(!in_array(t($_GET['type']), $conf['findPeople'])) $this->error('參數錯誤！');
        }
        // 獲取相關資料
        $cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
        $sex = isset($_GET['sex']) ? intval($_GET['sex']) : 0;
        $area = isset($_GET['area']) ? intval($_GET['area']) : 0;
        $verify = isset($_GET['verify']) ? intval($_GET['verify']) : 0;
        // 載入資料
        $this->assign('cid', $cid);
        $this->assign('sex', $sex);
        $this->assign('area', $area);
        $this->assign('verify', $verify);
        // 頁面類型
        $type = isset($_GET['type']) ? t($_GET['type']) : $conf['findPeople'][0];
        $this->assign('type', $type);
        // 獲取後臺配置使用者
        if(in_array($type, array('verify', 'official'))) {
            switch($type) {
            case 'verify':
                $conf = model('Xdata')->get('admin_User:verifyConfig');
                $this->assign('pid',intval($_GET['pid']));
                break;
            case 'official':
                $conf = model('Xdata')->get('admin_User:official');
                break;
            }
            $this->assign('topUser', $conf['top_user']);
        }

        //SEO
        switch ($type) {
        case 'verify':
            $title = '認證使用者推薦';
            $cate = model('CategoryTree')->setTable('user_verified_category')->getNetworkList();  //TODO
            break;
        case 'official':
            $title = '官方推薦';
            $cate = model('CategoryTree')->setTable('user_official_category')->getNetworkList();
            break;
        case 'tag':
            $title = '按標籤查找使用者';
            $cate = model('UserCategory')->getNetworkList();
            break;
        case 'area':
            $title = '按地區查找使用者';
            //上級id
            $pid = D('area')->where('area_id='.$area)->getField('pid');
            //同級
            $tongji = model('CategoryTree')->setTable('area')->getNetworkList($pid);
            //下級
            $cate = model('CategoryTree')->setTable('area')->getNetworkList($area);
            //dump($cate);exit;
            break;
        }
        $cate = getSubByKey($cate,'title');
        $this->setTitle( $title );
        $this->setKeywords( $title );
        $this->setDescription( implode(',', $cate) );

        $this->display();
    }

    /**
     * 獲取指定父分類的樹形結構
     * @return integer $pid 父分類ID
     * @return array 指定父分類的樹形結構
     */
    public function getNetworkList()
    {
        $pid = intval($_REQUEST['pid']);
        $list = model('CategoryTree')->setTable('area')->getNetworkList($pid);
        $id = $pid + 100;
        // exit($list[$id]['child']);
        //dump($list[$id]['child']);
        exit(json_encode($list[$id]['child']));
    }
}
