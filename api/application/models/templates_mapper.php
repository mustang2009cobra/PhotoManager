<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of templatesmapper
 *
 * @author David
 */
class Templates_mapper extends CI_Model {
   
    public function __construct(){
        //$this->load->database();
    }
    
    public function get_templates(){
        $dirPath = 'application/views/';
        if(is_dir($dirPath)) {
            if($dh = opendir($dirPath)) {
            while($file = readdir($dh)) {
                if($file == '.' || $file == '..') { continue; }
                //echo "filename: " . $file . " : filetype: " . filetype($dirPath . $file) . "<br />";
                $templates[basename($dirPath . $file, ".html")] = file_get_contents($dirPath . $file);
            }
            closedir($dh);
            }
            return $templates;
        }
        else{
            throw new Exception("Templates directory not found");
        }
    }
}

?>
