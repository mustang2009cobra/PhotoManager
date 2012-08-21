var AlertView = Backbone.View.extend({
   
   initialize: function(){
       this.template = _.template(PHOTO_MANAGER.Templates.Alert);
   },
   
   el: "#alertArea",
   
   render: function(){
       console.log("TYPE");
       console.log(this.type);
       var options = {
           type: this.type,
           message: this.message
       }
       
       console.log("TYPE");
       console.log(this.type);
       
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


