<?php
/**
 * 頻道頂部選單Widget
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class TopMenuWidget extends Widget
{
    /**
     * 模板渲染
     * @param array $data 相關資料
     * @return string 頻道內容渲染入口
     */
    public function render($data)
    {
        // 設定頻道模板
        $template = 'menu';
        // 頻道分類ID
        $var['cid'] = intval($data['cid']);
        // 頻道名稱
        $var['title'] = t($data['title']);
        // 頻道分類資料
        $var['channelCategory'] = $data['channelCategory'];
        // 獲取頻道的關注數目
        $var['followingCount'] = D('ChannelFollow', 'channel')->getFollowingCount($var['cid']);
        // 獲取頻道的記錄數目
        $var['channelCount'] = D('Channel', 'channel')->getChannelCount($var['cid']);
        // 獲取使用者與頻道分類的關注狀態
        $var['followStatus'] = model('ChannelFollow')->getFollowStatus($GLOBALS['ts']['mid'], $var['cid']);

        $content = $this->renderFile(dirname(__FILE__)."/".$template.".html", $var);
        return $content;
    }

    /**
     * 頻道關注狀態修改介面
     * @return json 處理後返回的資料
     */
    public function upFollowStatus()
    {
        $uid = intval($_POST['uid']);
        $cid = intval($_POST['cid']);
        $type = t($_POST['type']);
        $res = model('ChannelFollow')->upFollow($uid, $cid, $type);
        $result = array();
        if($res) {
            $result['status'] = 1;
            $result['info'] = '';
        } else {
            $result['status'] = 0;
            $result['info'] = '';
        }

        exit(json_encode($result));
    }
}
