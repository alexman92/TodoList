/** 
 * Backbone controllers
 * Author: alexman92
 */

App.Controllers.MainController = Backbone.Router.extend({
    routes: {
        "" : "startPage"
    },
    
    // renders start page
    startPage: function() {
        new App.Views.MainView();
    } 
    
});


