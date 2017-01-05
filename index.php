<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
// Import our controller
require_once 'controllers/ContestController.php';
require_once 'controllers/UserController.php';
require_once 'controllers/PhotoController.php';

// Import our helper
require_once 'service/helper.php';
require_once 'service/connection.php';



$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);


$app->add(new \RKA\SessionMiddleware(['name' => 'berseck-fbdev']));

// Define the container for the views

$container = $app->getContainer();

$container['view'] = function($container){
    $view = new \Slim\Views\Twig('views/');
    
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$app->get('/', function($request, $response, $args){
    // Make a call to the contestController
    $this->logger->addInfo("Something interesting happened");
    $homeController = new ContestController();

    $contestController = new ContestController();
    return $this->view->render($response, 'example.twig', [
        'controller' => $contestController
    ]);
});

$app->get('/upload/{userID}', function($request, $response, $args){
     $userController = new UserController();
        return $this->view->render($response, 'upload.twig', [
            'controller' => $userController,
        ]);
})->setName('upload');

$app->post('/albums', function($request, $response, $args){
    $helper = new Helper();
    $userID = $helper->getID($request, 'userID');

    $photoController = new PhotoController($userID);
    $albums = $photoController->getAlbums();

 
    if(count($albums) > 0){
        return json_encode($albums);
    }
});

$app->post('/photos', function($request, $response, $args){
    $helper = new Helper();
    $userID = $helper->getID($request, 'userID');
    $albumID = $helper->getID($request, 'albumID');
    $photoController = new PhotoController($userID);
    
    // get the photo
    $photos = $photoController->getPictures($albumID);

    if(count($photos) > 0){
        return json_encode($photos);
    }
});

$app->post('/token', function($request, $response, $args){
    $helper = new Helper();
    $token = $helper->getToken($request);
    $userID = $helper->getID($request, 'userID');

    $saveToken = new connexion();
    $res = $saveToken->adduser($userID, $token);

    print_r($res);
    // @TODO Use is_bool to compare 2 boolean...
    if($res == true){
        $response->withJson(array('status' => 'success'), 401);
    } else {
        $response->withJson(array('status' => 'error'), 401);
    }

    
});

$app->get('/login', function($request, $response, $args){
    return $this->view->render($response, 'login.twig');
});

$app->get('/admin/login', function($request, $response, $args){

});

$app->get('/admin/config', function($request, $response, $args){

});

$app->get('/admin/analytics', function($request, $response, $args){

});

$app->get('/admin/pictures', function($request, $response, $args){

});

$app->get('/admin/views', function($request, $response, $args){

});

$app->run();



