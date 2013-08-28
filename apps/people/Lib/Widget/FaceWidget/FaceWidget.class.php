<?php
/**
 *
 */
class FaceWidget extends Widget
{
    /**
     * 模板渲染
     * @param array $data 相關資料
     * @return string 使用者身份選擇模板
     */
    public function render($data) {
        // 設定模板
        $template = empty($data['tpl']) ? 'face' : strtolower(t($data['type']));
        // 獲取相關資料
        $var['uids'] = explode(',', $data['uids']);
        $var['type'] = t($data['type']);
        $var['faceList'] = D('People', 'people')->getTopUserInfos($var['uids'], $var['type']);
        // 渲染模版
        $content = $this->renderFile(dirname(__FILE__)."/".$template.".html", $var);
        // 輸出資料
        return $content;
    }
}
