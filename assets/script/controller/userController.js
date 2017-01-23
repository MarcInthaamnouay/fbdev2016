/**
 * User Controler
 *      Controller of the user
 * @public
 */
const userController = (function(){
    let helper = helperModule;
    let el = document.getElementById('albums-list');
    // Check if the access token is present
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
        console.log('click');
        let url = this.src;
        const req = new RequestBackend('/upload/photo', 'POST', {userID : haveToken.userID, photoURL : url});
        req.prepare().execute()
        .then(success => {
            console.log(success);
        })
        .catch(err => {
            console.log(err);
        });
    }

    /**
     *  Display Photos
     *              Display photos of an album
     *  @private
     */
    const displayPhotos = function(){
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
                let tmpl = `<div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
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

            });
    };

    /**
     *  Upload Photo From Computer
     *          Upload photo from computer 
     *  @private
     */
    const uploadPhotoFromComputer = () => {

        const imageRegExp = 'image.*';
        const formData = new FormData();
        let selectedFile = document.getElementById('input').files[0];

        // @TODO return a user info to the user that he did not upload the right file..
        if (!selectedFile.type.match(imageRegExp)){
            console.log(selectedFile);
            return;
        }   
        

        formData.append('image', selectedFile, 'tamere');
        formData.append('userID', haveToken.userID);
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
    // Add a listener to the DOM
    document.addEventListener('DOMContentLoaded', () => {
        displayAlbum();
        document.getElementById('upload-computer').addEventListener('click', uploadPhotoFromComputer);
    });
})(document, window);