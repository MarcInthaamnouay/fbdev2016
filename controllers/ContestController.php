<?php

require_once __DIR__.'/../entity/Contest.php';

class ContestController {

	private $contest;

	function __construct()
	{
		$this->contest = new Contest();
	}

	/**
    * R�cup�ration des donn�es d'un concours
    * @var idContest L'identifiant de notre concours
    * @return tous les participants � un concours
    */
	public function getDataContest($idContest)
	{
		if(empty($idContest) || !is_int($idContest)) return false;
		$result = $this->contest->getParticipationsOfContest($idContest);
		return $result;
	}

	/**
    * R�cup�ration des donn�es du concours en cours
    * @return Le concours en cours, false sinon
    */
	public function getCurrentContest()
	{
		$result = $this->contest->getCurrentContest();
		if(count($result) == 0 ) return false;
		return $result;
	}

	
}
