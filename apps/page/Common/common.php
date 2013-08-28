<?php
function getTagName($tagid){
    $tag = get_X_Tag();
    $tag = $tag['name'];

    return $tag[$tagid];
}

//過慮如123，456這樣的數字串
function intMember($val){
    $isArr = is_array($val);

    if(!$isArr){
        $val = explode(',', $val);
    }

    foreach ($val as $k=>&$v){
        $v = intval($v);
        if(empty($v))   unset($val[$k]);
    }

    if($isArr){
        return $val;
    }

    return implode(',', $val);
}

function dealTag($tagname){
    $tagname    =   str_replace(array(' ','，',';','；'),',',$tagname);
    $tagnames   =   explode(',',$tagname);
    foreach ($tagnames as $v){
        if(empty($v)) continue;

        $arr[] = $v;
    }
    return implode(',', $arr);
}
/**
 * 判斷一個字元串是否是整數
 * @param unknown_type $pString
 * @return string|string|string
 */
function isNumber($pString){
    $length = strlen($pString);
    //空字元串返回不是整數
    if($length==0)
    {
        return false;
    }
    for($i=0;$i<$length;$i++)
    {
        //根據ASCII判斷是否字元串中的每個字元都是數字
        if($pString[$i]<"0" || $pString[$i]>"9")
        {
            return false;
        }
    }
    return true;
}
/**
 * 以陣列中的一個欄位的值為唯一索引返回一個三維陣列
 * @param $pArray 一個二維陣列
 * @param $pFieldBy 作為索引的欄位的KEY值
 * @param $pIncludeFileld 可以定義返回的陣列的包含的原陣列的欄位
 * @return 返回新的三維陣列
 */
function group($pArray, $pFieldBy, $pIncludeFileld=""){
    if($pIncludeFileld!="")
        $fields = explode(",", $pIncludeFileld);
    $result_array = array();

    for($i=0; $i<count($pArray); $i++){
        $group_key = $pArray[$i][$pFieldBy];
        if( !isset( $result_array[$group_key] ) ){
            $result_array[$group_key] = array();
        }

        if($pIncludeFileld!=""){
            $temp = array();
            foreach($fields as $field){
                $temp[$field] = $pArray[$i][$field];
            }
            $result_array[$group_key][] = $temp;
        }else{
            $result_array[$group_key][] = $pArray[$i];
        }
    }
    return $result_array;
}
