/**
 * Login Controller
 *      Controller of the login page (Front side)
 * @public
 */
const loginController = (function(){
    console.log('fire');
    document.addEventListener('DOMContentLoaded', addListener)
    const route = (window.location.href).split('/');

    /**
     * Process Login 
     *          Process login help the user to log on the app
     * @private 
     */
    const processLogin = function(){
        console.log(FB);
        FB.login(function(response) {
            console.log(response);
            if (response.authResponse) {
                const authObj = {
                    token : response.authResponse.accessToken, 
                    userID : parseInt(response.authResponse.userID)
                }
                //set the token as a session by using our request service
                let newRequest = new RequestBackend("/token", "POST", authObj);
                newRequest.prepare().execute().then(success => {
                    if(success.error !== undefined) 
                        return Promise.reject(success.error);
                        
                    localStorage.setItem("facebook_oauth_token", JSON.stringify(authObj));

                    if(route[route.length - 1] === 'login')
                        window.location.href = `/upload`;
                    else    
                        window.location.href = `/admin/${parseInt(response.authResponse.userID)}/config`;
                })  
                .catch(err => { 
                    console.log(err);
                });
            } else {
                console.log('not log');
            }
        },{scope: 'email,user_likes',
            return_scopes: true
        });
        // scope that we might have to ask 
        //email,user_likes,user_photos,publish_actions
    }

    function addListener(){
        console.log('add bit');
        document.getElementById('button').addEventListener('click', processLogin)
    }
}.bind({}))();