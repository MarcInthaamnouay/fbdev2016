<?php

require_once __DIR__ . '/../entity/Db.php';

Class Helper{
    
    /**
     *  Get FB Service
     *          Return an instance of the Facebook sdk
     *  @return fb instance of facebook sdk
     */
    function getFBService(){
        $fb = new Facebook\Facebook([
            'app_id' => '1418106458217541',
            'app_secret' => '951fc8f75cad3716a15efd1f4f053647',
            'default_graph_version' => 'v2.8',
        ]);

        return $fb;
    }

    /**
     * GetToken
     *    Return a token
     * @return token which is a String
     */
    function getToken($request){
        $data = $request->getParsedBody();
        $token = filter_var($data['token']);
        
        if(isset($token)){
            return $token;
        } else {
            return false;
        }
    }

    /**
     *  GetID
     *          Return the user id
     *  @param request HTTP Request
     *  @param paramName string, name of the parameter 
     *  @return the parameter 
     */
    function getID($request, $paramName){
        $data = $request->getParsedBody();
        $id = filter_var($data[$paramName]);

        return $id;
    }

    /**
     *  Instance FB App
     *          Return an instance of the FB Appliations
     *  @return instance of Facebook application
     */
    function instanceFBApp(){
        $fbApp = new Facebook\FacebookApp('1418106458217541', '951fc8f75cad3716a15efd1f4f053647');

        return $fbApp;
    }

    /**
     *  Get DB Token 
     *              return the token based on the userID
     *  @param userID string 
     *  @return token string
     *  @return error message if PDOException
     */
    function getDBToken($userID){
        $db = new Db();
        try{
            $con = $db->connect();
            $results = $con->query("SELECT * FROM user_trace WHERE id_user = ".$userID);
            $result = $results->fetch();
            $token = '';

            foreach($result as $key => $value){
                if($key == 'token'){
                    $token = $value;
                }
            }

            return $token;
        } catch(PDOException $e){
            return $e;
        }
    }
}

