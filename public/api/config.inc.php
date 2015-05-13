<?php
define('UC_CONNECT', 'mysql');
define('UC_DBHOST', 'localhost');
define('UC_DBUSER', 'online');
define('UC_DBPW', 'TWvJrGhU4pAmTFBp');
define('UC_DBNAME', 'online');
define('UC_DBCHARSET', 'gbk');
define('UC_DBTABLEPRE', '`online`.ucenter_');
define('UC_DBCONNECT', '0');
define('UC_KEY', '8a149XgHUF6j/8nGpZMic8btRlQxJzoD+QMIx9A');
define('UC_API', 'http://ucenter.hfutonline.net');
define('UC_CHARSET', 'gbk');
define('UC_IP', '');
define('UC_APPID', '25');
define('UC_PPP', '20');


define('UC_COOKIE','Book_Auth');
// define('USR_API', 'http://user.hfutonline.net/api/index');
define('USR_API', 'http://user.hfutonline.net/api/index');



//ucexample_2.php 用到的应用程序数据库连接参数
$dbhost = 'localhost';			// 数据库服务器
$dbuser = 'online';			// 数据库用户名
$dbpw = 'TWvJrGhU4pAmTFBp';				// 数据库密码
$dbname = 'online';			// 数据库名
$pconnect = 0;				// 数据库持久连接 0=关闭, 1=打开
$tablepre = 'ucenter_';   		// 表名前缀, 同一数据库安装多个论坛请修改此处
$dbcharset = 'gbk';			// MySQL 字符集, 可选 'gbk', 'big5', 'utf8', 'latin1', 留空为按照论坛字符集设定

//同步登录 Cookie 设置
$cookiepre = '';
$cookiedomain = ''; 			// cookie 作用域
$cookiepath = '/';			// cookie 作用路径

