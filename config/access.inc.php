<?php
/*
 * 遊客訪問的黑/白名單，不需要開放的，可以註釋掉
 */
return array (
	"access" => array (
		'public/Register/*' => true, // 註冊
		'public/Passport/*' => true, // 登入
		'public/Widget/*'	=> true, // 插件
		'page/Index/index'	=> true,
		'api/*/*' => true, // API
		                      
		// 網站公告
		'public/Index/announcement' => true,
		
		// 個人主頁
		'public/Profile/index' => true,
		'public/Profile/following' => true,
		'public/Profile/follower' => true,
		'public/Profile/data' => true,
		
		// 微博內容
		'public/Profile/feed' => true,
		
		// 微博話題
		'public/Topic/index' => true,

		// 微博排行榜
		'public/Rank/*' => true,
		
		// 頻道
		'channel/Index/*' => true,
		
		// 找人
		'people/Index/*' => true,

		// 微吧
		'weiba/Index/index' => true,
		'weiba/Index/detail' => true,
		'weiba/Index/postDetail' => true,
		'weiba/Index/postList' => true,
		'weiba/Index/weibaList' => true,
		
		// 升級查詢
		'public/Tool/*' => true,
		
		'wap/*/*' => true,

		'develop/Public/*' => true,
	)
		 
);