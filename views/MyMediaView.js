var MyMediaView = Backbone.View.extend({
   
    initialize: function(){
        this.template = _.template(PHOTO_MANAGER.Templates.MyFiles);
    },

    el: "#mainContent",

    events: {
        "click .addFile": "openAddFileDialog",
        "click #uploadImageButton": "uploadFile",
        'click .deleteFile' : "deleteSelectedFiles",
        'click .downloadFile' : "downloadSelectedFiles",
        'click .editFile' : "editSelectedFilesMetadata",
        'click .replaceFile' : "replaceSelectedFile"
    },

    render: function(){
        this.$el.html(this.template());
        this.getFiles();
    },

    //Shows the "Add File" dialog box
    openAddFileDialog: function(){
        var fileDialogTempl = _.template(PHOTO_MANAGER.Templates.uploadModal); //Compile addFile dialog
        
        $("#mainModal").html(fileDialogTempl()); //Set modal content to be file upload dialog
        $('#mainModal').modal();
    },

    //Uploads a file from the form
    uploadFile: function(){
        //TODO Hide the submit button so they can't click twice
        
        document.getElementById('file_upload_form').target = 'upload_target';
        document.forms['file_upload_form'].submit();
        document.getElementById("upload_target").onload = this.uploadComplete;
    },
   
    uploadComplete: function(){
        var myMediaThis = Router.myMedia; //Since we're in the context of the iframe, we need to be in our view's context
        var ret = frames['upload_target'].document.getElementsByTagName("p")[0].innerHTML;

        document.getElementById('file_upload_form').reset();

        switch(ret){
            case "SAVE_TO_DATABASE_FAILED":
                Router.alert.render({type: "error", message: "The file upload failed due to a database error."});
                break;
            case "FILE_UPLOAD_FAILED":
                Router.alert.render({type: "error", message: "The file could not be uploaded to the server."});
                break;
            case "UPLOAD_FAILED":
                Router.alert.render({type: "error", message: "The file upload failed due to a server issue."});
                break;
            default: //Success
                var retData = $.parseJSON(ret);
                myMediaThis.filesView.collection.add(new File(retData[0])); //Add new item to collection
                Router.alert.render({type: "success", message: "The file was uploaded successfully"});
                break;
        }

        //TODO Replace the loader with the submit button

        //Close the modal window
        $('#mainModal').modal('hide');
    },
   
    getFiles: function(){
        var thisView = this;
        
        $.ajax({
            data: {
                method: "get",
                resource: "files"
            },
            success: function(data){
                var files = $.parseJSON(data);
                console.log("Starting to create the FilesView");
                thisView.filesView = new FilesView(files);

                //Hide loader
                $("#ajaxLoadingBar").remove();
            }
        });
    },
    
    deleteSelectedFiles: function(){
        if($('.selected').size() < 1){ //If none are selected
            Router.alert.render({type: "info", message: "You must select a file to delete"});
        }
        
        var deleteDialogTempl = _.template(PHOTO_MANAGER.Templates.deleteModal); //Compile deleteFile dialog
        
        $("#mainModal").html(deleteDialogTempl()); //Set modal content to be file upload dialog
        $('#mainModal').modal();
    },
    
    downloadSelectedFiles: function(){
        if($('.selected').size() < 1){ //If none are selected
            Router.alert.render({type: "info", message: "You must select a file to download"});
        }
        
        //Don't show modal, just download the file
    },
    
    editSelectedFilesMetadata: function(){
        if($('.selected').size() < 1){ //If none are selected
            Router.alert.render({type: "info", message: "You must select a file to edit"});
        }
        
        //Show modal to allow for file editing
    },
    
    replaceSelectedFile: function(){
        if($('.selected').size() < 1){ //If none are selected
            Router.alert.render({type: "info", message: "You must select a file to replace"});
        }
        
        //Show modal to allow for replacing file
    }
    
});


