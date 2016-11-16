<?php
require_once __DIR__ . '/fb_sdk/src/Facebook/autoload.php';
 
session_start();
 
$fb = new Facebook\Facebook([
    'app_id' => '639819622867674',
    'app_secret' => '505ddf28d58518809be301ac1e732393',
    'default_graph_version' => 'v2.8',
    'default_access_token' => isset($_SESSION['facebook_access_token']) ? $_SESSION['facebook_access_token'] : '639819622867674s|505ddf28d58518809be301ac1e732393'
  ]);
 
$helper = $fb->getRedirectLoginHelper();
 
try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  //echo 'Graph returned an error: ' . $e->getMessage();
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  //echo 'Facebook SDK returned an error: ' . $e->getMessage();
}
 
if (isset($accessToken)) {
  echo "login";
  $_SESSION['facebook_access_token'] = (string) $accessToken;
  $fb ->setDefaultAccessToken($_SESSION['facebook_access_token']);
} elseif ($helper->getError()) {
  echo "error";
}
header('Location: index.php');
 