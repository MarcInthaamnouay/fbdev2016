<?php

require_once __DIR__.'/../service/photos.php';
require_once __DIR__.'/../entity/Contest.php';

Class UserController{
    private $token;
    private $contest;
    private $photo;

    function __construct($userID){
        $this->photo = new Photos($userID);
        $this->contest = new Contest();
    }

    /**
    * Rcupration des albums de l'utilisateur
    * @return {Object} les Albums de notre utilisateur
    */
    public function getAlbums(){
        $album = $this->photo->getAlbums();
        
        return $album;
    }

    function getPictures($albumID){
        $pictures = $this->photo->getListOfPhotosFromAlbum();
    }

    function setToken($token){
        $this->token = $token;
    }

    /**
     * V�rifie si l'utilisateur est dans le concours
     * @var idUser L'identifiant de l'utilisateur
     * @var idContest L'identifiant de notre concours
     * @return {Boolean}, vrai si l'utilisateur est dans le contest et faux sinon
    */
    public function inContest($idUser, $idContest){
        if(empty($idUser) || empty($idContest) || !is_int($idUser) || !is_int($idContest)) return false;
        $results = $this->contest->getContestOfUser();
        foreach ($$results as $key => $value) {
            if($value['id_user'] == $idContest) return true;
        }
        return false;
    }

    /**
     * Ajoute avec une v�rification l'image de l'utilisateur dans le concours
     * @var idUser L'identifiant de l'utilisateur
     * @var idContest L'identifiant de notre concours
     * @var idPhoto L'identifiant de la photo a ajout� � notre concours
     * @return {Boolean}, vrai dans les cas
    */
    public function addToContest($idUser, $idContest, $idPhoto){
        if(empty($idUser) || empty($idContest) || !is_int($idUser) || empty($idPhoto) || !is_int($idPhoto) || !is_int($idContest)) return false;
        if($this->inContest($idUser,$idContest))
            $this->contest->addPhotoToContest($idContest,$idUser,$idPhoto);
        else
            $this->contest->updatePhotoToContest($idContest,$idUser,$idPhoto);
        return true;
    }
}