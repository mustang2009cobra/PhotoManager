var FileView = Backbone.View.extend({
    tagName: "tr",
    className: "file",
    
    initialize: function(){
        this.template = _.template(PHOTO_MANAGER.Templates.File);
    },
    
    events: {
        "click": "selectFile"
    },

    render: function(){
        this.$el.html(this.template(this.model.toJSON()));
        return this;
    },
    
    selectFile: function(event){
        var item = $(event.target);
        $(item).parent().addClass("selected");
    }
});
