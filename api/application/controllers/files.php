<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("utils/UID.php");
require_once('utils/getid3-1.9/getid3/getid3.php');

/**
 * API controller for Files resource
 */
class Files extends CI_Controller {

    /**
    * Mapping of mime types to file types
    * @var Array 
    */
    private $docTypeMap = array(
        //IMAGE
        "image/bmp" => "image",
        "image/vnd.djvu" => "image",
        "image/gif" => "image",
        "image/x-icon" => "image",
        "image/ief" => "image",
        "image/jp2" => "image",
        "image/jpeg" => "image",
        "image/x-macpaint" => "image",
        "image/x-portable-bitmap" => "image",
        "image/pict" => "image",
        "image/x-portable-graymap" => "image",
        "image/png" => "image",
        "image/x-portable-anymap" => "image",
        "image/x-portable-pixmap" => "image",
        "image/x-quicktime" => "image",
        "image/x-cmu-raster" => "image",
        "image/x-rgb" => "image",
        "image/tiff" => "image",
        "image/vnd.wap.wbmp" => "image",
        "image/x-xbitmap" => "image",
        "image/x-xpixmap" => "image",
        "image/x-xwindowdump" => "image",
    );
    
    public function __construct(){
        parent::__construct();
        $this->load->model('files_mapper');
    }
    
    /**
    * Entry point for API requests made to this resource
    */
    public function index()
    {
       //Get resource
       //Get the API method specified
       if(isset($_POST["method"])){ $method = strtolower($_POST["method"]); }
       else{
           if(isset($_POST['photoDescription'])){ //Embarassing hack to get my file uploads to work with the API. TODO - Make this prettier later
               $this->uploadFile();
               return;
           }
           else{
              throw new Exception("Must specify an API method");  
           }
       } //Change this "Thrown exception" to be an HTTP error message

       //Get data sent to be passed to function call
       if(isset($_POST["params"])){ $params = $_POST["params"]; }
       else{ $params = ""; }

       //Determine which type of request we are fullfilling
       switch($method){
           case "get":
               $this->GET($params);
               break;
           case "post":
               $this->POST($params);
               break;
           case "put":
               $this->PUT($params);
               break;
           case "delete":
               $this->DELETE($params);
               break;
           default:
               throw new Exception("Invalid API method");
               break;
       }
    }

    /**
    * Function used for GET action on this resource
    * 
    * @param Array $params An array of the params required by this action
    */
    private function GET($params){
        if(isset($params['FileID'])){
            $file = $this->files_mapper->get_file($params['FileID']);
            echo json_encode($file);
            return;
        }
        else{
            $files = $this->files_mapper->get_files();
            echo json_encode($files);
            return;
        }
    }

    /**
    * Function used for POST action on this resource
    * 
    * @param Array $params An array of the params required by this action
    */
    private function POST($params){
       var_dump($_POST);
       var_dump($_FILES);
       die();
       $file = json_decode($params['file']);
       
       
       //throw new Exception("POST Request method not allowed on this resource");
    }

    /**
    * Function used for PUT action on this resource
    * 
    * @param Array $params An array of the params required by this action
    */
    private function PUT($params){
       throw new Exception("PUT Request method not allowed on this resource");
    }

    /**
    * Function used for DELETE action on this resource
    * 
    * @param Array $params An array of the params required by this action
    */
    private function DELETE($params){
       $file = json_decode($params['file']);
       $this->files_mapper->delete_file($file);
       
       echo json_encode("SUCCESS");
       
       //$count = $this->files_mapper->delete_file($params[])
    }
    
    /**
     * Script for uploading files
     * 
     * @return type 
     */
    private function uploadFile(){
        echo "<p>";

        if($_FILES['file']['error'] != 0){
            echo "FILE_UPLOAD_FAILED";
            echo "</p>";
            return;
        }

        //See if they're uploading the correct filetype (images only)
        $uploadedFileType = strtolower(substr(strrchr($_FILES['file']['name'],'.'),1));
        if(!$this->isValidFileType($uploadedFileType)){
            echo "INVALID_FILETYPE</p>";
            return;
        }

        $tmpFilePath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $mimeType = $_FILES['file']['type'];
        $fileSize = $_FILES['file']['size'];

        $description = $_POST['photoDescription'];

        //Create a guid for the filename
        $uid = GuidGenerator::Create();
        $fileId = GuidGenerator::toGuid($uid);

        $fileType = isset($this->docTypeMap[$mimeType]) ? $this->docTypeMap[$mimeType] : 'miscellaneous';

        //Set the file path for where the file will be stored (currently all sits in one folder, that will change as we add users)
        $fileStorageDir = STORAGE_PATH;
        $filePath = $fileStorageDir. "/" . $fileId;

        //Move file to the upload folder
        $moveSuccess = move_uploaded_file($tmpFilePath, $filePath);

        //Echo back to client about upload success
        if(!$moveSuccess){
            echo "UPLOAD_FAILED</p>";
            return;
        }

        $now = time();
        $owner = '757204282';

        //Add file to the database
        $file = array();
        $file['FileID'] = $fileId;
        $file['Name'] = $fileName;
        $file['OwnerID'] = $owner; //TODO - Replace this with real owner information
        $file['Created'] = $now;
        $file['CreatedBy'] = $owner;
        $file['Modified'] = $now;
        $file['ModifiedBy'] = $owner;
        $file['Description'] = $description;
        $file['MimeType'] = $mimeType;
        $file['Size'] = $fileSize;
        $file['Type'] = $fileType;
        $file['Width'] = -1;
        $file['Height'] = -1;
        $file['Duration'] = -1;

        /******************************************************************************/
        /************************ANALYZE WITH GETID3***********************************/
        /******************************************************************************/
        $getID3 = new getID3;
        $fileInfo = $getID3->analyze($filePath);

        if($fileType == 'image'){
            $file['Width'] = $fileInfo['video']['resolution_x'];
            $file['Height'] = $fileInfo['video']['resolution_y'];
        }
        else if($fileType == 'video'){
            $file['Width'] = $fileInfo['video']['resolution_x'];
            $file['Height'] = $fileInfo['video']['resolution_y'];
            $file['Duration'] = $fileInfo['playtime_seconds'];
        }
        else if($fileType == 'audio'){
            $file['Duration'] = $fileInfo['playtime_seconds'];
        }
        
        $result = $this->files_mapper->insert_file($file);
        
        //if($result != 1){
        //    echo "SAVE_TO_DATABASE_FAILED";
        //}
        //else{
        echo json_encode($this->files_mapper->get_file($file['FileID']));
        //}

        echo "</p>";
    }
    
    function isValidFileType($fileType){
        switch($fileType){
            case "jpg": 
                return true;
                break;
            case "jpeg": 
                return true;
                break;
            case "png": 
                return true;
                break;
            case "bmp": 
                return true;
                break;
            case "gif": 
                return true;
                break;
            default:
                return false;
                break;
        }
    }
}

/* Location: ./application/controllers/files.php */