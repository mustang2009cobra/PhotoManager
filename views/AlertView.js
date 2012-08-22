var AlertView = Backbone.View.extend({
   
   initialize: function(){
       this.template = _.template(PHOTO_MANAGER.Templates.Alert);
   },
   
   el: "#alertArea",
   
   render: function(params){
       var options = {
           type: params.type,
           message: params.message
       }
       
       if(params.type == 'error'){
           options.header = "Error";
       }
       else if(params.type == 'success'){
           options.header = "Success";
       }
       else{
           options.header = "Info";
       }
       
       this.$el.html(this.template(options));
   }
});


