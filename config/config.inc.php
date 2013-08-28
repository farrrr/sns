<?php
if (!defined('SITE_PATH')) exit();

return array(
	// 資料庫常用配置
	'DB_TYPE'			=>	'mysql',			// 資料庫類型

	'DB_HOST'			=>	'localhost',			// 資料庫伺服器地址
	'DB_NAME'			=>	'farsns',			// 資料庫名
	'DB_USER'			=>	'root',		// 資料庫使用者名
	'DB_PWD'			=>	'office.flyingv.me',		// 資料庫密碼

	'DB_PORT'			=>	3306,				// 資料庫埠
	'DB_PREFIX'			=>	'nctu_',		// 資料庫表字首（因為漫遊的原因，資料庫表字首必須寫在本檔案）
	'DB_CHARSET'		=>	'utf8',				// 資料庫編碼
	'SECURE_CODE'		=>	'508254975521e0719bc013',	// 資料加密金鑰
	'COOKIE_PREFIX'		=>	'T3_',	// 資料加密金鑰
);