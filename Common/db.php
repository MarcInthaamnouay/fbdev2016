<?php

class Db{
  private $hostname;
  private $port;
  private $username;
  private $password;

  function __construct(){
    $this->hostname = 'berseck.fbdev.fr/db';
    $this->port = 30;
    $this->username = 'root';
    $this->password = 'root';
  }

  function Prepare(params, sqlIndice){

  }


  function Execute(){

  }

  function getSqlRequest(params, sqlIndice){
    switch 'sqlIndice' : {
      case '..' :
        // put the sql request there ... and with the params
      break;
    }
  }
}
