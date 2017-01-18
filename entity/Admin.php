<?php 

//require "Db.php";
require_once __DIR__.'/../service/helper.php';

class Admin extends Db{

    private $adminID;

    function __construct(){
        $this->helper = new Helper();
    }

    /**
     *  Is Admin
     *          Check if the user is an admin @Invoke by the checkIfAdmin function 
     *  @param int isAdmin
     *  @return boolean (true | false)
     */
    public function isAdmin($adminID){
            $fb = Helper::getFBService();
            $fbApp = $this->helper->instanceFBApp();
            $getRoles = new Facebook\FacebookRequest($fbApp, '1418106458217541|951fc8f75cad3716a15efd1f4f053647', 'GET', '/app/roles');
        try{
            $response = $fb->getClient()->sendRequest($getRoles);

            $result = $response->getDecodedBody();
            $user_data = $result['data'];
            foreach($user_data as $key => $d){
                if(strcmp($adminID, $d['user']) == 0){
                    if(strcmp($d['role'], 'administrators') == 0){
                        if($d['role'] == 'administrators'){
                            $this->adminID = $adminID;
                            return true;
                        }
                    }
                }
            }

            return false;
        } catch(Facebook\Exceptions\FacebookResponseException $e){
            return $e->getMessage();
        } catch(Facebook\Exceptions\FacebookSDKException $e){
            return $e->getMessage();
        }
        // If it's ok get the result of our request
    }
}