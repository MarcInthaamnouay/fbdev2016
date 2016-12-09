<?php

require_once __DIR__ . '/../Common/common.php';
require_once __DIR__ . '/../fb_sdk/src/Facebook/autoload.php';

class Admin{

  private $adminID;
  private $tokenAdmin;
  private $fbAppReference;
  /*
   * __construct
   * @Params {userID} Int
   * @Params {token} String
   */
  function __construct($userID, $token){
    $this->adminID = $userID;
    $this->tokenAdmin = $token;
    $this->fbAppReference = instanceFBApp();
  }

  /*
   *  IsAdmin
   *      Check if the user is an admin
   *  @{Return} bool
   */
  function isAdmin(){
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

    foreach($result as $key => $value){
      if(strcmp($this->adminID, $value['user'])){
        if(strcmp($value['role'],"administrators")){
          return true;
        }
      }
    }
  }


  /*
   * IsTokenValid
   *      Check if the token is valid
   * @Return {bool} boolean
   */
  function isTokenValid(){
    $fb = newFBService();
    $fb->setDefaultAccessToken($this->tokenAdmin);

    try{
      $request = $fb->get('/me?access_token='.$this->tokenAdmin);
      $res = $request->getGraphUser();
    } catch (Facebook\Exceptions\FacebookResponseException $e){
      var_dump($e->getMessage());
      return false;
    } catch(Facebook\Exceptions\FacebookResponseException $e){
      var_dump($e->getMessage());
    }

    return true;

  }


}
