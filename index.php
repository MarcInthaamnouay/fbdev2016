<?php
	require_once __DIR__ . '/fb_sdk/src/Facebook/autoload.php';

	session_start();
 	$messageError= array();

	$fb = new Facebook\Facebook([
	  'app_id' => '639819622867674',
	  'app_secret' => '505ddf28d58518809be301ac1e732393',
	  'default_graph_version' => 'v2.8',
	  'default_access_token' => isset($_SESSION['facebook_access_token']) ? $_SESSION['facebook_access_token'] : '639819622867674s|505ddf28d58518809be301ac1e732393'
	]);
  	
  	// Récupération du nom utilisateur
	try {
  		$response = $fb->get('/me?fields=id,name,age_rank,link,gender,locale,picture');
  		$user = $response->getGraphUser();
  		$userName = $user['name'];
  		$userID = $user['id'];
  		$userAge = $user['age_rank'];
  		$userLink = $user['link'];
  		$userGender = $user['gender'];
  		$userLocale = $user['locale'];
  		$userPicture = $user['picture'];
  		$userAccessToken = $_SESSION['facebook_access_token'];
  		$isConnect = true;

	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		$isConnect = false;
  		array_push($messageError,'Graph returned an error: ' . $e->getMessage());
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		$isConnect = false;
  		array_push($messageError,'Facebook SDK returned an error: ' . $e->getMessage());
	}
	
	//get login URL
	$helper = $fb->getRedirectLoginHelper();
	$permissions = ['email', 'user_photo','manage_pages','publish_pages'];
	$loginUrl = $helper->getLoginUrl('http://berseck.fbdev.fr/fb/login-callback.php', $permissions);

	//get photos
    $photos = $fb->get('/me/photos?access_token=' . $_SESSION['facebook_access_token'])>getGraphEdge();
    var_dump($photos);

?>