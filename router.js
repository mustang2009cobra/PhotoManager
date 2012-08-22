var Router = Backbone.Router.extend({
   initialize: function(){
       this.myMedia = new MyMediaView();
       this.rateMedia = new RateMediaView();
       this.alert = new AlertView();
   },
   
   routes: {
       "" : "myMedia",
       "rate-media/" : "rateMedia",
       "my-media/" : "myMedia"
   },
   
   myMedia: function(){
       this.myMedia.render();
   },
   
   rateMedia: function(){
       this.rateMedia.render();
   }
});


