var FileView = Backbone.View.extend({
    tagName: "tr",
    className: "file",
    
    initialize: function(){
        this.template = _.template(PHOTO_MANAGER.Templates.File);
    },
    
    events: {
        "click": "selectFile",
        "doubleclick": "previewFile",
        "remove:model": "removeView"
    },

    render: function(){
        this.$el.html(this.template(this.model.toJSON()));
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
        
    },
    
    removeView: function(){
        console.log(this.remove);
        this.remove();
    }
});
