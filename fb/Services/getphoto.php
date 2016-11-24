<?php

require_once __DIR__ . '/../fb_sdk/src/Facebook/autoload.php';

use Facebook\FacebookRequest;

session_start();

/*
 * uploadPhotos
 * @Private
 * Void
 */

function instanceFBApp(){
  $fbApp = new Facebook\FacebookApp('1418106458217541', '951fc8f75cad3716a15efd1f4f053647');

  return $fbApp;
}

function getListOfAlbums($fb){
  $album_array = array();
  $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
  try{
    $res = $fb->get('/me?fields=id,picture');
    $usr = $res->getGraphUser();
    $userID = $usr['id'];
    if(isset($userID)){
       $fbApp = instanceFBApp();
       $request = new Facebook\FacebookRequest($fbApp, $_SESSION['facebook_access_token'] , 'GET', '/'.$userID.'/albums');
      try{
        $response = $fb->getClient()->sendRequest($request);
        $resBody = $response->getDecodedBody();

        foreach($resBody as $key => $value){
          foreach($value as $v){
            $t = array_shift($v);
            array_push($album_array, $v);
          }
        }
        return $album_array;
      } catch(Facebook\Exceptions\FacebookResponseException $e){
        var_dump($e->getMessage());
      }
    }
  } catch(Facebook\Exceptions\FacebookResponseException $e){
    print_r($e->getMessage());
  }
}

function getListOfPhotosFromAlbum($albumID, $fb){
  $fbApp = instanceFBApp();
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
  echo $album_id;
}
