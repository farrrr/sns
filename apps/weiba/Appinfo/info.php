<?php
if (!defined('SITE_PATH')) exit();

return array(
    // 應用名稱 [必填]
    'NAME'                      => '微吧',
    // 應用簡介 [必填]
    'DESCRIPTION'               => '微吧',
    // 託管類型 [必填]（0:本地應用，1:遠端應用）
    'HOST_TYPE'                 => '0',
    // 前臺入口 [必填]（格式：Action/act）
    'APP_ENTRY'                 => 'Index/index',
    //為空
    'ICON_URL'                  => '',
    //為空
    'LARGE_ICON_URL'            => '',
    // 版本號 [必填]
    'VERSION_NUMBER'            => '1',
    // 後臺入口 [選填]
    'ADMIN_ENTRY'               => 'weiba/Admin/index',
    // 統計入口 [選填]（格式：Model/method）
    'STATISTICS_ENTRY'          => 'Statistics/statistics',
    //公司名稱
    'COMPANY_NAME'              => '智士軟體',
    //是否有移動端
    'HAS_MOBILE'                => '1',
);
