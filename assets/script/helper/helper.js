const helperModule = (function(){
    document.addEventListener('DOMContentLoaded', () => getToken)
    const storage = localStorage.getItem('facebook_oauth_token');

    /**
     *  Get Token
     *              Get the token from the localStorage
     */
    function getToken(){
        // Check if we have an access token
        if(storage == null || storage == undefined){
            return false;
        } else {
            return JSON.parse(storage);
        }
    }

    const addEvent = function(elClass = "", callback = function(){}){
        let element = document.getElementsByClassName(elClass);
        for(let i = 0; i < element.length; i++){
            element[i].addEventListener('click', callback);
        }
    };

    return {
        token : getToken,
        addListener : addEvent
    }
}.bind({}))();