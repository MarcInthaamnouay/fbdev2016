<?php

require_once __DIR__.'/../entity/Contest.php';

class ContestController {

	private $contest;

	function __construct()
	{
		$this->contest = new Contest();
	}

	/**
    * Recuperation des donnees d'un concours
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
    * Recuperation des donnees du concours en cours
    * @return Le concours en cours, false sinon
    */
	public function getCurrentContest()
	{
		$result = $this->contest->getCurrentContest();
		if(count($result) == 0 ) return false;
		return $result;
	}

	/**
	 *	Get Current Photos
	 *			Get the current photos of the contest
	 *	@param idContest int
	 */
	 public function getCurrentPhotos(){
		 $contestID = $this->getCurrentContest()['id'];
		 $contestData = $this->getDataContest(intval($contestID));

		 $dataArray = array($contestData);

		 return $dataArray;
	 }
	
}
