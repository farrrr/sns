<?php
/**
 * ChannelProtocolModel
 * 提供給TS核心呼叫的協議類
 *
 */
class ChannelProtocolModel extends Model {
    // 假刪除使用者資料
    function deleteUserAppData($uidArr) {
    }
    // 恢復假刪除的使用者資料
    function rebackUserAppData($uidArr) {
    }
    // 徹底刪除使用者資料
    function trueDeleteUserAppData($uidArr) {
        if (empty ( $uidArr ))
            return false;

        $map ['uid'] = array (
            'in',
            $uidArr
        );

        M('channel')->where($map)->delete();
        M('channel_follow')->where($map)->delete();
    }
}
