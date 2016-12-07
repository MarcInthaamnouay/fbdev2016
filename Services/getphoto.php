<?php

require_once __DIR__ . '/../fb_sdk/src/Facebook/autoload.php';
require_once __DIR__ . '/../Common/common.php';

use Facebook\FacebookRequest;

/*
 * uploadPhotos
 * @Private
 * Void
 */


function getListOfAlbums($token){
  $fb = newFBService();
  $album_array = array();
  $fb->setDefaultAccessToken($token);
  try{
    $res = $fb->get('/me?fields=id,picture');
    $usr = $res->getGraphUser();
    $userID = $usr['id'];
    if(isset($userID)){
       $fbApp = instanceFBApp();
       $request = new Facebook\FacebookRequest($fbApp, $token, 'GET', '/'.$userID.'/albums');
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

function getListOfPhotosFromAlbum($albumID,$token){
  $fbApp = instanceFBApp();
  $request = new Facebook\FacebookRequest($fbApp, $token , 'GET', '/'.$albumID.'/photos/uploaded?fields=source,images,name');

  try{
    $fb = newFBService();
    $response = $fb->getClient()->sendRequest($request);
    $resBody = $response->getDecodedBody();

    return $resBody;
  } catch(Facebook\Exceptions\FacebookResponseException $e){
    echo $e->getMessage();
  }
  echo $album_id;
}
