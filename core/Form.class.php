<?php
 class Form{



 	public static $adduser = [
 		"config" => ["method"=>"POST", "action"=> "user/add" ],
 		"data" => [
			 			"email"=>["type"=>"email", "required"=>true, "placeholder"=>"Votre email", "error"=>"Email incorrect", "unicite" => ["table"=>"Users", 
			 												"column"=>"email", 
			 												"error"=>"L'email existe déjà dans notre base"] 
							],

			 			"pwd"=>["type"=>"password", "required"=>true, "placeholder"=>"Votre mot de passe", "error"=>"Mot de passe incorrect"],
			 			"pwd2"=>["type"=>"password", "required"=>true, "confirm"=>"pwd", "placeholder"=>"Confirmation", "error"=>"Mot de passe de confirmation incorrect"],


			 			"country"=>["type"=>"select", "options"=>["fr"=>"France", "it"=>"Italie", "es"=>"Espagne"] , "error"=>"Pays incorrect"],


			 			"captcha"=>["type"=>"captcha", "error"=>"Captcha incorrect"],


			 			["type"=>"submit", "value"=>"S'inscrire", "noverification"=>true]
			 		]

 	];


	public static $loguser = [
	 		"config" => ["method"=>"POST", "action"=> "user/add" ],
	 		"data" => [
	 						"email"=>["type"=>"email", "required"=>true, "placeholder"=>"Votre email","error"=>"Email incorrect"] ,
			 				"pwd"=>["type"=>"password", "required"=>true, "placeholder"=>"Votre mot de passe", "error"=>"Mot de passe incorrect"],
			 				["type"=>"submit", "value"=>"Se connecter", "noverification"=>true]
	 					]
	 				];



 	public static function verificator($form, $data){

 		$error = false;
 		$msgError = [];


 		foreach ($form["data"] as $name => $options) {
 			
 			if(isset($options["noverification"])){
 				continue;
 			}
 			//VERIFICATION DU REQUIRED
 			else if(trim($data[$name]) == "" && isset($options["required"])){
 				$error = true;
 				$msgError[] = $options["error"];
 			}
 			//VERIFICATION DU FORMAT DE L'EMAIL
 			else if(
 						trim($data[$name]) != "" && 
 						$options["type"] == "email" && 
 						!filter_var($data[$name], FILTER_VALIDATE_EMAIL) 
 					){
 				$error = true;
 				$msgError[] = $options["error"];
 			}
 			//VERIFICATION DU FORMAT DU MOT DE PASSE
 			else if(
 						trim($data[$name]) != "" && 
 						$options["type"] == "password" && 
 						(strlen($data[$name])<8 || strlen($data[$name])>12)
 					){
 				$error = true;
 				$msgError[] = $options["error"];
 			}
 			//VERIFICATION DES CONFIRMATIONS
 			else if(isset($options["confirm"]) && $data[$name]!= $data[$options["confirm"]]){
 				$error = true;
 				$msgError[] = $options["error"];
 			}
 			//VERIFICATION DES SELECT
 			else if(
 						trim($data[$name]) != "" && 
 						$options["type"] == "select" && 
 						!isset($options["options"][$data[$name]])	 
 					){
 				$error = true;
 				$msgError[] = $options["error"];
 			}
 			//VERIFICATION DES CAPTCHA
 			else if(
 						$options["type"] == "captcha" &&  strtolower($data[$name]) != strtolower($_SESSION["captcha"]) 
 					){
 				$error = true;
 				$msgError[] = $options["error"];
 			}
 			else if( isset($options["unicite"])){

 				$object = new $options["unicite"]["table"];
 				$object->setEmail($data[$name]);
 				if( $object->exist($options["unicite"]["column"], $object->getEmail() ) ){

	 				$error = true;
	 				$msgError[] = $options["unicite"]["error"];
 				}

 			}



 		}

 		return ["error"=>$error, "msg"=>$msgError];

 	}

 }