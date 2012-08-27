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
       this.setActiveHeaderState("myFiles");
       this.myMedia.render();
   },
   
   rateMedia: function(){
       this.setActiveHeaderState("rateFiles");
       this.rateMedia.render();
   },
   
   setActiveHeaderState: function(page){
       if(page == "myFiles"){
           $("#myFilesPageLink").parent().addClass("active");
           $("#rateFilesPageLink").parent().removeClass("active");
       }
       else if(page == "rateFiles"){
           $("#myFilesPageLink").parent().removeClass("active");
           $("#rateFilesPageLink").parent().addClass("active");
       }
   }
});


