const helperModule = (function(){

    // type of error that we can have
    const TOKEN = 'Error validating access token';


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

        if (type === 'class'){
            element = document.getElementsByClassName(target);
            for(let i = 0; i < element.length; i++){
                element[i].addEventListener('click', callback);
            }
        } else {
            element = document.getElementById(target);
            element.addEventListener('click', callback);
        }   
    };

    /**
     *  Correct DB Date
     *          Correct Database Month
     *  Add a 0 in the day or month if it's under 10
     */
    const correctDBDate = function(date = 1){
        if (date < 10){
            return '0'+date;
        } else {
            return date;
        }
    }

    /**
     *  Get Img Data
     *              Get image from a file input
     *  @public
     *  @param {DOMElement} DOMelement
     *  @return {FormData} formData
     */
    const getImgData = function(DOMelement){
        let file = DOMelement.files[0];
        let formData = new FormData();
        formData.append('image', file);

        return formData;
    }

    /**
     *  Fb Permission
     *          Check if the user has the permission 
     *          If yes then return otherwise we asked the                    permission
     */
    const fbPerm = () => {
        let udid = getToken();
        // First we check if the user has the permission by making a request to the backend
        return req = new RequestBackend('/permissions', 'POST', {userID : udid.userID}).prepare();
    }

    const handleError = (error, reqscope, callback = {}) => {
        if (typeof error === 'string')
            if (error.indexOf('Error validating access token') !== -1)
                window.location.href = '/login';
        else if (error === 'permission not given'){
            FB.login(response => {
                if(response.authResponse){
                    callback.apply(response);
                    //console.log(response.authResponse);
                }
            },{ 
                scope : reqscope,
                return_scopes : true,
                auth_type: 'rerequest'
            });
        } else {
            swal(
                error,
                'error'
            )
        }
    }

    return {
        token : getToken,
        addListener : addEvent,
        fixDate : correctDBDate,
        image : getImgData,
        checkFBPerm : fbPerm,
        errorHandler : handleError
    }
}.bind({}))();

/**
 *  DOM Helper
 *          A Helper to set more easily a property to a 
 *          DOM Element
 *          Every property are chainable like jQuery
 *  @chainable
 */
const DOMHelper = (function(){
    let element;
    let DOMtype;

    /**
     *  Init
     *          Set an element for being use after
     */
    this.init = (DOMString = '', type) => {
        DOMtype = type;
        if (type === 'class')
            element = document.getElementsByClassName(DOMString);
        else   
            element = document.getElementById(DOMString);
        
        return this;
    };

    /**
     *  Set Style Prop
     *          Set the style to an element
     *  @param {String} propName
     *  @param {mixed} value
     */
    this.setStyleProp = (propName, value) => {
        element.style[propName] = value;

        return this;
    }

    /**
     *  Set Prop
     *          Set a property to a DOM Element
     *  @public
     */
    this.setProp = (propName, value) => {
        if (DOMtype === 'class'){
            for(let el of element){
                el.setAttribute(propName, value);
            }

            return this;
        }

        element.setAttribute(propName, value);

        return this;
    }

    /**
     *  Rm Prop
     *          Remove a property
     *  @param {String} propName
     */
    this.rmProp = (propName) => {
        if (DOMtype === 'class'){
            for(let el of element){
                el.removeAttribute(propName);
            }

            return this;
        }

        element.removeAttribute(propName);

        return this;
    };
    /**
     *  Set Content
     *          Set a template to an element
     *  @param {String} template
     *  @param {Array} ...params
     */
    this.setContent = (template, replace = false) => {
        console.log('setting content');
        if (replace){
            element.innerHTML = template;
        }

        element.insertAdjacentHTML('beforeend', template);

        return this;
    };

    /**
     *  Get Prop
     *          Return the properties of an element
     *  @param {String} propName
     *  @return {String} DOMElement.attribute
     *  @public
     */
    this.getProp = (propName, index = 0) => {
        if(DOMtype === 'class')
            return element[index].getAttribute(propName);

        return element.getAttribute(propName);
    };

    /**
     *  Get Element
     *          Return the element to do custom things
     *  @return {DOMElement} element
     */
    this.getElement = () => {
        return element;
    }

    /**
     *  Get Child 
     *          Get the child nodes of an element
     *  @return {Array} childNodes
     */
    this.getChild = () => {
        if (DOMtype === 'class'){
            let nodeArr = new Array();
            for(let i of element){
                nodeArr.push(i.childNodes);
            }

            return nodeArr;
        }

        return element.childNodes;
    }

    /**
     *  Hide 
     *  @param {String} DOMString
     *  @param {Number} timeout
     *  @return {Object} this
     *  @chainable
     */
    this.hide = (DOMString, timeout = 0) => {
        document.getElementById(DOMString).style.transition = `ease ${timeout}ms`;
        setTimeout(() => {
            document.getElementById(DOMString).style.opacity = 0;
        }, timeout);

        return this;
    };

    /**
     *  Destroy an element
     *      
     */
    this.destroy = (DOMString, timeout = 0) => {
        setTimeout(() => {
            let el = document.getElementById(DOMString).remove();
        }, timeout);
    };

    return {
        init : this.init
    }

}.bind({}))();