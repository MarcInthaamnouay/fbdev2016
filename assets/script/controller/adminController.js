/**
 *  Admin Helper
 *          A Collection of function that might be need in other views of the admin
 *          Use it required to use Object.create({})
 *          IF there's a need to use other props inside of the Object of our Object 
 *          onload use the .call(helper <this>) method to apply the this to our Object..
 */
const adminHelper = {
    checkData(){
        const helper = helperModule;
        const haveToken = helper.token();
        let elements = document.getElementsByClassName('data');

        let contest = {
            adminID : haveToken.userID
        };

        for(ele of elements){
            if(!ele.value)
                throw new Error('please fullfill every field');

            contest[ele.getAttribute('name')] = ele.value;
        }

        let req = new RequestBackend('/admin/contest', 'POST', contest);
        req.prepare().execute()
           .then(res => {
               console.log(res);
           })
           .catch(err => {
               console.log(err)
           });
    }, 
    sayHello(){
        console.log('hello');
    },
    listenCreation(){
        let listenerElement = document.getElementById('create-button');
        listenerElement.addEventListener('click', this.checkData.bind(this));
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


