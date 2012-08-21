var Router = Backbone.Router.extend({
   initialize: function(){
       this.myMedia = new MyMediaView();
       this.rateMedia = new RateMediaView();
       this.alert = new AlertView({
           
       });
   },
   
   routes: {
       "" : "myMedia",
       "rate-media/" : "rateMedia",
       "my-media/" : "myMedia"
   },
   
   myMedia: function(){
       console.log("Router.myMedia() called.");
       this.myMedia.render();
       this.alert.render();
   },
   
   rateMedia: function(){
       console.log("Router.rateMedia() called.");
       this.rateMedia.render();
   }
});


