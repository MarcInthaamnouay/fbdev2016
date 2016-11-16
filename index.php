<?php
	require_once __DIR__ . '/fb_sdk/src/Facebook/autoload.php';

	session_start();
 	$messageError= array();
 	$permissions = ['email','manage_pages','publish_pages', 'user_photos'];

	$fb = new Facebook\Facebook([
	  'app_id' => '639819622867674',
	  'app_secret' => '505ddf28d58518809be301ac1e732393',
	  'default_graph_version' => 'v2.8',
	  'default_access_token' => isset($_SESSION['facebook_access_token']) ? $_SESSION['facebook_access_token'] : '639819622867674s|505ddf28d58518809be301ac1e732393'
	]);
  	
  	// Récupération du nom utilisateur
	try {
  		$response = $fb->get('/me?fields=id,name,link,gender,locale,picture');
  		$user = $response->getGraphUser();
  		$userName = $user['name'];
  		$userID = $user['id'];
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
	$loginUrl = $helper->getLoginUrl('http://berseck.fbdev.fr/fb/login-callback.php', $permissions);
	$rerequestUrl = $helper->getReRequestUrl('http://berseck.fbdev.fr/fb/login-callback.php', $permissions);
	$logoutUrl = $helper->getLogoutUrl('http://berseck.fbdev.fr/fb/logout.php', $permissions);

	//Now if we are connect
	if($userName != null) echo "Bienvenue ". $userName;
	else echo '<a href="'.$loginUrl.'"> Login </a><br />';

	//get photos
    $pagesEdge = $fb->get('/me/photos?access_token=' . $_SESSION['facebook_access_token'])->getGraphEdge();
   
   	
    
    //Check permissions
    $lackPermission=false; //On suppose qu'au départ les permissions sont mauvaises.

	try {
	    $response = $fb->get('/me/permissions');
	    $perms = $response->getDecodedBody();

        foreach($permissions AS $myPerm){//Pour chacune de mes perms, je vérifie que l'utilisateur à la perm
        	foreach($perms['data'] AS $perm)
            	if($perm['permission'] == $myPerm && $perm['status']!='granted') $lackPermission = true;
        }

	} catch(Facebook\Exceptions\FacebookResponseException $e) {
	    array_push($messageError,"Erreur facebook : ".$e->getMessage());
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	    array_push($messageError,"erreur SDlL ".$e->getMessage());
	}

	if($lackPermission && $userName != null) // connecté avec manque de permission
		echo '<a href="'.$loginUrl.'"> Login </a><br />';
	else if(!$lackPermission && $userName != null) // connecté sans manque de permission
		echo "Bienvenue ". $userName;
	else echo '<a href="'.$rerequestUrl.'"> Login </a><br />';


?>