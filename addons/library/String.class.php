<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

class String
{

    /**
     +----------------------------------------------------------
     * 生成UUID 單機使用
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    static public function uuid()
    {
        $charid = md5(uniqid(mt_rand(), true));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }

    /**
     +----------------------------------------------------------
     * 生成Guid主鍵
     +----------------------------------------------------------
     * @return Boolean
     +----------------------------------------------------------
     */
    static public function keyGen() {
        return str_replace('-','',substr(com_create_guid(),1,-1));
    }

    /**
     +----------------------------------------------------------
     * 檢查字元串是否是UTF8編碼
     +----------------------------------------------------------
     * @param string $string 字元串
     +----------------------------------------------------------
     * @return Boolean
     +----------------------------------------------------------
     */
    function is_utf8($str) {
        $c=0; $b=0;
        $bits=0;
        $len=strlen($str);
        for($i=0; $i<$len; $i++){
            $c=ord($str[$i]);
            if($c > 128){
                if(($c >= 254)) return false;
                elseif($c >= 252) $bits=6;
                elseif($c >= 248) $bits=5;
                elseif($c >= 240) $bits=4;
                elseif($c >= 224) $bits=3;
                elseif($c >= 192) $bits=2;
                else return false;
                if(($i+$bits) > $len) return false;
                while($bits > 1){
                    $i++;
                    $b=ord($str[$i]);
                    if($b < 128 || $b > 191) return false;
                    $bits--;
                }
            }
        }
        return true;
    }

    /**
     +----------------------------------------------------------
     * 字元串擷取，支援中文和其它編碼
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $str 需要轉換的字元串
     * @param string $start 開始位置
     * @param string $length 擷取長度
     * @param string $charset 編碼格式
     * @param string $suffix 截斷顯示字元
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    static public function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
    {
        if(function_exists("mb_substr"))
            return mb_substr($str, $start, $length, $charset);
        elseif(function_exists('iconv_substr')) {
            return iconv_substr($str,$start,$length,$charset);
        }
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
        if($suffix) return $slice."…";
        return $slice;
    }

    /**
     +----------------------------------------------------------
     * 產生隨機字串，可用來自動生成密碼
     * 默認長度6位 字母和數字混合 支援中文
     +----------------------------------------------------------
     * @param string $len 長度
     * @param string $type 字串類型
     * 0 字母 1 數字 其它 混合
     * @param string $addChars 額外字元
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    static public function rand_string($len=6,$type='',$addChars='') {
        $str ='';
        switch($type) {
        case 0:
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
            break;
        case 1:
            $chars= str_repeat('0123456789',3);
            break;
        case 2:
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
            break;
        case 3:
            $chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
            break;
        case 4:
            $chars = "們以我到他會作時要動國產的一是工就年階義發成部民可出能方進在了不和有大這主中人上為來分生對於學下級地個用同行面說種過命度革而多子後自社加小機也經力線本電高量長黨得實家定深法表著水理化爭現所二起政三好十戰無農使性前等反體合鬥路圖把結第里正新開論之物從當兩些還天資事隊批點育重其思與間內去因件日利相由壓員氣業代全組數果期導平各基或月毛然如應形想制心樣幹都向變關問比展那它最及外沒看治提五解系林者米群頭意只明四道馬認次文通但條較克又公孔領軍流入接席位情運器並飛原油放立題質指建區驗活眾很教決特此常石強極土少已根共直團統式轉別造切九你取西持總料連任志觀調七麼山程百報更見必真保熱委手改管處己將修支識病象幾先老光專什六型具示覆安帶每東增則完風回南廣勞輪科北打積車計給節做務被整聯步類集號列溫裝即毫知軸研單色堅據速防史拉世設達爾場織歷花受求傳口斷況採精金界品判參層止邊清至萬確究書術狀廠須離再目海交權且兒青才證低越際八試規斯近注辦布門鐵需走議縣兵固除般引齒千勝細影濟白格效置推空配刀葉率述今選養德話查差半敵始片施響收華覺備名紅續均藥標記難存測士身緊液派準斤角降維板許破述技消底床田勢端感往神便賀村構照容非搞亞磨族火段算適講按值美態黃易彪服早班麥削信排臺聲該擊素張密害侯草何樹肥繼右屬市嚴徑螺檢左頁抗蘇顯苦英快稱壞移約巴材省黑武培著河帝僅針怎植京助升王眼她抓含苗副雜普談圍食射源例致酸舊卻充足短劃劑宣環落首尺波承粉踐府魚隨考刻靠夠滿夫失包住促枝局菌杆周護巖師舉曲春元超負砂封換太模貧減陽揚江析畝木言球朝醫校古呢稻宋聽唯輸滑站另衛字鼓剛寫劉微略範供阿塊某功套友限項餘倒卷創律雨讓骨遠幫初皮播優佔死毒圈偉季訓控激找叫雲互跟裂糧粒母練塞鋼頂策雙留誤礎吸阻故寸盾晚絲女散焊功株親院冷徹彈錯散商視藝滅版烈零室輕血倍缺釐泵察絕富城衝噴壤簡否柱李望盤磁雄似困鞏益洲脫投送奴側潤蓋揮距觸星鬆送獲興獨官混紀依未突架寬冬章溼偏紋吃執閥礦寨責熟穩奪硬價努翻奇甲預職評讀背協損棉侵灰雖矛厚羅泥闢告卵箱掌氧恩愛停曾溶營終綱孟錢待盡俄縮沙退陳討奮械載胞幼哪剝迫旋徵槽倒握擔仍呀鮮吧卡粗介鑽逐弱腳怕鹽末陰豐霧冠丙街萊貝輻腸付吉滲瑞驚頓擠秒懸姆爛森糖聖凹陶詞遲蠶億矩康遵牧遭幅園腔訂香肉弟屋敏恢忘編印蜂急拿擴傷飛露核緣遊振操央伍域甚迅輝異序免紙夜鄉久隸缸夾念蘭映溝乙嗎儒殺汽磷艱晶插埃燃歡鐵補咱芽永瓦傾陣碳演威附牙芽永瓦斜灌歐獻順豬洋腐請透司危括脈宜笑若尾束壯暴企菜穗楚漢愈綠拖牛份染既秋遍鍛玉夏療尖殖井費州訪吹榮銅沿替滾客召旱悟刺腦措貫藏敢令隙爐殼硫煤迎鑄粘探臨薄旬善福縱擇禮願伏殘雷延煙句純漸耕跑澤慢栽魯赤繁境潮橫掉錐希池敗船假亮謂託夥哲懷割擺貢呈勁財儀沉煉麻罪祖息車穿貨銷齊鼠抽畫飼龍庫守築房歌寒喜哥洗蝕廢納腹乎錄鏡婦惡脂莊擦險贊鍾搖典柄辯竹谷賣亂虛橋奧伯趕垂途額壁網截野遺靜謀弄掛課鎮妄盛耐援扎慮鍵歸符慶聚繞摩忙舞遇索顧膠羊湖釘仁音跡碎伸燈避泛亡答勇頻皇柳哈揭甘諾概憲濃島襲誰洪謝炮澆斑訊懂靈蛋閉孩釋乳巨徒私銀伊景坦累勻黴杜樂勒隔彎績招紹胡呼痛峰零柴簧午跳居尚丁秦稍追樑折耗鹼殊崗挖氏刃劇堆赫荷胸衡勤膜篇登駐案刊秧緩凸役剪川雪鏈漁啦臉戶洛孢勃盟買楊宗焦賽旗濾矽炭股坐蒸凝竟陷槍黎救冒暗洞犯筒您宋弧爆謬塗味津臂障褐陸啊健尊豆拔莫抵桑坡縫警挑汙冰柬嘴啥飯塑寄趙喊墊丹渡耳刨虎筆稀昆浪薩茶滴淺擁穴覆倫娘噸浸袖珠雌媽紫戲塔錘震歲貌潔剖牢鋒疑霸閃埔猛訴刷狠忽災鬧喬唐漏聞沈熔氯荒莖男凡搶像漿旁玻亦忠唱蒙予紛捕鎖尤乘烏智淡允叛畜俘摸鏽掃畢璃寶芯爺鑑祕淨蔣鈣肩騰枯拋軌堂拌爸循誘祝勵肯酒繩窮塘燥泡袋朗喂鋁軟渠顆慣貿糞綜牆趨彼屆墨礙啟逆卸航衣孫齡嶺騙休借".$addChars;
            break;
        default :
            // 默認去掉了容易混淆的字元oOLl和數字01，要添加請使用addChars參數
            $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
            break;
        }
        if($len>10 ) {//位數過長重複字元串一定次數
            $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
        }
        if($type!=4) {
            $chars   =   str_shuffle($chars);
            $str     =   substr($chars,0,$len);
        }else{
            // 中文隨機字
            for($i=0;$i<$len;$i++){
                $str.= self::msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1);
        }
        }
        return $str;
        }

    /**
     +----------------------------------------------------------
     * 生成一定數量的隨機數，並且不重複
     +----------------------------------------------------------
     * @param integer $number 數量
     * @param string $len 長度
     * @param string $type 字串類型
     * 0 字母 1 數字 其它 混合
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
        static public function build_count_rand ($number,$length=4,$mode=1) {
            if($mode==1 && $length<strlen($number) ) {
                //不足以生成一定數量的不重複數字
                return false;
        }
        $rand   =  array();
        for($i=0; $i<$number; $i++) {
            $rand[] =   rand_string($length,$mode);
        }
        $unqiue = array_unique($rand);
        if(count($unqiue)==count($rand)) {
            return $rand;
        }
        $count   = count($rand)-count($unqiue);
        for($i=0; $i<$count*3; $i++) {
            $rand[] =   rand_string($length,$mode);
        }
        $rand = array_slice(array_unique ($rand),0,$number);
        return $rand;
        }

    /**
     +----------------------------------------------------------
     *  帶格式生成隨機字元 支援批量生成
     *  但可能存在重複
     +----------------------------------------------------------
     * @param string $format 字元格式
     *     # 表示數字 * 表示字母和數字 $ 表示字母
     * @param integer $number 生成數量
     +----------------------------------------------------------
     * @return string | array
     +----------------------------------------------------------
     */
        static public function build_format_rand($format,$number=1)
        {
            $str  =  array();
            $length =  strlen($format);
            for($j=0; $j<$number; $j++) {
                $strtemp   = '';
                for($i=0; $i<$length; $i++) {
                    $char = substr($format,$i,1);
                    switch($char){
                    case "*"://字母和數字混合
                        $strtemp   .= String::rand_string(1);
                        break;
                    case "#"://數字
                        $strtemp  .= String::rand_string(1,1);
                        break;
                    case "$"://大寫字母
                        $strtemp .=  String::rand_string(1,2);
                        break;
                    default://其它格式均不轉換
                        $strtemp .=   $char;
                        break;
        }
        }
        $str[] = $strtemp;
        }

        return $number==1? $strtemp : $str ;
        }

    /**
     +----------------------------------------------------------
     * 獲取一定範圍內的隨機數字 位數不足補零
     +----------------------------------------------------------
     * @param integer $min 最小值
     * @param integer $max 最大值
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
        static public function rand_number ($min, $max) {
            return sprintf("%0".strlen($max)."d", mt_rand($min,$max));
        }
        }
?>
