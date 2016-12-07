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


// Slim app

$app = new Slim\App(["settings" => $config]);
// @TODO Define a constat for the API

/*
 * GetAlbums
 * @params {Token} token
 * @return {Json} album_data
 */
$app->post('/api/v1.0/albums/', function($request, $response, $args){
  //  $app = \Slim\Slim::getInstance();
  // Get the list of the albums
  $data = $request->getParsedBody();
  $token = filter_var($data['token']);

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
 * GetPhotos
 * @params {Token} token
 * @params {AlbumID} Number
 * @return {Json} photos
 */
$app->post('/api/v1.0/photos/', function($request, $response, $args){
  $data = $request->getParsedBody();
  $token = filter_var($data['token']);
  $id = filter_var($data['albumID']);

  $photos = getListOfPhotosFromAlbum($id, $token);

  return $response->withJson($photos, 201);
});

/*
 * GetPhotos
 * @params {ID} String
 * @return {Json} contest
 */

$app->post('/api/v1.0/contest/create', function($request, $response, $args){

});

$app->put('/api/v1.0/contest/subscribe', function($request, $response, $args){

});

$app->get('/api/v1.0/contest/result', function($request, $response, $args){

});

$app->get('/api/v1.0/contest/getStat', function($request, $response, $args){

});

$app->post('/api/v1.0/contest/crons', function($request, $response, $args){

});

$app->post('/api/v1.0/database', function($request, $response, $args){

});

$app->get('/api/v1.0/contest/{idContest}', function($request, $response, $args){

});

$app->post('/api/v1.0/admin/', function($request, $response, $args){
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
      $response->withJson(array('permission' => 'denied', 401));

      return;
    }
  } else{
    $response->withJson(array('permissions' => 'denied'), 401);
  }

  // Register other route

  
});


$app->run();
