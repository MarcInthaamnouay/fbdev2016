<?php

session_start();
require_once("/fb_sdk/src/Facebook/autoload.php");

$config = array();
$config['app_id'] = '639819622867674';
$config['app_secret'] = '505ddf28d58518809be301ac1e732393';
$config['fileUpload'] = false; // optional

$fb = new Facebook\Facebook($config);


$params = array(
  "access_token" => $_SESSION['facebook_access_token'],
  "message" => "Here is a blog post about auto posting on Facebook using PHP #php #facebook",
  "link" => "http://bambous-a-vapeur.com",
  "name" => "this is my test",
  "caption" => "www.lewis.net",
  "description" => "This is for my cran"
);
 try {
  $ret = $fb->post('/1801690206738337/feed',$params,  $_SESSION['facebook_access_token'] );
  echo 'Successfully posted to Facebook';
} catch(Exception $e) {
  echo $e->getMessage();
}
