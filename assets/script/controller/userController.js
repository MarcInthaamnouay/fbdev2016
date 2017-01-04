/**
 * User Controler
 *      Controller of the user
 */
const userController = (function(){
    let helper = helperModule;
    // Check if the access token is present
    const haveToken = helper.token();
    if(!haveToken){
        window.location.href = '/login';
        return;
    } else {
        // set the token of our app
    }

    const displayAlbum = function(){
        
    }
})();