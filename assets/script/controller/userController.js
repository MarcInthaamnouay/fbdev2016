/**
 * User Controler
 *      Controller of the user
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
     *  Display Photos
     *              Display photos of an album
     */
    const displayPhotos = function(){
        let album_id = this.getAttribute('data-id');

        // Call the back end services
        const req = new RequestBackend('/photos', "POST", {userID : haveToken.userID, albumID : album_id});
        req.prepare().execute()
        .then(success => {
            grid.innerHTML = '';
            for(let value of success.data){
                let tmpl = `<li><img src=${value.source}></li>`;
                grid.insertAdjacentHTML('beforeend', tmpl);
            }
        })
        .catch(err => {
            console.log(err);
        });
    };

    /**
     *  Display Album
     *          Display the photo of the album and add a listener to the link
     */
    const displayAlbum = function(){
        const req = new RequestBackend("/albums", "POST", {userID : haveToken.userID});
        req.prepare().execute()
        .then(success => {
            for(let value of success){
                let tmpl = `<a href="#" data-id="${value.id}" class="albums-link">${value.name}</a>`;
                el.insertAdjacentHTML('beforeend', tmpl);
            }
            console.log(helper);
            helper.addListener('albums-link', displayPhotos);
        })
        .catch(err => {
            console.log(err);
        })
    };

    document.addEventListener('DOMContentLoaded', displayAlbum);

    
    
})(document, window);