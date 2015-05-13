<?php

session_start();
//if( isset($_SESSION['last_key']) ) header("Location: weibolist.php");
include_once( 'config.php' );
include_once( 'weibooauth.php' );

$o = new WeiboOAuth( WB_AKEY , WB_SKEY  );

$keys = $o->getRequestToken();
$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , 'http://'.$_SERVER['HTTP_HOST'].'/api/callback.php');

$_SESSION['keys'] = $keys;
header('Location: '. $aurl .'');
?>
<a href="<?=$aurl?>">Use Oauth to login</a>