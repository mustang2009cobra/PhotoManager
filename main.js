/**
 * Initializes the application
 * 
 */

$(document).ready(function(){
    
    PHOTO_MANAGER = { }; //Global JS namespace for photo manager application
    PHOTO_MANAGER.Templates = { }; //Empty object for templates ajaxed from API
    
    $.ajaxSetup({
        type: "POST"
    });
    
    //Get all templates
    $.ajax({
        url: "api/index.php/templates",
        data: {
            method: "get"
        },
        async: false,
        success: function(data){
            PHOTO_MANAGER.Templates = $.parseJSON(data);
        }
    });
    
    //Init router
    Router = new Router(); //global
    Backbone.history.start();
   
});


