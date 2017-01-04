<?php

Class Helper{
    
    function getFBService(){
        $fb = new Facebook\Facebook([
            'app_id' => '1418106458217541',
            'app_secret' => '951fc8f75cad3716a15efd1f4f053647',
            'default_graph_version' => 'v2.8',
        ]);

        return $fb;
    }

    /*
    * GetToken
    *    Return a token
    * @Return {token} string
    */
    function getToken($request){

        $data = $request->getAttributes('user-param');
        $token = filter_var($data['token']);
        
        if(isset($token)){
            $_SESSION['token'] = $token;

            return $token;
        } else {
            return false;
        }
    }

    /*
    * GetUserID
    *    Return the user id
    * @Return {userID} string
    */
    function getUserID($request){
        $data = $request->getParsedBody();
        $id = filter_var($data['userID']);

        return $id;
    }

    function instanceFBApp(){
        $fbApp = new Facebook\FacebookApp('1418106458217541', '951fc8f75cad3716a15efd1f4f053647');

        return $fbApp;
    }
}

