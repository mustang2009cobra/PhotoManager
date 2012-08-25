<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of filesmapper
 *
 * @author David
 */
class Files_mapper extends CI_Model {
    
    public function __construct(){
        $this->load->database();
    }
    
    /**
     * Gets all files from the database
     * 
     * @param type $slug
     * @return type
     */
    public function get_files(){
        $query = $this->db->get('files');
        return $query->result_array();
    }
}

?>
