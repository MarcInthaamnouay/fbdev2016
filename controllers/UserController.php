<?php

require_once __DIR__.'/../service/photos.php';

Class UserController{
    private $token;
    private $photo;

    function __construct(){
        $this->photo = new Photos($this->token);
    }

    function getAlbums(){
        $album = $this->photo->getAblums();

        var_dump($album);
    }

    function getPictures($albumID){
        $pictures = $this->photo->getListOfPhotosFromAlbum("688238887890982");

        var_dump($pictures);
    }

    function setToken($token){
        $this->token = $token;
    }
}