<?php
/**
 * 任務Dao類
 * @author Stream
 *
 */
class TaskModel extends Model{
    protected $tableName = 'task';

    public function getUserTask( $type , $uid ){
        //使用者任務執行情況   主要判斷 ：是新手任務，進階任務 ......
        $data = D('task_user')->where('task_type='.$type.' and uid='.$uid)->order('task_level desc')->find();
        //返回使用者當前執行的任務名稱及任務等級 和類型
        return $data;
    }
    public function getTaskList( $tasktype , $uid ){
        $tasklevel = 1;
        //判斷主線任務執行階段
        if ( $tasktype == 2 ){
            $task = $this->getUserTask( $tasktype , $uid);
            $tasklevel = $task['task_level'] ? $task['task_level'] : 1;
        }
        //重新整理執行任務狀態
        $this->isComplete($tasktype, $uid , $tasklevel);
        //根據類型返回任務列表
        $list = D('task_user')->where('task_type='.$tasktype.' and task_level='.$tasklevel.' and uid='.$uid)->findAll();
        $map['id'] = array( 'in' , getSubByKey( $list , 'tid' ));
        $steplist = $this->where($map)->field('id,task_name,step_name,step_desc,action,reward')->findAll();
        $steps = array();
        foreach ( $steplist as $s ){
            $steps[$s['id']]['step_name'] = $s['step_name'];
            $steps[$s['id']]['step_desc'] = $s['step_desc'];
            $steps[$s['id']]['action'] = $s['action'] ? U($s['action']) : '';
            $steps[$s['id']]['reward'] = json_decode( $s['reward'] );
        }
        $iscomplete = 1;
        foreach ( $list as &$v ){
            if ( !$v['status'] || !$v['receive']){
                $iscomplete = 0;
            }
            $v['step_name'] = $steps[$v['tid']]['step_name'];
            $v['step_desc'] = $steps[$v['tid']]['step_desc'];
            $v['action'] = $steps[$v['tid']]['action'];
            $v['reward'] = $steps[$v['tid']]['reward'];
        }
        $redata['list'] = $list;
        $redata['task_name'] = $steplist[0]['task_name'];
        $redata['task_type'] = $tasktype;
        $redata['task_level'] = $tasklevel;
        $redata['iscomplete'] = $iscomplete;
        $redata['receive'] = false;
        //每日任務
        if ( $iscomplete ){
            //是否領取獎勵
            $rmap['task_type'] = $tasktype;
            $rmap['task_level'] = $tasklevel;
            $rmap['uid'] = $uid;
            if ( $tasktype == 1){
                $rmap['ctime'] = array('gt' , strtotime( date('Ymd') ));
            }
            $redata['receive'] = D('task_receive')->where($rmap)->limit(1)->count();
        }
        return $redata;
    }

    private function isComplete( $tasktype , $uid , $tasklevel){
        $map['task_type'] = $tasktype;
        $map['uid'] = $uid;
        $map['task_level'] = $tasklevel;
        //每日任務判斷
        $list = true;
        if ( $tasktype == 1 ){
            $map['ctime'] = array( 'gt' , strtotime( date('Ymd') ) );
        }
        //判斷任務是否存在
        $list = D('task_user')->where($map)->find();
        if ( $list ){
            //更新未完成的任務
            $map['status'] = 0;
            $nocomplete = D('task_user')->where($map)->findAll();
            $tids = getSubByKey( $nocomplete , 'tid' );
            $task_map['id'] = array( 'in' , $tids );
            $tasks = $this->where($task_map)->findAll();
            $userdata = model( 'UserData')->getUserData( $uid );
            foreach ( $tasks as $t ){
                $condition = json_decode( $t['condition'] );
                $conkey = key( $condition );
                //判斷任務是否完成
                $res = $this->_executeTask( $conkey , $condition->$conkey , $uid , $tasktype , $userdata);
                if ( $res ){
                    //重新整理使用者任務執行狀態
                    D('task_user')->setField('status' , 1 , 'tid='.$t['id'].' and uid='.$uid);
                }
            }
        } else {
            //每日任務資料初始化
            if ( $tasktype == 1 ){
                //刪除歷史
                $dmap['task_type'] = $tasktype;
                $dmap['uid'] = $uid;
                D('task_user')->where($dmap)->delete();

            }
            //初始化新的資料
            $tmap['task_type'] = $tasktype;
            $tmap['task_level'] = 1;
            if ( $this->addTask($tmap, $uid) ){
                //重新判斷任務程序
                $this->isComplete($tasktype, $uid ,$tasklevel);
            }
        }
    }

    public function completeTask( $tasktype , $tasklevel = 1 , $uid ){
        $complete = D('task_user')->where('status=0 and task_type='.$tasktype.' and task_level='.$tasklevel.' and uid='.$uid)->find();
        //是否完成
        if ( !$complete ){
            //是否重複領取
            $remap['uid'] = $uid;
            $remap['task_type'] = $tasktype;
            $remap['task_level'] = $tasklevel;
            if ( $tasktype == 1 ){
                $remap['ctime'] = array('gt' , strtotime( date('Ymd') ));
            }
            $receive = D('task_receive')->where($remap)->find();
            if ( $receive ){ return false; }
            //記錄領獎記錄
            $data['uid'] = $uid;
            $data['task_type'] = $tasktype;
            $data['task_level'] = $tasklevel;
            $data['ctime'] = $_SERVER['REQUEST_TIME'];
            $res = D('task_receive')->add($data);

            //初始化新的任務
            $map['task_level'] = $tasklevel + 1;
            $map['task_type'] = $tasktype;
            $this->addTask( $map , $uid );
            return $res;
        }
    }
    public function addTask( $map , $uid ){
        //查詢新的任務程序
        $list = $this->where($map)->findAll();
        if ( !$list ){
            return false;
        }
        foreach ( $list as $v ){
            $udata['uid'] = $uid;
            $udata['tid'] = $v['id'];
            $udata['task_level'] = $v['task_level'];
            $udata['task_type'] = $v['task_type'];
            $udata['ctime'] = $_SERVER['REQUEST_TIME'];
            $udata['status'] = 0;
            $udata['desc'] = '';
            $udata['receview'] = 0;
            //加入任務表
            D('task_user')->add($udata);
        }
        return true;
    }

    public function _executeTask( $excutetype , $num , $uid , $type , $userdata){
        //每日任務判斷
        if ( $type == 1){
            $starttime = strtotime( date('Ymd') );
            switch ( $excutetype ){
            case 'weibopost':
                $rescount = model('Feed')->where("uid=".$uid." and is_repost=0 and publish_time>".$starttime)->limit($num)->count();
                break;
            case 'weiborepost':
                $rescount = model('Feed')->where("uid=".$uid." and type='repost' and publish_time>".$starttime)->limit($num)->count();
                break;
            case 'weibocomment':
                $rescount = model('Comment')->where('uid='.$uid." and `table`='feed' and ctime>".$starttime)->limit($num)->count();
                break;
            case 'checkin':
                $rescount = D('check_info')->where('uid='.$uid.' and ctime>'.$starttime)->limit($num)->count();
                break;
            }
        } else {
            switch ( $excutetype ){
                //原創微博
            case 'weibopost':
                $rescount = model('Feed')->where('uid='.$uid." and is_repost=0")->limit($num)->count();
                break;
                //轉發微博
            case 'weiborepost':
                $rescount = model('Feed')->where('uid='.$uid." and type='repost'")->limit($num)->count();
                break;
                //微博評論
            case 'weibocomment':
                $rescount = model('Comment')->where('uid='.$uid." and `table`='feed'")->limit($num)->count();
                break;
                //上傳頭像
            case 'uploadface':
                $res = model('Avatar')->hasAvatar();
                return $res ? true : false;
                break;
                //粉絲數
            case 'following':
                $rescount = $userdata['follower_count'];
                break;
                //簽到
            case 'checkin':
                $rescount = D('check_info')->where('uid='.$uid.' and con_num>='.$num)->getField('con_num');
                break;
                //使用者資訊
            case 'userinfo':
                //個人標籤
                $tags = implode(',',model('Tag')->setAppName('public')->setAppTable('user')->getAppTags($uid));
                $userinfo = model('User')->getUserInfo($uid);
                if ( $tags && $userinfo['intro'] ){
                    return true;
                } else {
                    return false;
                }
                break;
                //關注感興趣的人
            case 'followinterest':
                $rescount = $userdata['following_count'];
                break;
                //發表微博通知好友
            case 'weibotofriend':
                $fids = D('Feed')->where('uid='.$uid)->field('feed_id')->findAll();
                $map['row_id'] = array( 'in' , getSubByKey( $fids , 'feed_id') );
                $res = D('atme')->where($map)->find();
                return $res ? true : false;
                break;
                //使用者等級
            case 'userlevel':
                $credit = model('Credit')->getUserCredit($uid);
                $rescount = $credit['level']['level'];
                break;
                //微博被轉發
            case 'weibotranspost':
                $rescount = model('Feed')->where('uid='.$uid.' and repost_count>0')->limit($num)->count();
                break;
                //微博被評論
            case 'weiboreceivecomment':
                $rescount = model('Feed')->where('uid='.$uid.' and comment_count>0')->limit($num)->count();
                break;
                //單條微博被轉發
            case 'weiboonetranspost':
                $res = model('Feed')->where('uid='.$uid.' and repost_count>'.$num)->find();
                return $res ? true : false;
                break;
                //單條微博被評論
            case 'weiboonecomment':
                $res = model('Feed')->where('uid='.$uid.' and comment_count>'.$num)->find();
                return $res ? true : false;
                break;
                //轉髮指定微博
            case 'weiboappoint':
                $map['uid'] = $uid;
                $map['type'] = 'repost';
                $map['app_row_id'] = $num;
                $res = model('Feed')->where($map)->find();
                return $res ? true : false;
                break;
                //關注微吧
            case 'weibafollow':
                $res = D('weiba_follow')->where('follower_uid='.$uid)->find();
                return $res ? true : false;
                break;
                //微吧釋出帖子
            case 'weibapost':
                $rescount = D('weiba_post')->where('post_uid='.$uid)->limit($num)->count();
                break;
                //微吧精華
            case 'weibamarrow':
                $rescount = D('weiba_post')->where('post_uid='.$uid.' and digest=1')->limit($num)->count();
                break;
                //釋出部落格
            case 'blogpost':
                break;
                //轉發部落格
            case 'blogrepost':
                break;
                //部落格精華
            case 'blogmarrow':
                break;
                //相簿上傳真實圖片
            case 'phototrueimg':
                break;
                //喜歡一張相簿圖片
            case 'photolove':
                break;
                //相互關注
            case 'followmutual':
                $res = model('Follow')->getFriendsList($uid);
                $rescount = $res['count'];
                break;
                //頻道投稿
            case 'channelcontribute':
                $rescount = D('channel')->where('uid='.$uid.' and status=1')->limit($num)->count();
                break;
                //管理員
            case 'manager':
                $res = D('user_group_link')->where('uid='.$uid.' and user_group_id='.$num)->find();
                return $res ? true : false;
                break;
            }
        }
        return $rescount >= $num;
    }
    /**
     * 領取獎勵
     * @param unknown_type $exp
     * @param unknown_type $score
     * @param unknown_type $medal
     */
    public function getReward( $exp , $score , $medal , $uid){
        if ( $uid ){
            model( 'Credit' )->addTaskCredit( $exp , $score , $uid );
            if( $medal ){
                //添加勳章
                $data['uid'] = $uid;
                $data['medal_id'] = $medal;
                $data['ctime'] = $_SERVER['REQUEST_TIME'];
                D('medal_user')->add($data);
                //清除使用者快取
                model('User')->cleanCache($uid);
            }
        }
    }
}
?>
