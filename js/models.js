/** 
 * Backbone models
 * Author: alexman92
 */


/**
 * User Model with primary data
 */
App.Models.UserModel = Backbone.Model.extend({
    defaults: {
        login: '',
        sid: ''
    },
    
    initialize: function() {
        setLocalData('sid', this.get('sid'));
        setLocalData('login', this.get('login'));
        
        new App.Views.TodoPageView({model: this});
    }, 
    // checks user's session
    isAuth: function() {
        if( (this.sessionId !== undefined) || this.sessionId !== '') {
            return true;
        }
        
        return false;
    }
});


