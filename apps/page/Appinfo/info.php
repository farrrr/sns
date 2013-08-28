<?php
/**
 * 頻道版本資訊
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if(!defined('SITE_PATH')) exit();

return array(
    // 應用名稱 [必填]
    'NAME'                      => 'DIY頁面',
    // 應用簡介 [必填]
    'DESCRIPTION'               => '可以通過簡單的模組組合方式，創建自定義頁面',
    // 託管類型 [必填]（0:本地應用，1:遠端應用）
    'HOST_TYPE'                 => '0',
    // 前臺入口 [必填]（格式：Action/act）
    'APP_ENTRY'                 => 'Diy/index',
    // 為空
    'ICON_URL'                  => '',
    // 為空
    'LARGE_ICON_URL'            => '',
    // 版本號 [必填]
    'VERSION_NUMBER'            => '1',
    // 後臺入口 [選填]
    'ADMIN_ENTRY'               => 'page/Admin/index',
    // 統計入口 [選填]（格式：Model/method）
    'STATISTICS_ENTRY'          => 'Statistics/statistics',
    // 公司名稱
    'COMPANY_NAME'              => '智士軟體',

    // 是否有移動端
    'HAS_MOBILE'                => '0',
);
