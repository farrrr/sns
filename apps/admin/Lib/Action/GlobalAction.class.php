<?php
class GlobalAction extends AdministratorAction {

    private function __isValidRequest($field, $array = 'post') {
        $field = is_array($field) ? $field : explode(',', $field);
        $array = $array == 'post' ? $_POST : $_GET;
        foreach ($field as $v){
            $v = trim($v);
            if ( !isset($array[$v]) || $array[$v] == '' ) return false;
        }
        return true;
    }

    /** 系統配置 - 積分配置 **/
    //積分類別設定
    public function creditType(){
        $creditType = M('credit_type')->order('id ASC')->findAll();
        $this->assign('creditType',$creditType);
        $this->display();
    }
    public function editCreditType(){
        $type   = $_GET['type'];
        if($cid = intval($_GET['cid'])){
            $creditType = M('credit_type')->where("`id`=$cid")->find();//積分類別
            if (!$creditType) $this->error('無此積分類型');
            $this->assign('creditType',$creditType);
        }

        $this->assign('type', $type);

        $typeArr = array('score', 'experience'); //這兩個類型的積分類別名不能被修改
        if(in_array($creditType['name'], $typeArr) && $type=='edit'){
            $this->assign('forbidEdit', true);
        }

        $this->display();
    }
    public function doAddCreditType(){
        // if ( !$this->__isValidRequest('name') ) $this->error('資料不完整');
        $name = h(t($_POST['name']));
        $alias=h(t($_POST['alias']));
        if(empty($name) ){
            $this->error('名稱不能為空');
        }
        if(empty($alias) ){
            $this->error('別名不能為空');
        }

        $_POST = array_map('t',$_POST);
        $_POST = array_map('h',$_POST);

        $_LOG['uid'] = $this->mid;
        $_LOG['type'] = '1';
        $data[] = '全局 - 積分配置  - 積分類型';
        if( $_POST['__hash__'] )unset( $_POST['__hash__'] );
        $data[] = $_POST;
        $_LOG['data'] = serialize($data);
        $_LOG['ctime'] = time();
        M('AdminLog')->add($_LOG);

        $res = M('credit_type')->add($_POST);
        if ($res) {
            $db_prefix  = C('DB_PREFIX');
            $model = M('');
            $setting = $model->query("ALTER TABLE {$db_prefix}credit_setting ADD {$_POST['name']} INT(11) DEFAULT 0;");
            $user    = $model->query("ALTER TABLE {$db_prefix}credit_user ADD {$_POST['name']} INT(11) DEFAULT 0;");
            // 清快取
            F('_service_credit_type', null);
            // 資料表快取

            $this->assign('jumpUrl', U('admin/Global/creditType'));
            $this->success('儲存成功');
        }else {
            $this->error('儲存失敗');
        }
    }
    public function doEditCreditType(){
        // if ( !$this->__isValidRequest('id,name') ) $this->error('資料不完整');
        $name = h(t($_POST['name']));
        $alias=h(t($_POST['alias']));
        if(empty($name) ){
            $this->error('名稱不能為空');
        }
        if(empty($alias) ){
            $this->error('別名不能為空');
        }
        $_POST = array_map('t',$_POST);
        $_POST = array_map('h',$_POST);
        $creditTypeDao = M('credit_type');
        //獲取原欄位名
        $oldName = $creditTypeDao->find($_POST['id']);

        $typeArr = array('score', 'experience'); //這兩個類型的積分類別名不能被修改
        if(in_array($oldName, $typeArr)){
            unset($_POST['name']);
        }

        //修改欄位名
        $res = $creditTypeDao->save($_POST);

        $_LOG['uid'] = $this->mid;
        $_LOG['type'] = '3';
        $data[] = '全局 - 積分配置 - 積分類型 ';
        $data[] = $oldName;
        if( $_POST['__hash__'] )unset( $_POST['__hash__'] );
        $data[] = $_POST;
        $_LOG['data'] = serialize($data);
        $_LOG['ctime'] = time();
        M('AdminLog')->add($_LOG);

        if ($res) {
            $db_prefix  = C('DB_PREFIX');
            $model = M('');
            $setting = $model->query("ALTER TABLE {$db_prefix}credit_setting CHANGE {$oldName['name']} {$_POST['name']} INT(11);");
            $user    = $model->query("ALTER TABLE {$db_prefix}credit_user CHANGE {$oldName['name']} {$_POST['name']} INT(11);");
            // 清快取
            F('_service_credit_type', null);
            $this->assign('jumpUrl', U('admin/Global/creditType'));
            $this->success('儲存成功');
        }else {
            $this->error('儲存失敗');
        }
    }
    public function doDeleteCreditType(){
        $ids = t($_POST['ids']);
        $ids = explode(',', $ids);
        if ( empty($ids) ) {echo 0; return ;}

        $map['id'] = array('in', $ids);
        $creditTypeDao = M('credit_type');
        //獲取欄位名
        $typeName = $creditTypeDao->where($map)->findAll();

        $typeArr = array('score', 'experience'); // 這兩個類型的積分類別名不能被修改
        foreach ( $typeName as $type ) {
            if (in_array ( $type['name'], $typeArr )) {
                echo -2;
                exit;
            }
        }

        $_LOG['uid'] = $this->mid;
        $_LOG['type'] = '2';
        $data[] = '全局 - 積分配置 - 積分類型 ';
        $data[] = $typeName;
        $_LOG['data'] = serialize($data);
        $_LOG['ctime'] = time();
        M('AdminLog')->add($_LOG);

        //清除type資訊和對應欄位
        $res = M('credit_type')->where($map)->delete();
        if ($res){
            $db_prefix  = C('DB_PREFIX');
            $model = M('');
            foreach($typeName as $v){
                $setting = $model->query("ALTER TABLE {$db_prefix}credit_setting DROP {$v['name']};");
                $user    = $model->query("ALTER TABLE {$db_prefix}credit_user DROP {$v['name']};");
            }
            // 清快取
            F('_service_credit_type', null);
            echo 1;
        }else{
            echo 0;
        }
    }
    //積分規則設定
    public function credit() {
        $list = M('credit_setting')->order('type ASC')->findPage(100);
        $creditType = M('credit_type')->order('id ASC')->findAll();
        $this->assign('creditType',$creditType);
        $this->assign($list);
        $this->display();
    }
    public function addCredit() {
        $creditType = M('credit_type')->order('id ASC')->findAll();//積分類別
        $this->assign('creditType',$creditType);
        $this->assign('type','add');
        $this->display('editCredit');
    }
    public function doAddCredit() {
        $name = trim($_POST['name']);
        if($name == "" && $_POST['name'] != ""){
            $this->error('名稱不能為空格');
        }
        if ( !$this->__isValidRequest('name') ) $this->error('資料不完整');

        $_POST = array_map('t',$_POST);
        $_POST = array_map('h',$_POST);

        $creditType = M('credit_type')->order('id ASC')->findAll();
        foreach($creditType as $v){
            if(!is_numeric($_POST[$v['name']])){
                $this->error($v['alias'].'的值必須為數字！');
            }
        }

        $_LOG['uid'] = $this->mid;
        $_LOG['type'] = '1';
        $data[] = '全局 - 積分配置 - 積分規則 ';
        if( $_POST['__hash__'] )unset( $_POST['__hash__'] );
        $data[] = $_POST;
        $_LOG['data'] = serialize($data);
        $_LOG['ctime'] = time();
        M('AdminLog')->add($_LOG);

        $res = M('credit_setting')->add($_POST);
        if ($res) {
            // 清快取
            F('_service_credit_rules', null);
            $this->assign('jumpUrl', U('admin/Global/credit'));
            $this->success('儲存成功');
        }else {
            $this->error('儲存失敗');
        }
    }
    public function editCredit() {
        $cid    = intval($_GET['cid']);
        $credit = M('credit_setting')->where("`id`=$cid")->find();
        if (!$credit) $this->error('無此積分規則');

        $creditType = M('credit_type')->order('id ASC')->findAll();//積分類別
        $this->assign('creditType',$creditType);

        $this->assign('credit', $credit);
        $this->assign('type', 'edit');

        $this->display();
    }
    public function doEditCredit() {
        $name = trim($_POST['name']);
        if($name == "" && $_POST['name'] != ""){
            $this->error('名稱不能為空格');
        }
        if ( !$this->__isValidRequest('id,name') ) $this->error('資料不完整');

        $_POST = array_map('t',$_POST);
        $_POST = array_map('h',$_POST);

        $creditType = M('credit_type')->order('id ASC')->findAll();
        foreach($creditType as $v){
            if(!is_numeric($_POST[$v['name']])){
                $this->error($v['alias'].'的值必須為數字！');
            }
        }

        $_LOG['uid'] = $this->mid;
        $_LOG['type'] = '3';
        $data[] = '全局 - 積分配置 - 積分規則 ';
        $credit_info = M('credit_setting')->where('id='.$_POST['id'])->find();
        $data[] = $credit_info;
        $_POST['info'] = $credit_info['info'];
        if( $_POST['__hash__'] )unset( $_POST['__hash__'] );
        $data[] = $_POST;
        $_LOG['data'] = serialize($data);
        $_LOG['ctime'] = time();
        M('AdminLog')->add($_LOG);
        $id = $_POST['id'];
        unset($_POST['id']);
        $res = M('credit_setting')->where('id='.$id)->save($_POST);
        if ($res) {
            // 清快取
            F('_service_credit_rules', null);
            $this->assign('jumpUrl', U('admin/Global/credit'));
            $this->success('儲存成功');
        }else {
            $this->error('儲存失敗');
        }
    }
    public function doDeleteCredit() {
        $ids = t($_POST['ids']);
        $ids = explode(',', $ids);
        if ( empty($ids) ) {echo 0; return ;}

        $map['id'] = array('in', $ids);

        $_LOG['uid'] = $this->mid;
        $_LOG['type'] = '2';
        $data[] = '全局 - 積分配置 - 積分規則 ';
        $data[] = M('credit_setting')->where($map)->findAll();
        $_LOG['data'] = serialize($data);
        $_LOG['ctime'] = time();
        M('AdminLog')->add($_LOG);

        $res = M('credit_setting')->where($map)->delete();
        if ($res) {
            // 清快取
            F('_service_credit_rules', null);
            echo 1;
        } else {
            echo 0;
        }
    }
    //批量使用者積分設定
    public function creditUser(){
        $creditType = M('credit_type')->order('id ASC')->findAll();
        $this->assign('creditType',$creditType);
        $this->assign('grounlist',model('UserGroup')->getUserGroupByMap('','user_group_id, user_group_name'));
        $this->display();
    }
    public function doCreditUser(){
        set_time_limit(0);
        //查詢使用者ID
        $_POST['uId'] && $map['uid'] = array('in',explode(',',t($_POST['uId'])));
        $_POST['gId']!='all' && $map['user_group_id'] = intval($_POST['gId']);
        // $_POST['active']!='all' && $map['is_active'] = intval($_POST['active']);
        $user = M('user_group_link')->where($map)->field('uid')->findAll();
        if($user == false){
            $this->error('查詢失敗，沒有這樣條件的人');
        }
        //組裝積分規則
        $setCredit = model('Credit');
        $creditType = $setCredit->getCreditType();
        foreach($creditType as $v){
            $action[$v['name']] = intval($_POST[$v['name']]);
        }



        if($_POST['action'] == 'set'){//積分修改為
            foreach($user as $v){
                if($v['uid'] != 0){
                    $setCredit->setUserCredit($v['uid'],$action,'reset');
                    // if($setCredit->getInfo()===false)$this->error('儲存失敗');
                }
            }
        }else{//增減積分
            foreach($user as $v){
                if($v['uid'] != 0){
                    $setCredit->setUserCredit($v['uid'],$action);
                    if($setCredit->getInfo()===false)$this->error('儲存失敗');
                }
            }
        }

        $this->assign('jumpUrl', U('admin/Global/creditUser'));

        $_LOG['uid'] = $this->mid;
        $_LOG['type'] = '1';
        if( $_POST['action'] == 'set' ){
            $data[] = '全局 - 積分配置 - 設定使用者積分 - 積分修改操作 ';
        }else{
            $data[] = '全局 - 積分配置 - 設定使用者積分 - 積分增減操作 ';
        }
        $data['1'] = $action;
        $data['1']['uid'] = $_POST['uId'];
        $data['1']['gId'] = $_POST['gId'];
        $data['1']['active'] = $_POST['active'];
        $data['1']['action'] = $_POST['action'];
        $_LOG['data'] = serialize($data);
        $_LOG['ctime'] = time();
        M('AdminLog')->add($_LOG);

        $this->success('儲存成功');
    }

    //積分等級設定
    public function creditLevel(){
        $_REQUEST['tabHash'] = 'level';
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_POINT_RULE'),'tabHash'=>'rule','url'=>U('admin/Global/credit'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_POINT_TYPE'),'tabHash'=>'type','url'=>U('admin/Global/creditType'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_POINT_SETTING'),'tabHash'=>'user','url'=>U('admin/Global/creditUser'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_POINT_LEVEL'),'tabHash'=>'level','url'=>U('admin/Global/creditLevel'));
        $this->pageTitle[ACTION_NAME] = L('PUBLIC_SYSTEM_POINT_LEVEL');
        $list = model('Credit')->getLevel();
        $this->assign('list',$list);
        $this->display('admin_creditlevel');
    }

    public function setCreditLevel(){
        if(!empty($_POST)){ //添加&編輯積分類型
            $res = model('Credit')->saveCreditLevel($_POST);
            $this->assign('jumpUrl',U('admin/Global/creditLevel'));
            $this->success();
            exit();
        }

        $_REQUEST['tabHash'] = 'level';
        $this->pageKeyList = array('level','name','image','start','end');

        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_POINT_RULE'),'tabHash'=>'rule','url'=>U('admin/Global/credit'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_POINT_TYPE'),'tabHash'=>'type','url'=>U('admin/Global/creditType'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_POINT_SETTING'),'tabHash'=>'user','url'=>U('admin/Global/creditUser'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_POINT_LEVEL'),'tabHash'=>'level','url'=>U('admin/Global/creditLevel'));

        $this->savePostUrl = U('admin/Global/setCreditLevel');
        $list = model('Credit')->getLevel();
        $detailData = $list[$_GET['level']-1];
        $this->pageTitle[ACTION_NAME] = L('PUBLIC_SYSTEM_POINT_EDIT');
        $this->displayConfig($detailData);
    }

    //儲存積分規則
    public function savecredit(){
        $res = model('Credit')->saveCreditSet($_POST['creditSet']);
        $this->success();
    }

    public function editCreditLevel(){
        $data['type'] = h($_GET['type']);
        $data['id'] = h($_GET['id']);
        $list = model('Xdata')->get('admin_Credit:level');
        $data['list'] = $list;
        $data['value'] = $list[$data['id']-1];
        $this->assign($data);
        $this->display();
    }

    public function doAddCreditLevel(){
        $start = intval($_POST['start']);
        $end = intval($_POST['end']);
        // if(!$start || !$end){
        //     $this->error('請填寫積分值');
        // }
        $name = h(t($_POST['name']));
        // if(empty($name)){
        //     $this->error('請填寫等級名稱');
        // }

        $list = model('Xdata')->get('admin_Credit:level');
        foreach ($list as $key => $v) {
            $new_list[] = $v;
        }
        $level = count($new_list);
        $value = array();
        $value['level'] = 1;
        $value['start'] = $start;
        $value['end'] = $end;
        $value['name'] = $name;
        $value['image'] = 'level1.png';
        $new_list[$level] = $value;
        $res = model('Xdata')->put('admin_Credit:level',$new_list);
        if($res){
            $this->success('添加成功');
        }
        if(!$res){
            $this->error('添加失敗');
        }
    }

    public function doEditCreditLevel(){
        $level = intval($_POST['level']);
        $list = model('Xdata')->get('admin_Credit:level');

        $data = $list;
        $data[$level-1]['start'] = $_POST['start'];
        $data[$level-1]['end'] = $_POST['end'];
        $data[$level-1]['name'] = $_POST['name'];
        $res = model('Xdata')->put('admin_Credit:level',$data);
        if($res){
            $this->success('編輯成功');
        }else{
            $this->error('編輯失敗');
        }
    }
    public function doDeleteCreditLevel(){
        $ids = t($_POST['ids']);
        $ids = explode(',', $ids);
        if ( empty($ids) ) {echo 0; return ;}

        $list = model('Xdata')->get('admin_Credit:level');
        foreach ($ids as $key => $value) {
            unset($list[$value-1]);
        }
        echo model('Xdata')->put('admin_Credit:level',$list);
    }
}
