<?php
/**
 * 頻道後臺配置
 * 1.頻道分類管理 - 目前支援1級分類
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
tsload(APPS_PATH.'/admin/Lib/Action/AdministratorAction.class.php');
class AdminAction extends AdministratorAction
{
    private $_model_category;

    /**
     * 初始化，配置內容標題
     * @return void
     */
    public function _initialize()
    {
        // 管理標題項目
        $this->pageTitle['index'] = '頻道基本配置';
        $this->pageTitle['channelCategory'] = '頻道分類配置';
        $this->pageTitle['auditList'] = '已稽覈列表';
        $this->pageTitle['unauditList'] = '未稽覈列表';
        // 管理分頁項目
        $this->pageTab[] = array('title'=>$this->pageTitle['index'],'tabHash'=>'index','url'=>U('channel/Admin/index'));
        $this->pageTab[] = array('title'=>$this->pageTitle['channelCategory'],'tabHash'=>'channelCategory','url'=>U('channel/Admin/channelCategory'));
        $this->pageTab[] = array('title'=>$this->pageTitle['auditList'],'tabHash'=>'auditList','url'=>U('channel/Admin/auditList'));
        $this->pageTab[] = array('title'=>$this->pageTitle['unauditList'],'tabHash'=>'unauditList','url'=>U('channel/Admin/unauditList'));

        $this->_model_category = model('CategoryTree')->setTable('channel_category');

        parent::_initialize();
    }

    /**
     * 頻道基本配置頁面
     * @return void
     */
    public function index()
    {
        // 列表key值 DOACTION表示操作
        $this->pageKeyList = array('is_audit', 'default_category', 'show_type');
        $this->opt['is_audit'] = array('是', '否');
        $this->opt['default_category'] = $this->_model_category->getCategoryHash();
        $this->opt['show_type'] = array('瀑布流', '列表');

        $this->displayConfig();
    }

    /**
     * 頻道分類配置頁面
     * @return void
     */
    public function channelCategory()
    {
        $_GET['pid'] = intval($_GET['pid']);
        $treeData = $this->_model_category->getNetworkList();
        $extra = array('attach', 'show_type'=>array('瀑布流', '列表'), 'user_bind', 'topic_bind');
        $channelConf = model('Xdata')->get('channel_Admin:index');
        $defaultExtra = array('show_type'=>$channelConf['show_type']);
        $extra = encodeCategoryExtra($extra, $defaultExtra);
        // 配置刪除關聯資訊
        $delParam['app'] = 'channel';
        $delParam['module'] = 'Channel';
        $delParam['method'] = 'deleteAssociatedData';
        $this->displayTree($treeData, 'channel_category', 1, $delParam, $extra);
    }

    /**
     * 已稽覈管理頁面
     * @return void
     */
    public function auditList()
    {
        // 批量操作按鈕
        $this->pageButton[] = array('title'=>'取消推薦','onclick'=>"admin.cancelRecommended()");
        // 獲取列表資料
        $map['status'] = 1;
        $listData = $this->_getData($map, 'audit');

        $this->displayList($listData);
    }

    /**
     * 未稽覈管理頁面
     * @return void
     */
    public function unauditList()
    {
        // 批量操作按鈕
        $this->pageButton[] = array('title'=>'通過稽覈','onclick'=>"admin.auditChannelList()");
        $this->pageButton[] = array('title'=>'駁回','onclick'=>"admin.rejectChannel()");
        // 獲取列表資料
        $map['status'] = 0;
        $listData = $this->_getData($map, 'unaudit');

        $this->displayList($listData);
    }

    /**
     * 取消推薦操作
     * @return josn 相關操作資訊資料
     */
    public function cancelRecommended()
    {
        $post = t($_POST['rowId']);
        $rowIds = explode(',', $post);
        $res = D('Channel', 'channel')->cancelRecommended($rowIds);
        $result = array();
        if($res) {
            $result['status'] = 1;
            $result['data'] = '取消推薦成功';
        } else {
            $result['status'] = 0;
            $result['data'] = '取消推薦失敗';
        }

        exit(json_encode($result));
    }

    /**
     * 稽覈操作
     * @return josn 相關操作資訊資料
     */
    public function auditChannelList()
    {
        $post = t($_POST['rowId']);
        $rowIds = explode(',', $post);
        $res = D('Channel', 'channel')->auditChannelList($rowIds);
        $result = array();
        if($res) {
            foreach($rowIds as $v){
                $config['feed_content'] = getShort(D('feed_data')->where('feed_id='.$v)->getField('feed_content'),10);
                $channel_category = D('channel')->where('feed_id='.$v)->findAll();
                $map['channel_category_id'] = array('in',getSubByKey($channel_category,'channel_category_id'));
                $config['channel_name'] = implode(',',getSubByKey(D('channel_category')->where($map)->field('title')->findAll(),'title'));
                $config['feed_url'] = '<a target="_blank" href="'.U('public/Profile/feed',array('feed_id'=>$v,'uid'=>$channel_category[0][uid])).'">'.$config['feed_content'].'</a>';
                model('Notify')->sendNotify($uid, 'channel_add_feed', $config);
            }
            $result['status'] = 1;
            $result['data'] = '稽覈成功';
        } else {
            $result['status'] = 0;
            $result['data'] = '稽覈失敗';
        }

        exit(json_encode($result));
        }

        /**
         * 頻道管理彈窗
         * @return void
         */
        public function editAdminBox()
        {
            // 獲取微博ID
            $data['feedId'] = intval($_REQUEST['feed_id']);
            // 頻道分類ID
            $data['channelId'] = empty($_REQUEST['channel_id']) ? 0 : intval($_REQUEST['channel_id']);
            // 獲取全部頻道列表
            $data['categoryList'] = $this->_model_category->getCategoryList();
            // 獲取該微博已經選中的頻道
            $data['selectedChannels'] = D('Channel', 'channel')->getSelectedChannels($data['feedId']);

            $this->assign($data);
            $this->display();
        }

        /**
         * 獲取內容資訊
         * @param array $map 查詢條件
         * @param string $type 類型
         * @return array 獲取相應的列表資訊
         */
        private function _getData($map, $type)
        {
            // 鍵值對
            $this->pageKeyList = array('id','uname','content','status','category','DOACTION');
            $data = D('Channel', 'channel')->getChannelList($map);
            // 組裝資料
            foreach($data['data'] as &$value) {
                $value['id'] = $value['feed_id'];
                $value['content'] = '<div style="width:500px;line-height:22px" model-node="feed_list">'.$value['content'].'  <a target="_blank" href="'.U('public/Profile/feed', array('feed_id'=>$value['feed_id'],'uid'=>$value['uid'])).'">'.L('PUBLIC_VIEW_DETAIL').'&raquo;</a></div>';
                $value['status'] = ($value['status'] == 1) ? '<span style="color:green;cursor:auto;">已稽覈</span>' : '<span style="color:red;cursor:auto;">未稽覈</span>';
                $value['category'] = implode('<br />', getSubByKey($value['categoryInfo'], 'title'));
                switch($type) {
                case 'audit':
                    $value['DOACTION'] = '<a href="javascript:;" onclick="admin.cancelRecommended('.$value['feed_id'].')">取消推薦</a>';
                    break;
                case 'unaudit':
                    $channelId = implode(',', getSubByKey($value['categoryInfo'], 'channel_category_id'));
                    $value['DOACTION'] = '<a href="javascript:;" onclick="admin.auditChannelList('.$value['feed_id'].', \''.$channelId.'\')">通過稽覈</a>&nbsp;-&nbsp;<a href="javascript:;" onclick="admin.rejectChannel('.$value['feed_id'].')">駁回</a>';
                    break;
        }
        }

        return $data;
        }
        }
