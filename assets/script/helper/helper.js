const helperModule = (function(){
    document.addEventListener('DOMContentLoaded', () => getToken)

    const storage = localStorage.getItem('facebook_oauth_token');
    function getToken(){
        // Check if we have an access token
        if(storage == null || storage == undefined){
            return false;
        } else {
            return JSON.parse(storage);
        }
    }

    return {
        token : getToken
    }
}.bind({}))();