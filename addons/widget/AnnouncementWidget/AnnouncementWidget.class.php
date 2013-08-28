<?php
/**
 * 公告展示 wigdet
 * @example  {:W('Announcement',array('type'=>1, 'limit' =>3))}
 * @version  TS3.0
 */
class AnnouncementWidget extends Widget {

    /**
     * @param  integer type 類型(1:公告,2:頁尾配置文章)
     * @param  integer limit 顯示條數
     */
    public function render($data) {
        $var['type']  = 1;
        $var['limit'] = 3;
        $var = array_merge($var, $data);
        $map['type'] = $var['type'];
        $var['announcement'] = model('Xarticle')->where($map)->order('sort desc')->limit($var['limit'])->findAll();
        $content = $this->renderFile(dirname(__FILE__)."/default.html", $var);
        return $content;
    }

}
