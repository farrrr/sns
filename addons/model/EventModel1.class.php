<?php
include_once SITE_PATH.'/apps/event/Lib/Model/BaseModel.class.php';
/**
 * EventModel
 * 活動主資料庫模型
 * @uses BaseModel
 * @package
 * @version $id$
 * @copyright 2009-2011 SamPeng
 * @author SamPeng <sampeng87@gmail.com>
 * @license PHP Version 5.2 {@link www.sampeng.cn}
 */
class EventModel extends BaseModel{
    var $mid;
    function getConfig($key=NULL){
        $config = model('Xdata')->lget('event');
        $config['limitpage']    || $config['limitpage'] =10;
        $config['canCreate']===0 || $config['canCreat']=1;
        ($config['credit'] > 0   || '0' === $config['credit']) || $config['credit']=100;
        $config['credit_type']  || $config['credit_type'] ='experience';
        ($config['limittime']   || $config['limittime']==='0') || $config['limittime']=10;//換算為秒

        if($key){
            return $config[$key];
        }else{
            return $config;
        }
    }
    public function getEventList($map='',$order='id DESC',$mid){
        $this->mid = $mid;
        $result  = $this->where($map)->order($order)->findPage($this->getConfig('limitpage'));
        //追加必須的資訊
        if( !empty( $result['data'] )){
            $user = self::factoryModel( 'user' );
            //$friendsId = $this->api->friend_get();
            $map = array();
            $map['action']    = 'joinIn';
            $map['status']    = 1;
            //$map['uid']       = $friendsId?array( 'in',$friendsId):NULL;
            foreach( $result['data'] as &$value ){
                $value = $this->appendContent( $value );
                //追加參與者
                $map['eventId'] = $value['id'];
                $value['users'] = $user->where( $map )->findAll();
                $value['cover'] = getCover($value['coverId']);
                //計算待審核人數
                if( $value['opts']['allow'] && $this->mid == $value['uid'] ){
                    $value['verifyCount'] = $user->where( "status = 0 AND action='joinIn' AND eventId =".$value['id'] )->count();
                }
            }
        }
        return $result;
    }
    /**
     * appendContent
     * 追加和反解析資料
     * @param mixed $data
     * @access public
     * @return void
     */
    public function appendContent( $data ){
        $opts = self::factoryModel( 'opts' );
        $type = self::factoryModel( 'type' );
        $data['type'] = $type->getTypeName( $data['type'] );

        //反解析時間
        $data['time'] = date( 'Y-m-d H:i:s',$data['sTime'] )." 至 ".date( 'Y-m-d H:i:s',$data['eTime'] );
        $data['dl']   = date( 'Y-m-d H:i:s',$data['deadline'] );

        //追加選項內容
        $opts_list    = $opts->getOpts( $data['optsId'] );
        //追加城市和其它選項
        $data['city']        = $opts_list['province']." ".$opts_list['city']." ".$opts_list['area'];
        $data['opts']        = unserialize( $opts_list['opts'] );
        $data['cost']        = $opts_list['cost'];
        $data['costExplain'] = $opts_list['costExplain'];
        $data['isHot']       = $opts_list['isHot'];

        //追加許可權
        $data += $this->checkMember( $data['uid'],$data['opts'],$this->mid );

        //追加是否已參加和是否已關注的判定
        $userDao = self::factoryModel( 'user' );
        if( $result = $userDao->hasUser( $this->mid,$data['id'],'joinIn' ) ){
            $data['canJoin']   = false;
            $data['canAtt']    = false;
            $data['hasMember'] = $result['status'];
            return $data;
        }else if( $userDao->hasUser( $this->mid,$data['id'],'attention' ) ){
            $data['canAtt'] = false;
        }

        return $data;
    }
    /**
     * checkRoll
     * 檢查許可權
     * @param mixed $uid
     * @access public
     * @return void
     */
    public function checkMember( $eventAdmin,$opts,$mid ){
        $result = array(
            'admin'   => false,
            'follow'  => true,
            'canJoin' => true,
            'canAtt'  => true,
            'hasMember' =>false,
        );
        if( $mid == $eventAdmin ){
            $result['admin']   = true;
            $result['follow']  = false;
            $result['canJoin'] = false;
            $result['canAtt']  = false;
            return $result;
        }

        //如果是好友可以參加
        if( 1 == $opts['friend'] ){
            if( 'unfollow' == getFollowState($eventAdmin,$mid) ){
                $result['canJoin'] = false;
                $result['follow']  = false;
            }
        }
        return $result;
    }

    /**
     * doAddEvent
     * 添加活動
     * @param mixed $map
     * @param mixed $feed
     * @access public
     * @return void
     */
    public function doAddEvent($eventMap,$optsMap,$cover){
        $eventMap['cTime'] = isset( $eventMap['cTime'] )?$eventMap['cTime']:time();
        $eventMap['coverId']    = $cover['status']?$cover['info'][0]['attach_id']:0;
        $eventMap['limitCount'] = 0 == intval($eventMap['limitCount']) ? 999999999:$eventMap['limitCount'];
        $has_friend        = $optsMap['opts']['friend'];
        $optsMap['opts']   = serialize( $optsMap['opts'] );

        //false
        $optsDao = self::factoryModel( 'opts' );
        if( $eventMap['optsId'] = $optsDao->add( $optsMap )){
            $addId    = $this->add( $eventMap );
        }else{
            return false;
        }

        //添加參與動作
        $user           = self::factoryModel( 'user' );
        $map['uid']     = $eventMap['uid'];
        $map['eventId'] = $addId;
        $map['contact'] = $eventMap['contact'];
        $map['action']  = 'admin';
        $map['cTime']   = time();
        $user->add( $map );

        //如果是只有我關注的人可參與，給所有我關注的人發送通知
        // if( 1 == $has_friend  ){
        //  //我關注的人的ID
        //  $fids = M('user_follow')->field('fid')->where("uid={$eventMap['uid']}")->findAll();
        //  foreach($fids as $k=>&$v){
        //      $fids[$k] = $v['fid'];
        //  }

        //           $data['url']     =  U('//eventDetail',array('id'=>$addId,'uid'=>$eventMap['uid']));
        //           $data['title']   = "<a href=\"{$data['url']}\" target=\"_blank\">{$eventMap['title']}</a>";
        //           $data['content'] = t(getBlogShort($eventMap['explain'],40));
        //           X('Notify')->send($fids,'event_add',$data, $eventMap['uid']);
        // }

        //釋出到微薄
        $_SESSION['new_event']=1;

        return $addId;
    }

    /**
     * getEventContent
     * 獲得活動具體類容頁
     * @param mixed $eventId
     * @param mixed $uid
     * @param mixed $mid
     * @access public
     * @return void
     */
    public function getEventContent( $eventId,$uid){
        //分別獲得使用者和照片資料庫模型
        $user = self::factoryModel( 'user' );
        //$photo = self::factoryModel( 'photo' );
        $map['id'] = $eventId;

        $result = $this->where( $map )->find();

        //檢查是否正確的管理員id
        if( $uid != $result['uid']  ){
            return false;
        }

        //追加相簿圖片
        //$result['photolist'] = $photo->getPhotos( $eventId,10 );
        //追加參與者和關注者
        $join = $att = array();
        $att['action'] = 'attention';
        $att['eventId'] = $result['id'];
        $att['status']  = 1;
        $join = $att;
        $join['action'] = 'joinIn';

        $result['attention'] = $user->getUserList( $att,4 );

        $result['member']    = $user->getUserList( $join,16 );

        $result['lc'] = 5000000 < $result['limitCount'] ? "無限制":$result['limitCount'];
        $result['cover']     = getCover($result['coverId'],200,200);
        $result = $this->appendContent( $result );
        return $result;
    }

    public function doEditEvent($eventMap,$optsMap,$cover,$id){
        $eventMap['cTime'] = isset( $eventMap['cTime'] )?$eventMap['cTime']:time();
        $eventMap['coverId']   = $cover['info'][0]['attach_id']>0?$cover['info'][0]['attach_id']:0;
        $eventMap['limitCount']  = 0 == intval($eventMap['limitCount']) ? 999999999:$eventMap['limitCount'];

        $has_friend        = $optsMap['opts']['friend'];
        $optsMap['opts']   = serialize( $optsMap['opts'] );

        //false
        $optsDao = self::factoryModel( 'opts' );
        if( false !== $optsDao->where( 'id='.$id['optsId'] )->save( $optsMap )){
            $addId    = $this->where( 'id ='.$id['id'] )->save( $eventMap );
        }else{
            return false;
        }

        return $addId;

    }

    /**
     * factoryModel
     * 工廠方法
     * @param mixed $name
     * @static
     * @access private
     * @return void
     */
    public static function factoryModel( $name ){
        return D("Event".ucfirst( $name ), 'event');
    }

    /**
     * doAddUser
     * 添加使用者行為
     * @param mixed $data
     * @param mixed $allow
     * @access public
     * @return void
     */
    public function doAddUser( $data,$allow ){
        $userDao = self::factoryModel( 'user' );
        $optsDao = self::factoryModel( 'opts' );

        //檢查這個id是否存在
        if( false == $event = $this->where( 'id ='.$data['id'] )->find() ){
            return -1;
        }

        if( $data['action'] === 'joinIn' ){
            //自動獲取已填寫的聯繫方式
            $contact_fields = M('user_set')->where('module=\'contact\'')->findAll();
            $contact_info   = M('user_profile')->getField('data',"uid={$data['uid']} AND module='contact'");
            $contact_info   = unserialize($contact_info );

            $need_fields = array('手機','QQ','MSN');
            foreach( $contact_fields as $field ){
                if( in_array($field['fieldname'],$need_fields) && !empty($contact_info[$field['fieldkey']])){
                    $contacts .= $field['fieldname'].':'.$contact_info[$field['fieldkey']].' ';
                }
            }
        }

        //檢查僅好友參與選項
        $opts = $optsDao->where( 'id='.$event['optsId'] )->find();
        $opt = unserialize($opts['opts']);
        $role = $this->checkMember( $event['uid'],$opt,$data['uid'] );

        //檢查是否已經加入或者關注
        if( $userDao->hasUser( $data['uid'],$data['id'],$data['action'] ) ){
            return -2;
        }
        //關注的話，再檢查是否已經加入
        if( $data['action'] == 'attention' && $userDao->hasUser( $data['uid'],$data['id'],'joinIn' ) ){
            return -2;
        }

        $map = $data;
        $map['eventId'] = $data['id'];
        unset( $map['id'] );
        $map['cTime']   = time();
        $map['contact'] = $contacts;
        switch( $data['action'] ){
        case "attention":
            if( false === $opts['canAtt'] ){
                return -2;
            }
            if($userDao->add( $map )){
                $this->setInc('attentionCount','id='.$map['eventId']);
                return 1;
            }else{
                return 0;
            }
            break;
        case "joinIn":
            if( false === $role['canJoin'] ){
                return -2;
            }
            if(!$role['follow']){
                return 0;
            }
            $map['status'] = $opt['allow']?0:1;

            //如果有關注的情況，直接更新為參與
            $temp_map['uid']      = $map['uid'];
            $temp_map['action']   = 'attention';
            $temp_map['eventId']  = $map['eventId'];
            if( $id = $userDao->where( $temp_map )->getField('id') ){
                if($res = $userDao->where("id={$id}")->save( $map )){
                    $this->setDec('attentionCount','id='.$map['eventId']);
                }
            }else{
                $res = $userDao->add( $map );
            }
            if( $res ){
                if($map['status']){
                    $this->setInc('joinCount','id='.$map['eventId']);
                    $this->setDec('limitCount','id='.$map['eventId']);
                    model('Credit')->setUserCredit($map['uid'],'join_event');
                }
                return 1;
            }else{
                return 0;
            }
            break;
        default:
            return -3;
        }
    }

    /**
     * doArgeeUser
     * 同意申請
     * @param mixed $data
     * @access public
     * @return void
     */
    public function doArgeeUser( $data ){
        $userDao = self::factoryModel( 'user' );
        if($userDao->where('id='.$data['id'])->setField( 'status',1)){
            $this->setInc('joinCount','id='.$data['eventId']);
            $this->setDec('limitCount','id='.$data['eventId']);
            model('Credit')->setUserCredit($data['uid'],'join_event');
            //如果有參與的情況。刪除參與的資料集
            $data['action'] = 'attention';
            if( $id = $userDao->where( $data )->getField('id') ){
                $userDao->delete( $id );
                $this->setDec('attentionCount','id='.$data['eventId']);
            }
            return 1;
        }
        return 0;
    }

    /**
     * doDelUser
     * 取消關注或參加
     * @param mixed $data
     * @access public
     * @return void
     */
    public function doDelUser( $data ){
        $userDao = self::factoryModel( 'user' );
        //檢查這個id是否存在
        if( false == $event = $this->where( 'id ='.$data['id'] )->find() ){
            return -1;
        }
        //檢查是否存在。如果存在，刪除這條記錄
        $map['uid']     = $data['uid'];
        $map['eventId'] = $data['id'];
        $map['action']  = $data['action'];
        //檢測是否存在這個使用者
        if( $event_user = $userDao->hasUser( $data['uid'],$data['id'],$data['action'] ) ){
            //刪除使用者操作記錄
            if($userDao->where( $map )->delete()){
                //記錄數相應減1
                $deleteMap['id'] = $map['eventId'];
                switch( $map['action'] ){
                case 'attention':
                    $delete = 'attentionCount';
                    $this->setDec( $delete,$deleteMap );
                    break;
                case 'joinIn':
                    if($event_user['status']){
                        $delete = "joinCount";
                        $this->setInc( 'limitCount',$deleteMap );
                        $this->setDec( $delete,$deleteMap );
                        model('Credit')->setUserCredit($data['uid'],'cancel_join_event');
                    }
                    break;
                }
                return 1;
            }
        }else{
            return -2;
        }
    }

    public function getMember( $map,$uid ){
        $user   = self::factoryModel( 'user' ) ;
        $result = $user->getUserList( $map,20,true);
        $data   = $result['data'];
        //修正成員狀態
        foreach ( $data as $key=>$value ){
            if( $value['uid'] == $uid ){
                $result['data'][$key]['role'] = "發起者";
            }else{
                if( 'joinIn' == $value['action'] ){
                    $result['data'][$key]['role'] = "成員";
                }
                if( 'attention' == $value['action'] ){
                    $result['data'][$key]['role'] = "關注中";
                }
                if( 'joinIn' == $value['action'] && 0 == $value['status'] ){
                    $result['data'][$key]['role'] = "待稽覈";
                }
            }
        }
        return $result;
    }

    public function doEditData( $time,$id ){
        //檢查安全性，防止非管理員訪問
        $uid = $this->where( 'id='.$id )->getField( 'uid' );
        if( $uid != $this->mid ){
            return -1;
        }

        if( $this->where( 'id='.$id )->setField( 'deadline',$time ) ){
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * getList
     * 供後臺管理獲取列表的方法
     * @param mixed $order
     * @param mixed $limit
     * @access public
     * @return void
     */
    public function getList( $map,$order,$limit ){
        $result = $this->where( $map )->order( $order )->findPage($limit);
        //將屬性追加
        foreach( $result['data'] as &$value ){
            $value = $this->appendContent( $value );
        }
        return $result;
    }

    /**
     * doDeleteEvent
     * 刪除活動
     * @param mixed $eventId
     * @access public
     * @return void
     */
    public function doDeleteEvent( $eventId ){
        //TODO 檢查是否是管理員

        if( empty( $eventId ) ){
            return false;
        }
        //取出選項ID
        $optsIds = $this->field('uid,optsId')->where( $eventId )->findAll();
        $uIds    = array();
        foreach($optsIds as &$v){
            //積分
            model('Credit')->setUserCredit($v['uid'],'delete_event');
            $v = $v['optsId'];
        }
        $opts_map['id'] = array('in',$optsIds);

        //刪除活動
        if( $this->where( $eventId )->delete() ){
            //刪除選項
            self::factoryModel( 'opts' )->where($opts_map)->delete();
            //刪除成員
            $user_map['eventId'] = $eventId['id'];
            self::factoryModel( 'user' )->where($user_map)->delete();
            return true;
        }

        return false;


    }
    /**
     * getConfig
     * 獲取配置
     * @param mixed $index
     * @access public
     * @return void
     */
    /*public function getConfig( $index ){
        $config = $this->config->$index;
        return $config;
    }*/

    /**
     * doIsHot
     * 設定推薦
     * @param mixed $map
     * @param mixed $act
     * @access public
     * @return void
     */
    public function doIsHot( $map,$act ){
        if( empty($map) ){
            throw new ThinkException( "不允許空條件操作資料庫" );
}
$optsIds = $this->where( $map )->getField( 'optsId' );
$map_opts['id'] = array('in',$optsIds);

switch( $act ){
case "recommend":   //推薦
    $data['isHot'] = 1;
    $data['rTime'] = time();
    $result = self::factoryModel( 'opts' )->where($map_opts)->save($data);
    break;
case "cancel":   //取消推薦
    $data['isHot'] = 0;
    $data['rTime'] = 0;
    $result = self::factoryModel( 'opts' )->where($map_opts)->save($data);
    break;
}
return $result;
}

/**
 * getHotList
 * 推薦列表
 * @param mixed $map
 * @param mixed $act
 * @access public
 * @return void
 */
public function getHotList(){
    $opts_ids  = self::factoryModel( 'opts' )->field('id')->where('isHot=1')->limit(5)->findAll();
    foreach( $opts_ids as &$v ){
        $v = $v['id'];
}
$event_map['optsId'] = array('in',$opts_ids);
$event_ids = $this->where($event_map)->findAll();
$typeDao   = self::factoryModel( 'type' );
foreach($event_ids as &$v){
    $v['type']    = $typeDao->getTypeName($v['type']);
    $v['address'] = getShort($v['address'],6);
    $v['coverId'] = getCover($v['coverId'],100,100);
}
return $event_ids;
}

/**
 * hasMember
 * 判斷是否是有這個成員
 * @param mixed $uid
 * @access public
 * @return void
 */
public function hasMember( $uid, $eventId){
    $user = self::factoryModel( 'user' );
    if( $result = $user->where( 'uid='.$uid.' AND eventId='.$eventId )->field( 'action,status' )->find() ){
        return $result;
}else{
    return false;
}
}
}
?>
