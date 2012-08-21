var RateMediaView = Backbone.View.extend({
   
   initialize: function(){
       this.template = _.template(PHOTO_MANAGER.Templates.RateFiles);
   },
   
   el: "#mainContent",
   
   render: function(){
       this.$el.html(this.template());
   }
});