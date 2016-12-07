/*
 *  MakeRequest
 *      Type Class
 *  @params{}
 */
class makeRequest {
    constructor(request = "", httpMethod = "GET", params = null) {
        this.request = request;
        this.httpMethod = httpMethod;
        this.params = params;
    }

    /**
     * Preparing a request to our TEST API
     * @return {Request} request
     */
    Prepare() {
        // Init our headers
        const headers = new Headers();
        // Precise that we want a JSON back to the front
        headers.append('Content-type', 'application/json');
        // Init our API
        const config = {
            method: this.httpMethod,
            headers: headers,
            mode: 'cors',
            cache: 'default',
        }

        // Check if there're param in our request constructor ...
        console.log(this.params);
        if (this.params != null) {
            config.body = JSON.stringify(this.params);
        }

        console.log(config);
        // Prepare the request
        let request = new Request('http://berseck.fbdev.fr/' + this.request, config);
        return request;
    }

    /**
     * Execute a request to our TEST API
     * @Params {Request} req
     * @Return {promise} promise
     */
    Execute(req) {
        let q = new Promise(function(resolve, reject) {
            fetch(req)
                .then((response) => {
                    response.json()
                        .then(json => {
                            resolve(json);
                        })
                })
                .catch((error) => {
                    reject(error);
                });
        });

        return q;
    }
}

// listener on auth button

(function(){
  document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('photoAlbum').addEventListener('click', () => {
      getAlbums();
    });

    document.getElementById('login').addEventListener('click', () => {
      FBLog.login();
    });

    document.getElementById('logout').addEventListener('click', () => {
      FBLog.logout();
    });
  }, false);
}.bind({}))();

// test the access token

function getAlbums() {
    const storageValue = JSON.parse(localStorage.getItem('facebook_oauth_token'));
    const authReq = new makeRequest('api/v1.0/albums/', "POST", {'token' : storageValue.token});
    let reqPrepare = authReq.Prepare();

    // Only for the test

    let list = document.getElementById('albums-list');

    // execute the request
    authReq.Execute(reqPrepare)
      .then(response => {
        // list of album
        console.log(response);
        for(let i = 0; i < response.length; i++){
          let template = `<a href="#!" class="collection-item" data-id="${response[i].id}">${response[i].name}</a>`;
          list.insertAdjacentHTML('beforeend',template);
        }

        // Adding listener after inserting the choice
        listener();
      })
      .catch(err => {
        console.log(err);
      })
}


function listener(){
  const ln = document.getElementsByClassName('collection-item');

  for(let c = 0; c < ln.length; c++){
    ln[c].addEventListener('click', function(){
      getPhotos(this.getAttribute('data-id'));
    }, false);
  }
}

function isLog(){
  document.getElementById('login').style.display = 'none';
}

function getPhotos(id = null){
  const storageValue = JSON.parse(localStorage.getItem('facebook_oauth_token'));

  const photoRequest = new makeRequest('api/v1.0/photos/', 'POST', {'token' : storageValue.token, 'albumID' : id});
  let req = photoRequest.Prepare();

  let parentElement = document.getElementById('car');

  photoRequest.Execute(req)
    .then(res => {
      console.log(res);
      for(let u = 0; u < res.data.length; u++){
          let tmpl = `<a class="carousel-item" href="#one!"><img src="${res.data[u].source}"></a>`;
          parentElement.insertAdjacentHTML('beforeend', tmpl);
      }

      $(document).ready(function(){
        $('.carousel').carousel();
      });
    })
    .catch(err => {
      console.log('error '+e);
    })
}

function adminTest(){
  
}
// Login and Logout

const FBLog = {
  login(){
    FB.login(function(response) {
      if (response.authResponse) {
        console.log('log !');
      } else {
        console.log('not log');
      }
    },{
      scope: 'email,user_likes,user_photos',
      return_scopes: true
    });
    return false;
  },
  logout : function(){
    FB.logout(() => {
      localStorage.removeItem('facebook_oauth_token')
    })
  }
}
