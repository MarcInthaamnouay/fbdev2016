<?php 
class View{

	public $view;
	public $template;
	public $data=[];
	
	public $formPath = "views/templates/form.php";

	public function __construct($view, $template="template"){

		if( file_exists("views/".$view."View.php")){

			if(file_exists("views/templates/".$template.".php")){

				$this->view = "views/".$view."View.php";
				$this->template = "views/templates/".$template.".php";

			}else{
				die("Le template n'existe pas.");
			}
		}else{
			die("La vue n'existe pas.");
		}

	}

	public function assign($key, $value){
		$this->data[$key]=$value;
	}



	public function __destruct(){
		extract($this->data);
		include $this->template;
	}

}




