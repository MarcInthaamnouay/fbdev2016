<?php 

//require "Db.php";

class Admin extends Db{

    private $adminID;

    function __construct(){
        $this->adminID = $adminID;
    }

    public function isAdmin(){
        $fb = newFBService();
        $fbReq = new Facebook\FacebookRequest($this->fbAppReference, '1418106458217541|951fc8f75cad3716a15efd1f4f053647', 'GET', '/app/roles');
        try{
        $response = $fb->getClient()->sendRequest($fbReq);
        } catch(Facebook\Exceptions\FacebookResponseException $e){
        var_dump($e->getMessage());
        } catch(Facebook\Exceptions\FacebookSDKException $e){
        var_dump($e->getMessage());
        }
        // If it's ok get the result of our request
        $result = $response->getDecodedBody();
        $user_data = $result['data'];
        foreach($user_data as $key => $d){
        if(strcmp($this->adminID, $d['user']) == 0){
            if(strcmp($d['role'], 'administrators') == 0){
            if($d['role'] == 'administrators'){
                return true;
            }
            }
        }
        }
    }
}