// TESTING THE API OF OUR PROJECT

class makeRequest{
  constructor(request = "", httpMethod = "GET", params = null){
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
    headers.append('Content-type','application/json');
    // Init our API
    const config = {
      method : this.httpMethod,
      headers : headers,
      mode : 'cors',
      cache : 'default',
    }

    // Check if there're param in our request constructor ...
    if(!this.params === null) {
      config.body = this.params;
    }
    // Prepare the request
    let request = new Request('http://berseck.fbdev.fr/'+this.request, config);
    return request;
  }

  /**
   * Execute a request to our TEST API
   * @Params {Request} req
   * @Return {promise} promise
   */
  Execute(req) {
    let q = new Promise(function(resolve, reject){
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

(function(){
  document.addEventListener('DOMContentLoaded', () => {
    // Test our API
    let auth_API = new makeRequest('api/v1.0/auth');
    const auth_API_prepare = auth_API.Prepare();

    auth_API.Execute(auth_API_prepare)
    .then(response => {
      if(!response.auth_status) {
        document.getElementById('login-link').setAttribute('href',response.auth_url);
      } else {
        console.log('user already connected !');
      }

    })
    .catch(error => {
      console.log('error '+error);
    })

  })
})();
