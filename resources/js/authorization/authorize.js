import policies from './policies';

export default{
    install(Vue, options){
        Vue.prototype.authorize = function(policy, model){
            if(!window.Auth.signedIn) return false;
            return policies[policy](window.Auth.user, model);
        };
        Vue.prototype.signedIn = window.Auth.signedIn;
    }
}
