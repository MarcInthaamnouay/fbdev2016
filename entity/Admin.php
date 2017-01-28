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
            $fbApp = Helper::instanceFBApp();
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

    /**
     *  Bulk Common Request
     *           This might be useful for other stuff..
     */  
     public static function bulkAdminRequest($params, $token, $users){
        $fbApp = Helper::instanceFBApp();
        $request = new Facebook\FacebookRequest($fbApp, $token , 'POST', '?batch='.urlencode(json_encode($params)));
        $batchData = array();

        try{
            $fb = Helper::getFBService();
            $response = $fb->getClient()->sendRequest($request);
            $resBody = $response->getDecodedBody();

            foreach($resBody as $key => $value){
                $res = json_decode($value['body']);
                if($res->last_name && $res->first_name)
                    array_push($batchData, $res);
            }

            foreach($users as $key=>$user){
                foreach($batchData as $data){
                    if($user['id_user'] == $data->id){
                        $users[$key]['last_name'] = $data->last_name;
                        $users[$key]['first_name'] = $data->first_name;
                    }
                }
            }

            return $users;
        } catch (Facebook\Exceptions\FacebookResponseException $e){
            return $e->getMessage();
        }
     }
}