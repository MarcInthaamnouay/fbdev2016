<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
// Import our controller
require_once 'controllers/AdminController.php';
require_once 'controllers/ContestController.php';
require_once 'controllers/UserController.php';
require_once 'controllers/PhotoController.php';

// Import our service
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

$app->get('/', function($request, $response, $args){
    // Make a call to the contestController
  //  $this->logger->addInfo("Something interesting happened");
    $homeController = new ContestController();

    $contestController = new ContestController();
    return $this->view->render($response, 'index.twig', [
        'controller' => $contestController
    ]);
});

$app->get('/upload', function($request, $response, $args){
     $userController = new UserController();
    return $this->view->render($response, 'upload.twig', [
        'controller' => $userController,
    ]);
})->setName('upload');

$app->post('/albums', function($request, $response, $args){
    $photoController = new PhotoController($request);
    $albums = $photoController->getAlbums();

    if(count($albums) > 0){
        return json_encode($albums);
    }
});

$app->post('/albums/photocover', function($request, $response, $args){
    $photoController = new PhotoController($request);
    $res = $photoController->getAlbumCoverPhoto($request);

    if(!is_array($res))
        return $response->withJson(array('status' => 'error '.$res));
    
    return $response->withJson($res);
});

$app->post('/photos', function($request, $response, $args){
    $photoController = new PhotoController($request);
    
    // get the photo
    $photos = $photoController->getPictures($request);

    if(count($photos) > 0){
        return json_encode($photos);
    }
});

$app->post('/upload/photo', function($request, $response, $args){
 
    $contest = new Contest();
    $contestID = $contest->getCurrentContest()['id'];
    // Instance our controller with this parameters
    $userController = new UserController();
    $res = $userController->addToContest($request, intval($contestID));

    if($res){
        return $response->withJson(array('status' => 'success'), 200);
    } else{
        return $response->withJson(array('status' => 'error '.$res), 200);
    }
});

$app->post('/token', function($request, $response, $args){
    $helper = new Helper();
    $token = $helper->getToken($request);
    // get the userID
    $userID = Helper::getID($request, 'userID');

    $saveToken = new connexion();
    $res = $saveToken->adduser($userID, $token);

    // @TODO Use is_bool to compare 2 boolean...
    if($res === true){
        return $response->withJson(array('status' => 'success'), 200);
    } else {
        return $response->withJson(array('status' => 'error'), 200);
    }
});

$app->post('/user/like', function($request, $response, $args){
    // Create an instance of the ContestController
    $contestController = new ContestController();
    $res = $contestController->setLikes($request);

    if($res && is_bool($res)){
        return $response->withJson(array('status' => 'success'), 200);
    } else {
        if(is_bool($res))
            return $response->withJson(array('status' => 'error'), 500);
        else
            return $response->withJson(array('status' => 'error '.$res), 200);
    }
});

$app->get('/login', function($request, $response, $args){
    return $this->view->render($response, 'login.twig');
});

// @TODO make every admin request to POST
$app->post('/admin/login', function($request, $response, $args){
    $isAdmin = AdminController::checkIfAdmin($request);

    if ($isAdmin)
        return $response->withJson(array('status' => 'success'));
    else
        return $response->withJson(array('status' => 'error'));
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



