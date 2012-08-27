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
        var modelAttrs = this.model.attributes;
        modelAttrs.downloadType = "download";
        
        if(this.$el.hasClass("image")){
            var imagePreviewTmpl = _.template(PHOTO_MANAGER.Templates.previewModal); //Compile imagePreview dialog
        
            $("#mainModal").html(imagePreviewTmpl({header: "Image Preview"})); //Set modal content to be file upload dialog
            $("#mainModal .modal-body").html("<img src='download/download.php?file=" + JSON.stringify(modelAttrs) + "' />"); //Add image tag to modal
            $("#mainModal .downloadFileBtn").attr('href', "download/download.php?file=" + JSON.stringify(modelAttrs)).attr('target', "_blank"); //Add link to download file
            $('#mainModal').modal();
        }
        else{
            window.open("download/download.php?file=" + JSON.stringify(modelAttrs));
        }
    },
    
    deleteFile: function(){
        var thisContext = this;
        $.ajax({ //Grab all file information
            url: "api/index.php/files",
            data: {
                method: "delete",
                params: {
                    "file" : JSON.stringify(this.model.attributes)
                }
            },
            success: function(data){
                var retData = $.parseJSON(data);
                thisContext.remove(); //Remove visual DOM element
                thisContext.unbind(); //Unbind all events
            }
        });
    }
});
