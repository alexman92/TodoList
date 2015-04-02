/**
 * GLOBAL VARS AND FUNCTIONS
 * Author: alexman92
 */

window.App = {
    Models: {},
    Views: {},
    Controllers: {}
};

window.template = function(id) {
    return _.template( $('#' + id).html() );
};

window.setLocalData = function(key, value) {
    localStorage.setItem(key, value);
};
window.getLocalData = function(key) {
    return localStorage.getItem(key);
};


