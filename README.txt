#
# ThinkSNS 安裝說明.txt
#

+ 常用路徑
  - 安裝路徑: http://yoursite/install
  - 前臺登入: http://yoursite
  - 後臺登入: http://yoursite/index.php?app=admin

+ 其他說明
  - 如果安裝後遇到資料庫連結錯誤、頁面提示_NO_DB_CONFIG_可以執行 /cleancache.php
  - 安裝完成後，請到後臺全局配置中，對網站logo、登入頁圖片進行配置管理
  - 開啟偽靜態和個性化域名:  參見"開啟URL偽靜態的方法.txt"

+ 注意事項
  - PHP需要開啟mysql, gd, curl, mbstring支援
  - _runtime、data、config、install目錄需要可寫許可權(777)
  - 升級使用者，請看升級說明（注意升級前做好備份）

+ ThinkSNS V3 安裝、升級說明
  http://demo.thinksns.com/t3/index.php?app=weiba&mod=Index&act=postDetail&post_id=640#

+ ThinkSNS V3 常見問題解答
  http://demo.thinksns.com/t3/index.php?app=weiba&mod=Index&act=postDetail&post_id=641