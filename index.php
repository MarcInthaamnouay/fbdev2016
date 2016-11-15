<?php
	require_once __DIR__ . '/fb_sdk/src/Facebook/autoload.php';

	session_start();
 
	$fb = new Facebook\Facebook([
	  'app_id' => '639819622867674',
	  'app_secret' => '505ddf28d58518809be301ac1e732393',
	  'default_graph_version' => 'v2.8',
	  'default_access_token' => isset($_SESSION['facebook_access_token']) ? $_SESSION['facebook_access_token'] : '639819622867674s|505ddf28d58518809be301ac1e732393'
	]);
  
	try {
  		$response = $fb->get('/me?fields=id,name');
  		$user = $response->getGraphUser();
  		echo 'Name: ' . $user['name'];
  		exit; //redirect, or do whatever you want
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
  	echo 'Graph returned an error: ' . $e->getMessage();
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
}
 
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'user_likes'];
$loginUrl = $helper->getLoginUrl('http://berseck.fbdev.fr/fb/login-callback.php', $permissions);
echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
 
?>