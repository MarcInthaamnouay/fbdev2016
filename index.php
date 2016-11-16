<?php
	require_once __DIR__ . '/fb_sdk/src/Facebook/autoload.php';

	session_start();
 	$messageError= array();
 	$permissions = ['email','manage_pages','publish_pages', 'user_photos', 'user_videos', 'page_show_list'];

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
            	if($perm['permission'] == $myPerm && $perm['status']!='granted') { $lackPermission = true; break; }
        }

	} catch(Facebook\Exceptions\FacebookResponseException $e) {
	    array_push($messageError,"Erreur facebook : ".$e->getMessage());
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	    array_push($messageError,"erreur SDlL ".$e->getMessage());
	}

	//Check albums
	try{
    	$res = $fb->get('/me?fields=id,picture');
    	$usr = $res->getGraphUser();
    	$userID = $usr['id'];
    		if(isset($userID)){
      			$fbApp = new Facebook\FacebookApp('639819622867674', '505ddf28d58518809be301ac1e732393');
      			$request = new Facebook\FacebookRequest($fbApp, $_SESSION['facebook_access_token'] , 'GET', '/'.$userID.'/albums');
      try{
        $response = $fb->getClient()->sendRequest($request);
 
        $resBody = $response->getDecodedBody();
     	echo '<br />
     		<form>
				<select name="albums" size="1">';

        foreach($resBody as $key => $value){
          foreach($value as $v){
          	if(isset($v["name"]))
            echo '<option value='.$v["id"].'>'.$v["name"].'</option>';
          }
        }
        	echo '<select>
        	</form>';

 
      } catch(Facebook\Exceptions\FacebookResponseException $e){
        echo $e->getMessage();
      }
    }
 
  } catch(Facebook\Exceptions\FacebookResponseException $e){
    echo "<p>An error happened while getting you're photo ".$e."</p>";
  }

	//Affichage
	if($lackPermission && $userName != null) // connecté avec manque de permission
		echo '<a href="'.$loginUrl.'"> Login </a><br />';
	else if(!$lackPermission && $userName != null)// connecté sans manque de permission
		echo '<a href="'.$logoutUrl.'"> Logout </a><br />';
	else echo '<a href="'.$rerequestUrl.'"> Login </a><br />';

/*public function checkAccessToken()
{
	if (empty($_SESSION['facebook_access_token'])) return false;
	try {
			$reponse = $fb->get('/debug_token?input_token'.$_SESSION['facebook_access_token']);
			$graphObject = $response->getGraphObject();
		}
		catch(Exception $e) {
			return false;
		}
	return true;
}
*/

//Check photos
	try{
    	$albumID = "1378638042376891";
    		if(isset($albumID)){
      			$fbApp = new Facebook\FacebookApp('639819622867674', '505ddf28d58518809be301ac1e732393');
      			$request = new Facebook\FacebookRequest($fbApp, $_SESSION['facebook_access_token'] , 'GET', '/'.$albumID.'/photos/uploaded?fields=source,images,name');
      try{
        $response = $fb->getClient()->sendRequest($request);
 
        $resBody = $response->getDecodedBody();
     	echo 'Affichage de l"album : '.$albumID.'<br />';

        foreach($resBody as $key => $value){
          foreach($value as $v){
          	if(isset($v["id"]))
          	{
          		if(isset($v["name"])) $name = $v["name"];
          		else $name = 'Pas de nom';
          		echo '<img src="'.$v["source"].'" width =50px/><br /> Name : '.$name.'<br />';
          	}
          }
        }

 
      } catch(Facebook\Exceptions\FacebookResponseException $e){
        echo $e->getMessage();
      }
    }
 
  } catch(Facebook\Exceptions\FacebookResponseException $e){
    echo "<p>An error happened while getting you're photo ".$e."</p>";
  }


  //upload photo
  
  try {
  	$file = "test.JPG";
  	$ch = curl_init();
	$args = array(
	   'message' => 'Photo from application',
	);
	$args[basename($file)] = '@' . realpath($file);
	$ch = curl_init();
	$url = 'http://graph.facebook.com/'.$albumID.'/photos?access_token='.$_SESSION['facebook_access_token'];
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
	$data = curl_exec($ch);
	//returns the photo id
	print_r(json_decode($data,true));
  } catch(FacebookRequestException $e) {

    echo "Exception occured, code: " . $e->getCode();
    echo " with message: " . $e->getMessage();

  }   

?>