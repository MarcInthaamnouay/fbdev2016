<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
// Import our controller
require_once 'controllers/ContestController.php';
require_once 'controllers/UserController.php';

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);


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
    $contestController = new ContestController();
    return $this->view->render($response, 'example.twig', [
        'controller' => $contestController

    ]);
});

$app->get('/upload', function($request, $response, $args){
    $userController = new UserController();
    return $this->view->render($response, 'upload.twig', [
        'controller' => $userController
    ]);
})->setName('upload');

$app->get('/admin', function($request, $response, $args){

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



