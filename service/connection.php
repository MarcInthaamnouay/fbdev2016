<?php

require_once __DIR__.'/../entity/Db.php';

class connextion {

	private $db;

	function __construct()
	{
		$this->db = new Db();
	}

	function checkUserExist($idUser){
		 $result = $this->db->selectUser();
		 if(count($result) == 0) return false;
		 else return true; 
	}

	function adduser($idUser,$token) {
		if(!$this->db->checkUserExist($idUser))
			$result = $this->db->addUser();
		else
			$result = $this->db->updateUser();
	}
