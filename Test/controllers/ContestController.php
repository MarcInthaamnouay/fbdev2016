<?php

require '../entity/Db.php';



class ContestController {

	private $contest;

	function __construct()
	{
		$this->contest = new Db();
	}

	public function getDataContest($idContest)
	{
		$result = $this->contest->getParticipationsOfContest($idContest);
		return $result;
	}

	public function getCurrentContest()
	{
		$result = $this->contest->getCurrentContest();
		return $result;
	}



}
