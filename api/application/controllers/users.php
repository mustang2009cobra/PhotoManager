<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * API controller for Files resource
 */
class Files extends CI_Controller {
    
    public function __construct(){
        //session_start() //STARTS THE PHP SESSION
        
        //TO LOG OUT, call session_destroy(), then reload the HOME page
        //Have a verifyAuthentication() function before every data call (put an isset($_SESSION['username']) to see)
        
        parent::__construct();
        $this->load->model('users_mapper');
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
        //USED FOR AUTHENTICATING EXISTING USERS

        /*If ($q->num_rows > 0){ //If the user was authenticated
            return $q->row(); //Return (Throw it in the session somewhere?)
         * $_SESSION['username'] = username; //Sets session variable
         * }else{
         *  return "ACCESS DENIED"
         * }
         */
    }

    /**
    * Function used for POST action on this resource
    * 
    * @param Array $params An array of the params required by this action
    */
    private function POST(){
       //USED FOR ADDING USERS TO DB
    }
    
    /**
    * Function used for PUT action on this resource
    * 
    * @param Array $params An array of the params required by this action
    */
    private function PUT($params){
        //USED FOR UPDATING USER INFORMATION
    }

    /**
    * Function used for DELETE action on this resource
    * 
    * @param Array $params An array of the params required by this action
    */
    private function DELETE($params){
       //USED FOR DELETING USER INFORMATION
    }
}