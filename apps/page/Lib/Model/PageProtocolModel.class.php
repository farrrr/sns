<?php
/**
 * ***ProtocolModel
 * 提供給TS核心呼叫的協議類
 *
 */
class PageProtocolModel extends Model {
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

        M('diy_page')->where($map)->delete();
        M('diy_widget')->where($map)->delete();
    }
}
