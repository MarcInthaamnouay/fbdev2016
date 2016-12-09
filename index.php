<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

session_start();

// Using slim framework to handle all the APP and mainly the API.

include('vendor/autoload.php');
require_once __DIR__ . '/Autoload/autoload.php';
require_once __DIR__ . '/Services/getphoto.php';
require_once __DIR__ . '/Services/admin.php';

// Configure the framework to show the error in DEV
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;
const API_NAMESPACE = '/api/v1.0/';


// Slim app

$app = new Slim\App(["settings" => $config]);
// @TODO Define a constat for the API

/*
 * Albums/
 *    Get the albums of the user
 * @Params {token} String
 */
$app->post(API_NAMESPACE.'albums/', function($request, $response, $args){
  // Get the list of the albums
  $token = getToken($request)

  if($token){
    // Call the getListOfAlbums function by passing the fb variable
    $data = getListOfAlbums($token);
    $newdata = $response->withJson($data, 201);

    return $newdata;
  } else {
    return $response->withJson(array('nodata' => 'no data'), 404);
  }
});

/*
 * Photos
 * @params {Token} token
 * @params {AlbumID} Number
 * @return {Json} photos
 */
$app->post(API_NAMESPACE.'photos/', function($request, $response, $args){
  $data = $request->getParsedBody();
  $token = filter_var($data['token']);
  $id = filter_var($data['albumID']);

  $photos = getListOfPhotosFromAlbum($id, $token);

  return $response->withJson($photos, 201);
});

/*
 * Contest/create
 * @params {Token} string
 * @params {UserID} string
 * @Return {response} Json
 */
$app->post(API_NAMESPACE.'contest/create', function($request, $response, $args){
  // Checking the auth

  $token = getToken($request);
  $userID = getUserID($request);

  if (isset($token) && isset($userID)){
    // Checking if the user is an admin
    $admin = new Admin($userID,$token);
    $isGranted = $admin->isAdmin();

    if($isGranted){
      if($admin->isTokenValid()){
          $response->withJson(array('status' => 'can create contest'), 200);
      }
      // Can create a contest
    } else {
      $response->withJson(array('status' => 'contest not created'), 401);
    }
  }

});

$app->put(API_NAMESPACE.'contest/subscribe', function($request, $response, $args){

});

$app->get(API_NAMESPACE.'contest/result', function($request, $response, $args){

});

$app->get(API_NAMESPACE.'contest/getStat', function($request, $response, $args){

});

$app->post(API_NAMESPACE.'contest/crons', function($request, $response, $args){

});

$app->post(API_NAMESPACE.'database', function($request, $response, $args){

});

$app->get(API_NAMESPACE.'contest/{idContest}', function($request, $response, $args){

});

/*
 * admin/auth
 * @Params {Token} string
 * @Params {UserID} string
 * @Return {Response} Json
 */
$app->post(API_NAMESPACE.'admin/auth', function($request, $response, $args){
  // Get the token
  $token = getToken($request);
  // Get the userID
  $id = getUserID($request);

  if($token != '' && $id != ''){
    // Instantiate a new Admin class
    $admin = new Admin($id,$token);
    // Check if the user is an admin of the app
    $isAdmin = $admin->isAdmin();

    if(!$isAdmin){
      $response->withJson(array('permission' => 'denied', 'code' => 401));
      return;
    }

    if($admin->isTokenValid()){
        $response->withJson(array('permission' => 'granted', 'code' => 200));
    }
  } else{
    $response->withJson(array('permissions' => 'denied', 'code' => 401));
  }

  // Register other route
});


$app->post(API_NAMESPACE.'admin/config', function($request, $response, $args){


});

$app->post(API_NAMESPACE.'admin/analytics', function($request, $response, $args){


});

$app->post(API_NAMESPACE.'admin/pictures', function($request, $response, $args){


});

$app->post(API_NAMESPACE.'admin/views', function($request, $response, $args){


});

$app->run();
