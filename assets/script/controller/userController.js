/**
 * User Controler
 *      Controller of the user
 * @public
 */
const userController = (function(){
    let helper = helperModule;
    let el = document.getElementById('albums-list');
    let grid = document.getElementById('grid');
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
        let url = this.previousSibling.src;
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
     *  Save Photos
     *              Save a photo of the user
     *  @private
     */
    const savePhoto = function(){
        // remove the element from other element..
        this.classList.add('select');
        const tmpl = `<button id='add'>add</button>`;
        this.parentElement.insertAdjacentHTML('beforeend', tmpl);
        helper.addListener('add', sendPhotos, 'id');
    };

    /**
     *  Display Photos
     *              Display photos of an album
     *  @private
     */
    const displayPhotos = function(){
        let album_id = this.getAttribute('data-id');

        // Call the back end services
        const req = new RequestBackend('/photos', "POST", {userID : haveToken.userID, albumID : album_id});
        req.prepare().execute()
        .then(success => {
            grid.innerHTML = '';
            for(let value of success.data){
                let tmpl = `<li><img src=${value.source} class="userImg"></li>`;
                grid.insertAdjacentHTML('beforeend', tmpl);
            }
            helper.addListener('userImg', savePhoto)
        })
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
        const req = new RequestBackend("/albums", "POST", {userID : haveToken.userID});
        req.prepare().execute()
        .then(success => {
            for(let value of success){
                let tmpl = `<a href="#" data-id="${value.id}" class="albums-link">${value.name}</a>`;
                el.insertAdjacentHTML('beforeend', tmpl);
            }

            helper.addListener('albums-link', displayPhotos);
        })
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
    // Add a listener to the DOM
    document.addEventListener('DOMContentLoaded', displayAlbum);
})(document, window);