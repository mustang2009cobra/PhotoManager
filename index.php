<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Photo Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A simple photo manager site">
    <meta name="author" content="David Woodruff">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/photo-manager.css" rel="stylesheet">
    <style type="text/css">
      
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <!-------------------------------------------------------------------------->
    <!-------------------------------HEADER BAR--------------------------------->
    <!-------------------------------------------------------------------------->
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Photo Manager</a>
          <div class="btn-group pull-right">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i> dsw88
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="#">Profile</a></li>
              <li class="divider"></li>
              <li><a href="#">Sign Out</a></li>
            </ul>
          </div>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a id="myFilesPageLink">My Media</a></li>
              <li><a id="rateFilesPageLink">Rate Media</a></li>
            </ul>
          </div>
          <!--/.nav-collapse -->
        </div>
      </div>
    </div>
      
    <!-------------------------------------------------------------------------->
    <!----------------------------------ALERTS---------------------------------->
    <!-------------------------------------------------------------------------->
    <div class="container-fluid">
        <div id="alertArea" class="row-fluid">
            <!--Alerts are rendered using JS templating-->
        </div>
    </div>
    
    
    <!-------------------------------------------------------------------------->
    <!--------------------------MAIN PAGE CONTENT------------------------------->
    <!-------------------------------------------------------------------------->
    <div id="mainContent" class="container-fluid">
        <!-- PAGE CONTENT WILL BE RENDERED HERE, VIA TEMPLATES FROM AJAX CALLS -->
    </div><!--/.fluid-container-->
    
    <!-- Template for application alert dialogs -->
    <script type="text/template" class="alertTemplate">
        <div class="alert alert-<%= type %>">
            <a class="close" data-dismiss="alert">x</a>
            <h4><%= header %></h4>
            <p><%= message %></p>
        </div>
    </script>
    
    <script type="text/template" id="fileTemplate">
        <tr>
            <td class="fileName"><%= name %></td>
            <td class="fileDescription"><%= description %></td>
            <td class="fileDimensions"><%= dimensions %></td>
            <td class="fileDuration"><%= duration %></td>
            <td class="fileModified"><%= modified %></td>
        </tr>
    </script>
    
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.8.0.js"></script>
    <script src="js/underscore.js"></script>
    <script src="js/backbone.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>
    <script type="text/javascript">
        
        var PHOTO_MANAGER = { }; //JS namespace for photo manager application
        
        /******************BACKBONE MODEL/COLLECTION DEFINES*******************/
        var File = Backbone.Model.extend({
            initialize: function(){ //Maybe don't need anything here
                this.bind("change:name", function(){
                    alert("NAME CHANGED! Should save here or something")
                });
            },
            validate: function( attributes ){
                //Any validation code goes here before things are saved or set
            }
        });
        
        var Files = Backbone.Collection.extend({
            model: File
        });
        
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
        
        var FilesView = Backbone.View.extend({
           el: $("#filesTableBody"),
           
           initialize: function(files){ //When created, the collection passed in will be the collection for the Files View
               
               this.collection = new Files(files);
               console.log(this.collection);
               this.render();
           },
           
           render: function(){
               var that = this;
               _.each(this.collection.models, function(file){ //For each model in the collection:
                   that.renderFile(file); //Render the file element
               }, this);
           },
           
           renderFile: function(file){
               var fileView = new FileView({ //Create new FileView, passing the file as its model
                   model: file
               });
               this.$el.append(fileView.render().el); //Apend rendered file to the files table
           }
           
        });
        
        
        
        /*****************************JS FUNCTIONS*****************************/
        
        $(document).ready(function(){
            renderMyFiles();
            
            $('#uploadImageButton').live('click', function(){
                //Hide the submit button so they can't click twice
                document.getElementById('file_upload_form').target = 'upload_target';
                document.forms['file_upload_form'].submit();
                document.getElementById("upload_target").onload = photoUploadDone;
            });
            
            $("#rateFilesPageLink").live('click', function(){
                $("#rateFilesPageLink").parent().addClass('active');
                $("#myFilesPageLink").parent().removeClass('active');
                renderRateMedia();
            });
            
            $("#myFilesPageLink").live('click', function(){
                $("#rateFilesPageLink").parent().removeClass('active');
                $("#myFilesPageLink").parent().addClass('active');
                renderMyFiles();
            });
        });
        
        /**
         * Compiles the "Rate Media" html template, gotten via ajax call
         */
        function renderRateMedia(){
            //Get the HTML template
            $.post("api/", //Url
                { //Data
                    resource : "rateMediaTemplate"
                },
                function(data){ //On success
                    var rateFilesOptions = {}; //No options yet, maybe later
                    
                    //Wrap template in underscore templating function
                    var compiledRateFilesPage = _.template(data);
                    
                    //Compile template using options
                    $("#mainContent").html(compiledRateFilesPage(rateFilesOptions));
                }
            );
        }
        
        /**
         * Compiles the "My Files html template, gotten via ajax call
         */
        function renderMyFiles(){
            //Get the HTML template
            $.post("api/", //Url
                { //Data
                    resource : "myFilesTemplate"
                },
                function(data){ //On success
                    var myFilesOptions = {}; //No options yet, maybe later
                    
                    //Wrap template in underscore templating function
                    var compiledMyFilesPage = _.template(data);
                    
                    //Compile template using options
                    $("#mainContent").html(compiledMyFilesPage(myFilesOptions));
                    
                    $(".addFile").click(function(){
                        $("#uploadImageDialog").modal(); 
                    });
                    
                    getFiles();
                }
            );
        }
        
        /**
         * Gets all files for a given user
         */ 
        function getFiles(){
            $.post("api/",
                {
                    resource: "files"
                },
                function(data){
                    var files = $.parseJSON(data);
                    
                    var file = files;
                    
                    var array = [ file ];
                    
                    
                    var filesView = new FilesView(array);
                    
                    //Hide loader
                    $("#ajaxLoadingBar").remove();
                }
            );
            
            
        }
        
        /**
         * Function called when a file upload is complete
         */ 
        function photoUploadDone(){
            var ret = frames['upload_target'].document.getElementsByTagName("p")[0].innerHTML;
            
            document.getElementById('file_upload_form').reset();
            
            if(ret == "UPLOAD_COMPLETE"){
                showAlert({type:"success", message:"The file was uploaded successfully."});
            }
            else{
                showAlert({type:"error", message:"There was an error uploading the file."});
            }
            
            //Replace the loader with the submit button
            
            //Close the modal window
            $('#uploadImageDialog').modal('hide');
        }
        
        /**
         * Shows an alert dialog at the top of the page.
         * Requires the following options to be passed in a JS object:
         *  -type: Determines which alert to show. Acceptable values: "info", "error", or "success"
         *  -message: A string value of the message to show in the alert
         */
        function showAlert(options){
            if(options.type == 'error'){
                options.header = "Error";
            }
            else if(options.type == 'success'){
                options.header = "Success";
            }
            else{
                options.header = "Info";
            }
            
            //Get html template and wrap in underscores template
            var compiledAlert = _.template(
                $("script.alertTemplate").html()
            );
            
            //Compile template using options that came in
            $("#alertArea").html(compiledAlert(options));
        }
        
    </script>

  </body>
</html>