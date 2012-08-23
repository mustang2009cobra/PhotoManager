var File = Backbone.Model.extend({
    idAttribute: "FileID",
    
    initialize: function(){ //Maybe don't need anything here
        this.on('remove', this.modelRemoved);
    },
    
    validate: function( attributes ){
        //Any validation code goes here before things are saved or set
    },
    
    modelRemoved: function(){
        this.trigger("removeModel", this.get("FileID"));
    }
});