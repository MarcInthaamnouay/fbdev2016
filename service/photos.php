<?php

require_once __DIR__ . '/../vendor/fb_sdk/src/Facebook/autoload.php';
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/../entity/Db.php';
require_once __DIR__ . '/fb.php';

use Facebook\FacebookRequest;
use Facebook\FileUpload\FacebookFile;

Class Photos{

    private $token;
    private $helper;
    private $userID;

    function __construct($userID){
        $this->userID = $userID;
        $this->helper = new Helper();
        $this->token = Helper::retrieveToken($this->userID);
    }

    /**
     *  GetAlbums services
     *  Get a List of Albums
     *  @return an array of album
     *  @return a String if an error happened
     */
    public function getAlbums(){
        $album_array = array();

        $request = '/'.$this->userID.'/albums';
        $fbRequest = new FacebookServices($request, $this->token, 'GET', null);
        $res = $fbRequest->make();
        
        foreach($res['data'] as $key => $value){
            array_push($album_array, $value);
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
        $fbApp = Helper::instanceFBApp();
        $request = new Facebook\FacebookRequest($fbApp, $this->token , 'GET', '/'.$albumID.'/photos/uploaded?fields=id,source,images,name');

        try{
            $fb = Helper::getFBService();
            $response = $fb->getClient()->sendRequest($request);
            $resBody = $response->getDecodedBody();

            return $resBody;
        } catch(Facebook\Exceptions\FacebookResponseException $e){
            return $e->getMessage;
        }
    }

    /**
     *  Bulk Request
     *          Make a bulk request 
     *  @param mixed
     *  @return mixed data
     */
    public function bulkRequest($params){
        $fbApp = Helper::instanceFBApp();
        $request = new Facebook\FacebookRequest($fbApp, $this->token , 'POST', '?batch='.urlencode(json_encode($params)));
        $batchData = array();

        try{
            $fb = Helper::getFBService();
            $response = $fb->getClient()->sendRequest($request);
            $resBody = $response->getDecodedBody();

            foreach($resBody as $key => $value){
                $res = json_decode($value['body']);

                 if (count($res->data))
                     array_push($batchData,$res->data[0]);
            }

            return $batchData;
        } catch (Facebook\Exceptions\FacebookResponseException $e){
            return $e->getMessage();
        }
            
    }

    /**
     *  Upload Photo To User
     *          Uploading a photo from the computer to Facebook
     */
    public function createAlbum($image, $message){
        // first we check if an album with the name of berseck already checkIfAlbumExist
        $exist = $this->checkIfAlbumExist();
        
        if(!is_bool($exist)){
            return $this->uploadPhotoAlbum($exist, $image, $message);
        }
            
        $albumData = array('location' => 'france',
                            'message'  => 'message of the contest',
                            'name'     => 'berseck');

        $request = '/'.$this->userID.'/albums';
        $fbRequest = new FacebookServices($request, $this->token, 'POST', $albumData);
        $res = $fbRequest->make();

        if($res['id'])
            return $this->uploadPhotoAlbum($res['id'], $image, $message);
    }

    /**
     *  Check If Album Exist
     *          Check if the berseck album exist
     */
    private function checkIfAlbumExist(){
       $albums = $this->getAlbums();
       foreach($albums as $album){
           if($album['name'] == 'berseck')
                return $album['id'];
       }

       return false;
    }

    /**
     *  Upload Photo Album
     */
    private function uploadPhotoAlbum($albumID, $image, $message){
        if(empty($image['image'])){
            var_dump('expected a photo');
        }
            
        $facebookFile = new FacebookFile($image['image']->file);
        $albumData = array(
                    'source' => $facebookFile,
                    'message' => $message,
                );

        var_dump($albumData);
        $request = '/'.$albumID.'/photos';

        $fbRequest = new FacebookServices($request, $this->token, 'POST', $albumData);
        $res = $fbRequest->make();

        // Now that we have upload the photo we can set it directly in the DB

        if(!$res['id'])        
            throw new Exception('error');

        $imageID = $res['id'];
        $listOfPhotos = $this->getListOfPhotosFromAlbum($albumID);

        foreach($listOfPhotos['data'] as $photo){
            if($photo['id'] == $imageID)   
                $this->setAsImg($photo['source']);
        }
    }


    private function setAsImg($url){
        // @TODO review this as we're in a hurry i'm duplicating this.. from the userController
        $userController = new UserController();
        $contest = new Contest();
        $contestID = $contest->getCurrentContest()['id'];
        $res = $userController->addToContest(intval($contestID), intval($this->userID), $url);
    }
}

