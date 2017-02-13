/**
 *  Home Controller
 *          Home Controller is the controller of the homepage
 *          We use an IIFE to execute at starting at runtime some functions of the controller  
 */
const homeController = (function(){
    // @private
    // Get our helper module
    let helper = helperModule;
    const ls = helper.token();
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
        const attr = DOMHelper.init('active', 'class');

        // make a feedback to the user that no photo is in the contest
        if(attr.getProp('data-id-contestant', 0) === 'none'){
            DOMHelper.init('myBody', 'id')
                     .setContent(`
                        <div class="alert alert-warning" id="warning" role="alert">No photos to like</div>
                     `)
                     .hide('warning', 300)
                     .destroy('warning', 600);
        
            return;
        }

        const data = {
            id_contestant : attr.getProp('data-id-contestant', 0),
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
     *  Share 
     *          Share a photo with a custom message
     *  @private
     */
    const share = () => {
        let element = DOMHelper.init('active', 'class');

        if(element.getProp('data-id-contestant', 0) === 'none'){
            DOMHelper.init('myBody', 'id')
                     .setContent(`
                        <div class="alert alert-warning" id="warning-share" role="alert">No photos to share</div>
                     `)
                     .hide('warning-share', 300)
                     .destroy('warning-share', 600);
        
            return;
        }
            
        // show the modal
        let child = element.getChild();

        DOMHelper.init('modal-img', 'id')
                 .setProp('src', child[0][1].currentSrc); 

        $('#myModal').modal('toggle');
        helper.addListener('post-share', sharePost.bind(null, child[0][1].currentSrc) ,'id')
    };

    /**
     *  Share Post
     *          Share a post
     */
    const sharePost = (imglink) => {
        // make request
        const req = new RequestBackend('/user/share', 'POST', {
            message : document.getElementById('message-modal').value,
            privacy : document.getElementById('privacy').value,
            link : imglink,
            userID : ls.userID
        }).prepare().execute()
                  .then(res => {
                      $('#myModal').modal('toggle');

                      if(res.error !== undefined)
                            return Promise.reject(res.error);
                  })
                  .catch(err => {
                      console.log(err);
                  });
    };

    const shareProcess = () => {
        // first we need to check if the user has the permission
        helper.checkFBPerm().execute()
              .then(res => {
                  if(res.error !== undefined)
                    return Promise.reject(res.error);
                
                  let perm = res.data.find( (data) => {
                    return data.permission === 'publish_actions'
                  });

                  if(!perm || perm.status != 'granted')
                    return Promise.reject('permission not given'); 
              })
              .then(share)
              .catch(err => {
                  console.log(err);
                  helper.errorHandler(err, 'publish_actions') 
              });
    };

    /**
     *  Listen photo event
     *          Add an event to the photos 
     *  @public
     */
    const listenPhoto = function(){
        helper.addListener('share', shareProcess, 'id');
        helper.addListener('like', likePhoto, 'id');
    }

    // Listen for the dom ready then add our listener 
    document.addEventListener('DOMContentLoaded', listenPhoto, false);
    //document.addEventListener('DOMContentLoaded', sharePhoto, false);
    // Return our listener this can be useful for other cases
    return {
        listen : listenPhoto
    }
}.bind({}))();   