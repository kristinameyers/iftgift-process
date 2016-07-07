<?php
include('../connect/connect.php');
$action = $_REQUEST["action"];
switch($action){
	case "fblogin":
	include('../facebook/facebook/facebook.php');
	$appid 		= "341516796048593";
	$appsecret  = "6e0c09aa6694edae11174421037b1d50";
	$facebook   = new Facebook(array(
  		'appId' => $appid,
  		'secret' => $appsecret,
  		'cookie' => TRUE,
	));
	$fbuser = $facebook->getUser();
	if ($fbuser) {
		try {
		    $user_profile = $facebook->api('/me');
			$friends = $facebook->api('/me/friends');
			$_SESSION['friendslist'] = $friends;
			header("location:".ru."dashboard");
		}
		catch (Exception $e) {
			echo $e->getMessage();
			exit();
		}
		
	}
	break;
}

?>