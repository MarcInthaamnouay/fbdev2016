<?php

require_once __DIR__.'/../entity/Db.php';

class ContestController {

	private $db;

	function __construct()
	{
		$db = new Db();
	}

	public function getDataContest()
	{

	}

	public function getDataTest(){
		return "BOUU";
	}
}
