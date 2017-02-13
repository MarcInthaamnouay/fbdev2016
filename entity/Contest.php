<?php

require "Db.php";

class Contest extends Db {

    function isUserInContest($idContest,$idUsers) {
        $connection = $this -> connect(true);
        $contest = new Contest();
    }

    /**
     *  Get All Contest
     *          Get all contest
     *  @public
     */
    public function getAllContest() {
        // Connect to the database
        $connection = $this -> connect(true);

        // Query the database
        try{
            $stmt = $connection -> prepare("SELECT * FROM contest order by end desc");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function getCurrentContest() {
        // Connect to the database
        $connection = $this -> connect(true);

        // Query the database
        try{
            $results = $connection -> query("SELECT * FROM contest");
            $result = false;
            foreach($results as $row){
                if($row['active'] == 1)
                    $result = $row;
            }
            return $result;
        } catch (PDOException $e){
            return $e->getMessage();
        }
    }
    
    /**
     *  disactivate Contest
     *          Disactivate a contest based on it's id
     *  @param int contestID
     *  @return string if error
     */
    public function disactivateContest($contestID){
        $connection = $this -> connect(true);
        $date = new DateTime();

        try {
            $stmt = $connection->prepare('UPDATE contest SET active = 0 WHERE id = :contestID');

            $stmt->bindParam(':contestID', $contestID);
       //     $stmt->bindParam(':endDate', $date->date);
            $res = $stmt->execute();

            return (bool) $res;
        } catch(PDOException $e){
            return $e->getMessage();
        }
    }

    /**
     *  Activate Contest
     *          Activate a contest based on it's id
     */
     public function activateContest($contestID){
        $connection = $this -> connect(true);

        try {
            $stmt = $connection->prepare('UPDATE contest SET active = 1 WHERE id = :contestID');

            $stmt->bindParam(':contestID', $contestID);
            $res = $stmt->execute();
        } catch(PDOException $e){
            return $e->getMessage();
        }
     }

    /** 
     *  Add Contest
     *          Add a contest into the database
     */
    public function addContest($title,$text,$lot,$start,$end,$infos){
        $connection = $this -> connect(true);

        try{
             $isDataValid = $this->beforeAddContest($title, $text, $lot, $start, $end, $infos);
             try{
                $req = $connection->prepare("INSERT INTO contest (title, text, lot, start, end, titrelot) VALUES (:title, :texte, :lot, :start, :end, :titrelot)");
                $req->bindParam(':title', $title, PDO::PARAM_STR);
                $req->bindParam(':texte', $text, PDO::PARAM_STR);
                $req->bindParam(':lot', $lot, PDO::PARAM_STR);
                $req->bindParam(':start', $start, PDO::PARAM_STR);
                $req->bindParam(':end', $end, PDO::PARAM_STR);
                $req->bindParam(':titrelot', $infos, PDO::PARAM_STR); 
                $req->execute();

                return true;
            } catch(PDOException $e){
                return $e->getMessage();
            }
        } catch (Exception $e){
            return $e->getMessage();
        }
    }

    /**
     *  Before Add Contest
     *          Check if there's a contest in the DB
     *  @param string title
     *  @param text string
     *  @param lot string
     *  @param string start (date)
     *  @param string end (date)
     *  @param string infos
     *  @return boolean
     */
    private function beforeAddContest($title,$text,$lot,$start,$end,$infos){
        $results = $this->getAllContest();
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);

        foreach($results as $res){
            $dbStartTime = new DateTime($res['start']);
            $dbEndTime = new DateTime($res['end']);

            if ($startDate <= $dbStartTime || $endDate <= $dbEndDate){
                throw new Exception('date are invalids');
                return false;
            } 
            else if ($startDate > $endDate){
                throw new Exception('start date is before the end date');
                return false;
            }
        }

        return true;
    }

    /**
     *  Update Contest
     *          Update a contest 
     *  @param int id
     *  @param string title
     *  @param string text
     *  @param string lot
     *  @param string info
     *  @param string (date) start
     */
    public function updateContest($id,$title,$lot,$end,$desc){
        $connection = $this -> connect(true);

        try{
            $stmt = $connection->prepare('UPDATE contest SET title = :title, lot = :lot, end = :end, text = :text WHERE id= :id');
            
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':lot', $lot, PDO::PARAM_STR);
            $stmt->bindParam(':end', $end, PDO::PARAM_STR);
            $stmt->bindParam(':text', $desc, PDO::PARAM_STR);
            $stmt->bindParam(':id', intval($id), PDO::PARAM_INT);

            return (bool) $stmt->execute();

        } catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function getContestOfUser($idUser){
        $connection = $this -> connect(true);
        $results = $connection -> query("SELECT * FROM participants WHERE id_user = ".$idUser);
        return $results;

    }

    public function addPhotoToContest($idContest,$idUser,$idPhoto) {
        $connection = $this -> connect(true);
        try{
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
            $connection = $this -> connect(true);
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
        $connection = $this -> connect(true);
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
        $connection = $this -> connect(true);

        try{
            $stmt = $connection -> prepare('SELECT * FROM vote WHERE id_participant = :id_participant AND id_user = :id_user AND id_contest = :id_contest');
            
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);
            $stmt->bindParam(':id_participant', $id_participant, PDO::PARAM_STR);
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
        $connection = $this -> connect(true);
        // Get our contest ID
        $contest = $this -> getCurrentContest();
        $contestID = $contest['id'];

        $isPresent = $this->checkVote($id_participant, $id_user, $contestID);

        if(!$isPresent){
            return 'vote is already present';
        }

        try{
            $stmt = $connection -> prepare('INSERT INTO vote (id_user, id_participant, date_vote, id_contest) VALUES (:id_user, :id_participant, :date_vote, :id_contest)');

            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);
            $stmt->bindParam(':id_participant', $id_participant, PDO::PARAM_STR);
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
        $connection = $this -> connect(true);
        $counter = 0;
        try{
            // Prepare the request 
            $stmt = $connection -> prepare('SELECT COUNT(*) AS NumberOfVote FROM vote WHERE id_participant = :id_participant AND id_contest = :id_contest');
            // Bind the param
            $stmt->bindParam(':id_participant', $id_participant, PDO::PARAM_STR);
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
            $updateStmt->bindParam(':id_participant', $id_participant, PDO::PARAM_STR);
            $updateStmt->bindParam(':vote_number', $counter, PDO::PARAM_INT);
            $updateRes = $updateStmt->execute();

            if(!$updateRes)
                return 'error';

        } catch (PDOException $e){
            return $e;
        }
    }

    /**
     *  Get Single Contest
     *          Return the data of a single contest based on the ID
     *  @param int contestID
     */
    public function getSingleContest($contestID){
        $connection = $this -> connect(true);
        try{
            $stmt = $connection->prepare('SELECT * FROM contest INNER JOIN participants ON contest.id = participants.id_contest INNER JOIN user_trace ON participants.id_user = user_trace.id_user WHERE contest.id = :contestID');

            $stmt->bindParam(':contestID', $contestID, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch(PDOException $e){
            return $e->getMessage();
        }
    }

    /**
     *  Single Helper
     *          (!) Retrieve the contest if the other function has fail to get the data as the inner join failed to get the information.. THIS MEAN THAT THERE'S NO OTHER DATA IN THE OTHER TABLES...
     *  @param int contestID
     */
    public function singleHelper($contestID){
        $connection = $this -> connect(true);
        try{
            $stmt = $connection->prepare('SELECT * FROM contest WHERE id = :contestID');

            $stmt->bindParam(':contestID', $contestID, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // @FIX as we use two of this function we might get some issue with the contest id 
            // therefore we set a id_contest which is equal to the id
            $result[0]['id_contest'] = $result[0]['id'];
            return $result;
        } catch(PDOException $e){
            return $e->getMessage();
        }
    }

    
}