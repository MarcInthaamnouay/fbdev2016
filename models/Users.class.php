<?php
class Users extends Sql{

	protected $id = -1;
	protected $email;
	protected $password;
	protected $country;


	public function __construct($email = null, $password = null, $country = null){
		parent::__construct();
		$this->setEmail($email);
		$this->setPassword($password);
		$this->setCountry($country);
	}


	public function setId($id){
		if(is_numeric($id))
			$this->id=$id;
	}

	public function setEmail($email){
		$this->email=strtolower(trim($email));
	}

	public function setPassword($password){
		//60 caractères
		$this->password = password_hash($password,PASSWORD_BCRYPT) ;
	}

	public function setCountry($country){
		$this->country=strtolower(trim($country));
	}




	public function getId(){
		return $this->id;
	}

	public function getEmail(){
		return $this->email;
	}

	public function getPassword(){
		return $this->password;
	}
	
	public function getCountry(){
		return $this->country;
	}
	



}

