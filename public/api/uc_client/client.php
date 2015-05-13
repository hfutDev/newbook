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
define('UC_ROOT', substr(__FILE__, 0, -10));		//note �û����Ŀͻ��˵ĸ�Ŀ¼ UC_CLIENTROOT
define('UC_DATADIR', UC_ROOT.'./data/');		//note �û����ĵ����ݻ���Ŀ¼
define('UC_DATAURL', UC_API.'/data');			//note �û����ĵ����� URL
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
 *  dfopen ��ʽȡָ����ģ��Ͷ���������
 *
 * @param string $module	�����ģ��
 * @param string $action 	����Ķ���
 * @param array $arg		����������ܵķ�ʽ���ͣ�
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
 * ���췢�͸��û����ĵ���������
 *
 * @param string $module	�����ģ��
 * @param string $action	����Ķ���
 * @param string $arg		����������ܵķ�ʽ���ͣ�
 * @param string $extra		���Ӳ���������ʱ�����ܣ�
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
 * MYSQL ��ʽȡָ����ģ��Ͷ���������
 *
 * @param string $model		�����ģ��
 * @param string $action	����Ķ���
 * @param string $args		����������ܵķ�ʽ���ͣ�
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
 * �ַ��������Լ����ܺ���
 *
 * @param string $string	ԭ�Ļ�������
 * @param string $operation	����(ENCODE | DECODE), Ĭ��Ϊ DECODE
 * @param string $key		��Կ
 * @param int $expiry		������Ч��, ����ʱ����Ч�� ��λ �룬0 Ϊ������Ч
 * @return string		������ ԭ�Ļ��� ���� base64_encode ����������
 *
 * @example
 *
 * 	$a = authcode('abc', 'ENCODE', 'key');
 * 	$b = authcode($a, 'DECODE', 'key');  // $b(abc)
 *
 * 	$a = authcode('abc', 'ENCODE', 'key', 3600);
 * 	$b = authcode('abc', 'DECODE', 'key'); // ��һ��Сʱ�ڣ�$b(abc)������ $b Ϊ��
 */
function uc_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;	//note �����Կ���� ȡֵ 0-32;
				//note ���������Կ���������������κι��ɣ�������ԭ�ĺ���Կ��ȫ��ͬ�����ܽ��Ҳ��ÿ�β�ͬ�������ƽ��Ѷȡ�
				//note ȡֵԽ�����ı䶯����Խ�����ı仯 = 16 �� $ckey_length �η�
				//note ����ֵΪ 0 ʱ���򲻲��������Կ

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
 *  Զ�̴�URL
 *  @param string $url		�򿪵�url������ http://www.baidu.com/123.htm
 *  @param int $limit		ȡ���ص����ݵĳ���
 *  @param string $post		Ҫ���͵� POST ���ݣ���uid=1&password=1234
 *  @param string $cookie	Ҫģ��� COOKIE ���ݣ���uid=123&auth=a2323sd2323
 *  @param bool $bysocket	TRUE/FALSE �Ƿ�ͨ��SOCKET��
 *  @param string $ip		IP��ַ
 *  @param int $timeout		���ӳ�ʱʱ��
 *  @param bool $block		�Ƿ�Ϊ����ģʽ
 *  @return			ȡ�����ַ���
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
 * ȡ���û�����
 *
 * @param string $username	�û���
 * @param int $isuid	�Ƿ�ΪUID
 * @return array (uid, username, email)
 */
function uc_get_user($username, $isuid=0) {
	$return = call_user_func(UC_API_FUNC, 'user', 'get_user', array('username'=>$username, 'isuid'=>$isuid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * �û��ϲ����Ĵ���
 *
 * @param string $oldusername	���û���
 * @param string $newusername	���û���
 * @param string $uid		��UID
 * @param string $password	����
 * @param string $email		Email
 * @return int
	-1 : �û������Ϸ�
	-2 : ����������ע��Ĵ���
	-3 : �û����Ѿ�����
	>1 : ��ʾ�ɹ�����ֵΪ UID
 */
function uc_user_merge($oldusername, $newusername, $uid, $password, $email) {
	return call_user_func(UC_API_FUNC, 'user', 'merge', array('oldusername'=>$oldusername, 'newusername'=>$newusername, 'uid'=>$uid, 'password'=>$password, 'email'=>$email));
}



/**
 * ��ȡ����������
 *
 * @return array()
 */
function uc_domain_ls() {
	$return = call_user_func(UC_API_FUNC, 'domain', 'ls', array('1'=>1));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * ��ȡ������Ϣ
 *
 * @param int $uid	�û�id
 * @return array (uid,nickname,sex)
	1:����
	0:Ů��
 */
function uc_getBaseInfo($uid){
		$postdata = "uid= ".$uid."&op=base";
		$return = uc_fopen2(USR_API, 500000, $postdata, '', TRUE, UC_IP, 20);
	return objectToArray(json_decode($return));
}

/**
 * ��ȡ������Ϣ
 *
 * @param int $uid	�û�id
 * @return array (uid,realname, realname_permission ,college ,college_permission ,province ,province_permission ,qq ,qq_permission ,phonenumber ,phonenumber_permission ,realnameinfo)
 */
function uc_getOptInfo($uid){
	$postdata = "uid=".$uid."&op=opt";
	$return = uc_fopen2(USR_API, 500000, $postdata, '', TRUE, UC_IP, 20);
	return objectToArray(json_decode($return));
}

/**
 * ��ȡȨ����Ϣ
 *
 * @param int $uid	�û�id
 * @return array (id,power,authname)
	1:����
	0:Ů��
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
 * ���uc_server�����ݿ�汾�ͳ���汾
 * @return mixd
 *		array('db' => 'xxx', 'file' => 'xxx');
 *		null �޷����õ��ӿ�
 *		string �ļ��汾����1.5
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
