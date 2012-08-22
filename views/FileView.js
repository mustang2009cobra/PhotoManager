var FileView = Backbone.View.extend({
    
    
    initialize: function(){
        this.template = _.template(PHOTO_MANAGER.Templates.File);
    },

    render: function(){
        this.$el.html(this.template(this.model.toJSON()));
        return this;
    }
});
