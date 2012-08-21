var FileView = Backbone.View.extend({
    tagName: "tr",
    className: "file",
    template: $("#fileTemplate").html(),

    render: function(){
        var tmpl = _.template(this.template);

        this.$el.html(tmpl(this.model.toJSON()));
        return this;
    }
});
