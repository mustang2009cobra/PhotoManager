<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Photo Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A simple photo manager site">
    <meta name="author" content="David Woodruff">

    <!-- STYLES -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/photo-manager.css" rel="stylesheet">
    

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- FAVICON AND TOUCH ICONS -->
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
              <li class="active"><a href="#my-media/" id="myFilesPageLink">My Media</a></li>
              <li><a href="#rate-media/" id="rateFilesPageLink">Rate Media</a></li>
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
        <!-- PAGE CONTENT WILL BE RENDERED HERE -->
    </div><!--/.fluid-container-->
    
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- LIBRARIES -->
    <script src="lib/jquery-1.8.0.js"></script>
    <script src="lib/underscore.js"></script>
    <script src="lib/backbone.js"></script>
    <script src="lib/bootstrap-alert.js"></script>
    <script src="lib/bootstrap-modal.js"></script>
    <script src="lib/bootstrap-dropdown.js"></script>
    <script src="lib/bootstrap-scrollspy.js"></script>
    <script src="lib/bootstrap-tab.js"></script>
    <script src="lib/bootstrap-tooltip.js"></script>
    <script src="lib/bootstrap-popover.js"></script>
    <script src="lib/bootstrap-button.js"></script>
    <script src="lib/bootstrap-collapse.js"></script>
    <script src="lib/bootstrap-carousel.js"></script>
    <script src="lib/bootstrap-typeahead.js"></script>
    <script src="js/utils.js"></script>
    <!-- MODELS -->
    <script src="models/File.js"></script>
    <!-- COLLECTIONS -->
    <script src="collections/Files.js"></script>
    <!-- VIEWS -->
    <script src="views/AlertView.js"></script>
    <script src="views/MyMediaView.js"></script>
    <script src="views/RateMediaView.js"></script>
    <script src="views/FileView.js"></script>
    <script src="views/FilesView.js"></script>
    <!-- MAIN -->
    <script src="router.js"></script>
    <script src="main.js"></script>
    
    <script type="text/javascript">

        
        /**
         * Gets all files for a given user
         */ 
        function getFiles(){
            
        }
        
        /**
         * Function called when a file upload is complete
         */ 
        function photoUploadDone(){
            
        }
        
        /**
         * Shows an alert dialog at the top of the page.
         * Requires the following options to be passed in a JS object:
         *  -type: Determines which alert to show. Acceptable values: "info", "error", or "success"
         *  -message: A string value of the message to show in the alert
         */
        function showAlert(options){
            
            
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