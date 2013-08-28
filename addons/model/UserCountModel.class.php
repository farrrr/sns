<?php
/**
 * 使用者統計模型 - 資料物件模型
 * @author 小川
 * @version TS3.0
 */
class UserCountModel extends Model {

    /**
     * 獲取指定使用者的通知統計數目
     * @param integer $uid 使用者UID
     * @return array 指定使用者的通知統計數目
     */
    public function getUnreadCount($uid) {
        $msg_model  = model('Message');
        $data_model = model('UserData');

        $user_data = $data_model->setUid($uid)->getUserData();
        // 未讀通知數目
        $return['unread_notify']  = model('Notify')->getUnreadCount($uid);
        // 未讀@Me數目
        $return['unread_atme']    = intval($user_data['unread_atme']);
        // 未讀評論數目
        $return['unread_comment'] = intval($user_data['unread_comment']);
        // 未讀短資訊數目
        $return['unread_message'] = (int)$msg_model->getUnreadMessageCount($uid, array(MessageModel::ONE_ON_ONE_CHAT, MessageModel::MULTIPLAYER_CHAT));
        // 新的關注數目
        $return['new_folower_count'] = intval($user_data['new_folower_count']);
        // 合計的未讀數目
        $return['unread_total']  = array_sum($return);
        $group = model('App')->getAppByName('group');
        if ( $group['status'] ){
            $groupatme = D( 'GroupUserCount' , 'group' )->where('uid='.$uid)->findAll();
            $gatme = 0;
            $gcomment = 0;
            foreach ( $groupatme as $v){
                $gatme += intval( $v['atme'] );
                $gcomment += intval( $v['comment'] );
            }
            $return['unread_group_atme'] = $gatme;
            $return['unread_group_comment'] = $gcomment;
        }

        return $return;
    }

    /**
     * 更新指定使用者的通知統計數目
     * @param integer $uid 使用者UID
     * @param string $key 統計數目的Key值
     * @param integer $rate 數目變動的值
     * @return void
     */
    public function updateUserCount($uid, $key, $rate) {
        $data_model = model('UserData');
        $data_model->setUid($uid)->updateKey($key, $rate);
    }

    /**
     * 重置指定使用者的通知統計數目
     * @param integer $uid 使用者UID
     * @param string $key 統計數目的Key值
     * @param integer $value 統計數目變化的值，默認為0
     * @return void
     */
    public function resetUserCount($uid, $key, $value = 0) {
        $data_model = model('UserData');
        $data_model->setKeyValue($uid, $key, $value);
    }
}
