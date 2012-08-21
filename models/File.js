var File = Backbone.Model.extend({
    initialize: function(){ //Maybe don't need anything here
        this.bind("change:name", function(){
            alert("NAME CHANGED! Should save here or something")
        });
    },
    validate: function( attributes ){
        //Any validation code goes here before things are saved or set
    }
});