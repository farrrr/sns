<?php
/**
 * 頻道首頁控制器
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class IndexAction extends Action
{
    /**
     * 頻道首頁頁面
     * @return void
     */
    public function index()
    {
        // 添加樣式
        $this->appCssList[] = 'channel.css';
        // 獲取頻道分類列表
        $channelCategory = model('CategoryTree')->setTable('channel_category')->getCategoryList();
        $this->assign('channelCategory', $channelCategory);
        // 頻道分類選中
        $cid = intval($_GET['cid']);
        $categoryIds = getSubByKey($channelCategory, 'channel_category_id');
        if (!in_array($cid, $categoryIds) && !empty($cid)) {
            $this->error('您請求的頻道分類不存在');
            return false;
        }
        $channelConf = model('Xdata')->get('channel_Admin:index');
        if(empty($cid)) {
            $cid = $channelConf['default_category'];
            if (empty($cid)) {
                $cid = array_shift($categoryIds);
            }
        }
        $this->assign('cid', $cid);
        // 獲取模板樣式
        $templete = t($_GET['tpl']);
        if(empty($templete) || !in_array($templete, array('load', 'list'))) {
            $categoryConf = model('CategoryTree')->setTable('channel_category')->getCatgoryConf($cid);
            $templete = empty($categoryConf) ? (($channelConf['show_type'] == 1) ? 'list' : 'load') : (($categoryConf['show_type'] == 1) ? 'list' : 'load');
        }
        $this->assign('tpl', $templete);
        // 設定頁面資訊
        $titleHash = model('CategoryTree')->setTable('channel_category')->getCategoryHash();
        $title = empty($cid) ? '頻道首頁' : $titleHash[$cid];
        $this->setTitle($title);
        $this->setKeywords($title);
        $this->setDescription(implode(',', getSubByKey($channelCategory,'title')));

        $this->display();
    }

    /**
     * 獲取分類資料列表
     */
    public function getCategoryData()
    {
        $data = model('CategoryTree')->setTable('channel_category')->getCategoryList();
        $result = array();
        if(empty($data)) {
            $result['status'] = 0;
            $result['data'] = '獲取資料失敗';
        } else {
            $result['status'] = 1;
            $result['data'] = $data;
        }

        exit(json_encode($result));
    }

    /**
     * 投稿釋出框
     * @return void
     */
    public function contributeBox()
    {
        $cid = intval($_GET['cid']);
        $this->assign('cid', $cid);
        // 獲取投稿分類資訊
        $info = model('CategoryTree')->setTable('channel_category')->getCategoryInfo($cid);
        $title = '投稿到：['.$info['title'].']';
        $this->assign('title', $title);
        // 釋出框類型
        $type = array('at', 'topic', 'contribute');
        $actions = array();
        foreach($type as $value) {
            $actions[$value] = false;
        }
        $this->assign('actions', $actions);

        $this->display();
    }
}
