<?php

/**
 * filesAjax.php
 *  Ajax file that contains functions called by UI ajax code
 * 
 * @author David Woodruff <mustang2009cobra@gmail.com>
 * 
 * @copyright (C) 2012, David Woodruff
 * 
 * You may use, copy, modifiy, and distribute this file as desired as long as you
 *  give credit to the original authors.
 * 
 */

//Get ajax function names to call (we require that all ajax calls be routed through a function)
if(isset($_POST["funcName"])){ $funcName = $_POST["funcName"]; }
else{ throw new Exception("Must specify a function to execute"); }

//Get data sent to be passed to function call
if(isset($_POST["callData"])){ $callData = $_POST["callData"]; }
else{ $callData = ""; }

//Call ajax function specified
switch($funcName){
    case "getFiles":
        getFiles($callData);
        break;
    case "getMyFilesTemplate":
        $filePath = '../views/templates/MyFiles.html';
        $template = file_get_contents($filePath);
        echo $template;
        break;
    case "getRateMediaTemplate":
        getRateMediaTemplate($callData);
        break;
    default:
        throw new Exception("Invalid function specified");
        break;
}

/******************************************************************************/
/*****************************AJAX PHOTOMANAGER FUNCTIONS**********************/
/******************************************************************************/
/**
 * Gets all files from the DB and returns them
 * 
 * @param Array $data Any data required by this function
 */
function getFiles($data){
    $model = array(
        'id' => "SOMEID",
        'name' => "SOMENAME",
        'description' => "SOMEDESCRIPTION",
        'dimensions' => "DIMENSIONS",
        'duration' => "DURATION",
        'modified' => "LAST MODIFIED"
    );
    
    echo json_encode($model);
}

/**
 * Returns the HTML template for the "Rate Media" page
 * 
 * @param Array $data Any data required by this function
 */
function getRateMediaTemplate($data){
    ?>
    
    <?php
}

/**
 * Returns the HTML template for the "My Files" page
 * 
 * @param Array $data Any data required by this function
 */
function getMyFilesTemplate($data){
    ?>
    
    <?php
}

?>