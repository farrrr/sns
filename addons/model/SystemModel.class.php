<?php
/**
 * 系統模型 - 業務邏輯模型
 * @author liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.0
 */
class SystemModel
{
    /**
     * 插入統計資料
     * @return array 返回的相關資訊
     */
    public function upgrade()
    {
        // 請求地址
        $url = 'http://t.thinksns.com/upgrade.php';
        $siteData = $this->_getSiteData();
        // 是否開啟CURL，配置CURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($siteData));
        $result = curl_exec($curl);
        curl_close($curl);
        // 解析資料
        $result = unserialize($result);
        if($result === false) {
            $result['error'] = 1;
            $result['error_message'] = '獲取資訊失敗';
        } else {
            $result['error'] = 0;
            $result['error_message'] = '';
        }

        return $result;
    }

    /**
     * 獲取相關站點資料
     * @return array 相關站點資料
     */
    private function _getSiteData()
    {
        $result['site'] = SITE_URL;
        $result['version'] = '3.0';
        $result['output_format'] = '';

        return $result;
    }
}
