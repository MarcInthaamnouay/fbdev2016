<?php
class IndexController{

	public function indexAction($args){

		$view = new View("home");
		$view->assign("pseudo", "Yves");
		
	}

}
