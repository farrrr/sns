<?php
/*
* @brief a utils file
* This is only a simple demo.
* It is a free software; you can redistribute it
* and/or modify it.
*/
//包含配置

/**
 * @brief get a normalized string
 *
 * @param $params
 *
 * @return a normalized string
 */
function get_normalized_string($params)
{
	ksort($params);
	$normalized = array();
	foreach($params as $key => $val)
	{
		$normalized[] = $key."=".$val;
	}

	return implode("&", $normalized);
}

/**
 * @brief get the signature by hmac-sha1
 * @param $key
 * @param $str
 * @return the signature
 */

function get_signature($str, $key)
{
	$signature = "";
	if (function_exists('hash_hmac'))
	{
		$signature = base64_encode(hash_hmac("sha1", $str, $key, true));
	}
	else
	{
		$blocksize	= 64;
		$hashfunc	= 'sha1';
		if (strlen($key) > $blocksize)
		{
			$key = pack('H*', $hashfunc($key));
		}
		$key	= str_pad($key,$blocksize,chr(0x00));
		$ipad	= str_repeat(chr(0x36),$blocksize);
		$opad	= str_repeat(chr(0x5c),$blocksize);
		$hmac 	= pack(
		'H*',$hashfunc(
		($key^$opad).pack(
		'H*',$hashfunc(
		($key^$ipad).$str
		)
		)
		)
		);
		$signature = base64_encode($hmac);
	}

	return $signature;
}

/**
 * @brief get a urlencode string
 *        rfc1738 urlencode
 * @param $params
 *
 * @return a urlencode string
 */
function get_urlencode_string($params)
{
	ksort($params);
	$normalized = array();
	foreach($params as $key => $val)
	{
		$normalized[] = $key."=".rawurlencode($val);
	}

	return implode("&", $normalized);
}

/**
 * @brief check the openid is valid or not
 *
 * @param $openid
 * @param $timestamp
 * @param $sig
 *
 * @return true or false
 */
function is_valid_openid($openid, $timestamp, $sig)
{
	$key = QZONE_SECRET;
	$str = $openid.$timestamp;
	$signature = get_signature($str, $key);
	return $sig == $signature;
}

/**
 * @brief all get request will call this function
 *
 * @param $url
 * @param $appid
 * @param $appkey
 * @param $access_token
 * @param $access_token_secret
 * @param $openid
 *
 */
function do_get($url, $appid, $appkey, $access_token, $access_token_secret, $openid)
{
	$sigstr = "GET"."&".rawurlencode("$url")."&";

	//必要參數, 不要隨便更改!!
	$params = $_GET;
	$params["oauth_version"]          = "1.0";
	$params["oauth_signature_method"] = "HMAC-SHA1";
	$params["oauth_timestamp"]        = time();
	$params["oauth_nonce"]            = mt_rand();
	$params["oauth_consumer_key"]     = $appid;
	$params["oauth_token"]            = $access_token;
	$params["openid"]                 = $openid;
	unset($params["oauth_signature"]);

	//參數按照字母升序做序列化
	$normalized_str = get_normalized_string($params);
	$sigstr        .= rawurlencode($normalized_str);

	//簽名,確保php版本支援hash_hmac函數
	$key = $appkey."&".$access_token_secret;
	$signature = get_signature($sigstr, $key);
	$url      .= "?".$normalized_str."&"."oauth_signature=".rawurlencode($signature);

	//echo "$url\n";
	return file_get_contents($url);
}

/**
 * @brief do multi-part post request will call this function
 *
 * @param $url
 * @param $appid
 * @param $appkey
 * @param $access_token
 * @param $access_token_secret
 * @param $openid
 *
 */
function do_multi_post($url, $appid, $appkey, $access_token, $access_token_secret, $openid)
{
	//構造簽名串.源串:方法[GET|POST]&uri&參數按照字母升序排列
	$sigstr = "POST"."&"."$url"."&";

	//必要參數,不要隨便更改!!
	$params = $_POST;
	$params["oauth_version"]          = "1.0";
	$params["oauth_signature_method"] = "HMAC-SHA1";
	$params["oauth_timestamp"]        = time();
	$params["oauth_nonce"]            = mt_rand();
	$params["oauth_consumer_key"]     = $appid;
	$params["oauth_token"]            = $access_token;
	$params["openid"]                 = $openid;
	unset($params["oauth_signature"]);


	//獲取上傳圖片資訊
	foreach ($_FILES as $filename => $filevalue)
	{
		if ($filevalue["error"] != UPLOAD_ERR_OK)
		{
			//echo "upload file error $filevalue['error']\n";
			//exit;
		}
		$params[$filename] = file_get_contents($filevalue["tmp_name"]);
	}

	//對參數按照字母升序做序列化
	$sigstr .= get_normalized_string($params);

	//簽名,需要確保php版本支援hash_hmac函數
	$key = $appkey."&".$access_token_secret;
	$signature = get_signature($sigstr, $key);
	$params["oauth_signature"] = $signature;

	//處理上傳圖片
	foreach ($_FILES as $filename => $filevalue)
	{
		$tmpfile = dirname($filevalue["tmp_name"])."/".$filevalue["name"];
		move_uploaded_file($filevalue["tmp_name"], $tmpfile);
		$params[$filename] = "@$tmpfile";
	}

	/*
	echo "len: ".strlen($sigstr)."\n";
	echo "sig: $sigstr\n";
	echo "key: $appkey&\n";
	*/

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_URL, $url);
	$ret = curl_exec($ch);
	//$httpinfo = curl_getinfo($ch);
	//print_r($httpinfo);

	curl_close($ch);
	//刪除上傳臨時檔案
	unlink($tmpfile);
	return $ret;

}

function do_post($url, $appid, $appkey, $access_token, $access_token_secret, $openid)
{
	//構造簽名串.源串:方法[GET|POST]&uri&參數按照字母升序排列
	$sigstr = "POST"."&".rawurlencode($url)."&";

	//必要參數,不要隨便更改!!
	$params = $_POST;
	$params["oauth_version"]          = "1.0";
	$params["oauth_signature_method"] = "HMAC-SHA1";
	$params["oauth_timestamp"]        = time();
	$params["oauth_nonce"]            = mt_rand();
	$params["oauth_consumer_key"]     = $appid;
	$params["oauth_token"]            = $access_token;
	$params["openid"]                 = $openid;
	unset($params["oauth_signature"]);

	//對參數按照字母升序做序列化
	$sigstr .= rawurlencode(get_normalized_string($params));

	//簽名,需要確保php版本支援hash_hmac函數
	$key = $appkey."&".$access_token_secret;
	$signature = get_signature($sigstr, $key);
	$params["oauth_signature"] = $signature;

	$postdata = get_urlencode_string($params);

	//echo "$sigstr******\n";
	//echo "$postdata\n";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt($ch, CURLOPT_URL, $url);
	$ret = curl_exec($ch);

	curl_close($ch);
	return $ret;
}
/**
 * @brief get a request token by appid and appkey
 *        rfc1738 urlencode
 * @param $appid
 * @param $appkey
 *
 * @return a string, the format as follow:
 *      oauth_token=xxx&oauth_token_secret=xxx
 */
function get_request_token($appid, $appkey)
{
	//獲取request token介面, 不要隨便更改!!
	$url    = "http://openapi.qzone.qq.com/oauth/qzoneoauth_request_token?";
	//構造簽名串.源串:方法[GET|POST]&uri&參數按照字母升序排列
	$sigstr = "GET"."&".rawurlencode("http://openapi.qzone.qq.com/oauth/qzoneoauth_request_token")."&";

	//必要參數,不要隨便更改!!
	$params = array();
	$params["oauth_version"]          = "1.0";
	$params["oauth_signature_method"] = "HMAC-SHA1";
	$params["oauth_timestamp"]        = time();
	$params["oauth_nonce"]            = mt_rand();
	$params["oauth_consumer_key"]     = $appid;

	//對參數按照字母升序做序列化
	$normalized_str = get_normalized_string($params);
	$sigstr        .= rawurlencode($normalized_str);

	//簽名,需要確保php版本支援hash_hmac函數
	$key = $appkey."&";
	$signature = get_signature($sigstr, $key);
	//構造請求url
	$url      .= $normalized_str."&"."oauth_signature=".rawurlencode($signature);

	//echo "$sigstr\n";
	//echo "$url\n";

	return file_get_contents($url);
}
/**
 * @brief get a access token
 *        rfc1738 urlencode
 * @param $appid
 * @param $appkey
 * @param $request_token
 * @param $request_token_secret
 * @param $vericode
 *
 * @return a string, as follows:
 *      oauth_token=xxx&oauth_token_secret=xxx&openid=xxx&oauth_signature=xxx&oauth_vericode=xxx&timestamp=xxx
 */
function get_access_token($appid, $appkey, $request_token, $request_token_secret, $vericode)
{
    //獲取access token介面，不要隨便更改!!
    $url    = "http://openapi.qzone.qq.com/oauth/qzoneoauth_access_token?";
    //構造簽名串.源串:方法[GET|POST]&uri&參數按照字母升序排列
    $sigstr = "GET"."&".rawurlencode("http://openapi.qzone.qq.com/oauth/qzoneoauth_access_token")."&";

    //必要參數，不要隨便更改!!
    $params = array();
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = time();
    $params["oauth_nonce"]            = mt_rand();
    $params["oauth_consumer_key"]     = $appid;
    $params["oauth_token"]            = $request_token;
    $params["oauth_vericode"]         = $vericode;

    //對參數按照字母升序做序列化
    $normalized_str = get_normalized_string($params);
    $sigstr        .= rawurlencode($normalized_str);

    //echo "sigstr = $sigstr";

    //簽名,確保php版本支援hash_hmac函數
    $key = $appkey."&".$request_token_secret;
    $signature = get_signature($sigstr, $key);
    //構造請求url
    $url      .= $normalized_str."&"."oauth_signature=".rawurlencode($signature);

    return file_get_contents($url);
}
?>