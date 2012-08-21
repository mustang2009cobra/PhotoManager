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
        getMyFilesTemplate($callData);
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
    Hello World!
    
    <hr>

    <footer>
        <p>&copy; FineWoods 2012</p>
    </footer>
    <?php
}

/**
 * Returns the HTML template for the "My Files" page
 * 
 * @param Array $data Any data required by this function
 */
function getMyFilesTemplate($data){
    ?>
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
            <tbody id="filesTableBody">
            </tbody>
        </table>
        <div id="ajaxLoadingBar" class="span3">
            <p>Loading...</p>
            <div class="progress progress-striped active">
                <div class="bar" style="width: 100%;"></div>
            </div>
        </div>
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
    <?php
}

?>
