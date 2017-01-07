const dom = Object.create({});

/**
 *  Remove Element From All
 *              Remove every element of a same target's class Element
 *  @param {String} target [default : ""]
 *  @param {String} removeClass [default : ""]
 *  @param {String} type [default : "class"]
 *  @public
 */
dom.removeElementFromAll = function(target = "", removeClass = "", type = "class"){
    // Define a variable where we're going to store our DOMElement
    let element;

    if(type === 'class'){
        element = document.getElementsByClassName(target);
    } else {
        element = document.getElementsByClassName(removeClass);
    }

    // @TODO finish this function.. 
};