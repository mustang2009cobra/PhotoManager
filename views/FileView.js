var FileView = Backbone.View.extend({
    className: "file",
    
    initialize: function(){
        this.template = _.template(PHOTO_MANAGER.Templates.File);
    },

    render: function(){
        console.log("Rendering a single file");

        this.$el.html(this.template(this.model.toJSON()));
        return this;
    }
});
