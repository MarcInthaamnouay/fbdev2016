<?php

require_once __DIR__.'/../service/photos.php';

Class PhotoController{
    private $token;
    private $photo;

    function __construct($userID){
        $this->photo = new Photos($userID);
    }

    /**
    * Rcupration des albums de l'utilisateur
    * @return {Object} les Albums de notre utilisateur
    */
    public function getAlbums(){
        $album = $this->photo->getAlbums();
    
        return $album;
    }

    public function getPictures($albumID){
        $pictures = $this->photo->getListOfPhotosFromAlbum($albumID);
        
        return $pictures;
    }
}