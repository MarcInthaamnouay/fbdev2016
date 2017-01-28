/**
 *  Admin Helper
 *          A Collection of function that might be need in other views of the admin
 *          Use it required to use Object.create({})
 *          IF there's a need to use other props inside of the Object of our Object 
 *          onload use the .call(helper <this>) method to apply the this to our Object..
 */
const adminHelper = {
    /**
     * Helper 
     *      Set the helper module
     */
    helper : helperModule,
    /**
     * Dom Helper
     *      A module to help interacting with the dom
     */
    domHelper : Object.create(DOMHelper),
    /**
     *  Check data before sending to the DB
     */
    checkData(isUpdate = false){
        const haveToken = this.helper.token();
        let elements = document.getElementsByClassName('data');

        let contest = {
            adminID : haveToken.userID
        };

        for(ele of elements){
            if(!ele.value)
                throw new Error('please fullfill every field');

            contest[ele.getAttribute('name')] = ele.value;
        }

        if(isUpdate){
            let id = document.getElementById('id-contest').getAttribute('data-id');
            contest.id = id;
            this.req.call(null, '/admin/updateContest', 'POST', contest, function(res){
                console.log(res);
                return;
            });
        } else {
            this.req.call(null, '/admin/contest', 'POST', contest, function(res){
                console.log(res);
            });
        }
    }, 
    sayHello(){
        console.log('hello');
    },
    /**
     *  Listen Creation
     *          Listen to the create button
     */
    listenCreation(){
        console.log(this);
        this.helper.addListener('create-button', this.checkData.bind(this), 'id');
    },
    /**
     *  Disable a contest
     */
    listenDeletion(){
        const haveToken = this.helper.token();
        let id = document.getElementById('id-contest').getAttribute('data-id');
        try{
            this.helper.addListener('disactivate', 
            this.req.bind(this, '/admin/disable', 'POST', {contestID : id, adminID : haveToken.userID}, 
            function(res){
                if (res.status === 'success'){
                    this.domHelper.init('status-container', 'id')    
                                  .setContent(`
                                    <a class="btn btn-default pull-right" id="activate">
                                        <i class="fa fa-play start-button" aria-hidden="true"></i> Activer le concours
                                    </a>
                                  `, true);
                }
            }),'id');
        } catch(e){
            console.log(e);
        } 
    },
    /**
     *  Req 
     *      Send a request to the database
     *  @param {String} request
     *  @param {String} method
     *  @param {Object} param
     *  @param {Function} callback
     *  @public
     */
    req(request, method, param, callback = {}){
        let self = this;
        let req = new RequestBackend(request, method, param);
        req.prepare().execute()
           .then(function(res){
               console.log(res);
               return callback.call(self, res);
           })
           .catch(function(err){
               return callback.call(self ,err);
           });
    },
    /**
     *  Activate Contest
     *          Activate a contest 
     *  @public
     */
    activateContest(){
        const haveToken = this.helper.token();
        let id = document.getElementById('id-contest').getAttribute('data-id');

        try{
            this.helper.addListener('activate', this.req.bind(this, 
            '/admin/setcontest', 'POST', {contestID : id, adminID : haveToken.userID}, 
            function(res){
                console.log(res);
                if (res.status === 'success'){
                    console.log('there');
                    console.log(this.domHelper);
                    this.domHelper.init('status-container', 'id')    
                                  .setContent(`
                                    <a class="btn btn-default pull-right" id="disactivate">
                                        <i class="fa fa-stop stop-button" aria-hidden="true"></i> Stopper le concours
                                    </a>
                                  `, true);
                }    
            }), 'id');
        } catch(e){
            console.log(e);
        }
    },
    editContest(){
        this.helper.addListener('editContent', function(){
            this.domHelper.init('form-control', 'class')
                          .rmProp('disabled', false);

            // change the data
            this.domHelper.init('validContent', 'id')
                          .setStyleProp('display', 'block');

            // Add listener to the add button
            this.helper.addListener('validContent', function(){
                this.checkData(true);
            }.bind(this), 'id');

            this.domHelper.init('cancelEdit', 'id')
                          .setStyleProp('display', 'block');

            // add listener to the cancel button
            this.helper.addListener('cancelEdit', function(){
                this.domHelper.init('form-control', 'id')
                          .setProp('disabled', 'true');
            }.bind(this), 'id');

        }.bind(this), 'id');
    },
    exportContest(){

    }
};

/** 
 *  Admin Controller
 *          As we doesn't need any function that need to be run in Runtime
 *          We only use an Object which store our function that we can call later on the 
 *          creation... (!) We can say that it's a sort of controller but it's also a sort of helper 
 *          if you want..
 */
const adminController = (function(){
    const href = (window.location.href).split('/');

    // start function at runtime depending of the routes 
    // It's a sort of Bootloader
    const init = () => {
        console.log(this);
       // we parsed the url and get the config...
       // we load the config of the admin locally...
       let req = new RequestBackend('/assets/admin.json', 'GET');
       req.prepare().execute()
          .then(res => {
              // first we parsed the url...
              let routeName = href[href.length - 1];
              let routeConfig = res[routeName];

              if(!routeConfig){
                  if(parseInt(routeName)){
                      routeConfig = res['contestID'];
                  }
              }
                
              this.startLoadedFunc(routeConfig, routeName);
          })
          .catch(err => {
              console.log(err);
          });
    }

    this.startLoadedFunc = (routeConfig, routeName) => {
        let helper = Object.create(adminHelper);
        for(func of routeConfig.load.func){
            helper[func].call(helper);
        }
    };

    document.addEventListener('DOMContentLoaded', init);
}.bind({}))();


