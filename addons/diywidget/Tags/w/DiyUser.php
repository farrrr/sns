<?php
/**
 * 使用者模組
 * @author Stream
 *
 */
class DiyUser extends TagsAbstract{
    /**
     * 是否是封閉的標籤
     * @var unknown_type
     */
    static $TAG_CLOSED = false;

    public $config = array();

    public function getTagStatus(){
        return self::$TAG_CLOSED;
    }
    /* (non-PHPdoc)
     * @see TagsAbstract::getTemplateFile()
     */
    function getTemplateFile($tpl = '') {
        //返回需要渲染的模板
        $file = $this->attr ['style'];
        if(!empty($tpl)){
            $file = $tpl;
        }
        return dirname(__FILE__).'/DiyUser/'.$file.'.html';
    }

    /* 參數處理
     * @see TagsAbstract::replace()
     */
    function replace() {
        $attr = $this->attr;
        $userDao = model( 'User' );
        $limit = 10;
        if ( !empty($attr['limit']) ){
            $limit = intval( $attr['limit'] );
            $limit = $limit>100 ? 100 : $limit;
        }
        switch ( $attr['source'] ){
        case 'new'://最新註冊
            $list = $userDao->getList( 'is_audit=1 and is_init=1 and is_active=1' , $limit , 'uid,uname' , 'ctime desc');
            break;
        case 'follow'://粉絲最多
            $list = model ( 'UserData' )->where("`key`='follower_count'")->field('uid')->order('`value`+0 desc')->limit($limit)->findAll();

            $map['uid'] = array( 'in' , getSubByKey( $list , 'uid' ) );
            $users = $userDao->getList( $map , $limit , 'uid,uname');
            $kusers = array();
            foreach ( $users as $us ){
                $kusers[$us['uid']] = $us['uname'];
            }


            foreach ( $list as &$u ){
                $u['uname'] = $kusers[$u['uid']];
            }
            break;
        case 'custom'://指定使用者
            $list = $userDao->where('uid in ('.$attr['user'].')')->field('uid,uname')->findAll();
            break;
        }
        $followercount = array();
        $followstate = array();
        if ( $attr['style'] == 'down' || $attr['style'] == 'numdown'){
            $fids = getSubByKey( $list , 'uid' );
            $follower_map['fid'] = array('IN', $fids );
            // 粉絲數
            $follower = model('Follow')->field('COUNT(1) AS `count`,`fid`')->where($follower_map)->group('`fid`')->findAll();
            foreach ($follower as $v) {
                $followercount[$v['fid']]['follower'] =  $v['count'];
            }

            $mid = $GLOBALS['ts']['mid'];;
            $followstate = model('Follow')->getFollowStateByFids( $mid , $fids );
        }
        foreach ( $list as &$v ){
            $v['face'] = model('Avatar')->init($v['uid'])->getUserAvatar();
            $v['followercount'] = intval($followercount[$v['uid']]['follower']);
            $v['follow'] = $followstate[$v['uid']];
        }
        $attr['list'] = $list;
        return $attr;
    }


}
