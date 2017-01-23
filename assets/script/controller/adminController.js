/** 
 *  Admin Controller
 *          As we doesn't need any function that need to be run in Runtime
 *          We only use an Object which store our function that we can call later on the 
 *          creation... (!) We can say that it's a sort of controller but it's also a sort of helper 
 *          if you want..
 */
const adminController = {
    send : () => {
        
    },
    checkData : function(){

    },
    instantiate : function(){
        return Object.create(this);
    }
};