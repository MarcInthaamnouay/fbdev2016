<?php

require_once __DIR__.'/../entity/Contest.php';

class ContestController {

	private $contest;

	function __construct()
	{
		$this->contest = new Contest();
	}

	/**
    /* Récupération des données d'un concours
    /* @var idContest L'identifiant de notre concours
    /* @return tous les participants à un concours
    */
	public function getDataContest($idContest)
	{
		$result = $this->contest->getParticipationsOfContest($idContest);
		return $result;
	}

	/**
    /* Récupération des données du concours en cours
    /* @return Le concours en cours, false sinon
    */
	public function getCurrentContest()
	{
		$result = $this->contest->getCurrentContest();
		if(count($result) == 0 ) return false;
		return $result;
	}

	
}
