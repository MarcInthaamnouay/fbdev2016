<?php

require_once __DIR__.'/../entity/Db.php';

Class connexion {

	private $db;

	function __construct()
	{
		$this->db = new Db();
	}

	/** 
	 *	Check User Exist 
	 *		Check if the user exist 
	 *	@param Int idUser
	 */
	function checkUserExist($idUser){
		 $result = $this->db->selectUser($idUser);
		 if($result->rowCount() == 0) 
		 	return false;
		 else 
		 	return true; 
	}

	/**
	 *	Add User 
	 *		Add a user to the DB
	 *	@param Int idUser 
	 *	@param String token
	 */	
	function adduser($idUser,$token) {
		if($this->checkUserExist($idUser) === false){
			return $this->db->addUser($token, $idUser);
		}
		else{
			return $this->db->updateUser($idUser, $token);
		}
			
		return false;
	}
}
