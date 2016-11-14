<?php 
class UserController{


	public function addAction($args){

		$userForm = Form::$adduser;
		$userLogForm = Form::$loguser;


		$view = new View("adduser");


		if( isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"]=="POST" ){

			
			$msgForm = Form::verificator($userForm, $args);

			$view->assign("msgform", $msgForm);
			$view->assign("post", $args);

			
			if( !$msgForm["error"] ){
				$user = new Users($args["email"], $args["pwd"], $args["country"]);
				$user->save();

			}

		}

		$view->assign("form", $userForm);
		$view->assign("formLog", $userLogForm);


	}


}