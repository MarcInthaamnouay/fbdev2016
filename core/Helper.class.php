<?php
class Helper{
	
	public static function showArray($array){
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}

	public static function includeModule($path, $variable){
		include $path;
	}
}
