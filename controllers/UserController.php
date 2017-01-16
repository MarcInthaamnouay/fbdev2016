<?php


require_once __DIR__.'/../entity/Contest.php';
require_once __DIR__.'/../service/helper.php';

Class UserController{
    private $contest;
    private $photo;

    function __construct(){
        $this->contest = new Contest();
    }

    /**
     * Verifie si l'utilisateur est dans le concours
     * @var idUser L'identifiant de l'utilisateur
     * @var idContest L'identifiant de notre concours
     * @return {Boolean}, vrai si l'utilisateur est dans le contest et faux sinon
    */
    public function inContest($idUser, $idContest){
        if(empty($idUser) || empty($idContest) || !is_int($idUser) || !is_int($idContest)) return false;
        $results = $this->contest->getContestOfUser($idUser);
    
        foreach ($results as $key => $value) {
            if($value['id_contest'] == $idContest)
                if($value['id_user'] == $idUser) 
                    return true;
        }

        return false;
    }

    /**
     * Ajoute avec une verification l'image de l'utilisateur dans le concours
     * @var idUser L'identifiant de l'utilisateur
     * @var idContest L'identifiant de notre concours
     * @var idPhoto L'identifiant de la photo a ajoute a notre concours
     * @return Boolean, vrai dans les cas
     */
    public function addToContest($request, $idContest){
        $idUser = intval(Helper::getID($request, 'userID'));
        $idPhoto = Helper::getID($request, 'photoURL');

        if(empty($idUser) || empty($idContest) || !is_int($idUser) || empty($idPhoto) || !is_int($idContest)){
            return "invalid params";
        }
            
        if(!$this->inContest($idUser,$idContest)){
            $res = $this->contest->addPhotoToContest($idContest,$idUser,$idPhoto);
            return $res;
        }

        $resUpdate = $this->contest->updatePhotoToContest($idContest,$idUser,$idPhoto);

        return $resUpdate;
    }
}