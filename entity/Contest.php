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
        $results = $connection -> query("SELECT * FROM participants WHERE id_user = ".$idUser);
        return $results;

    }

    public function addPhotoToContest($idContest,$idUser,$idPhoto) {
        $connection = $this -> connect();
        try{
            var_dump($idPhoto);
            var_dump($idUser);
            var_dump($idContest);
            $req = $connection->prepare("INSERT INTO participants (id_picture, id_user, id_contest) VALUES (?, ?, ?)");
            $req->bindParam(1, $idPhoto);
            $req->bindParam(2, $idUser);
            $req->bindParam(3, $idContest);
            $res = $req->execute();

            return true;
        } catch(PDOException $e){
            return $e;
        }

    }

    public function UpdatePhotoToContest($idContest,$idUser,$idPhoto) {
        try{
            $connection = $this -> connect();
            $sql = "UPDATE participants SET id_picture='".$idPhoto."', id_contest=".$idContest." WHERE id_user=".$idUser;
            $req = $connection->prepare($sql);
            $req->execute();
        } catch(PDOException $e){
            return $e;
        }
        
    }

    /**
     *  Get Participations Of Contest
     *          Get a list of contestants of a contest based on the idContest
     *  @param an int idContest which represent the id of the addContest
     *  @return an assoc array representing the images
     *  @return an error message of type String if there's an error
     */
    public function getParticipationsOfContest($idContest){
        $connection = $this -> connect();
        try{
            $stmt = $connection -> prepare('SELECT * FROM participants WHERE id_contest = :id_contest');
            $stmt->bindParam(':id_contest', $idContest, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            
            return $result;
        } catch (PDOException $e){
            return $e;
        }
    }

    /**
     *  Check Vote
     *          Check vote check if there's the same vote in the Database 
     *          based on the given parameters
     *  @param id_participant an int representing the participant
     *  @param id_user an int representing the id of the user_error
     *  @param date a String date representing the date of the vote (today)
     *  @param contest id is an int representing the id of the contest at this time
     *  @return boolean
     */
    private function checkVote($id_participant, $id_user, $contestID){
        $connection = $this -> connect();

        try{
            $stmt = $connection -> prepare('SELECT * FROM vote WHERE id_participant = :id_participant AND id_user = :id_user AND id_contest = :id_contest');
            
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->bindParam(':id_participant', $id_participant, PDO::PARAM_INT);
            $stmt->bindParam(':id_contest', $contestID ,PDO::PARAM_INT);

            $stmt->execute();
            $res = $stmt->fetchAll();

            if(count($res) > 0){
                return false;
            } else {
                return true;
            }
        } catch(PDOException $e){
            return $e;
        }
    }

    /**
     *  Set Vote 
     *          Set vote set a vote based on the params
     *  @param id_participant an int representing the participant
     *  @param id_user an int representing the id of the user_error
     *  @param date a String date representing the date of the vote (today)
     *  @return boolean
     */
    public function setVote($id_participant, $id_user, $date){
        $connection = $this -> connect();
        // Get our contest ID
        $contest = $this -> getCurrentContest();
        $contestID = $contest['id'];

        $isPresent = $this->checkVote($id_participant, $id_user, $contestID);

        if(!$isPresent){
            return 'vote is already present';
        }

        try{
            $stmt = $connection -> prepare('INSERT INTO vote (id_user, id_participant, date_vote, id_contest) VALUES (:id_user, :id_participant, :date_vote, :id_contest)');

            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->bindParam(':id_participant', $id_participant, PDO::PARAM_INT);
            $stmt->bindParam(':date_vote', $date, PDO::PARAM_STR);
            $stmt->bindParam(':id_contest', $contestID ,PDO::PARAM_INT);

            $res = $stmt->execute();

            // Update the participants counter
            $this->updateCounterVote($id_participant, $contestID);

            return $res;
        } catch (PDOException $e){
            //var_dump('ma');
            return $e;
        }
    }

    /**
     *  Update Counter Vote
     *          Update the vote counter of a participant
     *  @param participant_id 
     *  @param contestID
     */
    private function updateCounterVote($id_participant, $contestID){
        $connection = $this -> connect();
        $counter = 0;
        try{
            // Prepare the request 
            $stmt = $connection -> prepare('SELECT COUNT(*) AS NumberOfVote FROM vote WHERE id_participant = :id_participant AND id_contest = :id_contest');
            // Bind the param
            $stmt->bindParam(':id_participant', $id_participant, PDO::PARAM_INT);
            $stmt->bindParam(':id_contest', $contestID, PDO::PARAM_INT);
            $stmt->execute();

            $res = $stmt->fetchAll();

            foreach($res as $value){
                $counter = $value['NumberOfVote'];
            }

            if(!$res)
                return 'error';
            
            // Now update the participant database

            $updateStmt = $connection -> prepare("UPDATE participants SET vote = :vote_number WHERE id_user = :id_participant");
            // bind the param
            $updateStmt->bindParam(':id_participant', $id_participant, PDO::PARAM_INT);
            $updateStmt->bindParam(':vote_number', $counter, PDO::PARAM_INT);
            $updateRes = $updateStmt->execute();

            if(!$updateRes)
                return 'error';

        } catch (PDOException $e){
            return $e;
        }
    }
}