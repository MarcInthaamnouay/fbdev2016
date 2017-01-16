<?php

require_once __DIR__.'/../entity/Contest.php';
require_once __DIR__.'/../service/helper.php';

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

		 return $contestData;
	 }

	 public function setLikes($request){
		 $id_participant = intval(Helper::getID($request, 'id_contestant'));
		 $id_user = intval(Helper::getID($request, 'id_user'));
		 $date = Helper::getID($request, 'date_vote');

		 // Call our contest entity to add a vote
		 $res = $this->contest->setVote($id_participant, $id_user, $date);

		 return $res;
	 }
}
