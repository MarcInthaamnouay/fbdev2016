/**
 *  Status Change Callback
 *              Callback of the getLoginStatus
 */
function statusChangeCallback(response) {
    if (response.status === 'connected') {
      return;
    } else if (response.status === 'not_authorized') {
        window.location.href = '/login';
    } else {
        checkIfLogin();
    }
  }

/**
 *  Check Login State
 *          Check the state of the login
 */
function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
}

/**
 *  Check If Login
 *          Check if the user is in the login page
 */
function checkIfLogin(){
    if(/login/.test(window.location.href)){
        return;
    } else {
        window.location.href = '/login';
    }
}

window.fbAsyncInit = function() {
    FB.init({
      appId      : '1418106458217541',
      cookie     : true,  // enable cookies to allow the server to access
      version    : 'v2.8' // use graph api version 2.8
    });

    // Parse the url 
    FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
    });
};
