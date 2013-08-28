<?php
/**
 * 頻道內容渲染Widget
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class ContentWidget extends Widget
{
    /**
     * 模板渲染
     * @param array $data 相關資料
     * @return string 頻道內容渲染入口
     */
    public function render($data)
    {
        // 設定頻道模板
        $template = empty($data['tpl']) ? 'list' : t($data['tpl']);
        // 配置參數
        // $var['loadCount'] = intval($data['loadCount']);
        // $var['loadMax'] = intval($data['loadMax']);
        // $var['loadId'] = intval($data['loadId']);
        // $var['loadLimit'] = intval($data['loadLimit']);
        $var['cid'] = intval($data['cid']);
        // 獲取微博資料
        if($template == 'list') {
            $var['list'] = $this->getListData($var['cid']);
            // 微博配置
            $weiboSet = model('Xdata')->get('admin_Config:feed');
            $var['weibo_premission'] = $weiboSet['weibo_premission'];
        }
        if($template === 'load') {
            $var['categoryJson'] = json_encode($data['channelCategory']);
        }

        $content = $this->renderFile(dirname(__FILE__)."/".$template.".html", $var);
        return $content;
    }

    /**
     * 載入頻道內容
     * @return json 頻道渲染內容
     */
    public function loadMore()
    {
        // 頻道分類ID
        $cid = intval($_REQUEST['cid']);
        $loadLimit = intval($_REQUEST['loadlimit']);
        $loadId = intval($_REQUEST['loadId']);
        $loadCount = intval($_REQUEST['loadcount']);
        // 獲取HTML資料
        $content = $this->getData($cid, $loadLimit, $loadId);
        // 檢視是否有更多資料
        if(empty($content['html']) && empty($content['pageHtml'])) {
            $return['status'] = 0;
            $return['msg'] = L('PUBLIC_WEIBOISNOTNEW');
        } else {
            $return['status'] = 1;
            $return['msg'] = L('PUBLIC_SUCCESS_LOAD');
            $return['html'] = $content['html'];
            $return['loadId'] = $content['lastId'];
            $return['firstId'] = (empty($_REQUEST['p']) && empty($_REQUEST['loadId']) ) ? $content['firstId'] : 0;
            $return['pageHtml'] = $content['pageHtml'];
        }

        exit(json_encode($return));
    }

    public function getData($cid, $loadLimit, $loadId)
    {
        // 獲取微博資料
        $list = D('Channel', 'channel')->getDataWithCid($cid, $loadId, $loadLimit);
        // 分頁的設定
        if(!empty($list['data'])) {
            $content['firstId'] = $var['firstId'] = $list['data'][0]['feed_channel_link_id'];
            $content['lastId'] = $list['data'][(count($list['data'])-1)]['feed_channel_link_id'];
            $var['data'] = $this->_formatContent($list['data']);
            // 微博配置
            $weiboSet = model('Xdata')->get('admin_Config:feed');
            $var['weibo_premission'] = $weiboSet['weibo_premission'];
        }

        $content['pageHtml'] = $list['html'];
        // 渲染模版
        $content['html'] = fetch(dirname(__FILE__).'/_load.html', $var);

        return $content;
    }

    /**
     * 處理微博附件資料
     * @param array $data 頻道關聯陣列資訊
     * @return array 處理後的微博資料
     */
    private function _formatContent($data)
    {
        // 組裝微博資訊
        foreach($data as &$value) {
            // 獲取微博資訊
            $feedInfo = model('Feed')->get($value['feed_id']);
            $value = array_merge($value, $feedInfo);
            switch($value['type']) {
            case 'postimage':
                $feedData = unserialize($value['feed_data']);
                $imgAttachId = is_array($feedData['attach_id']) ? $feedData['attach_id'][0] : $feedData['attach_id'];
                $attach = model('Attach')->getAttachById($imgAttachId);
                $value['attachInfo'] = getImageUrl($attach['save_path'].$attach['save_name'],'225');
                $value['attach_id'] = $feedData['attach_id'];
                $feedData['body'] = replaceUrl($feedData['body']);
                $value['body'] = parse_html($feedData['body']);
                break;
            case 'postvideo':
                $feedData = unserialize($value['feed_data']);
                $value['body'] = replaceUrl($feedData['body']);
                $value['flashimg'] = $feedData['flashimg'];
                break;
            case 'postfile':
                $feedData = unserialize($value['feed_data']);
                $attach = model('Attach')->getAttachByIds($feedData['attach_id']);
                foreach($attach as $key => $val) {
                    $_attach = array(
                        'attach_id' => $val['attach_id'],
                        'name' => $val['name'],
                        'attach_url' => getImageUrl($val['save_path'].$val['save_name'],'225'),
                        'extension' => $val['extension'],
                        'size' => $val['size']
                    );
                    $value['attachInfo'][] = $_attach;
                }
                $feedData['body'] = replaceUrl($feedData['body']);
                $value['body'] = parse_html($feedData['body']);
                break;
            case 'repost':
                $feedData = unserialize($value['feed_data']);
                $value['body'] = parse_html($feedData['body']);
                break;
            case 'weiba_post':
                $feedData = unserialize($value['feed_data']);
                $post_url = '<a class="ico-details" target="_blank" href="'.U('weiba/Index/postDetail',array('post_id'=>$value['app_row_id'])).'"></a>';
                $value['body'] = preg_replace('/\<a href="javascript:void\(0\)" class="ico-details"(.*)\>(.*)\<\/a\>/',$post_url,$value['body']);
                break;
            case 'weiba_repost':
                $feedData = unserialize($value['feed_data']);
                $post_id = D('feed')->where('feed_id='.$value['app_row_id'])->getField('app_row_id');
                $post_url = '<a class="ico-details" target="_blank" href="'.U('weiba/Index/postDetail',array('post_id'=>$post_id)).'"></a>';
                $value['body'] = preg_replace('/\<a href="javascript:void\(0\)" class="ico-details"(.*)\>(.*)\<\/a\>/',$post_url,$value['body']);
                break;
            }
        }

        return $data;
    }

    /**
     * 獲取頻道分類列表資料
     * @param integer $cid 頻道分類ID
     * @return array 頻道分類列表資料
     */
    public function getListData($cid)
    {
        $list = D('Channel', 'channel')->getChannelFindPage($cid);
        $feedIds = getSubByKey($list['data'], 'feed_id');
        $list['data'] = model('Feed')->getFeeds($feedIds);

        return $list;
    }
}
