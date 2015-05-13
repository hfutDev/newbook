<?php
/*************************
 * 登陆 同步登陆 登出
 * 检查站点设置与用户实名信息检查
*************************/

class Application_Model_UserCheck
{

	public function __construct()
	{
		include_once './api/config.inc.php';
		include_once './api/uc_client/client.php';
	}
	
	public function test($uid){
		return uc_getBaseInfo($uid);
	}

	public function getBaseInfo($uid){
		return uc_getBaseInfo($uid);
	}
    
	public function getOptInfo($uid){
		return uc_getOptInfo($uid);
	}
	
	public function getAuth($uid){
		return uc_getAuth($uid);
	}
	
	public function getAvatarUrl($uid) {
        return UC_API.'/avatar.php?uid='.$uid;
    }

	public function checklogin() {
		return uc_checkLogin();
	}

	public static function getSession() {
		$session = new Zend_Session_Namespace(self::$_sessions);
		return $session;
	}
}
