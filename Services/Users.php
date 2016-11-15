<?php
	require_once 'rest.php';

	class Users extends Rest_Rest {
	
		public $data = "";
			
		public function __construct(){
			parent::__construct();		
		}
		
		/**
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi($func){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest']))); 		
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',204);
		}
		
		/**
		 * Simple verif exist preco
		 * numanc : <NUMANC>
		 */
		private function preco(){
			 if($this->get_request_method() != "GET"){ $this->response('',406); } 
			$my_preco=new Model_Precos;
			$numanc=$this->_request['numanc'];
			$res=$my_preco->fetchRow("numanc='$numanc'");
			if(is_object($res)){
				$this->response($this->json($res->toArray()), 200);
			}else{
				$this->response('',204);
			}
		}

		/**
		 * Get User Profils
		 */
		private function getUserProfil(){
			if($this->get_request_method() != "GET"){ $this->response('',406); } 
			
			require_once __DIR__ . '/fb_sdk/src/Facebook/autoload.php';
			session_start();
			$facebook_access_token=$this->_request['accessTokenRequest'];

			$fb = new Facebook\Facebook([
			  'app_id' => '639819622867674',
			  'app_secret' => '505ddf28d58518809be301ac1e732393',
			  'default_graph_version' => 'v2.8',
			  'default_access_token' => isset($_SESSION['facebook_access_token']) ? $_SESSION['facebook_access_token'] : '639819622867674s|505ddf28d58518809be301ac1e732393'
			]);
		  
			try {
		  		$response = $fb->get('/me?fields=id,name');
		  		$user = $response->getGraphUser();
		  		$res = $user;
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  	echo 'Graph returned an error: ' . $e->getMessage();
		  	$res = $e->getMessage();
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  $res = $e->getMessage();
		}

			if(is_object($res)){
				$this->response($this->json($res->toArray()), 200);
			}else{
				$this->response('',204);
			}
		}
		
		/**
		* Suppression preco
		* numanc : $numanc
		*/
		private function suppr_res(){
			if($this->get_request_method() != "DELETE"){ $this->response('',406); }
		   $my_preco=new Model_Precos;
		   $numanc=$this->_request['numanc'];
		   $ret=$my_preco->delete($numanc);
		   If($ret){
			   $success = array('status' => "Success", "msg" => "Element supprimé.");
			   $this->response($this->json($success),200);
		   }else{
			   $this->response('',204);
		   }
		}
		
		/**
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
	
	// Inititiate Library
	$api = new Users;
	$api->processApi();

?>