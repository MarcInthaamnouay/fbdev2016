<?php
class Db {
    // The database connection
    protected static $connection;

    /**
     * Connect to the database
     * 
     * @return bool false on failure / mysqli MySQLi object instance on success
     */
    public function connect($boolForPath = true) {    
        // Try and connect to the database
        if(!isset(self::$connection)) {
            // Load configuration as an array. Use the actual location of your configuration file. If 0 -> normal path if 1-> path for style
            if($boolForPath){
                $config = parse_ini_file('./conf.inc.php');     
            }else{
                $config = parse_ini_file('../conf.inc.php'); 
            }
            

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
        $connection = $this -> connect(true);

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
        $connection = $this -> connect(true);
        return $connection -> error;
    }

    /**
     * Quote and escape value for use in a database query
     *
     * @param string $value The value to be quoted and escaped
     * @return string The quoted and escaped string
     */
    public function quote($value) {
        $connection = $this -> connect(true);
        return "'" . $connection -> real_escape_string($value) . "'";
    }

    public function selectUser($id) {
        try{
            $connection = $this -> connect(true);
            $result = $connection -> query("SELECT * FROM user_trace WHERE id_user = ".$id, PDO::FETCH_ASSOC);
    
            return $result;
        } catch(PDOException $e){
            return $e;
        }
        
    }
   
    /**
     *  Update User
     *  @param Int $idUser
     *  @param String $token
     *  @return bool | String 
     */
    public function UpdateUser($idUser,$token) {
        $connection = $this -> connect(true);

        try{
            $stmt = $connection->prepare('UPDATE user_trace SET token = :token WHERE id_user = :idUser');
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':idUser', $idUser);

            return (bool) $stmt->execute();

        } catch(PDOException $e){
            return $e->getMessage();
        }
        

    }

    /**
     *  Add User
     *      Add a user 
     *  @param String $token
     *  @param idUser int
     */
    public function addUser($token,$idUser) {
        try{
            $connection = $this -> connect(true);
            $req = $connection->prepare("INSERT INTO user_trace (id_user, token) VALUES (:id_user, :token)");
            $req->bindParam(':id_user', $idUser, PDO::PARAM_STR);
            $req->bindParam(':token', $token, PDO::PARAM_STR);
            return (bool) $req->execute();
        } catch(PDOException $e){
            return $e;
        }
    }

    public function getActiveBackgroundColor(){

        // CONNECT TO DATABASE
        $connection = $this -> connect(false);
        
        try{
            $stmt = $connection -> prepare("SELECT backgroundcolor FROM stylesheet WHERE active=1");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function getActiveFontColor(){

        // CONNECT TO DATABASE
        $connection = $this -> connect(false);
        
        try{
            $stmt = $connection -> prepare("SELECT fontcolor FROM stylesheet WHERE active=1");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function getActiveFontColorHover(){

        // CONNECT TO DATABASE
        $connection = $this -> connect(false);
        
        try{
            $stmt = $connection -> prepare("SELECT hoverfontcolor FROM stylesheet WHERE active=1");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function getActiveBackgroundColorHover(){

        // CONNECT TO DATABASE
        $connection = $this -> connect(false);
        
        try{
            $stmt = $connection -> prepare("SELECT hoverbackgroundcolor FROM stylesheet WHERE active=1");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function disactivateAllStyle(){
        $connection = $this -> connect(false);

        try {
            $stmt = $connection->prepare('UPDATE stylesheet SET active = 0');

            $res = $stmt->execute();

            return (bool) $res;
        } catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function activateStyle($styleID){
        $connection = $this -> connect(false);

        try {
            $stmt = $connection->prepare('UPDATE stylesheet SET active = 1 WHERE id = :styleID');

            $stmt->bindParam(':styleID', $styleID);
            
            $res = $stmt->execute();

            return (bool) $res;
        } catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function selectAllStyle($id){

        // CONNECT TO DATABASE
        $connection = $this -> connect(false);
        
        try{
            $stmt = $connection -> prepare("SELECT backgroundcolor, hoverbackgroundcolor, fontcolor, hoverfontcolor  FROM stylesheet WHERE id_contest = :id");
            $stmt->bindParam(':id', intval($id), PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch(PDOException $e){
            return $e->getMessage();
        }
    }
}