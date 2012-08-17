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
        
    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    
    <!-- Template for application alert dialogs -->
    <script type="text/template" class="alertTemplate">
        <div class="alert alert-<%= type %>">
            <a class="close" data-dismiss="alert">x</a>
            <h4><%= header %></h4>
            <p><%= message %></p>
        </div>
    </script>
    
    <script type="text/template" class="rateFilesTemplate">
        Hello World!
    </script>
    
    <script type="text/template" class="myFilesTemplate">
        <div class="row-fluid">
            <div class="btn-group">
                <button class="btn btn-primary addFile">Add File</button>
                <!-- <button class="btn btn-primary addFolder">Add Folder</button> -->
            </div>
        </div><!--/row-->
        <div class="row-fluid">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            Name
                        </th>
                        <th>
                            Description
                        </th>
                        <th>
                            Dimensions
                        </th>
                        <th>
                            Duration
                        </th>
                        <th>
                            Modified
                        </th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>

        <!-------------------------------------------------------------------------->
        <!----------------------------------MODALS---------------------------------->
        <!-------------------------------------------------------------------------->
        <div class="modal hide" id="uploadImageDialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>Upload File</h3>
            </div>
            <div class="modal-body">
                <form id="file_upload_form" method="post" enctype="multipart/form-data" action="upload/photoUpload.php"
                    <p>
                        <label id="photoInput_label" for="photoInput" class=" ">Select a file</label>
                        <input class="photoInput" name="file" type="file" />
                    </p>
                    <p>
                        <textarea id="photoDescription" rows="5" cols="10" name="photoDescription"></textarea>
                    </p>
                    <!--<input type="hidden" id="enrollmentidFormField" name="enrollmentid" />-->
                    <input id="photo-upload-submit-btn" class="hidden" type="submit" value="photoUploadSubmit">
                    <iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn" data-dismiss="modal">Close</a>
                <a id="uploadImageButton" class="btn btn-primary">Upload</a>
            </div>
        </div>
        
        <hr>

        <footer>
            <p>&copy; FineWoods 2012</p>
        </footer>
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
         * Compiles the "Rate Media" html template, then ajaxes all the data for the screen
         */
        function renderRateMedia(){
            var rateFilesOptions = {}; //No options yet, maybe later
            
            //Get html template and wrap in underscores template
            var compiledRateFilesPage = _.template(
                $("script.rateFilesTemplate").html()
            );
            
            //Compile template using options that came in
            $("#mainContent").html(compiledRateFilesPage(rateFilesOptions));
        }
        
        /**
         * Compiles the "My Files
         */
        function renderMyFiles(){
            var myFilesOptions = {}; //No options yet, maybe later
            
            //Get html template and wrap in underscores template
            var compiledMyFilesPage = _.template(
                $("script.myFilesTemplate").html()
            );
            
            //Compile template using options that came in
            $("#mainContent").html(compiledMyFilesPage(myFilesOptions));
            
            $(".addFile").click(function(){
               $("#uploadImageDialog").modal(); 
            });
        }
        
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