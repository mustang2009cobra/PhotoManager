<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * API controller for Files resource
 */
class Templates extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('templates_mapper');
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
    * Function used for POST action on this resource
    * 
    * @param Array $params An array of the params required by this action
    */
    private function POST($params){
       throw new Exception("Request method not allowed on this resource");
    }

    /**
    * Function used for PUT action on this resource
    * 
    * @param Array $params An array of the params required by this action
    */
    private function PUT($params){
       throw new Exception("Request method not allowed on this resource");
    }

    /**
    * Function used for DELETE action on this resource
    * 
    * @param Array $params An array of the params required by this action
    */
    private function DELETE($params){
       throw new Exception("Request method not allowed on this resource");
    }
    
    private function GET($params){
        $templates = $this->templates_mapper->get_templates();
        echo json_encode($templates);
        return;
    }
}

/* Location: ./application/controllers/templates.php */
