<?php


require_once __DIR__ . '/../vendor/fb_sdk/src/Facebook/autoload.php';
require_once __DIR__ . '/helper.php';

use Facebook\FacebookRequest;

Class Photos{

    private $token;
    private $helper;
    private $userID;

    function __construct($userID){
        $this->userID = $userID;
        $this->helper = new Helper();
        $this->token = $this->helper->getDBToken($this->userID);
    }

    function getAlbums(){
        $fb = $this->helper->getFBService();
        $fb->setDefaultAccessToken($this->token);
        $album_array = array();
        try{
            if(isset($this->userID)){
                $fbApp = $this->helper->instanceFBApp();
                $request = new Facebook\FacebookRequest($fbApp, $this->token, 'GET', '/'.$this->userID.'/albums');
            try{
                $response = $fb->getClient()->sendRequest($request);
                $resBody = $response->getDecodedBody();

                foreach($resBody as $key => $value){
                    foreach($value as $v){
                        $t = array_shift($v);
                        array_push($album_array, $v);
                    }
                }
            } catch(Facebook\Exceptions\FacebookResponseException $e){
                var_dump($e->getMessage());
            }
            }
        } catch(Facebook\Exceptions\FacebookResponseException $e){
            print_r($e->getMessage());
        }

        return $album_array;
    }

    public function getListOfPhotosFromAlbum($albumID){
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
    }
}

