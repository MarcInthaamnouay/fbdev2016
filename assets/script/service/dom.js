/**
 *  Dom Component
 *          A DOM Helper factory
 *  @param {String} element
 *  @param {String} type (Class | id)
 *  @param {...} ...args other parameters 
 *  @return {Object} ...
 */
const domComponent = (element, type ,...args) => {

    let DOMElement = null,
        isClass = false
        props = null;

    /**
     *  Set Props 
     *          Set common property to our Factory
     */
    const setProps = (function(){
        if (type === 'class'){
            DOMElement = document.getElementsByClassName(element);
            isClass = true;
        }
        else
            DOMElement = document.getElementById(element);

        if (args.length !== 0)
            props = args;

        return this;
    })();

    /**
     *  Apply Attribute 
     *          Add an attribute to a given DOMElement
     *  @param {String} name
     *  @param {mix value} arg  
     */
    const applyAttribute = function(name, arg){
        if (!typeof name === 'string')
            throw new Error("attribute is not a type of string");
        
        DOMElement.setAttribute(name, arg);

        return this;
    };

    /**
     *  Get Attribute 
     *          Get the attribute of a DOM element 
     *  @private
     */
    const getAttribute = function(name, index){
        if (typeof index === 'number')
            return DOMElement[index].getAttribute(name);
        
        return DOMElement.getAttribute(name);
    };

    /**
     *  Get Src 
     *          Get the source image of an element
     */
    const getSrc = function(index){
        if (isClass)
            return DOMElement[index].children[0].src;
    
        return DOMElement.children[0].src;
    } 

    return {
        setAttr : applyAttribute,
        getAttr : getAttribute,
        getSrc : getSrc
    }
};