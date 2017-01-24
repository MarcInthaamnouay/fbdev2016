<?php


require_once __DIR__.'/../entity/Contest.php';
require_once __DIR__.'/../entity/Admin.php';
require_once __DIR__.'/../service/helper.php';

class AdminController {

	private $contest;
	private $adminID;
	private $helper;
	private $admin;
	public  $dataContest;
	public  $tableData;

	function __construct($id)
	{	
		$this->contest = new Contest();
		$this->adminID = $id;
		$this->admin = new Admin();
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
		$title = Helper::getID($request, 'title');
		$text = Helper::getID($request, 'text');
		$lot = Helper::getID($requst, 'lot');
		$infos = Helper::getID($request, 'infos');
		$end = Helper::getID($request, 'end');
		$start = new DateTime();

		$returnMsg = '';
		if(empty($title) || empty($lot) || empty($end) ){
			if(empty($title)) $returnMsg .= 'Le titre est manquant. <br />';
			if(empty($lot)) $returnMsg .= 'Le lot est manquant. <br />';
			if(empty($start)) $returnMsg .= 'La date de debut est manquante. <br />';
			if(empty($end)) $returnMsg .= 'La date de fin est manquante. <br />';
		}
		else {
			// First we disactivat the current contest
			$this->contest->addContest($title,$text,$lot,$infos,$start,$end);
			$returnMsg .= true;
		}

		return $returnMsg;
	
	}

	/**
	 *	Check If Admin
	 *			Check if the user is an administrator
	 *	@param HTTP Request request
	 *	@return res
	 */
	public static function checkIfAdmin($request, $user_id){
		// Get the admin ID 
		if($request)
			$adminID = Helper::getID($request, 'userID');
		else 
			$adminID = $user_id;

		$admin = new Admin();
		// Check if the user is an admin or not...
		$fbApp = Helper::getFBService();
		// Save the token
		$token = Helper::retrieveToken($adminID);

		$fbApp->setDefaultAccessToken($token);
 
	   try{
	      $res = $admin->isAdmin($adminID);
		  
		  return $res;
	    } catch (Facebook\Exceptions\FacebookResponseException $e){
	      var_dump($e->getMessage());
	      return false;
	    } catch(Facebook\Exceptions\FacebookResponseException $e){
	      var_dump($e->getMessage());
	    }
	}

	/**
	 *	Check Token Validity
	 *			Check the token validity
	 *	
	 */
	public static function checkTokenValidity($userID){
		// get the token
		if(!$userID)
			return;

		$token = Helper::retrieveToken($userID);

		if(!$token)
			return;

		$fb = Helper::getFBService();
		$fbApp = Helper::instanceFBApp();
		$fb->setDefaultAccessToken($token);

		$fbRequest = new Facebook\FacebookRequest($fbApp, $token , 'GET', 'me?fields=id,name');

		try {
			$res = $fb->getClient()->sendRequest($fbRequest);
			$resBody = $res->getDecodedBody();

			return true;
	} catch (Facebook\Exceptions\FacebookResponseException $e){
			// We imply that the issue is due to the access token
			return false;
		}
	}

	/**
	 *	Get ID
	 *			Return the adminID to the twig file
	 *	@return int adminID
	 */
	public function getID(){
		return $this->adminID;
	}

	/**
	 *	Get All Contest
	 *			Return every contest to the user
	 */
	public function getAllContest(){
		return $this->contest->getAllContest();
	}

	/**
	 *	Get Current contest
	 *			Return the current contest to the user
	 */
	public function getCurrentContest(){
		return $this->contest->getCurrentContest();
	}

	/**
	 *	Get Date
	 *			Return the date of today from the contest Services
	 */
	public function compareDate($endDate){
		$now = new DateTime();
		$endDate = new DateTime($endDate);
		
		if($endDate > $now)
			return true;
		
		return false;
	}

	/**
	 *	Get Single Contest Data
	 *			Get Single contest Data return the information about
	 *			a single contest
	 */
	public function getSingleContestData($contestID){
		$token = Helper::retrieveToken($this->adminID);
		$res = $this->contest->getSingleContest($contestID);
		$this->dataContest = $res[0];

		$bulk = array();

		// Preparing the bulk request
		foreach($res as $contestant){
			$patternBulk = $contestant['id_user'].'?fields=id,last_name,first_name';
			array_push($bulk, array(
				'method' => 'GET',
				'relative_url' => $patternBulk
			));
		}
		
		$this->tableData = $this->admin->bulkAdminRequest($bulk, $token, $res);
	}
}