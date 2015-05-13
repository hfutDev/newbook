<?php

/*
	[UCenter] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: client.php 864 2008-12-11 05:06:20Z monkey $
*/

if(!defined('UC_API')) {
	exit('Access denied');
}

error_reporting(0);

define('IN_UC', TRUE);
define('UC_CLIENT_VERSION', '1.5.0');
define('UC_CLIENT_RELEASE', '20081212');
define('UC_ROOT', substr(__FILE__, 0, -10));		//note 用户中心客户端的根目录 UC_CLIENTROOT
define('UC_DATADIR', UC_ROOT.'./data/');		//note 用户中心的数据缓存目录
define('UC_DATAURL', UC_API.'/data');			//note 用户中心的数据 URL
define('UC_API_FUNC', UC_CONNECT == 'mysql' ? 'uc_api_mysql' : 'uc_api_post');
$GLOBALS['uc_controls'] = array();

function uc_addslashes($string, $force = 0, $strip = FALSE) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = uc_addslashes($val, $force, $strip);
			}
		} else {
			$string = addslashes($strip ? stripslashes($string) : $string);
		}
	}
	return $string;
}

if(!function_exists('daddslashes')) {
	function daddslashes($string, $force = 0) {
		return uc_addslashes($string, $force);
	}
}

function uc_stripslashes($string) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(MAGIC_QUOTES_GPC) {
		return stripslashes($string);
	} else {
		return $string;
	}
}

/**
 *  dfopen 方式取指定的模块和动作的数据
 *
 * @param string $module	请求的模块
 * @param string $action 	请求的动作
 * @param array $arg		参数（会加密的方式传送）
 * @return string
 */
function uc_api_post($module, $action, $arg = array()) {
	$s = $sep = '';
	foreach($arg as $k => $v) {
		$k = urlencode($k);
		if(is_array($v)) {
			$s2 = $sep2 = '';
			foreach($v as $k2 => $v2) {
				$k2 = urlencode($k2);
				$s2 .= "$sep2{$k}[$k2]=".urlencode(uc_stripslashes($v2));
				$sep2 = '&';
			}
			$s .= $sep.$s2;
		} else {
			$s .= "$sep$k=".urlencode(uc_stripslashes($v));
		}
		$sep = '&';
	}
	$postdata = uc_api_requestdata($module, $action, $s);
	return uc_fopen2(UC_API.'/index.php', 500000, $postdata, '', TRUE, UC_IP, 20);
}

/**
 * 构造发送给用户中心的请求数据
 *
 * @param string $module	请求的模块
 * @param string $action	请求的动作
 * @param string $arg		参数（会加密的方式传送）
 * @param string $extra		附加参数（传送时不加密）
 * @return string
 */
function uc_api_requestdata($module, $action, $arg='', $extra='') {
	$input = uc_api_input($arg);
	$post = "m=$module&a=$action&inajax=2&release=".UC_CLIENT_RELEASE."&input=$input&appid=".UC_APPID.$extra;
	return $post;
}

function uc_api_url($module, $action, $arg='', $extra='') {
	$url = UC_API.'/index.php?'.uc_api_requestdata($module, $action, $arg, $extra);
	return $url;
}

function uc_api_input($data) {
	$s = urlencode(uc_authcode($data.'&agent='.md5($_SERVER['HTTP_USER_AGENT'])."&time=".time(), 'ENCODE', UC_KEY));
	return $s;
}

/**
 * MYSQL 方式取指定的模块和动作的数据
 *
 * @param string $model		请求的模块
 * @param string $action	请求的动作
 * @param string $args		参数（会加密的方式传送）
 * @return mix
 */
function uc_api_mysql($model, $action, $args=array()) {
	global $uc_controls;
	if(empty($uc_controls[$model])) {
		include_once UC_ROOT.'./lib/db.class.php';
		include_once UC_ROOT.'./model/base.php';
		include_once UC_ROOT."./control/$model.php";
		eval("\$uc_controls['$model'] = new {$model}control();");
	}
	if($action{0} != '_') {
		$args = uc_addslashes($args, 1, TRUE);
		$action = 'on'.$action;
		$uc_controls[$model]->input = $args;
		return $uc_controls[$model]->$action($args);
	} else {
		return '';
	}
}

function uc_serialize($arr, $htmlon = 0) {
	include_once UC_ROOT.'./lib/xml.class.php';
	return xml_serialize($arr, $htmlon);
}

function uc_unserialize($s) {
	include_once UC_ROOT.'./lib/xml.class.php';
	return xml_unserialize($s);
}

/**
 * 字符串加密以及解密函数
 *
 * @param string $string	原文或者密文
 * @param string $operation	操作(ENCODE | DECODE), 默认为 DECODE
 * @param string $key		密钥
 * @param int $expiry		密文有效期, 加密时候有效， 单位 秒，0 为永久有效
 * @return string		处理后的 原文或者 经过 base64_encode 处理后的密文
 *
 * @example
 *
 * 	$a = authcode('abc', 'ENCODE', 'key');
 * 	$b = authcode($a, 'DECODE', 'key');  // $b(abc)
 *
 * 	$a = authcode('abc', 'ENCODE', 'key', 3600);
 * 	$b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
 */
function uc_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;	//note 随机密钥长度 取值 0-32;
				//note 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
				//note 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
				//note 当此值为 0 时，则不产生随机密钥

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

/**
 *  远程打开URL
 *  @param string $url		打开的url，　如 http://www.baidu.com/123.htm
 *  @param int $limit		取返回的数据的长度
 *  @param string $post		要发送的 POST 数据，如uid=1&password=1234
 *  @param string $cookie	要模拟的 COOKIE 数据，如uid=123&auth=a2323sd2323
 *  @param bool $bysocket	TRUE/FALSE 是否通过SOCKET打开
 *  @param string $ip		IP地址
 *  @param int $timeout		连接超时时间
 *  @param bool $block		是否为阻塞模式
 *  @return			取到的字符串
 */
function uc_fopen2($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
	$__times__ = isset($_GET['__times__']) ? intval($_GET['__times__']) + 1 : 1;
	if($__times__ > 2) {
		return '';
	}
	$url .= (strpos($url, '?') === FALSE ? '?' : '&')."__times__=$__times__";
	return uc_fopen($url, $limit, $post, $cookie, $bysocket, $ip, $timeout, $block);
}

function uc_fopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
	$return = '';
	$matches = parse_url($url);
	!isset($matches['host']) && $matches['host'] = '';
	!isset($matches['path']) && $matches['path'] = '';
	!isset($matches['query']) && $matches['query'] = '';
	!isset($matches['port']) && $matches['port'] = '';
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;
	if($post) {
		$out = "POST $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
	if(!$fp) {
		return '';//note $errstr : $errno \r\n
	} else {
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
		if(!$status['timed_out']) {
			while (!feof($fp)) {
				if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
					break;
				}
			}

			$stop = false;
			while(!feof($fp) && !$stop) {
				$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
				$return .= $data;
				if($limit) {
					$limit -= strlen($data);
					$stop = $limit <= 0;
				}
			}
		}
		@fclose($fp);
		return $return;
	}
}

function uc_app_ls() {
	$return = call_user_func(UC_API_FUNC, 'app', 'ls', array());
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}




/**
 * 取得用户数据
 *
 * @param string $username	用户名
 * @param int $isuid	是否为UID
 * @return array (uid, username, email)
 */
function uc_get_user($username, $isuid=0) {
	$return = call_user_func(UC_API_FUNC, 'user', 'get_user', array('username'=>$username, 'isuid'=>$isuid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * 用户合并最后的处理
 *
 * @param string $oldusername	老用户名
 * @param string $newusername	新用户名
 * @param string $uid		老UID
 * @param string $password	密码
 * @param string $email		Email
 * @return int
	-1 : 用户名不合法
	-2 : 包含不允许注册的词语
	-3 : 用户名已经存在
	>1 : 表示成功，数值为 UID
 */
function uc_user_merge($oldusername, $newusername, $uid, $password, $email) {
	return call_user_func(UC_API_FUNC, 'user', 'merge', array('oldusername'=>$oldusername, 'newusername'=>$newusername, 'uid'=>$uid, 'password'=>$password, 'email'=>$email));
}



/**
 * 获取域名解析表
 *
 * @return array()
 */
function uc_domain_ls() {
	$return = call_user_func(UC_API_FUNC, 'domain', 'ls', array('1'=>1));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * 获取基本信息
 *
 * @param int $uid	用户id
 * @return array (uid,nickname,sex)
	1:男生
	0:女生
 */
function uc_getBaseInfo($uid){
		$postdata = "uid= ".$uid."&op=base";
		$return = uc_fopen2(USR_API, 500000, $postdata, '', TRUE, UC_IP, 20);
	return objectToArray(json_decode($return));
}

/**
 * 获取更多信息
 *
 * @param int $uid	用户id
 * @return array (uid,realname, realname_permission ,college ,college_permission ,province ,province_permission ,qq ,qq_permission ,phonenumber ,phonenumber_permission ,realnameinfo)
 */
function uc_getOptInfo($uid){
	$postdata = "uid=".$uid."&op=opt";
	$return = uc_fopen2(USR_API, 500000, $postdata, '', TRUE, UC_IP, 20);
	return objectToArray(json_decode($return));
}

/**
 * 获取权限信息
 *
 * @param int $uid	用户id
 * @return array (id,power,authname)
	1:男生
	0:女生
 */
function uc_getAuth($uid){
	$postdata = "uid=".$uid."&op=auth&appid=".UC_APPID;
	$return = uc_fopen2(USR_API, 500000, $postdata, '', TRUE, UC_IP, 20);
	$return = objectToArray(json_decode($return));
	return $return;
}

function uc_checkLogin(){
	$cookiefile = $_COOKIE[UC_COOKIE];
	$cookiefile = explode("\t",uc_authcode($cookiefile,'DECODE'));
	if($cookiefile[0] > 0) {
		if(!is_utf8($cookiefile[1])){
			$cookiefile[1] = iconv("GBK","UTF-8",$cookiefile[1]);
		}
		$info = array(
			'bool'=>1,
			'type'=>'Logined',
			'info'=>array(
				'uid' => $cookiefile[0],
				'username' => $cookiefile[1]
			)
		);
		return $info;
	} else {
		$info = array(
			'bool' =>-1,
			'type' => 'Undefined'
		);
		return $info;
	}
}

/**
 * 检测uc_server的数据库版本和程序版本
 * @return mixd
 *		array('db' => 'xxx', 'file' => 'xxx');
 *		null 无法调用到接口
 *		string 文件版本低于1.5
 */
function uc_check_version() {
	$return = uc_api_post('version', 'check', array());
	$data = uc_unserialize($return);
	return is_array($data) ? $data : $return;
}

function arrayToObject($e){
	if( gettype($e)!='array' ) return;
		foreach($e as $k=>$v){
			if( gettype($v)=='array' || getType($v)=='object' )
			$e[$k]=(object)arrayToObject($v);
		}
	return (object)$e;
}

function objectToArray($e){
	$e=(array)$e;
	foreach($e as $k=>$v){
		if( gettype($v)=='resource' ) return;
		if( gettype($v)=='object' || gettype($v)=='array' )
		$e[$k]=(array)objectToArray($v);
	}
	return $e;
}
function is_utf8($word) { 
	if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true) { 
		return true; 
	} else { 
		return false; 
	} 
}
?>
