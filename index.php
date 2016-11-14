<?php
// Reporte toutes les erreurs PHP
error_reporting(E_ALL);
// Même chose que error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);


/*

 include '........php';
 require '........php';

 include_once  '........php';
 require_once  '........php';

*/
session_start();
require 'conf.inc.php';

function myAutoloader($class) {

	if(file_exists('core/' . $class . '.class.php')){
    	require 'core/' . $class . '.class.php';
    }else if(file_exists('models/' . $class . '.class.php')){
    	require 'models/' . $class . '.class.php';
    }

}

spl_autoload_register('myAutoloader');





//Helper::showArray($_SERVER);
// /Projet%20MVC%20mise%20a%20niveau/fsdqfsdq/fdsqfsdqF/fdswfdqs/
$uri = trim(str_replace(UNIX_PATH, "", $_SERVER['REQUEST_URI']), "/");
//   fsdqfsdq/fdsqfsdqF/fdswfdqs/fdsfds/fdsfds/fdsfds
$arrayUri = explode("/", $uri);

$controller = ( empty($arrayUri[0]) )? "index":$arrayUri[0];
$action = ( empty($arrayUri[1]) )? "index":$arrayUri[1];

unset($arrayUri[0]);
unset($arrayUri[1]);

$args = array_merge($arrayUri, $_REQUEST);

$controller = ucfirst(strtolower($controller))."Controller";
$controller_path = "controllers/".$controller.".class.php";

if( file_exists($controller_path) ){
	require $controller_path;
	$c = new $controller;

	$action = $action."Action";
	if(method_exists($c, $action)){
		
		$c->$action($args);

	}else{
		die("L'action n'existe pas.");
	}

}else{
	die("Le controller n'existe pas.");
}








