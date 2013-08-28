<?php
$menu = array(
    //後臺頭部TAB配置
    'admin_channel' =>  array(
        'index'     =>  '首頁', //L('PUBLIC_SYSTEM'),
        'system'    =>  L('PUBLIC_SYSTEM'),
        'user'      =>  L('PUBLIC_USER'),
        'content'   =>  L('PUBLIC_CONTENT'),
        'task'      =>  L('PUBLIC_TASK'),
        'apps'      =>  L('PUBLIC_APPLICATION'),
        'extends'   =>  '插件',//L('PUBLIC_EXPANSION'),
    ),
    //後臺選單配置
    'admin_menu'    => array(
        'index' => array(
            '首頁'  => array(
                L('PUBLIC_BASIC_INFORMATION')   =>  U('admin/Home/statistics'),
                L('PUBLIC_VISIT_CALCULATION')   =>  U('admin/Home/visitorCount'),
                //'資源統計'    =>  U('admin/Home/sourcesCount'),
                L('PUBLIC_MANAGEMENT_LOG')  =>  U('admin/Home/logs'),
                '群發訊息'  =>  U('admin/Home/message'),//L('PUBLIC_MESSAGE_NOTIFY')    =>  U('admin/Home/message'),
                L('PUBLIC_SCHEDULED_TASK_NEWCREATE')    =>  U('admin/Home/schedule'),
                //'資料字典'    =>  U('admin/Home/systemdata'),
                L('PUBLIC_CLEANCACHE')  =>  U('admin/Tool/cleancache'),
                '快取配置'              => U('admin/Home/cacheConfig'),
                '資料備份'              => U('admin/Tool/backup'),
                '線上升級'              => U('admin/Update/index'),
                '小工具'                => U('admin/Tool/index'),
            )
        ),

        'system'    => array(
            L('PUBLIC_SYSTEM_SETTING')  =>  array(
                L('PUBLIC_WEBSITE_SETTING') =>  U('admin/Config/site'),
                L('PUBLIC_NAVIGATION_SETTING')  =>  U('admin/Config/nav'),
                L('PUBLIC_REGISTER_SETTING')    =>  U('admin/Config/register'),
                '邀請配置'  =>  U('admin/Config/invite'),
                L('PUBLIC_WEIBO_SETTING')   =>  U('admin/Config/feed'),
                L('PUBLIC_EMAIL_SETTING')   =>  U('admin/Config/email'),
                L('PUBLIC_FILE_SETTING')    =>  U('admin/Config/attach'),
                L('PUBLIC_FILTER_SETTING')  =>  U('admin/Config/audit'),
                L('PUBLIC_POINT_SETTING')   =>  U('admin/Global/credit'),
                '地區配置'          =>  U('admin/Config/area'),
                L('PUBLIC_LANGUAGE')    =>  U('admin/Config/lang'),
                L('PUBLIC_MAILTITLE_ADMIN') =>  U('admin/Config/notify'),
                //L('PUBLIC_POINTS_SETTING')    =>  U('admin/Apps/setCreditNode'),
                '部門配置'                  => U('admin/Department/index'),
                L('PUBLIC_AUTHORITY_SETTING')   =>  U('admin/Apps/setPermNode'),
                // L('PUBLIC_WEIBO_TEMPLATE_SETTING')   =>  U('admin/Apps/setFeedNode'),
                'SEO配置'   =>  U('admin/Config/setSeo'),
                '頁面配置同步' => U('admin/Config/updateAdminTab'),
                'UCenter配置' => U('admin/Config/setUcenter'),
            ),
        ),

        'user'  =>  array(
            L('PUBLIC_USER')                =>  array(

                L('PUBLIC_USER_MANAGEMENT') =>  U('admin/User/index'),
                L('PUBLIC_USER_GROUP_MANAGEMENT')   =>  U('admin/UserGroup/index'),
                L('PUBLIC_PROFILE_SETTING') =>  U('admin/User/profile'),
                '使用者標籤'    =>  U('admin/User/category'),
                '使用者認證'    =>  U('admin/User/verifyCategory'),
                '找人配置'  =>  U('admin/User/findPeopleConfig'),
                '找人推薦'  =>  U('admin/User/officialCategory'),
            ),
        ),

        'content'   => array(
            L('PUBLIC_CONTENT_MANAGEMENT')          =>  array(
                L('PUBLIC_ANNOUNCEMENT_SETTING')    =>  U('admin/Config/announcement'),
                L('PUBLIC_WEIBO_MANAGEMENT')    =>  U('admin/Content/feed'),
                '話題管理'  =>  U('admin/Content/topic'),
                L('PUBLIC_COMMENT_MANAGEMENT')  =>  U('admin/Content/comment'),
                L('PUBLIC_PRIVATE_MESSAGE_MANAGEMENT')  =>  U('admin/Content/message'),
                L('PUBLIC_FILE_MANAGEMENT') =>  U('admin/Content/attach'),
                L('PUBLIC_REPORT_MANAGEMENT')   =>  U('admin/Content/denounce'),
                L('PUBLIC_TAG_MANAGEMENT')      =>  U('admin/Home/tag'),
                L('PUBLIC_INVITE_CALCULATION')  =>  U('admin/Home/invatecount'),
                '模板管理'  =>  U('admin/Content/template'),
            ),
        ),
        'task'  => array(
            L('PUBLIC_TASK_INFO')           => array(
                L('PUBLIC_TASK_LIST')   => U('admin/Task/index'),
                L('PUBLIC_TASK_REWARD') => U('admin/Task/reward'),
                '勳章列表'              => U('admin/Medal/index'),
                '使用者勳章'                => U('admin/Medal/userMedal'),
                '任務配置'              => U('admin/Task/taskConfig')
            )
        ),
        'apps'  => array(
            L('PUBLIC_APP_MANAGEMENT')          =>  array(
                L('PUBLIC_INSTALLED_APPLIST')   =>  U('admin/Apps/index'),
                L('PUBLIC_UNINSTALLED_APPLIST') =>  U('admin/Apps/install'),
                '線上應用'  =>  U('admin/Apps/onLineApp'),
            ),
        ),
        'extends'       => array(
            '插件管理' => array(
                '所有插件列表' => U('admin/Addons/index'),
            ),
        ),
    )
);

$app_list = model('App')->getConfigList();
foreach($app_list as $k=>$v){
    $menu['admin_menu']['apps'][L('PUBLIC_APP_MANAGEMENT')][$k] = $v;
}
$plugin_list = model('Addon')->getAddonsAdminUrl();
foreach($plugin_list as $k=>$v){
    $menu['admin_menu']['extends']['插件管理'][$k] = $v;
}

if(defined('iswaf_status') && iswaf_status==1){
    $menu['admin_menu']['index']['首頁']['安全防護'] = 'http://www.fanghuyun.com/?do=simple&IDKey='.md5(iswaf_connenct_key);
}
return $menu;
