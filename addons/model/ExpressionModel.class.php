<?php
/**
 * 表情模型 - 業務邏輯模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class ExpressionModel {

    /**
     * 獲取當前所有的表情
     * @param boolean $flush 是否更新快取，默認為false
     * @return array 返回表情資料
     */
    public function getAllExpression($flush = false) {
        $cache_id = '_model_expression';
        if(($res = S($cache_id)) === false || $flush===true) {
            global $ts;
            // $pkg = $ts['site']['expression'];
            $pkg = 'miniblog'; //TODO 臨時寫死
            $filepath = THEME_PUBLIC_PATH.'/image/expression/'.$pkg;
            require_once ADDON_PATH.'/library/io/Dir.class.php';
            $expression = new Dir($filepath);
            $expression_pkg = $expression->toArray();

            $res = array();
            foreach($expression_pkg as $value) {
                /*
                if(!is_utf8($value['filename'])){
                    $value['filename'] = auto_charset($value['filename'],'GBK','UTF8');
                }*/
                list ($file) = explode(".", $value['filename']);
                $temp['title'] = $file;
                $temp['emotion'] = '[' . $file . ']';
                $temp['filename'] = $value['filename'];
                $temp['type'] = $pkg;
                $res[$temp['emotion']] = $temp;
            }
            S($cache_id, $res);
        }

        return $res;
    }

    /**
     * 將表情格式化成HTML形式
     * @param string $data 內容資料
     * @return string 轉換為表情連結的內容
     */
    public function parse($data) {
        $data = preg_replace("/img{data=([^}]*)}/", "<img src='$1'  data='$1' >", $data);
        return $data;
    }
}
