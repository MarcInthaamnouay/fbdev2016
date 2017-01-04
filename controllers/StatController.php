<?php

require_once __DIR__.'/../entity/Contest.php';
require_once __DIR__.'/../entity/Stat.php';

class ContestController {

	private $contest;

	function __construct()
	{
		$this->contest = new Contest();
	}
	
	public function addDayVisits(){

	}

	public function getDayVisits($date){

	}

	public function getWeekVisits($date){

	}

	public function getMonthVisits($date){

	}

	public function getVisitsSinceStartContest($idContest){

	}
}