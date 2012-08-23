var FileView = Backbone.View.extend({
    tagName: "tr",
    className: "file",
    
    initialize: function(){
        this.template = _.template(PHOTO_MANAGER.Templates.File);
        this.model.bind("change", this.render, this);
        this.model.bind("remove", this.deleteFile, this);
    },
    
    events: {
        "click": "selectFile",
        "dblclick": "previewFile"
    },

    render: function(){
        this.$el.html(this.template(this.model.toJSON()));
        this.$el.addClass(this.model.get("Type"));
        this.$el.attr("mimeType", this.model.get("MimeType"));
        return this;
    },
    
    selectFile: function(event){
        var item = $(event.target);
        if($('.selected').size() > 0){ //A file is already selected
            $(".selected").removeClass('selected');
        }
        $(item).parent().addClass("selected");
    },
    
    previewFile: function(event){
        if(this.$el.hasClass("audio")){
            
        }
        else if(this.$el.hasClass("video")){
            
        }
        else if(this.$el.hasClass("image")){
            //Show the image in the modal
        }
        else{
            //Just download the file
        }
    },
    
    deleteFile: function(){
        //DELETE IN MY DB CLASS ISN'T WORKING
//        $.ajax({ //Grab all file information
//            data: {
//                method: "delete",
//                resource: "file",
//                params: {
//                    "FileID" : this.model.get("FileID")
//                }
//            },
//            success: function(data){
//                var retData = $.parseJSON(data);
                this.remove(); //Remove visual DOM element
                this.unbind(); //Unbind all events
//            }
//        });
        
        
    }
});
