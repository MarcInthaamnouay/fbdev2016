<?php

require "Db.php";

class Contest extends Db {

    function isUserInContest($idContest,$idUsers) {
        $connection = $this -> connect();
    }

    
    public function getAllContest() {
        // Connect to the database
        $connection = $this -> connect();

        // Query the database
        $result = $connection -> query("SELECT * FROM contest order by end asc");
        return $result;
    }

    public function getCurrentContest() {
        // Connect to the database
        $connection = $this -> connect();

        // Query the database
        $results = $connection -> query("SELECT * FROM contest");
        $now = new DateTime();
        $result = false;
        foreach($results as $row){
            if(new DateTime($row['start'])<$now && new DateTime($row['end']) >$now)
                $result = $row;
        }
        return $result;
    }

     public function haveCurrentContest() {
        $connection = $this -> connect();
        $results = $connection -> query("SELECT * FROM contest");
        $now = new DateTime();
        foreach($results as $row){
            if(new DateTime($row['start'])<$now && new DateTime($row['end']) >$now)
                return true;
        }
        return false;
    }

    public function addContest($title,$text,$lot,$infos,$start,$end){
        $connection = $this -> connect();
        $req = $connection->prepare("INSERT INTO contest (title, text, lot, infos, start, end) VALUES (?, ?, ?, ?, ?, ?)");
        $req->bindParam(1, $title);
        $req->bindParam(2, $text);
        $req->bindParam(3, $lot);
        $req->bindParam(4, $infos); 
        $req->bindParam(5, $start);
        $req->bindParam(6, $end);
        $req->execute();
    }

    public function updateContest($id,$title,$text,$lot,$infos,$start,$end){
        $connection = $this -> connect();
        $sql = "UPDATE contest SET title=".$title.", text=".$text.", lot=".$lot.", infos=".$infos.", start=".$start.", end=".$end." WHERE id_user=".$id;
        $req = $connection->prepare($sql);
        $req->execute();
    }

    public function getContestOfUser($idUser){
        $connection = $this -> connect();
        $results = $connection -> query("SELECT * FROM participants WHERE id_users = ".$idUser);
        return $results;

    }

    public function addPhotoToContest($idContest,$idUser,$idPhoto) {
        $connection = $this -> connect();
        $req = $connection->prepare("INSERT INTO participants (id_picture, id_users, id_contest) VALUES (?, ?, ?)");
        $req->bindParam(1, $idPhoto);
        $req->bindParam(2, $idUser);
        $req->bindParam(3, $idContest);
        $res = $req->execute();

        print_r($res);
        var_dump('yolo');
    }

    public function UpdatePhotoToContest($idContest,$idUser,$idPhoto) {
        var_dump("laaa");
        $connection = $this -> connect();
        $sql = "UPDATE participants SET id_picture=".$idPhoto.", id_contest=".$idContest." WHERE id_user=".$idUser;
        $req = $connection->prepare($sql);
        $req->execute();
    }

    public function getParticipationsOfContest($idContest){
        $connection = $this -> connect();
        $results = $connection -> query("SELECT * FROM participants where id_contest = ".$idContest);
        $result = $results->fetch();
        return $result;
    }
}