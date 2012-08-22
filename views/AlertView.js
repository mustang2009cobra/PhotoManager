var AlertView = Backbone.View.extend({
   
   initialize: function(){
       this.template = _.template(PHOTO_MANAGER.Templates.Alert);
   },
   
   el: "#alertArea",
   
   render: function(){
       var options = {
           type: this.type,
           message: this.message
       }
       
       if(this.type == 'error'){
           options.header = "Error";
       }
       else if(this.type == 'success'){
           options.header = "Success";
       }
       else{
           options.header = "Info";
       }
       
       this.$el.html(this.template(options));
   }
});


