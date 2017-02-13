/**
 * User Controler
 *      Controller of the user
 * @public
 */
const userController = (function(){
    const imageRegExp = 'image.*',
          formData = new FormData();

    let helper = helperModule;
    let el = document.getElementById('albums-list');
    const haveToken = helper.token();
    
    if(!haveToken){
        window.location.href = '/login';
        return;
    } 

    /**
     *  Send Photos to the backend
     *              Send photo to the backend and save it's url
     *  @private
     */
    const sendPhotos = function(){  
        let url = this.src;
        const req = new RequestBackend('/upload/photo', 'POST', {userID : haveToken.userID, photoURL : url});
        req.prepare().execute()
        .then(success => {
            console.log(success);
        })
        .then(publish.bind(null, url))
    }

    /**
     *  Publish 
     *          Publish a message when a user participate to 
     *          a contest
     */
    const publish = (url) => {
        // First check the permission if there're given 
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
              .then(publishFlow.bind(null, url))
              .then(hydrateAlert.bind(null, 'message published successfully', 'success'))
              .catch(err => {
                helper.errorHandler(err, 'publish_actions');
              });
    };

    /**
     *  Publish Flow 
     *          Publish an image if the user has authorized the publication
     */
    const publishFlow = (url) => {
        return new Promise((resolve, reject) => {
            let req = new RequestBackend('/user/share', 'POST', {
                message : 'Je participe au concours',
                userID : haveToken.userID,
                link : url,
                privacy : 'EVERYONE'
            });

            req.prepare().execute()
                .then(res => {
                    resolve(true);
                })
                .catch(err => {
                    reject(err);
                });
        });
    };

    /**
     *  Listen Photo 
     */
    const listenPhoto = function(){
        document.getElementById('albumCollapse').classList.add('in');
        document.getElementById('imageCollapse').classList.remove('in');
    };


    /**
     *  Display Photos
     *              Display photos of an album
     *  @private
     */
    const displayPhotos = function(){
        document.getElementById('albumCollapse').classList.remove('in');
        document.getElementById('imageCollapse').classList.add('in');
        let grid = document.getElementById('photo-list');
        let album_id = this.getAttribute('data-id');

        // Call the back end services
        const req = new RequestBackend('/photos', 'POST', {userID : haveToken.userID, albumID : album_id});
        req.prepare().execute()
        .then(success => {
            grid.innerHTML = '';
            for(let value of success.data){
                let tmpl = `
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                        <a href="#" class="thumbnail" data-toggle="modal" data-target="#myModal2">
                            <img src="${value.source}" class="userImg">
                        </a>
                    </div>
                `;
                 grid.insertAdjacentHTML('beforeend', tmpl);
            }
        })
        .then(helper.addListener.bind(null, 'backb', listenPhoto, 'id'))
        .then(helper.addListener.bind(null, 'userImg', sendPhotos))
        .catch(err => {
            console.log(err);
        });
    };

    /**
     *  Display Album
     *          Display the photo of the album and add a listener to the link
     *  @private
     */
    const displayAlbum = function(){
        let albumsIDs = new Array();
        const req = new RequestBackend("/albums", "POST", {userID : haveToken.userID});
        req.prepare().execute()
        .then(success => {
            for(let value of success){
                // push an array of albums that will be use to hydrate the view
                albumsIDs.push(value.id);
                // create a template à la volée
                let tmpl = `<div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 upload-photo">
                                <div class="thumbnail" data-id=${value.id}>
                                    <img src="http://placehold.it/200x200" class="thumb-img">
                                    <div class="caption">
                                    <h3 class="album-name">${value.name}</h3>
                                    </div>
                                </div>
                            </div>`;
                el.insertAdjacentHTML('beforeend', tmpl);
            }
        })
        .then(hydrateAlbums.bind(null, albumsIDs))
        .then(helper.addListener.bind(null, 'thumbnail', displayPhotos))
        .catch(err => {
            console.log(err);
            if(err.indexOf('expired') != -1){
                // This mean that the tokin has expired so he have to relog to the app
                window.location.href = '/login';
            } else{
                console.log(err);
            }
            
        })
    };

    /**
     *  Hydrate Albums
     *          Add content to the albums DOMElement 
     * @param {Array} albumsIDs
     * @private
     */
    const hydrateAlbums = (albumsIDs) => {
        // make a bulk request
        const req = new RequestBackend('/albums/photocover', 'POST', {userID : haveToken.userID, albums : albumsIDs});
        req.prepare().execute()
            .then(success => {
                let elements = document.getElementsByClassName('thumb-img');
                for(let i = 0; i < elements.length; i++){
                    elements[i].src = success[i].source;
                }
            })
            .catch(err => {
                console.log(err);
            });
    };
    

    /**
     *  Stylize Upload
     *          Create a popup that let you edit your photo to
     *          send on facebook
     *  @param {FileUpload} imgFile
     *  @private
     */
    const stylizeUpload = () => {
        let selectedFile = document.getElementById('input').files[0];

        console.log(selectedFile);

        // @TODO return a user info to the user that he did not upload the right file..
        if (!selectedFile.type.match(imageRegExp)){
            console.log('jreect');
            return;
        }
            
        
        let reader = new FileReader();
        reader.onload = (e) => {
            let makePopup = document.getElementById('albums-list');
            makePopup.insertAdjacentHTML('beforeend', `
                <div class="popup">
                    <div id="image"><img src=${reader.result}></div>
                    <div id="setInfo">
                        <input type="text" id="message">
                        <button type="button" class="btn btn-default btn-lg btn-block share-button" id="upload-computer">
                        <i class="fa fa-upload"></i> Upload
                    </button>
                    </div>
                    
                </div>
            `);

            document.getElementById('upload-computer').addEventListener('click', uploadPhotoFromComputer.bind(null, selectedFile));
        }

        reader.readAsDataURL(selectedFile);
    };


    /**
     *  Upload Photo From Computer
     *          Upload photo from computer 
     *  @private
     */
    const uploadPhotoFromComputer = (selectedFile) => {  
        let message = document.getElementById('message').value;

        if(!message)
            return;
            // display an image...
            
        formData.append('image', selectedFile, 'tamere');
        formData.append('userID', haveToken.userID);
        formData.append('message', message);
        formData.append('location', location);
        // Now make a request to the back-end
        
        const request = new RequestBackend('/upload/photo/computer', 'POST', formData, 'data');
        request.prepare().execute()
               .then((success) => {
                   console.log(success);
               })
               .catch((error) => {
                   console.log(error);
               });

    };


    /**
     *  Hydrate Alert 
     */
    const hydrateAlert = (text, type) => {
        console.log(text);
        console.log(type);
        swal(
            'great',
            text,
            type
        )
    };
    // Add a listener to the DOM
    document.addEventListener('DOMContentLoaded', function(){
        helper.checkFBPerm().execute()
              .then(res => {
                if(res.error !== undefined)
                    return Promise.reject(res.error);

                let perm = res.data.find( (data) => {
                        return data.permission === 'user_photos'
                });

                if(!perm || perm.status != 'granted')
                    return Promise.reject('permission not given'); 
              })
              .then(displayAlbum)
              .then(function(){
                  document.getElementById('input').addEventListener('change', stylizeUpload);
              })
              .catch(err => { 
                  helper.errorHandler(err, 'user_photos');
                  console.log(err);
              });
    });
}.bind({}))(document, window);