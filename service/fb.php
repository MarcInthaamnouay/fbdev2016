<?php 

require_once __DIR__ . '/helper.php';


Class FacebookServices{

    private $request,
            $token,
            $method,
            $data;

    function __construct($request, $token, $method, $postData){
        $this->request = $request;
        $this->token   = $token;
        $this->method  = $method;
        $this->data    = $postData;
    }

    /** 
     *  Make 
     *          Make a request to the facebook service
     *  @public
     *  @return mixed var
     *  @return string
     */
    public function make(){
        $fb = Helper::getFBService();
        $fb->setDefaultAccessToken($this->token);

        try{
            $fbApp = Helper::instanceFBApp();
            
            if($this->method == 'POST')
                $request = new Facebook\FacebookRequest($fbApp, $this->token, $this->method, $this->request, $this->data);
            else
                $request = new Facebook\FacebookRequest($fbApp, $this->token, $this->method, $this->request);

            $response = $fb->getClient()->sendRequest($request);
            $resBody = $response->getDecodedBody();

            return $resBody;
        } catch(Facebook\Exceptions\FacebookResponseException $e){
            return $e->getMessage();
        }
    }
}