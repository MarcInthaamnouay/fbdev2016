/**
 * Login Controller
 *      Controller of the login page (Front side)
 * @public
 */
const loginController = (function(){
    console.log('fire');
    document.addEventListener('DOMContentLoaded', addListener)

    /**
     * Process Login 
     *          Process login help the user to log on the app
     * @private 
     */
    const processLogin = function(){
        console.log('pickaboo');
        FB.login(function(response) {
            if (response.authResponse) {
                //set the token as a session by using our request service
                let newRequest = new RequestBackend("/token", "POST", {"token" : response.authResponse.accessToken, "userID" : response.authResponse.userID});
                newRequest.prepare().execute().then(success => {
                    console.log(success);
                })  
                .catch(err => { 
                    console.log(err);
                });

                console.log(response);
                console.log('log !');
            } else {
                console.log('not log');
            }
        },{scope: 'email,user_likes,user_photos,publish_actions',
            return_scopes: true
        });
    }

    function addListener(){
        console.log('add bit');
        document.getElementById('button').addEventListener('click', processLogin)
    }

    
}.bind({}))();