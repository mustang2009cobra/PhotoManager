var Files = Backbone.Collection.extend({
    model: File,
    initialize: function(){
    },
    
    addFile: function(file){
        
        alert("File added!");
    },
    
    removeFile: function(file){
        console.log(file);
        alert("File removed!");
    }
});