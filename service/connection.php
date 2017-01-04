<?php

require_once __DIR__.'/../entity/Db.php';

Class connexion {

	private $db;

	function __construct()
	{
		$this->db = new Db();
	}

	function checkUserExist($idUser){
		 $result = $this->db->selectUser($idUser);
		 //print_r(count($result));
		 if($result->rowCount() == 0) 
		 	return false;
		 else 
		 	return true; 
	}

	function adduser($idUser,$token) {
		if($this->checkUserExist($idUser) === false){
			$result = $this->db->addUser($token, $idUser);

			return true;
		}
		else{
			$result = $this->db->updateUser($idUser, $token);

			return true;
		}
			
		return false;
	}
}
