<?php
/**
 * 使用者貢獻排行榜Widget
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class TopListWidget extends Widget
{
    /**
     * 模板渲染
     * @param array $data 相關資料
     * @return string 頻道內容渲染入口
     */
    public function render($data)
    {
        // 設定頻道模板
        $template = 'top';
        // 配置參數
        $var['cid'] = intval($data['cid']);
        $var['list'] = D('Channel', 'channel')->getTopList($var['cid']);

        $content = $this->renderFile(dirname(__FILE__)."/".$template.".html", $var);
        return $content;
    }
}
