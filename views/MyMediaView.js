var MyMediaView = Backbone.View.extend({
   
    initialize: function(){
        this.template = _.template(PHOTO_MANAGER.Templates.MyFiles);
    },

    el: "#mainContent",

    events: {
        "click .addFile": "openAddFileDialog",
        "click #uploadImageButton": "uploadFile",
        'click #deleteFileButton' : "deleteFiles",
        'click #editMetadataButton' : "editFiles",
        'click .deleteFile' : "showDeleteModal",
        'click .downloadFile' : "downloadSelectedFiles",
        'click .editFile' : "showEditModal",
        'click .replaceFile' : "showReplaceModal"
    },

    render: function(){
        this.$el.html(this.template());
        this.renderFilesTable();
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
                showAlert({type: "error", message: "File upload failed: Database error."});
                break;
            case "FILE_UPLOAD_FAILED":
                showAlert({type: "error", message: "File upload failed: File couldn't be uploaded to the server."});
                break;
            case "UPLOAD_FAILED":
                showAlert({type: "error", message: "File upload failed: File upload failed due to a server issue."});
                break;
            case "INVALID_FILETYPE":
                showAlert({type: "error", message: "File upload failed: Incorrect file type. Allowed types: bmp, gif, jpg, png"})
                break;
            default: //Success
                var retData = $.parseJSON(ret);
                myMediaThis.filesView.collection.add(new File(retData[0])); //Add new item to collection
                showAlert({type: "success", message: "The file was uploaded successfully"});
                break;
        }

        //TODO Replace the loader with the submit button

        //Close the modal window
        $('#mainModal').modal('hide');
    },
   
    /**
     * Gets the complete filelist via ajax, then renders them in a new FilesView
     */
    renderFilesTable: function(){
        var thisView = this;
        
        $.ajax({ //Grab all file information
            url: "api/index.php/files",
            data: {
                method: "get"
            },
            success: function(data){
                var files = $.parseJSON(data);
                thisView.filesView = new FilesView(files); //Create new FilesViews

                //Hide loader
                $("#ajaxLoadingBar").remove();
            }
        });
    },
    
    showDeleteModal: function(){
        if($('.selected').size() < 1){ //If none are selected
            showAlert({type: "info", message: "You must select a file to delete"});
            return;
        }
        
        var deleteDialogTempl = _.template(PHOTO_MANAGER.Templates.deleteModal); //Compile deleteFile dialog
        
        $("#mainModal").html(deleteDialogTempl()); //Set modal content to be file upload dialog
        $('#mainModal').modal();
    },
    
    deleteFiles: function(){
        var files = $('.selected');
        var that = this;
        _.each(files, function(file){
            that.filesView.collection.remove($(file).attr('id'));
        });
        
        $('#mainModal').modal("hide");
    },
    
    downloadSelectedFiles: function(){
        if($('.selected').size() < 1){ //If none are selected
            showAlert({type: "info", message: "You must select a file to download"});
            return;
        }
        
        var thisModel = this.filesView.collection.get($(".selected").attr('id'));
        var modelAttrs = thisModel.attributes;
        window.open("download/download.php?file=" + JSON.stringify(modelAttrs));
    },
    
    showEditModal: function(){
        if($('.selected').size() < 1){ //If none are selected
            showAlert({type: "info", message: "You must select a file to edit"});
            return;
        }
        
        var thisModel = this.filesView.collection.get($(".selected").attr('id'));
        var editDialogTmpl = _.template(PHOTO_MANAGER.Templates.editModal); //Compile deleteFile dialog
        
        $("#mainModal").html(editDialogTmpl(thisModel.toJSON())); //Set modal content to be file upload dialog
        
        //Don't know how to use templating to insert default values in forms, so we're inserting them using JQuery here
        $("#editFilename").val(thisModel.get('Name'));
        $("#editDescription").val(thisModel.get('Description'));
        
        $('#mainModal').modal();
    },
    
    editFiles: function(){
        var model = this.filesView.collection.get($('.selected').attr('id')); //Get the model we mean to change
        model.set('Name', $("#editFilename").val());
        model.set('Description', $("#editDescription").val());
        
        $.ajax({ //Grab all file information
            url: "api/index.php/files",
            data: {
                method: "post",
                params: {
                    "file" : JSON.stringify(model.attributes)
                }
            },
            success: function(data){
                var files = $.parseJSON(data);
                alert("Check yer console");
                console.log(files);
            }
        });
    },
    
    showReplaceModal: function(){
        if($('.selected').size() < 1){ //If none are selected
            showAlert({type: "info", message: "You must select a file to replace"});
            return;
        }
        
        //Show modal to allow for replacing file
    }
    
});


