<?php

require_once __DIR__.'/../service/photos.php';

Class UserController{
    private $token;
    private $photo;

    function __construct($token){
        $this->token = $token;
        $this->photo = new Photos($this->token);
    }

    function getAlbums(){
        $album = $this->photo->getAblums();

        return $album;
    }
}