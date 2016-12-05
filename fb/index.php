<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

session_start();

// Using slim framework to handle all the APP and mainly the API.

include('vendor/autoload.php');
require_once __DIR__ . '/Autoload/autoload.php';
require_once __DIR__ . '/Services/getphoto.php';
require_once __DIR__ . '/Services/cron.php';

// Configure the framework to show the error in DEV
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

// Slim app

$app = new Slim\App(["settings" => $config]);

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

$app->post('/api/v1.0/photos/', function($request, $response, $args){
  $data = $request->getParsedBody();
  $token = filter_var($data['token']);
  $id = filter_var($data['albumID']);

  $photos = getListOfPhotosFromAlbum($id, $token);

  return $response->withJson($photos, 201);
});

$app->get('/api/v1.0/crons', function($request, $response, $args){

});

$app->get('/api/v1.0/contest/{id}', function($request, $response, $args){

});

$app->post('/api/v1.0/contest/create', function($request, $response, $args){

});

$app->run();
