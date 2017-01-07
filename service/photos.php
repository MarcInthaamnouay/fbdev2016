<?php

require_once __DIR__ . '/../vendor/fb_sdk/src/Facebook/autoload.php';
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/../entity/Db.php';

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

    /**
     *  GetAlbums services
     *  Get a List of Albums
     *  @return an array of album
     *  @return a String if an error happened
     */
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
                return $e->getMessage();
            }
            }
        } catch(Facebook\Exceptions\FacebookResponseException $e){
            return $e->getMessage();
        }

        return $album_array;
    }

    /**
     *  GetListOfPhotosFromAlbum
     *  Get a list of photo from an Album ID
     *  @return a list of photos
     *  @return a string of an error message
     *  @param a int representing the ID of an album
     */
    public function getListOfPhotosFromAlbum($albumID){
        $fbApp = $this->helper->instanceFBApp();
        $request = new Facebook\FacebookRequest($fbApp, $this->token , 'GET', '/'.$albumID.'/photos/uploaded?fields=source,images,name');

        try{
            $fb = $this->helper->getFBService();
            $response = $fb->getClient()->sendRequest($request);
            $resBody = $response->getDecodedBody();

            return $resBody;
        } catch(Facebook\Exceptions\FacebookResponseException $e){
            return $e->getMessage;
        }
    }

    /**
     *  SaveIntoDB
     *  Insert a photo in the participants database
     *  @param a string of the photo url selected by the user
     *  @return a response from the database
     */
    public function saveIntoDB($photoURL){
        $query = 'INSERT INTO participants (id_picture, id_user) VALUES (:id_picture, :id_user)';
        $query->bindParam(':id_picture', $photoURL);
        $query->bindParam(':id_user', $this->userID);

        $con = new Db();
        $res = $con -> query($query);

        return $res;
    }
}

