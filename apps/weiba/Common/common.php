<?php
/**
 * 微吧獲取圖片存在相對地址
 * @param integer $attachid 附件ID
 * @return string 附件存儲相對地址
 */
function getImageUrlByAttachIdByWeiba ($attachid) {
    if ($attachInfo = model('Attach')->getAttachById($attachid)) {
        return $attachInfo['save_path'].$attachInfo['save_name'];
    } else {
        return false;
    }
}
