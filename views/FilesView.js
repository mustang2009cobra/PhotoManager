var FilesView = Backbone.View.extend({
    el: "#filesTableBody",

    initialize: function(files){ //When created, the collection passed in will be the collection for the Files View
        this.collection = new Files(files);
        //this.fileViews = { };
        this.render();
        this.collection.bind("add", this.renderFile, this); //passes the view context instead of the collection
    },

    render: function(){
        var that = this;
        
        _.each(this.collection.models, function(file){ //For each model in the collection:
            that.renderFile(file); //Render the file element
        }, this);
    },

    renderFile: function(file){
        var fileView = new FileView({ //Create new FileView, passing the file as its model
            model: file
        });
        //this.fileViews[file.get("FileID")] = fileView; //Keep track of FileView in FilesView view
        
        this.$el.append(fileView.render().$el.attr('id', file.get("FileID"))); //Apend rendered file to the files table
    }

});