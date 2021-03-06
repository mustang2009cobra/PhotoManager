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
            throw new Exception("Must specify an API method");  
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
               $this->POST();
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
        $files = $this->files_mapper->get_files();
        echo json_encode($files);
        return;
    }

    /**
    * Function used for POST action on this resource
    * 
    * @param Array $params An array of the params required by this action
    */
    private function POST(){
       //Is an upload
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

       if(isset($_POST['existingFileID'])){
           $fileId = $_POST['existingFileID'];
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
           
           //Add file to the database
           $updates = array(
               'Name' => $fileName,
               'Modified' => $now,
               'Description' => $description,
               'MimeType' => $mimeType,
               'Type' => $fileType,
               'Width' => -1,
               'Height' => -1
           );

           //Analyze with GetID3
           $getID3 = new getID3;
           $fileInfo = $getID3->analyze($filePath);

           if($fileType == 'image'){
               $updates['Width'] = $fileInfo['video']['resolution_x'];
               $updates['Height'] = $fileInfo['video']['resolution_y'];
           }
           
           $replaced = $this->files_mapper->replace_file($updates, $fileId);
           
           echo json_encode($replaced);
       }
       else{
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

            //Analyze with GetID3
            $getID3 = new getID3;
            $fileInfo = $getID3->analyze($filePath);

            if($fileType == 'image'){
                $file['Width'] = $fileInfo['video']['resolution_x'];
                $file['Height'] = $fileInfo['video']['resolution_y'];
            }

            $insertedFile = $this->files_mapper->insert_file($file);

            //if($result != 1){
            //    echo "SAVE_TO_DATABASE_FAILED";
            //}
            //else{
            echo json_encode($insertedFile);
            //}

            
       }
       echo "</p>";
    }

    /**
    * Function used for PUT action on this resource
    * 
    * @param Array $params An array of the params required by this action
    */
    private function PUT($params){
       $file = json_decode($params['file']);
       $updated = $this->files_mapper->update_file($file);
       
       echo json_encode($updated);
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