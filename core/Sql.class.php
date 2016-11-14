<?php
class Sql{

	private $bdd;
	private $table;
	private $columns;
	

	public function __construct(){

		//Récupérer le nom de la class enfant exemple Users
		$this->table = get_class($this);
		//Récupérer la liste des attributs de la class enfant exemple email,password,country
		$this->columns = array_diff_assoc(
							get_class_vars($this->table), 
							get_class_vars(get_class())
						);

		try{
			$this->bdd = new PDO("mysql:host=".HOST.";dbname=".DBNAME,DBUSER,DBPWD);
		}catch(Exception $e){
			die("Erreur SQL ".$e->getMessage());
		}
		
	}


	public function save(){

		if( $this->id == -1){
			//Insert
			unset($this->columns["id"]);

			$query = $this->bdd->prepare("INSERT INTO ".$this->table." (". implode(",", array_keys($this->columns)) .")VALUES (:". implode(",:", array_keys($this->columns)) .") ");

			$data = [];

			foreach ($this->columns as $column => $value) {
				$data[$column] = $this->$column;
			}

			$query->execute($data);

		}else{
			//Update
			foreach ($this->columns as $column => $value) {
				$update[]=$column."=:".$column;
				$data[$column] = $this->$column;
			}

			$query = $this->bdd->prepare(" UPDATE ".$this->table." SET ".implode(",", $update)." WHERE id=:id ");

			$query->execute($data);

		}
		
	}


	public function exist($column, $value){


			$query = $this->bdd->prepare(" SELECT id FROM ".$this->table." WHERE ".$column."=:".$column.";");
			$query->execute([$column=>$value]);
			
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(empty($result)){
				return false;
			}

		return true;
	}


}