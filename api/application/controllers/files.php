<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * API controller for Files resource
 */
class Files extends CI_Controller {
    
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
       else{ throw new Exception("Must specify an API method"); } //Change this "Thrown exception" to be an HTTP error message

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
        $files = $this->files_mapper->get_files();
        echo json_encode($files);
        return;
    }

    /**
    * Function used for POST action on this resource
    * 
    * @param Array $params An array of the params required by this action
    */
    private function POST($params){
       throw new Exception("POST Request method not allowed on this resource");
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
}

/* Location: ./application/controllers/files.php */