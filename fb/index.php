<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

session_start();

// Using slim framework to handle all the APP and mainly the API.

include('vendor/autoload.php');
require_once __DIR__ . '/Autoload/autoload.php';
require_once __DIR__ . '/Services/getphoto.php';

// Configure the framework to show the error in DEV
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

// Slim app

$app = new Slim\App(["settings" => $config]);
$url = checkAccessToken();

$app->get('/api/v1.0/auth', function($request, $response, $args){
  $isAuth = array('auth_status' => false, 'auth_url' => null);
  if(isset($_SESSION['facebook_access_token'])){
    $isAuth['auth_status'] = true;
    return $response->withJson($isAuth, 201);
  } else {
    $url = initFacebookSDK();
    $isAuth['auth_url'] = $url;
    return $response->withJson($isAuth, 201);
  }
});

$app->get('/api/v1.0/albums/', function($request, $response, $args){
//  $app = \Slim\Slim::getInstance();
  // Get the list of the albums
  $haveToken = checkAccessToken();
  if(isset($_SESSION['facebook_access_token'])){
    // Call the getListOfAlbums function by passing the fb variable
    $internFB = newFBService();
    $data = getListOfAlbums($internFB);
    $newdata = $response->withJson($data, 201);

    return $newdata;
  }
});

$app->get('/api/v1.0/photos/{album-id}', function($request, $response, $args){
  $album_id = $request->getAttribute('album-id');
  $photos = getListOfPhotosFromAlbum($album_id);

  return $response->writeJson($photos, 201);
});

$app->get('/api/v1.0/crons', function($request, $response, $args){

});

$app->run();

function checkAccessToken(){
  if(isset($_SESSION['facebook_access_token'])){
    return;
  } else{
    return initFacebookSDK();
  }
}

function newFBService(){
  $fb = new Facebook\Facebook([
    'app_id' => '1418106458217541',
    'app_secret' => '951fc8f75cad3716a15efd1f4f053647',
    'default_graph_version' => 'v2.8',
  ]);

  return $fb;
}
