var FilesView = Backbone.View.extend({
    el: $("#filesTableBody"),

    initialize: function(files){ //When created, the collection passed in will be the collection for the Files View

        this.collection = new Files(files);
        console.log(this.collection);
        this.render();
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
        this.$el.append(fileView.render().el); //Apend rendered file to the files table
    }

});