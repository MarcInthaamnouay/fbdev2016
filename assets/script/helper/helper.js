const helperModule = (function(){
    document.addEventListener('DOMContentLoaded', () => getToken)
    const storage = localStorage.getItem('facebook_oauth_token');

    /**
     *  Get Token
     *              Get the token from the localStorage
     *  @private
     */
    function getToken(){
        // Check if we have an access token
        if(storage == null || storage == undefined){
            return false;
        } else {
            return JSON.parse(storage);
        }
    }

    /**  
     *  Add Event
     *              Add an event to a Class
     *  @param {String} target
     *  @param {Function} callback
     *  @param {String} type [default: class]
     *  @private
     */
    const addEvent = function(target = "", callback = function(){}, type = 'class'){
        let element;      
        console.log('add it');  

        if (type == 'class'){
            element = document.getElementsByClassName(target);
            for(let i = 0; i < element.length; i++){
                element[i].addEventListener('click', callback);
            }
        } else {
            console.log('should have add');
            console.log(target);
            element = document.getElementById(target);
            element.addEventListener('click', callback);
        }   
    };

    return {
        token : getToken,
        addListener : addEvent
    }
}.bind({}))();