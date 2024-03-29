<?php
/**
 * 使用者展示Widget
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class UserListWidget extends Widget
{
    /**
     * 模板渲染
     * @param array $data 相關資料
     * @return string 使用者展示列表
     */
    public function render($data) {
        // 設定模板
        $template = '';
        if(in_array($data['type'], array('tag', 'area'))) {
            $template = 'filter';
        } else {
            $template = 'user';
        }
        // 獲取一級行業分類
        $var['cid'] = intval($data['cid']);
        $var['sex'] = intval($data['sex']);
        $var['area'] = intval($data['area']);
        $var['verify'] = intval($data['verify']);
        $var['type'] = t($data['type']);
        $var['uids'] = t($data['uids']);
        $var['pid'] = intval($data['pid']);
        $var['userList'] = D('People', 'people')->getPeople($var, $var['type']);
        // 渲染模版
        $content = $this->renderFile(dirname(__FILE__)."/".$template.".html", $var);
        // 輸出資料
        return $content;
    }
}
