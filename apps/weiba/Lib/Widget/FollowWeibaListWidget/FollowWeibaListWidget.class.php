<?php
/**
 * 某人關注的微吧Widget
 * @example W('FollowWeibaList', array('follower_uid'=>10000))
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class FollowWeibaListWidget extends Widget {

    /**
     * 渲染關注按鈕模板
     * @example
     * $data['follower_uid'] integer 使用者ID
     * @param array $data 渲染的相關配置參數
     * @return string 渲染後的模板資料
     */
    public function render($data) {
        $var = array();
        $var['type'] = 'FollowWeibaList';

        $follow = D('weiba_follow')->where('follower_uid='.$data['follower_uid'])->findAll();

        $map['weiba_id'] = array('in', getSubByKey($follow, 'weiba_id'));
        $map['is_del'] = 0;
        $var['weibaList'] = D('weiba')->where($map)->findAll();

        $var['weibaListCount'] = D('weiba')->where($map)->count();
        foreach($var['weibaList'] as $k=>$v){
            $var['weibaList'][$k]['logo'] = getImageUrlByAttachIdByWeiba($v['logo']);
        }
        is_array($data) && $var = array_merge($var, $data);
        // 渲染模版
        $content = $this->renderFile(dirname(__FILE__) . "/followWeibaList.html", $var);
        unset($var,$data);
        // 輸出資料
        return $content;
    }
}
