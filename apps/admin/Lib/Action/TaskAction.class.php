<?php
tsload(APPS_PATH.'/admin/Lib/Action/AdministratorAction.class.php');
/**
 * 任務後臺管理
 * @author Stream
 *
 */
class TaskAction extends AdministratorAction{
    function _initialize(){
        $this->pageTitle['index'] = '任務列表';
        $this->pageTitle['mainIndex'] = '任務列表';
        $this->pageTitle['customIndex'] = '任務列表';
        $this->pageTitle['editCustomTask'] = '編輯副本任務';
        $this->pageTitle['editTask'] = '編輯任務';
        $this->pageTitle['addTask'] = '添加任務';
        $this->pageTitle['reward'] = '任務獎勵';
        $this->pageTitle['editReward'] = '編輯獎勵';
        $this->pageTitle['taskConfig'] = '任務配置';
        parent::_initialize();
    }
    /**
     * 初始化列表管理選單
     * @param string $type 列表類型，index、pending、dellist
     */
    private function _initListAdminMenu() {
        // tab選項
        $this->pageTab[] = array('title'=>'每日任務','tabHash'=>'index','url'=>U('admin/Task/index'));
        $this->pageTab[] = array('title'=>'主線任務','tabHash'=>'mainIndex','url'=>U('admin/Task/mainIndex'));
        $this->pageTab[] = array('title'=>'副本任務','tabHash'=>'customIndex','url'=>U('admin/Task/customIndex'));
    }
    /**
     * 任務列表
     */
    public function index(){
        $this->_initListAdminMenu();

        $this->pageKeyList = array('task_name','step_name','step_desc','count','reward','medal','DOACTION');
        $list = model('Task')->where('task_type=1')->field('id,task_name,step_name,step_desc,reward')->order('task_type,task_level')->findPage();
        $ids = getSubByKey( $list['data'] , 'id' );
        $users = D('task_user')->query('select tid,count(tid) as tcount from '.C('DB_PREFIX').'task_user where tid in('.implode( ',' , $ids ).') group by tid');
        $iduser = array();
        foreach ( $users as $u ){
            $iduser[$u['tid']] = $u['tcount'];
        }
        foreach ( $list['data'] as &$v ){
            $v['count'] = $iduser[$v['id']] ? $iduser[$v['id']] : 0;
            $reward = json_decode( $v['reward'] );
            $src = $reward->medal->src;
            $v['reward'] = '經驗+'.$reward->exp.' 財富+'.$reward->score;
            $src && $v['medal'] = '<img width="100px" height="100px" src="'.getImageUrl ( $src ).'" />';
            $v['DOACTION'] = "<a href='".U('admin/Task/editTask',array('id'=>$v['id']))."' >編輯</a>";
        }
        $this->allSelected = false;
        $this->displayList($list);
    }
    /**
     * 副本任務
     */
    public function customIndex(){
        $this->_initListAdminMenu();

        $this->pageKeyList = array('task_name','task_desc','condesc','count','reward','medal','DOACTION');
        $list = D('task_custom')->field('id,task_name,task_desc,task_condition,`condition`,reward')->order('id')->findPage();
        $ids = getSubByKey( $list['data'] , 'id' );
        $users = D('task_receive')->query('select task_level,count(task_level) as tcount from '.C('DB_PREFIX').'task_receive where task_level in('.implode( ',' , $ids ).') and task_type=3 group by task_level');
        $iduser = array();
        foreach ( $users as $u ){
            $iduser[$u['task_level']] = $u['tcount'];
        }
        foreach ( $list['data'] as &$v ){
            $v['count'] = $iduser[$v['id']];
            $reward = json_decode( $v['reward'] );
            $src = $reward->medal->src;
            $v['reward'] = '經驗+'.$reward->exp.' 財富+'.$reward->score;
            $src && $v['medal'] = '<img width="100px" height="100px" src="'.getImageUrl( $src ).'" />';
            $condesc = '';
            if ( $v['task_condition'] ){
                $c = explode( '-' , $v['task_condition']);
                $tname = D('Task')->where('task_type='.$c[0].' and task_level='.$c[1])->getField('task_name');
                $condesc .= '前置任務:'.$tname.'</br>';
            }
            $condition = json_decode( $v['condition'] );
            foreach ( $condition as $k=>$con ){
                switch ( $k ){
                case 'endtime':
                    $endtime = explode ( '|' , $condition->endtime );
                    $condesc .=  '領取時間：'.$endtime[0].' - '.$endtime[1].'</br>';
                    break;
                case 'userlevel':
                    $condesc .= '使用者等級：T( '.$condition->userlevel.' )'.'</br>' ;
                    break;
                case 'usergroup':
                    $groups = explode ( ',' , $condition->usergroup );
                    $gname = '';
                    foreach ( $groups as $g ){
                        $ginfo = model('UserGroup')->getUserGroup( $g );
                        $gname .= ' '.$ginfo['user_group_name'];
                    }
                    $condesc .= '使用者組：'.$gname.'</br>' ;
                    break;
                case 'regtime':
                    $regtime = explode ( '|' , $condition->regtime );
                    $condesc .= '使用者註冊時間：'.$regtime[0].' - '.$regtime[1].'</br>' ;
                    break;
                case 'topic':
                    $topic = $condition->topic;
                    $condesc .= '釋出指定話題：'.$topic.'</br>' ;
                    break;
                }
            }
            $v['condesc'] = $condesc;
            $v['DOACTION'] = "<a href='".U('admin/Task/editCustomTask',array('id'=>$v['id'],'tabHash'=>'customIndex'))."' >編輯</a>";
            $v['DOACTION'] .= " <a href='javascript:void(0)' onclick='admin.delcustomtask(".$v['id'].")'>刪除</a>";
        }

        $this->pageButton[] = array( 'title' => '添加任務' , 'onclick' => "javascript:location.href='".U('admin/Task/addTask',array('tabHash'=>'customIndex'))."';" );
        $this->pageButton[] = array( 'title' => '刪除' , 'onclick' => "admin.delcustomtask()" );
        $this->displayList($list);
    }
    /**
     * 刪除任務
     */
    public function doDeleteCustomTask(){
        $ids = $_POST['id'];
        $map['id'] = array( 'in' , explode( ',' , $ids ) );
        $res = model('TaskCustom')->where($map)->delete();
        if( $res ){
            $return['status'] = 1;
            $return['data'] = '刪除成功';
        } else {
            $return['status'] = 0;
            $return['data'] = '刪除失敗';
        }
        exit(json_encode($return));
    }
    public function mainIndex(){
        $this->_initListAdminMenu();

        $this->pageKeyList = array('task_name','step_name','step_desc','count','reward','medal','DOACTION');
        $list = model('Task')->where('task_type=2')->field('id,task_name,step_name,step_desc,reward')->order('task_type,task_level')->findPage();
        $ids = getSubByKey( $list['data'] , 'id' );
        $users = D('task_user')->query('select tid,count(tid) as tcount from '.C('DB_PREFIX').'task_user where tid in('.implode( ',' , $ids ).') group by tid');
        $iduser = array();
        foreach ( $users as $u ){
            $iduser[$u['tid']] = $u['tcount'];
        }
        foreach ( $list['data'] as &$v ){
            $v['count'] = $iduser[$v['id']] ? $iduser[$v['id']] : 0;
            $reward = json_decode( $v['reward'] );
            $src = $reward->medal->src;
            $v['reward'] = '經驗+'.$reward->exp.' 財富+'.$reward->score;
            $src && $v['medal'] = '<img width="100px" height="100px" src="'.getImageUrl( $src ).'" />';
            $v['DOACTION'] = "<a href='".U('admin/Task/editTask',array('id'=>$v['id'],'type'=>2,'tabHash'=>'mainIndex'))."' >編輯</a>";
        }
        $this->allSelected = false;
        $this->displayList($list);
    }
    public function addTask(){
        $this->_initListAdminMenu();
        $groups = model('UserGroup')->getAllGroup();
        $this->pageKeyList = array ( 'task_name','task_desc',
            array( 'end_time1' , 'end_time2' ) ,
            'userlevel',
            'usergroup',array('reg_time1','reg_time2'),'topic','task_condition','num','exp','score','attach_id','attach_small','medal_name','medal_desc');

        $this->opt['userlevel'] = array( 1=>'T1',2=>'T2',3=>'T3'
            ,4=>'T4',5=>'T5',6=>'T6'
            ,7=>'T7',8=>'T8',9=>'T9',10=>'T10');
        $this->opt['usergroup'] = $groups;
        $this->opt['task_condition'] = array( 0 => '無','1-1'=>'每日任務' , '2-1'=> '新手任務' , '2-2'=> '進階任務' , '2-3'=> '達人任務' , '2-4'=> '高手任務 ' , '2-5'=> '終極任務 ' );

        $this->notEmpty = array('task_name');
        $this->savePostUrl = U('admin/Task/doAddTask');

        $data['userlevel'] = array(1,2,3,4,5,6,7,8,9,10);
        $data['usergroup'] = array(1,2,3,5,6,7);
        $this->displayConfig($data);
    }
    /**
     *
     * @param unknown_type $data
     */
    private function validate( $data ){
        if ( trim( $data['task_name'] ) == ''){
            $this->error( '任務名稱不能為空！' );
        }

    }
    public function doAddTask(){
        $taskname = t ( $_POST['task_name'] );
        $taskdesc = t ( $_POST['task_desc'] );
        $num = intval ( $_POST['num'] );
        $taskcondition = intval ( $_POST['task_condition'] );
        $exp = abs ( intval( $_POST['exp'] ) );
        $score = abs ( intval( $_POST['score'] ) );
        $medal =  intval( $_POST['attach_id'] );
        $medalsmall = intval( $_POST['attach_small'] );

        $this->validate( $_POST );

        if ( strtotime( $_POST['end_time1'][0] ) > strtotime( $_POST['end_time1'][1] ) ){
            $this->error('領取的開始時間不能大於結束時間');
        }
        if ( strtotime( $_POST['reg_time1'][0] ) > strtotime( $_POST['reg_time1'][1] ) ){
            $this->error('註冊的開始時間不能大於結束時間');
        }

        if ( $medal ){
            $data['name'] = t ( $_POST['medal_name'] );
            $exist = model( 'Medal' )->where($data)->find();
            if ( $exist ){
                $this->error('已存在相同的勳章名稱');
            } else {
                $data['desc'] = t ( $_POST['medal_desc'] );

                if ( !$medalsmall ){
                    $this->error('請上傳勳章的小圖片');
                }

                $attach = model('Attach')->getAttachById( $medal );
                $src = $attach['save_path'].$attach['save_name'];
                $data['src'] = $medal.'|'.$src;

                $attachsmall = model('Attach')->getAttachById( $medalsmall );
                $srcsmall = $attachsmall['save_path'].$attachsmall['save_name'];
                $data['small_src'] = $medalsmall.'|'.$srcsmall;

                $data['type'] = 3;//自定義任務勳章
                $medal_id = model('Medal')->add($data);
            }
        }

        $task['task_name'] = $taskname;
        $task['task_desc'] = $taskdesc;
        $task['num'] = $num;
        $task['task_condition'] = $taskcondition;
        $task['medal_id'] = $medal_id;
        $task['reward'] = json_encode( array( 'exp' => $exp , 'score' => $score , 'medal' => array( 'id' => $medal_id , 'name'=>$data['name'] , 'src'=>$src ) ));

        $condition = array();
        if ( $_POST['end_time1'][0] || $_POST['end_time1'][1] ){
            $condition['endtime'] = t ( $_POST['end_time1'][0] ) .'|'. t ( $_POST['end_time1'][1] ) ;
        }
        if ( $_POST['reg_time1'][0] || $_POST['reg_time1'][1] ){
            $condition['regtime'] = t ( $_POST['reg_time1'][0] ) .'|'.t ( $_POST['reg_time1'][1] ) ;
        }
        if ( $_POST['userlevel'] ){
            $condition['userlevel'] = implode(',' , $_POST['userlevel'] );
        }
        if ( $_POST['usergroup'] ){
            $condition['usergroup'] = implode( ',' , $_POST['usergroup'] );
        }
        if ( $_POST['topic'] ){
            $condition['topic'] = t ( $_POST['topic'] );
        }


        $task['condition'] = json_encode( $condition );

        $res = D('task_custom')->add($task);
        if ( $res ){
            $this->assign( 'jumpUrl' , U('admin/Task/customIndex' , array('tabHash'=>'customIndex') ));
            $this->success('添加成功');
        } else {
            $this->error('添加失敗');
        }
    }
    /**
     * 編輯自定義任務
     */
    public function editCustomTask(){
        $this->_initListAdminMenu();
        $groups = model('UserGroup')->getAllGroup();
        $this->pageKeyList = array ( 'id','task_name','task_desc',
            array( 'end_time1' , 'end_time2' ) ,
            'userlevel',
            'usergroup',array('reg_time1','reg_time2'),'topic','task_condition','num','exp','medal_id','medal_name','medal_src','score');

        $this->opt['userlevel'] = array( 1=>'T1',2=>'T2',3=>'T3'
            ,4=>'T4',5=>'T5',6=>'T6'
            ,7=>'T7',8=>'T8',9=>'T9',10=>'T10');
        $this->opt['usergroup'] = $groups;
        $this->opt['task_condition'] = array( 0 => '無','1-1'=>'每日任務' , '2-1'=> '新手任務' , '2-2'=> '進階任務' , '2-3'=> '達人任務' , '2-4'=> '高手任務 ' , '2-5'=> '終極任務 ' );
        $this->notEmpty = array('task_name');
        $this->savePostUrl = U('admin/Task/doAddTask');

        $task = D('task_custom')->where('id='.intval($_GET['id']))->find();

        $condition = json_decode( $task['condition'] );
        $reward = json_decode( $task['reward'] );
        $task['end_time1'] = explode ( '|' , $condition->endtime );
        $task['reg_time1'] = explode ( '|' , $condition->regtime );
        $task['topic'] = $condition->topic;
        $task['userlevel'] = explode ( ',' , $condition->userlevel );
        $task['usergroup'] = explode ( ',' , $condition->usergroup );
        $task['exp'] = $reward->exp;
        $task['score'] = $reward->score;
        $task['medal_id'] = $reward->medal->id;
        $task['medal_name'] =$reward->medal->name;
        $task['medal_src'] = $reward->medal->src;
        $this->savePostUrl = U('admin/Task/doEditCustomTask');
        $this->displayConfig($task);
    }
    public function doEditCustomTask(){
        $taskname = t ( $_POST['task_name'] );
        $taskdesc = t ( $_POST['task_desc'] );
        $num = abs ( intval ( $_POST['num'] ) );
        $taskcondition = t ( $_POST['task_condition'] );
        $exp = abs ( intval( $_POST['exp'] ) );
        $score = abs ( intval( $_POST['score'] ) );
        $medalid = intval( $_POST['medal_id'] );
        $medalname = t ( $_POST['medal_name'] );
        $medalsrc = t ( $_POST['medal_src'] );

        $this->validate( $_POST );
        if ( strtotime( $_POST['end_time1'][0] ) > strtotime( $_POST['end_time1'][1] ) ){
            $this->error('領取的開始時間不能大於結束時間');
        }
        if ( strtotime( $_POST['reg_time1'][0] ) > strtotime( $_POST['reg_time1'][1] ) ){
            $this->error('註冊的開始時間不能大於結束時間');
        }
        // 		$medal =  intval( $_POST['attach_id'] );
        // 		if ( $medal ){
        // 			$data['name'] = t ( $_POST['medal_name'] );
        // 			$exist = model( 'Medal' )->where($data)->find();
        // 			if ( $exist ){
        // 				$this->error('已存在相同的勳章名稱');
        // 			} else {
        // 				$data['desc'] = t ( $_POST['medal_desc'] );
        // 				$attach = model('Attach')->getAttachById( $medal );
        // 				$src = $attach['save_path'].$attach['save_name'];
        // 				$data['src'] = $medal.'|'.$src;
        // 				$medal_id = model('Medal')->add($data);
        // 			}
        // 		}

        $task['task_name'] = $taskname;
        $task['task_desc'] = $taskdesc;
        $task['num'] = $num;
        $task['task_condition'] = $taskcondition;
        $task['reward'] = json_encode( array( 'exp' => $exp , 'score' => $score , 'medal' => array( 'id' => $medalid  , 'name'=>$medalname , 'src'=>$medalsrc ) ));

        $iscondition = false;
        $condition = array();
        if ( $_POST['end_time1'][0] || $_POST['end_time1'][1] ){
            $condition['endtime'] = t ( $_POST['end_time1'][0] ) .'|'. t ( $_POST['end_time1'][1] ) ;
            $iscondition = true;
        }
        if ( $_POST['reg_time1'][0] || $_POST['reg_time1'][1] ){
            $condition['regtime'] = t ( $_POST['reg_time1'][0] ) .'|'.t ( $_POST['reg_time1'][1] ) ;
            $iscondition = true;
        }
        if ( $_POST['userlevel'] ){
            $condition['userlevel'] = implode(',' , $_POST['userlevel'] );
            $iscondition = true;
        }
        if ( $_POST['usergroup'] ){
            $condition['usergroup'] = implode( ',' , $_POST['usergroup'] );
            $iscondition = true;
        }
        if ( $_POST['topic'] ){
            $condition['topic'] = t ( $_POST['topic'] );
            $iscondition = true;
        }

        if ( !$iscondition ){
            $this->error('請選擇任務條件');
        }

        $task['condition'] = json_encode( $condition );

        $res = D('task_custom')->where('id='.intval($_POST['id']))->save($task);
        if ( $res ){
            $this->assign( 'jumpUrl' , U('admin/Task/customIndex',array('tabHash'=>'customIndex')) );
            $this->success('編輯成功');
        } else {
            $this->error('編輯失敗');
        }
    }
    /**
     * 編輯任務
     */
    public function editTask(){
        $id = $_REQUEST['id'];
        $taskinfo = model('Task')->where('id='.$id)->find();
        $condition = json_decode( $taskinfo['condition'] );
        $conkey = key( $condition );

        $this->pageKeyList = array('id','task_name','step_name','step_desc','task_type','condition','num','exp','score','action','medal');
        $this->_initListAdminMenu();

        $reward = json_decode( $taskinfo['reward'] );
        $taskinfo['condition'] = $conkey;
        $taskinfo['num'] = $condition->$conkey;
        $taskinfo['exp'] = $reward->exp;
        $taskinfo['score'] = $reward->score;
        $taskinfo['medal'] = $reward->medal->id;

        $medals = model('Medal')->getAllMedal();
        $medals[0] = '無';
        ksort($medals);
        $this->opt['medal'] = $medals;
        $this->notEmpty = array('task_name','step_name');
        $this->savePostUrl = U('admin/Task/doEditTask');
        $this->displayConfig($taskinfo);
    }
    public function doEditTask(){
        $exp = intval ( $_REQUEST['exp'] );
        $score = intval ( $_REQUEST['score'] );
        $medal = intval ( $_REQUEST['medal'] );
        $num = abs ( intval( $_REQUEST['num'] ) );

        $condition = t ( $_REQUEST['condition'] );
        $taskname = t ( $_REQUEST['task_name'] );
        $stepname = t ( $_REQUEST['step_name'] );
        $stepdesc = t ( $_REQUEST['step_desc'] );
        $action = t ( $_REQUEST['action'] );
        if ( !$taskname ){
            $this->error('任務類型不能為空');
        }
        if ( !$stepname ){
            $this->error('任務名稱不能為空');
        }
        if ( $medal ){
            $name = model('Medal')->where('id='.$medal)->field('name,src')->find();
            $src = explode('|', $name['src']);
            $medalinfo = array( 'id' => $medal, 'name'=> $name['name'] , 'src' => $src[1] );
        }
        $reward = array ( 'exp' => $exp , 'score' => $score , 'medal' => $medalinfo );
        $condition = array( $condition=>$num );

        $data['task_name'] = $taskname;
        $data['step_name'] = $stepname;
        $data['step_desc'] = $stepdesc;
        $data['condition'] = json_encode( $condition );
        $data['reward'] = json_encode( $reward );
        $data['action'] = $action;

        $res = model( 'Task' )->where('id='.intval( $_REQUEST['id'] ))->save($data);
        if ( $res ) {
            $url = $_REQUEST['task_type'] == 1 ? 'index' : 'mainIndex';
            $this->assign('jumpUrl', U('admin/Task/'.$url,array('tabHash'=>$url)));
            $this->success(L('編輯成功'));
        } else {
            $this->error('編輯失敗');
        }
    }
    /**
     * 任務獎勵列表
     */
    public function reward(){
        $this->pageKeyList = array('task_name','reward','medal','DOACTION');
        $list = model('Task')->group('task_name')->where('task_type=2')->field('task_name,task_level,task_type')->findPage();
        foreach ( $list['data'] as &$v ){
            $taskreward = D('task_reward')->where('task_type='.$v['task_type'].' and task_level='.$v['task_level'])->getField('reward');
            $reward = json_decode( $taskreward );
            $src = $reward->medal->src;
            $v['reward'] = '經驗+'.intval($reward->exp).' 財富+'.intval($reward->score);
            if ( $src ){
                $v['medal'] = '<img width="100px" height="100px" src="'.getImageUrl( $src ).'" />';
            }
            $v['DOACTION'] = '<a href="'.U('admin/Task/editReward',array('task_level'=>$v['task_level'],'task_type'=>$v['task_type'])).'">編輯</a>';
        }
        $this->allSelected = false;
        $this->displayList($list);
    }
    /**
     * 編輯任務獎勵
     */
    public function editReward(){
        $tasklevel = intval ( $_GET['task_level'] );
        $tasktype = intval( $_GET['task_type'] );

        if ( $tasktype == 1 ){
            $this->pageKeyList = array('task_type','task_level','exp','score');
    } else {
        $this->pageKeyList = array('task_type','task_level','exp','score','medal');
    }

    $info = D('task_reward')->where('task_level='.$tasklevel.' and task_type='.$tasktype)->find();
    $reward = json_decode( $info['reward'] );
    $data['task_type'] = $tasktype;
    $data['task_level'] = $tasklevel;
    $data['exp'] = $reward->exp;
    $data['score'] = $reward->score;
    $data['medal'] = $reward->medal->id;
    $medals = model('Medal')->getAllMedal();
    $medals[0] = '無';
    ksort($medals);
    $this->opt['medal'] = $medals;
    $this->savePostUrl = U('admin/Task/doEditReward');
    $this->displayConfig($data);
    }
    public function doEditReward(){
        $tasktype = $_REQUEST['task_type'];
        $tasklevel = $_REQUEST['task_level'];

        $exp = intval ( $_REQUEST['exp'] );
        $score = intval ( $_REQUEST['score'] );
        $medal = intval ( $_REQUEST['medal'] );

        if ( $medal ){
            $name = model('Medal')->where('id='.$medal)->field('name,src')->find();
            $src = explode('|', $name['src']);
            $medalinfo = array( 'id' => $medal, 'name'=> $name['name'] , 'src' => $src[1] );
    }
    $reward = json_encode ( array ( 'exp' => $exp , 'score' => $score , 'medal' => $medalinfo ) );
    $isexist = D('task_reward')->where('task_type='.$tasktype.' and task_level='.$tasklevel)->find();
    if ( $isexist ){
        D('task_reward')->setField( 'reward' , $reward , 'task_type='.$tasktype.' and task_level='.$tasklevel );
    } else {
        $data['task_type'] = $tasktype;
        $data['task_level'] = $tasklevel;
        $data['reward'] = $reward;
        D('task_reward')->add($data);
    }
    $this->assign('jumpUrl', U('admin/Task/reward'));
    $this->success(L('編輯成功'));
    }
    public function taskConfig(){
        if ( $_POST ){
            $taskswitch = $_POST['task_switch'];
            model( 'Xdata' )->saveKey( 'task_config:task_switch' , $taskswitch );
            $this->success( '儲存成功' );
    }
    $data['task_switch'] = model('Xdata')->get('task_config:task_switch');
    !$data['task_switch'] && $data['task_switch'] = 1;
    $this->pageKeyList = array('task_switch');
    $this->opt['task_switch'] = array(1=>'開',2=>'關');
    $this->savePostUrl = U('admin/Task/taskConfig');
    $this->displayConfig($data);
    }
    }
?>
