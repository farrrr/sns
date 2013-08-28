<?php
/**
 * 系統微博類
 * @author Stream
 *
 */
class DiyWeibo extends TagsAbstract{

    /**
     * 是否是封閉的標籤
     * @var unknown_type
     */
    static $TAG_CLOSED = false;

    public $config = array();

    public function __construct(){

    }
    public function getTagStatus(){
        return self::$TAG_CLOSED;
    }
    /**
     * 返回模板檔案
     * @see TagsAbstract::getTemplateFile()
     */
    function getTemplateFile($tpl = '') {
        //返回需要渲染的模板
        $file = $this->attr ['style'];
        if(!empty($tpl)){
            $file = $tpl;
        }
        return dirname(__FILE__).'/DiyWeibo/'.$file.'.html';
    }

    /**
     * 參數處理
     * @see TagsAbstract::replace()
     */
    function replace() {
        $attr = $this->attr;
        $var['source'] = $this->attr['source'];
        if ( $attr['type'] == 'all' ){
            $attr['type'] = '';
        }
        if( !empty($attr['type']) ){
            $map['type'] = t($attr['type']);
        }
        $limit = 10;
        if ( !empty( $attr['limit'] ) ){
            $limit = $attr['limit'];
        }
        $map['is_del'] = 0;
        if ( !empty( $attr['order'] ) ){
            $order = $attr['order'];
        }
        switch ( $attr['source'] ){
        case 'user'://指定使用者微博
            if ( !empty($attr['user']) ){
                $map['uid'] = array ( 'in' , explode(',' , $attr['user']  ) );
            }
            break;
        case 'topic'://指定話題微博
            if ( !empty( $attr['topic'] ) ){
                $fids = model( 'FeedTopic' )->getFeedIdByTopic ( $attr['topic'] );
                $map['feed_id'] = array( 'in' , $fids );
            }
            break;
        }
        $list = model('Feed')->getList( $map , $limit , $order );
        $attr['data'] = $list['data'];
        $attr['list'] = $list;
        // 獲取微博配置
        $weiboSet = model('Xdata')->get('admin_Config:feed');
        $attr = array_merge($attr, $weiboSet);
        $attr['remarkHash'] = model('Follow')->getRemarkHash($GLOBALS['ts']['mid']);
        return $attr;
    }


}
