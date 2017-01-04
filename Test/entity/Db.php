<?php
class Db {
    // The database connection
    protected static $connection;

    /**
     * Connect to the database
     * 
     * @return bool false on failure / mysqli MySQLi object instance on success
     */
    public function connect() {    
        // Try and connect to the database
        if(!isset(self::$connection)) {
            // Load configuration as an array. Use the actual location of your configuration file
            $config = parse_ini_file('../conf.inc.php'); 

            try {
                 self::$connection = new PDO('mysql:dbname='.$config['dbname'].';host='.$config['host'], $config['username'],$config['password']);
            } catch (PDOException $e) {
                echo 'Connexion échouée : ' . $e->getMessage();
            }

        }

        // If connection was not successful, handle the error
        if(self::$connection === false) {
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
        }
        return self::$connection;
    }

    /**
     * Query the database
     *
     * @param $query The query string
     * @return mixed The result of the mysqli::query() function
     */
    public function query($query) {
        // Connect to the database
        $connection = $this -> connect();

        // Query the database
        $result = $connection -> query($query);

        return $result;
    }

    /**
     * Fetch rows from the database (SELECT query)
     *
     * @param $query The query string
     * @return bool False on failure / array Database rows on success
     */
    public function select($query) {
        $rows = array();
        $result = $this -> query($query);
        if($result === false) {
            return false;
        }
        while ($row = $result -> fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Fetch the last error from the database
     * 
     * @return string Database error message
     */
    public function error() {
        $connection = $this -> connect();
        return $connection -> error;
    }

    /**
     * Quote and escape value for use in a database query
     *
     * @param string $value The value to be quoted and escaped
     * @return string The quoted and escaped string
     */
    public function quote($value) {
        $connection = $this -> connect();
        return "'" . $connection -> real_escape_string($value) . "'";
    }

   

    function isUserInContest($idContest,$idUsers) {
        $connection = $this -> connect();
    }

    
    public function getAllContest() {
        // Connect to the database
        $connection = $this -> connect();

        // Query the database
        $result = $connection -> query("SELECT * FROM contest");
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
        // Connect to the database
        $connection = $this -> connect();

        // Query the database
        $results = $connection -> query("SELECT * FROM contest");
        $now = new DateTime();
        foreach($results as $row){
            if(new DateTime($row['start'])<$now && new DateTime($row['end']) >$now)
                return true;
        }
        return false;
    }

    function addContest($title,$text,$lot,$infos,$start,$end){
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

    function updateContest($title,$text,$lot,$infos,$start,$end){
        $connection = $this -> connect();
    }

    function addPhotoToContest($idContest,$idUsers,$idPhoto) {
        $connection = $this -> connect();
    }

    function getParticipationsOfContest($idContest){
        $connection = $this -> connect();
        $results = $connection -> query("SELECT * FROM participants where id_contest = 1");
        return $results;
    }


}