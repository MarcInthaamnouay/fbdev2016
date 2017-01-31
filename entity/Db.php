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
            $config = parse_ini_file('./conf.inc.php'); 

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

    public function selectUser($id) {
        try{
            $connection = $this -> connect();
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
        $connection = $this -> connect();

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
            $connection = $this -> connect();
            $req = $connection->prepare("INSERT INTO user_trace (id_user, token) VALUES (:id_user, :token)");
            $req->bindParam(':id_user', $idUser);
            $req->bindParam(':token', $token);
            return (bool) $req->execute();
        } catch(PDOException $e){
            return $e;
        }
    }
}