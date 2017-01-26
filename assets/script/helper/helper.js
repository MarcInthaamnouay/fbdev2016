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

    return {
        token : getToken,
        addListener : addEvent,
        fixDate : correctDBDate,
        image : getImgData
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

            return;
        }

        element.setAttribute(propName, value);
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
            return this;
        }

        element.insertAdjacentHTML('beforeend', template)    
    };

    return {
        init : this.init
    }

}.bind({}))();