// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
    console.log(response);
    localStorage.setItem('facebook_oauth_token', JSON.stringify({token : response.authResponse.accessToken, userID : response.authResponse.userID}));
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      isLog(response.authResponse.accessToken, response.authResponse.userID);
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      console.log(response);
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1418106458217541',
      cookie     : true,  // enable cookies to allow the server to access
      version    : 'v2.8' // use graph api version 2.8
    });

    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  };