<?php
if (!defined('THINKSNS_INSTALL'))
{
	exit ('Access Denied');
}

$i_message['install_lock'] = '您已安裝過ThinkSNS ' . $_TSVERSION . '，如果需要重新安裝，請先刪除install目錄下的install.lock檔案';
$i_message['install_title'] = 'ThinkSNS ' . $_TSVERSION . ' 安裝嚮導';
$i_message['install_wizard'] = '安裝嚮導';
$i_message['install_warning'] = '<strong>注意 </strong>這個安裝程式僅僅用在你首次安裝ThinkSNS。如果你已經在使用 ThinkSNS 或者要更新到一個新版本，請不要運行這個安裝程式。';
$i_message['install_intro'] = '<h4>安裝須知</h4><p>一、運行環境需求：PHP(5.2.0+)+MYSQL(4.1+)</p><p>二、安裝步驟：<br /><br />1、使用ftp工具以二進位制模式，將該軟體包裡的 thinksns 目錄及其檔案上傳到您的空間，假設上傳後目錄仍舊為 thinksns。<br /><br />2、如果您使用的是Linux 或 Freebsd 伺服器，先確認以下目錄或檔案屬性為 (777) 可寫模式。<br /><br />目錄: data<br />目錄: _runtime<br />目錄: install<br />目錄: config<br />3、運行 http://yourwebsite/thinksns/install/install.php 安裝程式，填入安裝相關資訊與資料，完成安裝！<br />4、運行 http://yourwebsite/thinksns/cleancache.php 清除系統快取檔案！<br />5、運行 http://yourwebsite/thinksns/index.php 開始體驗ThinkSNS！</p>';
$i_message['install_start'] = '開始安裝ThinkSNS';
$i_message['install_license_title'] = '安裝許可協議';
$i_message['install_license'] = '版權所有 (C) 2008-'.date('Y').'，ThinkSNS.com 保留所有權利。

ThinkSNS是由ThinkSNS項目組獨立開發的SNS程式，基於PHP指令碼和MySQL資料庫。本程式源碼開放的，任何人都可以從網際網路上免費下載，並可以在不違反本協議規定的前提下進行使用而無需繳納程式使用費。

官方網址： www.thinksns.com 交流社羣： t.thinksns.com

為了使你正確並合法的使用本軟體，請你在使用前務必閱讀清楚下面的協議條款：

    智士軟體（北京）有限公司為ThinkSNS產品的開發商，依法獨立擁有ThinkSNS產品著作權（中華人民共和國國家版權局著作權登記號 2011SR069454）。智士軟體（北京）有限公司網址為 http://www.zhishisoft.com，ThinkSNS官方網站網址為 http://www.thinksns.com。
    ThinkSNS著作權已在中華人民共和國國家版權局註冊，著作權受到法律和國際公約保護。使用者：無論個人或組織、盈利與否、用途如何（包括以學習和研究為目的），均需仔細閱讀本協議，在理解、同意、並遵守本協議的全部條款後，方可開始使用 ThinkSNS軟體。
智士軟體（北京）有限公司擁有對本授權協議的最終解釋權。
1.0   協議許可的權利
1)    您可以在完全遵守本終端使用者授權協議的基礎上，將本軟體應用於非商業用途，而不必支付軟體版權授權費用；
2)    您可以在協議規定的約束和限制範圍內修改 ThinkSNS 原始碼或介面風格以適應您的網站要求；
3)    您擁有使用本軟體構建的社羣中全部會員資料、文章及相關資訊的所有權，並獨立承擔與文章內容的相關法律義務；
4)    獲得商業授權之後，您可以將本軟體應用於商業用途，同時依據所購買的授權類型中確定的技術支援期限、技術支援方式和技術支援內容，自購買時刻起， 在技術支援期限內擁有通過指定的方式獲得指定範圍內的技術支援服務。商業授權使用者享有反映和提出意見的權力，相關意見將被作為首要考慮，但沒有一定被採納的承諾或保證。
2.0   協議規定的約束和限制
1)    未獲商業授權之前，不得將本軟體用於商業用途（包括但不限於企業網站、經營性網站、以營利為目或實現盈利的網站）。購買商業授權請登入http://www.thinksns.com 參考相關說明，也可以致電8610- 82431402瞭解詳情；
2)    不得對本軟體或與之關聯的商業授權進行出租、出售、抵押或發放子許可證；
3)    無論如何，即無論用途如何、是否經過修改或美化、修改程度如何，只要使用ThinkSNS的整體或任何部分，未經書面許可，頁面頁尾處的 Powered by ThinkSNS名稱和官網網站的連結（http://www.thinksns.com ）都必須保留，而不能清除或修改；
4)    禁止ThinkSNS的整體或任何部分基礎上以發展任何派生版本、修改版本或第三方版本用於重新分發；
5)    如果您未能遵守本協議的條款，您的授權將被終止，所被許可的權利將被收回，並承擔相應法律責任。
3.0     有限擔保和免責聲明

1)    本軟體及所附帶的檔案是作為不提供任何明確的或隱含的賠償或擔保的形式提供的；
2)    使用者出於自願而使用本軟體，您必須瞭解使用本軟體的風險，在尚未購買產品技術服務之前，我們不承諾提供任何形式的技術支援、使用擔保，也不承擔任何因使用本軟體而產生問題的相關責任；
3)    智士軟體（北京）有限公司不對使用本軟體構建的社羣中的文章或資訊承擔責任。
有關ThinkSNS終端使用者授權協議、商業授權與技術服務的詳細內容，均由ThinkSNS官方網站獨家提供。智士軟體（北京）有限公司擁有在不事先通知的情況下，修改授權協議和服務價目表的權力，修改後的協議或價目表對自改變之日起的新授權使用者生效。
電子文字形式的授權協議如同雙方書面簽署的協議一樣，具有完全的和等同的法律效力。您一旦開始安裝 ThinkSNS，即被視為完全理解並接受本協議的各項條款，在享有上述條款授予的權力的同時，受到相關的約束和限制。協議許可範圍以外的行為，將直接違反本授權協議並構成侵權，我們有權隨時終止授權，責令停止損害，並保留追究相關責任的權力。

';
$i_message['install_agree'] = '我已看過並同意安裝許可協議';
$i_message['install_disagree'] = '不同意';
$i_message['install_disagree_license'] = '您必須在同意授權協議的全部條件後，方可繼續ThinkSNS的安裝';
$i_message['install_prev'] = '上一步';
$i_message['install_next'] = '下一步';
$i_message['dirmod'] = '目錄和檔案的寫許可權';
$i_message['install_dirmod'] = '目錄和檔案是否可寫，如果發生錯誤，請更改檔案/目錄屬性 777';
$i_message['install_env'] = '伺服器配置';
$i_message['php_os'] = '作業系統';
$i_message['php_version'] = 'PHP版本';
$i_message['php_memory'] = '內存限制';
$i_message['php_session'] = 'SESSION支援';
$i_message['php_session_error']	= 'SESSION目錄不可寫';
$i_message['file_upload'] = '附件上傳';
$i_message['support'] = '支援';
$i_message['unsupport'] = '不支援';
$i_message['php_extention'] = 'PHP擴展';
$i_message['php_extention_unload_gd'] = '您的伺服器沒有安裝這個PHP擴展：gd';
$i_message['php_extention_unload_mbstring'] = '您的伺服器沒有安裝這個PHP擴展：mbstring';
$i_message['php_extention_unload_mysql'] = '您的伺服器沒有安裝這個PHP擴展：mysql';
$i_message['php_extention_unload_curl'] = '您的伺服器沒有安裝這個PHP擴展：curl';
$i_message['mysql'] = 'MySQL資料庫';
$i_message['mysql_unsupport'] = '您的伺服器不支援MYSQL資料庫，無法安裝ThinkSNS。';
$i_message['install_setting'] = '資料庫資料與管理員帳號設定';
$i_message['install_mysql'] = '資料庫配置';
$i_message['install_mysql_host'] = '資料庫伺服器';
$i_message['install_mysql_host_intro'] = '格式：地址(:埠)，一般為 localhost';
$i_message['install_mysql_username'] = '資料庫使用者名';
$i_message['install_mysql_password'] = '資料庫密碼';
$i_message['install_mysql_name'] = '資料庫名';
$i_message['install_mysql_prefix'] = '表名字首';
$i_message['install_mysql_prefix_intro'] = '同一資料庫安裝多個ThinkSNS時可改變預設值';
$i_message['site_url'] = ' 站點地址';
$i_message['site_url_intro'] = '';
$i_message['founder'] = '超級管理員資料';
$i_message['install_founder_name'] = '管理員帳號';
$i_message['install_founder_password'] = '密碼';
$i_message['install_founder_rpassword'] = '重複密碼';
$i_message['install_founder_email'] = '電子郵件';
$i_message['install_mysql_host_empty'] = '資料庫伺服器不能為空';
$i_message['install_mysql_username_empty'] = '資料庫使用者名不能為空';
$i_message['install_mysql_name_empty'] = '資料庫名不能為空';
$i_message['install_founder_name_empty'] = '超級管理員使用者名不能為空';
$i_message['install_founder_password_length'] = '超級管理員密碼必須大於6位';
$i_message['install_founder_rpassword_error'] = '兩次輸入管理員密碼不同';
$i_message['install_founder_email_empty'] = '超級管理員Email不能為空';
$i_message['mysql_invalid_configure'] = '資料庫配置資訊不完整';
$i_message['mysql_invalid_prefix'] = '您指定的資料表字首包含點字元(".")，請返回修改。';
$i_message['forbidden_character'] = '使用者名包含非法字元';
$i_message['founder_invalid_email'] = '電子郵件格式不正確';
$i_message['founder_invalid_configure'] = '超級管理員資訊不完整';
$i_message['founder_invalid_password'] = '密碼長度必須大於6位';
$i_message['founder_invalid_rpassword'] = '兩次輸入的密碼不一致';
$i_message['founder_intro'] = '網站創始人，擁有最高許可權';
$i_message['config_log_success']	= '資料庫配置資訊寫入完成';
$i_message['define_log_success']	= '網站全局配置資訊寫入完成';
$i_message['config_read_failed'] = '資料庫配置檔案寫入錯誤，請檢查config.inc.php檔案是否存在或屬性是否為777';
$i_message['define_read_failed'] = '網站全局配置檔案寫入錯誤，請檢查define.inc.php檔案是否存在或屬性是否為777';
$i_message['error'] = '錯誤';
$i_message['database_errno_2003'] = '無法連線資料庫，請檢查資料庫是否啟動，資料庫伺服器地址是否正確';
$i_message['database_errno_1045'] = '無法連線資料庫，請檢查資料庫使用者名或者密碼是否正確';
$i_message['database_errno_1044'] = '無法創建新的資料庫，請檢查資料庫名稱填寫是否正確';
$i_message['database_errno_1064'] = 'SQL執行錯誤，請檢查資料庫名稱填寫是否正確';
$i_message['database_errno'] = '程式在執行資料庫操作時發生了一個錯誤，安裝過程無法繼續進行。';
$i_message['configure_read_failed'] = '資料庫配置失敗';
$i_message['mysql_version_402'] = '您的 MYSQL 版本低於 4.1.0，安裝無法繼續進行！';
$i_message['thinksns_rebuild'] = '資料庫中已經安裝過 ThinkSNS，繼續安裝會清空原有資料！';
$i_message['mysql_import_data'] = '點選下一步開始匯入資料';
$i_message['import_processing'] = '匯入資料庫';
$i_message['import_processing_error'] = '匯入資料庫失敗';
$i_message['create_table'] = '創建表';
$i_message['create_founder'] = '創建超級管理員帳戶';
$i_message['create_founder_success'] = '超級管理員帳戶創建成功';
$i_message['create_founder_error']	= '超級管理員帳戶創建失敗';
$i_message['create_founderpower_success'] = '超級管理員許可權設定成功';
$i_message['create_founderpower_error']	= '超級管理員許可權設定失敗';
$i_message['create_cache'] = '創建快取';
$i_message['create_cache_success'] = '創建快取成功';
$i_message['auto_increment'] = '使用者的起始ID';
$i_message['set_auto_increment_success'] = '使用者起始ID設定成功';
$i_message['set_auto_increment_error'] = '使用者起始ID設定失敗';
$i_message['install_success'] = '安裝成功';
$i_message['install_success_intro'] = '<p>安裝程式執行完畢，請儘快刪除整個 install 目錄，以免被他人惡意利用。如要重新安裝，請刪除本目錄的 install.lock 檔案！</p><p><a href="../index.php">請點選這裡開始體驗ThinkSNS吧！</a></p>';
$i_message['install_dbFile_error'] = '資料庫檔案無法讀取，請檢查/install/t_thinksns_com.sql是否存在。';
