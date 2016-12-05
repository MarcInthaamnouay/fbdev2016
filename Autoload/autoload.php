<?php

session_start();

require_once __DIR__ . '/../fb_sdk/src/Facebook/autoload.php';

/*
 * initFacebookSDK()
 * @Public
 * Params : {}
 * Type : void
 */

function initFacebookSDK(){
  $fb = new Facebook\Facebook([
    'app_id' => '1418106458217541',
    'app_secret' => '951fc8f75cad3716a15efd1f4f053647',
    'default_graph_version' => 'v2.8',
  ]);

  $helper = $fb->getRedirectLoginHelper();
  $permissions = ['email', 'user_likes', 'manage_pages','publish_pages','user_photos']; // optional
  $url = $helper->getLoginUrl('http://berseck.fbdev.fr/login-callback.php', $permissions);

  return $url;
}
