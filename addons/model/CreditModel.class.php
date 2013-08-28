<?php
/**
 * 積分模型 - 資料物件模型
 * @example
 * $credit = model('Credit')->setUserCredit($uid,'weibo_demo');
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class CreditModel extends Model {

    // 所有設定的值
    var $info;
    var $creditType;
    private $type = 'experience'; // 等級的圖示類型

    /**
     * +----------------------------------------------------------
     * 架構函數
     * +----------------------------------------------------------
     *
     * @author melec製作
     * @access public
     *         +----------------------------------------------------------
     */
    public function __construct() {
        if (($this->creditType = F ( '_service_credit_type' )) === false) {
            $this->creditType = M ( 'credit_type' )->order ( 'id ASC' )->findAll ();
            F ( '_service_credit_type', $this->creditType );
        }
    }

    /**
     * 獲取積分設定資訊
     *
     * @return array 積分設定資訊
     */
    public function getSetData() {
        if (($data = model ( 'Cache' )->get ( 'credit_set' )) == false) {
            $data = model ( 'Xdata' )->get ( 'admin_Credit:set' );
            model ( 'Cache' )->set ( 'credit_set', $data );
        }

        return $data;
    }

    /**
     * 獲取使用者積分
     *
     * 返回積分值的資料結構
     * <code>
     * array(
     * 'score' =>array(
     * 'credit'=>'1',
     * 'alias' =>'積分',
     * ),
     * 'experience'=>array(
     * 'credit'=>'2',
     * 'alias' =>'經驗',
     * ),
     * '類型' =>array(
     * 'credit'=>'值',
     * 'alias' =>'名稱',
     * ),
     * )
     * </code>
     *
     * @param int $uid
     * @return boolean array
     */
    public function getUserCredit($uid) {
        if (empty ( $uid ))
            return false;

        $userCredit = S('getUserCredit_'.$uid);
        if($userCredit!=false){
            return $userCredit;
        }

        $userCreditInfo = M ( 'credit_user' )->where ( "uid={$uid}" )->find (); // 使用者積分

        if(!$userCreditInfo){
            $data['uid'] = $uid;
            M('credit_user')->add($data);// 使用者積分
        }

        foreach ( $this->creditType as $v ) {
            $userCredit ['credit'] [$v ['name']] = array (
                'value' => intval ( $userCreditInfo [$v ['name']] ),
                'alias' => $v ['alias']
            );
        }

        $userCredit ['creditType'] = $this->getTypeList ();

        // 獲取積分等級規則
        $level = $this->getLevel ();
        $data = $userCredit ['credit'] [$this->type] ['value'];

        foreach ( $level as $k => $v ) {
            if ($data >= $v ['start'] && $data <= $v ['end']) {
                $userCredit ['level'] = $v;
                $userCredit ['level'] ['level_type'] = $this->type;
                $userCredit ['level'] ['nextNeed'] = $v ['end'] - $data;
                $userCredit ['level'] ['nextName'] = $level [$k + 1] ['name'];
                $userCredit ['level'] ['src'] = THEME_PUBLIC_URL . '/image/level/' . $userCredit ['level'] ['image'];
                break;
            }
            if ($data > $v ['end'] && ! isset ( $level [$k + 1] )) {
                $userCredit ['level'] = $v;
                $userCredit ['level'] ['nextNeed'] = '';
                $userCredit ['level'] ['nextName'] = '';
                $userCredit ['level'] ['src'] = THEME_PUBLIC_URL . '/image/level/' . $userCredit ['level'] ['image'];
                break;
            }
        }
        S('getUserCredit_'.$uid, $userCredit, 604800);  //快取一週
        return $userCredit;
    }

    /**
     * 獲取積分類型列表
     *
     * @param string $return
     *          返回類型，默認為has
     * @return [type] [description]
     */
    public function getTypeList() {
        $arr = array ();
        foreach ( $this->creditType as $value ) {
            $arr [$value['name']] = $value ['alias'];
        }

        return $arr;
    }

    /**
     * 獲取積分等級規則
     *
     * @return array 積分等級規則資訊
     */
    public function getLevel() {
        $data = model ( 'Xdata' )->get ( 'admin_Credit:level' );
        if (! $data) {
            $file = ADDON_PATH . '/lang/zh-tw/creditlevel.php';
            $data = include ($file);
            model ( 'Xdata' )->put ( 'admin_Credit:level', $data );
        }

        return $data;
    }

    /**
     * 添加任務積分
     *
     * @param int $exp
     * @param int $score
     * @param int $uid
     */
    public function addTaskCredit($exp, $score, $uid) {
        // 加積分
        D ( 'credit_user' )->setInc ( 'experience', 'uid=' . $uid, $exp );
        D ( 'credit_user' )->setInc ( 'score', 'uid=' . $uid, $score );

        $this->cleanCache($uid);
    }

    /**
     * TS2相容方法：獲取積分類型列表
     *
     * @return array 積分類型列表
     */
    public function getCreditType() {
        return $this->creditType;
    }

    /**
     * TS2相容方法：設定使用者積分
     * 操作使用者積分
     *
     * @param int $uid
     *          使用者ID
     * @param array|string $action
     *          系統設定的積分規則的名稱
     *          或臨時定義的一個積分規則陣列，例如array('score'=>-4,'experience'=>3)即socre減4點，experience加三點
     * @param string|int $type
     *          reset:按照操作的值直接重設積分值，整型：作為操作的係數，-1可實現增減倒置
     * @return Object
     */
    public function setUserCredit($uid, $action, $type = 1) {
        if (! $uid) {
            $this->info = false;
            return $this;
        }
        if (is_array ( $action )) {
            $creditSet = $action;
        } else {
            // 獲取配置規則
            $credit_ruls = $this->getCreditRules ();
            foreach ( $credit_ruls as $v )
                if ($v ['name'] == $action)
                    $creditSet = $v;
        }
        if (! $creditSet) {
            $this->info = '積分規則不存在';
            return $this;
        }
        $creditUserDao = M ( 'credit_user' );
        $creditUser = $creditUserDao->where ( "uid={$uid}" )->find (); // 使用者積分
        // 積分計算
        if ($type == 'reset') {
            foreach ( $this->creditType as $v ) {
                $creditUser [$v ['name']] = $creditSet [$v ['name']];
            }
        } else {
            $type = intval ( $type );
            foreach ( $this->creditType as $v ) {
                $creditUser [$v ['name']] = $creditUser [$v ['name']] + ($type * $creditSet [$v ['name']]);
            }
        }
        $creditUser ['uid'] || $creditUser ['uid'] = $uid;
        // $res = $creditUserDao->save ( $creditUser ) || $res = $creditUserDao->add ( $creditUser ); // 首次進行積分計算的使用者則為插入積分資訊
        if($creditUserDao->where('uid='.$creditUser['uid'])->count()){
            $map['id'] = $creditUser['id'];
            $map['uid'] = $creditUser['uid'];
            unset($creditUser['id']);unset($creditUser['uid']);
            $res = $creditUserDao->where($map)->save ( $creditUser );
        }else{
            $res = $creditUserDao->add ( $creditUser );
        }
        // 使用者進行積分操作後，登入使用者的快取將修改
        $this->cleanCache($uid);
        // $userLoginInfo = S('S_userInfo_'.$uid);
        // if(!empty($userLoginInfo)) {
        // $userLoginInfo['credit']['score']['credit'] = $creditUser['score'];
        // $userLoginInfo['credit']['experience']['credit'] = $creditUser['experience'];
        // S('S_userInfo_'.$uid, $userLoginInfo);
        // }
        if ($res) {
            $this->info = $creditSet ['info'];
            return $this;
        } else {
            $this->info = false;
            return $this;
        }
        }

        /**
         * 獲取積分操作結果
         *
         * return string
         */
        public function getInfo() {
            return $this->info;
        }

        /**
         * 獲取所有系統積分規則
         */
        public function getCreditRules() {
            if (($res = F ( '_service_credit_rules' )) === false) {
                $res = M ( 'credit_setting' )->order ( 'type ASC' )->findAll ();
                F ( '_service_credit_rules', $res );
        }
        return $res;
        }


        /**
         * 儲存積分等級規則
         * @param array $d 修改的積分等級規則
         * @return void
         */
        public function saveCreditLevel($d) {
            $data = $this->getLevel();
            $data[$d['level'] - 1]['name'] = $d['name'];
            $data[$d['level'] - 1]['image'] = $d['image'];
            $data[$d['level'] - 1]['start'] = $d['start'];
            $data[$d['level'] - 1]['end'] = $d['end'];
            model('Xdata')->put('admin_Credit:level', $data);

            //清除使用者快取
            $users = model('User')->field('uid')->findAll();
            foreach($users as $user){
                $this->cleanCache($user['uid']);
        }
        }
        /**
         * 清除使用者積分快取
         * @return void
         */
        public function cleanCache($uid) {
            S ( 'S_userInfo_' . $uid, null );
            S('getUserCredit_'.$uid, NULL);
        }
        }
