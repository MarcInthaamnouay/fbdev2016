<?php

require_once __DIR__ . '/../vendor/fb_sdk/src/Facebook/autoload.php';
require_once __DIR__ . '/helper.php';

use Facebook\FacebookRequest;

Class Photos{

    private $token;
    private $helper;

    function __construct($token){
        $this->token = $token;
        $this->helper = new Helper();
    }

    function getAblums(){
        $fb = $this->helper->getFBService();
        $fb->setDefaultAccessToken($this->token);

        $album_array = array();
        try{
            $res = $fb->get('/me?fields=id,picture');
            $usr = $res->getGraphUser();
            $userID = $usr['id'];

            if(isset($userID)){
                $fbApp = $this->helper->instanceFBApp();
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

    function getListOfPhotosFromAlbum($albumID){
        $fbApp = $this->helper->instanceFBApp();
        $request = new Facebook\FacebookRequest($fbApp, $this->token , 'GET', '/'.$albumID.'/photos/uploaded?fields=source,images,name');

        try{
            $fb = $this->helper->getFBService();
            $response = $fb->getClient()->sendRequest($request);
            $resBody = $response->getDecodedBody();

            return $resBody;
        } catch(Facebook\Exceptions\FacebookResponseException $e){
            echo $e->getMessage();
        }
        echo $albumID;
    }
}

