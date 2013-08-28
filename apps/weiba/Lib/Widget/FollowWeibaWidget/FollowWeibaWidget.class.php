<?php
/**
 * 關注微吧按鈕Widget
 * @example W('FollowWeiba', array('weiba_id'=>10000, 'weiba_name'=>'weiba_name', 'follow_state'=>$followState))
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class FollowWeibaWidget extends Widget {

    /**
     * 渲染關注按鈕模板
     * @example
     * $data['weiba_id'] integer 目標微吧的ID
     * $data['weiba_name'] string 目標微吧的名稱
     * $data['follow_state'] array 當前使用者與目標微吧的關注狀態，array('following'=>1)
     * @param array $data 渲染的相關配置參數
     * @return string 渲染後的模板資料
     */
    public function render($data) {
        $var = array();
        $var['type'] = 'normal';
        $var['isrefresh'] = 0;
        is_array($data) && $var = array_merge($var, $data);
        // 渲染模版
        $content = $this->renderFile(dirname(__FILE__) . "/{$var['type']}.html", $var);
        unset($var,$data);
        // 輸出資料
        return $content;
    }
}
