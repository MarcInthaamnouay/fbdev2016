<?php

require_once __DIR__.'/../service/photos.php';

Class PhotoController{
    private $token;
    private $photo;

    function __construct($userID){
        $this->photo = new Photos($userID);
    }

    /**
     *  Get Albums from a Facebook user
     *  @return an album of an array if its an array
     *  @return an array dispatching an error 
     *  @Route("/albums")
     */
    public function getAlbums(){
        $album = $this->photo->getAlbums();

        if(gettype($album) === "array"){
            return $album;
        } else {
            return array("status" => "error".$album);
        }
    
        return $album;
    }

    /**
     *  Get a list of pictures from an Album ID
     *  @return a list of pictures
     *  @Route("/photos")
     */
    public function getPictures($albumID){
        $pictures = $this->photo->getListOfPhotosFromAlbum($albumID);
        
        return $pictures;
    }

    /**
     *  Save the photo in the database
     *  @return a json Object
     *  @Route("/upload/photo")
     */
    public function savePhotoInDB($photoURL){
        $insertResult = $this->photo->saveIntoDb($photoURL);

        var_dump($insertResult);
    }
}