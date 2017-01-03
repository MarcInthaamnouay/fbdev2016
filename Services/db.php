<?php

require_once __DIR__ . '/../Common/common.php';

Class Dbcon{

  private $adminToken;
  private $db;
  private $req;
  private $pwd;
  private $usr;
  private $stmt;
  private $params;

  /*
   *  __construct
   *      Constructor of the DBcon class
   *  @param {String} $token
   *  @param {String} $db
   *  @param {String} $req
   */
  function __construct($token, $db, $req, $params){
    $this->adminToken = $token;
    $this->db = $db;
    $this->req = $req;
    $this->params = $params;
    $this->usr = "root";
    $this->pwd = "root";
  }

  /*
   *  Open Con
   *      Open the connection with the database
   */
  function OpenCon(){
    try{
      $conn = new PDO("mysql:host=localhost;dbname=".$db, $this->usr, $this->pwd);

      // Set the error
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      return $conn;
    } catch(PDOException $e){
      print_r($e->getMessage());
      return $e;
    }
  }

 /*
  * Prepare
  *   Prepare the request to the database
  * @private
  */
  function prepareReq(){
    // Get the connection
    // conn is private
    $conn = $this->OpenCon();
    $this->stmt = $conn->prepare($this->req);

    if(count($this->params)){
      foreach($this->params as $value){
        $stmt->bindParam('')
      }
    }
  }

  /*
   * Execute
   *      Execute the request
   */
  function ExecuteReq(){
    try{
      $this->stmt->execute();
      $res = $this->stmt->setFetchMode(PDO::FETCH_ASSOC);

      return $res;
    } catch(PDOException $e){
      return $e;
    }
  }
}
