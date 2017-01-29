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
    return $this->view->render($response, './contest/index.twig', [
        'controller' => $contestController
    ]);
});

$app->get('/upload', function($request, $response, $args){
     $userController = new UserController();
    return $this->view->render($response, './contest/upload.twig', [
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
    $idUser = intval(Helper::getID($request, 'userID'));
    $idPhoto = Helper::getID($request, 'photoURL');
    $res = $userController->addToContest(intval($contestID), $idUser, $idPhoto);

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

$app->post('/upload/photo/computer', function($request, $response, $args){
    $photo = new PhotoController($request);
    
    $message = $request->getParams()['message'];
    $location = $request->getParams()['location'];

    $photo->setPhotoFacebook($request->getUploadedFiles(), $message, $location);
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

$app->post('/user/share', function(Request $request, Response $response, $args){
    $userController = new UserController();
    $res = $userController->sharePost($request);

    return Helper::responseHandler($response, $res);
});

$app->post('/permissions', function($request, $response, $args){
    $userController = new UserController();
    $res = $userController->getPermission($request);

    return Helper::responseHandler($response, $res);
});

$app->get('/login', function($request, $response, $args){
    return $this->view->render($response, './contest/login.twig');
});

// @TODO make every admin request to POST
$app->post('/admin/login', function($request, $response, $args){
    $isAdmin = AdminController::checkIfAdmin($request);

    if ($isAdmin)
        return $response->withJson(array('status' => 'success'));
    else
        return $response->withJson(array('status' => 'error'));
});

$app->get('/admin/{userID}/config', function($request, $response, $args){
    
    $adminWorkflow = Helper::adminWorkflow($args['userID']);
    $url = $this->router->pathFor('adminError');
    if(!$adminWorkflow)
        $response->withStatus(200)->withHeader('Location', $url);
    else{
        $adminController = new AdminController($args['userID']);
        $static_path = Helper::getConfigValue('admin_views_params');
        return $this->view->render($response, './admin/index.twig', [
            'controller' => $adminController,
            'data' => $static_path
        ]);
    }
});

$app->get('/admin/{userID}/contest/{contestID}', function($request, $response,$args){
    $adminWorkflow = Helper::adminWorkflow($args['userID']);

    $url = $this->router->pathFor('adminError');
    if(!$adminWorkflow)
        $response->withStatus(200)->withHeader('Location', $url);
    else{
        $adminController = new AdminController($args['userID']);
        $adminController->getSingleContestData($args['contestID'], 0);

        $static_path = Helper::getConfigValue('admin_views_params');
        return $this->view->render($response, './admin/contest.twig', [
            'controller' => $adminController,
            'data' => $static_path
        ]);
    }
});


$app->get('/admin/{userID}/creation', function($request, $response, $args){
    $adminWorkflow = Helper::adminWorkflow($args['userID']);
    $url = $this->router->pathFor('adminError');
    $static_path = Helper::getConfigValue('admin_views_params');
    
    if(!$adminWorkflow)
        $response->withStatus(200)->withHeader('Location', $url);
    else{
        $adminController = new AdminController($args['userID']);
        return $this->view->render($response, './admin/create.twig', [
            'controller' => $adminController,
            'data' => $static_path
        ]);
    }

    // @TODO replace the controller and the views...
});

$app->get('/admin/{userID}/pictures', function($request, $response, $args){
    $isAdmin = AdminController::checkIfAdmin(NULL, $args['userID']);
    $isTokenValid = AdminController::checkTokenValidity($args['userID']);

    if(!$isAdmin || !$isTokenValid)
        return $response->withJson(array('status' => 'access token error'));

    // @TODO replace the controller and the views...
    $adminController = new AdminController();
    return $this->view->render($response, './views/admin/index.twig', [
        'controller' => $adminController
    ]);
});

$app->get('/admin/{userID}/views', function($request, $response, $args){
    
    $isAdmin = AdminController::checkIfAdmin(NULL, $args['userID']);
    $isTokenValid = AdminController::checkTokenValidity($args['userID']);

    if(!$isAdmin || !$isTokenValid)
        return $response->withJson(array('status' => 'access token error'));

    // @TODO replace the controller and the views...
    $adminController = new AdminController();
    return $this->view->render($response, './index.twig', [
        'controller' => $adminController
    ]);
});

$app->post('/admin/contest', function($request, $response, $args){
    $adminID = Helper::getID($request,'adminID');
    $adminWorkflow = Helper::adminWorkflow($adminID);
    $url = $this->router->pathFor('adminError');
    $static_path = Helper::getConfigValue('admin_views_params');
    
    if(!$adminWorkflow)
        $response->withStatus(200)->withHeader('Location', $url);
    else{
        $adminController = new AdminController($adminID);
        $res = $adminController->addContest($request);

        if(!is_bool($res))
            return $response->withJson(array('status' => 'error '.$res));
        
        return $response->withJson(array('status' => 'success'));
    }
});

$app->post('/admin/setcontest', function($request, $response, $args){
    $adminID = Helper::getID($request,'adminID');
    $adminWorkflow = Helper::adminWorkflow($adminID);

    $url = $this->router->pathFor('adminError');

    if(!$adminWorkflow)
        $response->withStatus(200)->withHeader('Location', $url);
    else{
        $adminController = new AdminController($adminID);
        $res = $adminController->setContestToActive($request);

        if(!is_bool($res))
            return $response->withJson(array('status' => 'error '.$res));
        
        return $response->withJson(array('status' => 'success'));
    }
});

$app->post('/admin/updateContest', function($request, $response, $args){
    $adminID = Helper::getID($request,'adminID');
    $adminWorkflow = Helper::adminWorkflow($adminID);

    if(!$adminWorkflow){
       // $app->getContainer()->get('router')->pathFor('adminError');
    }

    $adminController = new AdminController($adminID);
    $res = $adminController->updateContest($request);

    var_dump($res);

    if(!is_bool($res))
        return $response->withJson(array('status' => 'error '.$res));
        
    return $response->withJson(array('status' => 'success'));
});

$app->post('/admin/disable', function($request, $response, $args){
    $adminID = Helper::getID($request,'adminID');
    $adminWorkflow = Helper::adminWorkflow($adminID);

    if(!$adminWorkflow){
        $app->getContainer()->get('router')->pathFor('adminError');
        return;
    }

    $adminController = new AdminController($adminID);
    $res = $adminController->disable($request);

    if(!is_bool($res))
        return $response->withJson(array('status' => 'error '.$res));
        
    return $response->withJson(array('status' => 'success'));
});

$app->get('/admin/error', function($request, $response, $args){
    return $this->view->render($response, 'error_admin.twig', [
            'error' => 'Are you sure that you are an admin ? Login once again please.'
    ]);
})->setName('adminError');

$app->run();



