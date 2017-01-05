/**
 *  Status Change Callback
 *              Callback of the getLoginStatus
 */
function statusChangeCallback(response) {
    if (response.status === 'connected') {
    
    } else if (response.status === 'not_authorized') {
        checkIfLogin();
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
    if(window.location.href.indexOf('login') != -1){
        window.location.href = '/login';
    } else {
        return;
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
