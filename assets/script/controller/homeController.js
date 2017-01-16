/**
 *  Home Controller
 *          Home Controller is the controller of the homepage
 *          We use an IIFE to execute at starting at runtime some functions of the controller  
 */
const homeController = (function(){
    // @private
    // Get our helper module
    let helper = helperModule;
    const date = new Date();
    const month = helper.fixDate(date.getMonth() + 1);
    const day = helper.fixDate(date.getDate());

    /**
     *  Like Photo
     *          Like photo increase the number of like of a photo by 
     *          sending a request +1 to the database
     *  @param {String} photoID
     */
    const likePhoto = function(photoID){
        const attr = domComponent('active', 'class');

        const data = {
            id_contestant : attr.getAttr('data-id-contestant', 0),
            id_user : helper.token().userID,
            date_vote : `${date.getFullYear()}-${month}-${day}`
        };

        // Make a request to our database
        const req = new RequestBackend('/user/like', "POST", data);
        req.prepare().execute()
            .then(success => {
                console.log(success);
            })
            .catch(err => {
                console.log(err);
            });
    }

    /**
     *  Listen photo event
     *          Add an event to the photos 
     *  @public
     */
    const listenPhoto = function(){
        helper.addListener('like', likePhoto, 'id');
    }

    // Listen for the dom ready then add our listener 
    document.addEventListener('DOMContentLoaded', listenPhoto, false);
    // Return our listener this can be useful for other cases
    return {
        listen : listenPhoto
    }
}.bind({}))();   