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
        FB.login(function(response) {
            if (response.authResponse) {
                const authObj = {
                    token : response.authResponse.accessToken, 
                    userID : parseInt(response.authResponse.userID)
                }
                //set the token as a session by using our request service
                let newRequest = new RequestBackend("/token", "POST", authObj);
                newRequest.prepare().execute().then(success => {
                    console.log(authObj.userID);
                    localStorage.setItem("facebook_oauth_token", JSON.stringify(authObj));
                    window.location.href = `/upload`;
                })  
                .catch(err => { 
                    console.log(err);
                });
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