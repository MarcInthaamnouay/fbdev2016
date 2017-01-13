<?php


require_once __DIR__.'/../entity/Contest.php';
require_once __DIR__.'/../entity/Admin.php';
require_once __DIR__.'/../service/helper.php';

class AdminController {

	private $contest;
	private $adminID;
	private $helper;
	private $admin;

	function __construct()
	{	
		$this->contest = new Contest();
	}

	/**
	 * R�cup�ration de notre historique des concours
	 * @return Un tableau contenant l'historique des concours.
	*/
	public function getHistoryContest() {
		$result = $this->contest->getAllContest();
		foreach ($results as $key => $value) {
			if ($value != $this->contest->getCurrentContest()){
				$rows[]=$value;
			}
		}
		return $rows;
	}

	/**
	 * Ajour d'un concours a notre base de donn�es.
	 * @var title Le titre du concours
	 * @var text Le texte du concours
	 * @var lot Le lot du concours
	 * @var infos Les informations compl�mentaires aux concours
	 * @var start La date du d�but du concours
	 * @var end La date de fin du concours
	 * @return Un message d�crivant l'erreur lors de l'ajour, ou un message de confirmation
	*/
	public function addContest($title,$text = null,$lot=null,$infos=null,$start,$end){
		$returnMsg = '';
		if(empty($title) || empty($lot) || empty($start) || empty($end) ){
			if(empty($title)) $returnMsg .= 'Le titre est manquant. <br />';
			if(empty($lot)) $returnMsg .= 'Le lot est manquant. <br />';
			if(empty($start)) $returnMsg .= 'La date de d�but est manquante. <br />';
			if(empty($end)) $returnMsg .= 'La date de fin est manquante. <br />';
		}
		else {
			$this->contest->addContest($title,$text,$lot,$infos,$start,$end);
			$returnMsg .= 'Concours ajout�. <br />';
		}

		return $returnMsg;
	
	}

	/**
	 *	Check If Admin
	 *			Check if the user is an administrator
	 */
	public static function checkIfAdmin($request){
		// Get the admin ID 
		$adminID = Helper::getID($request, 'userID');
		$admin = new Admin($adminID);
		// Check if the user is an admin or not...
		$fbApp = Helper::getFBService();
		// Save the token
		$token = Helper::retrieveToken($adminID);

		$fbApp->setDefaultAccessToken($token);
 
	   try{
	      
	    } catch (Facebook\Exceptions\FacebookResponseException $e){
	      var_dump($e->getMessage());
	      return false;
	    } catch(Facebook\Exceptions\FacebookResponseException $e){
	      var_dump($e->getMessage());
	    }
	}
}