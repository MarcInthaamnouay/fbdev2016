/**
 *  Request Backend Class 
 *          Make a request to the back end
 *  @param {String} req [default : ""]
 *  @param {String} method [default : "POST"]
 *  @param {Object} params [default : {}] 
 */
class RequestBackend{    
    constructor(req = "", method = "POST", params = null, type = 'json'){
        this.req = req;
        this.method = method;
        this.params = params;
        this.makeRequest = "";
        this.type = type;
    }

    /**
     *  Prepare
     *      Prepare the request to the back end 
     *  @chainable
     *  @return {this} Object 
     */
    prepare(){
        console.log(this.type);
        const headers = new Headers();
        // Precise that we want a JSON back to the front
        if (this.type === 'json'){
            headers.append('Content-type', 'application/json');
        }
        else {
           // headers.append('Content-type', 'multipart/form-data; boundary=our data');
        }    

        // Init our API
        const config = {
            method: this.method,
            headers: headers,
            mode: 'cors',
            cache: 'default',
        }

        // Check if there're param in our request constructor ...
        if (this.params != null && this.type === 'json')
            config.body = JSON.stringify(this.params);
        else if(this.params != null)
            config.body = this.params;

        // Prepare the request
        this.makeRequest = new Request('http://berseck.fbdev.fr' + this.req, config);

        return this;
    }

    /**
     *  Execute
     *          Execute the request
     *  @return {Promise} promise
     */
    execute(){
        const innerReq = this.makeRequest;
        let promise = new Promise(function(resolve, reject) {
            fetch(innerReq)
                .then((response) => {
                    response.json()
                        .then(json => {
                            if(json.status != undefined)
                                if(json.status != 'success')
                                    reject(json.status);
                            // Otherwise resolve
                            resolve(json);
                        })
                        .catch(error => {
                            reject(error);
                        })
                })
                .catch((error) => {
                    reject(error);
                });
        });

        return promise;
    }
}
