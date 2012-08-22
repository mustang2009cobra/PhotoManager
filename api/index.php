<?php

require_once("utils/DB.php");
/**
 * index.php
 *  Controller file for my custom API. It loosely tries to be RESTful (loosely because I have no idea about all the REST standards)
 *  -Note: This API is extremely thrown together. I should probably make the API cool using some framework at some point
 * 
 * @author David Woodruff <mustang2009cobra@gmail.com>
 * 
 * @copyright (C) 2012, David Woodruff
 * 
 * You may use, copy, modifiy, and distribute this file as desired as long as you
 *  give credit to the original authors.
 * 
 */
//Get the API method specified
if(isset($_POST["method"])){ $method = strtolower($_POST["method"]); }
else{ throw new Exception("Must specify an API method"); }

//Get the resource requested
if(isset($_POST["resource"])){ $resource = $_POST["resource"]; }
else{ throw new Exception("Must specify a resource"); }

//Get data sent to be passed to function call
if(isset($_POST["params"])){ $params = $_POST["params"]; }
else{ $params = ""; }

$api = new PhotoAPI(); //Create PhotoAPI instance

//Determine which type of request we are fullfilling
switch($method){
    case "get":
        $api->GET($resource, $params);
        break;
    case "post":
        $api->POST($resource, $params);
        break;
    case "put":
        $api->PUT($resource, $params);
        break;
    case "delete":
        $api->DELETE($resource, $params);
        break;
    default:
        throw new Exception("Invalid API method");
        break;
}

class PhotoAPI{
    private $db;
    
    public function __construct(){
        $this->db = new DB(); //Create the database interface
    }
    
    /**
     * Handling for API GET requests
     * 
     * @param string $resource The resource to GET
     * @param array $params Any params sent with the request
     */
    public function GET($resource, $params){
        switch($resource){
            case "templates": //Gets all templates at once
                $dirPath = '../views/templates/';
                if(is_dir($dirPath)) {
                    if($dh = opendir($dirPath)) {
                    while($file = readdir($dh)) {
                        if($file == '.' || $file == '..') { continue; }
                        //echo "filename: " . $file . " : filetype: " . filetype($dirPath . $file) . "<br />";
                        $templates[basename($dirPath . $file, ".html")] = file_get_contents($dirPath . $file);
                    }
                    closedir($dh);
                    }
                    echo json_encode($templates);
                }
                break;
            case "files":
                $this->getFiles($params);
                break;
            case "file":
                if(!isset($params['FileID'])){
                    throw new Exception("FileID required by files resource");
                }

                break;
            default:
                throw new Exception("Unsupported resource on GET");
                break;
        }
    }
    
    /**
     * Handling for API POST requests
     * 
     * @param string $resource The resource to POST
     * @param array $params Any params sent with the request
     */
    public function POST($resource, $params){
        switch($resource){
            default:
                throw new Exception("Unsupported resource on POST");
                break;
        }
    }
    
    /**
     * Handling for API PUT requests
     * 
     * @param string $resource The resource to PUT
     * @param array $params Any params sent with the request
     */
    public function PUT($resource, $params){
        switch($resource){
            default:
                throw new Exception("Unsupported resource on PUT");
                break;
        }
    }
    
    /**
     * Handling for API DELETE requests
     * 
     * @param string $resource The resource to DELETE
     * @param array $params Any params sent with the request
     */
    public function DELETE($resource, $params){
        switch($resource){
            case "file":
                $updates = array(
                    'Deleted' => time(),
                    'DeletedBy' => $params['currentUser']
                );
                $query = array(
                    'FileId' => $params['FileID']
                );
                
                $count = $this->db->update('files', $updates, $query);
                if($count != 1){
                    throw new Exception("File didn't get deleted");
                }
                break;
            default:
                throw new Exception("Unsupported resource on DELETE");
                break;
        }
    }
    
    
    /**************************************************************************/
    /*******************************PRIVATE FUNCTIONS**************************/
    /**************************************************************************/
    /**
     * Gets all files from the DB and returns them
     * 
     * @param Array $data Any data required by this function
     */
    private function getFiles($data){
        $files = $this->db->selectAllRows('files');
        
        echo json_encode($files);  
    }
}




?>
