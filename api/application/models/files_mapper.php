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
    
    public function get_file($FileID){
        $query = $this->db->get_where('files', array('FileID' => $FileID));
        return $query->result_array();
    }
    
    /**
     * Deletes the given file from the database and unsets the actual file in the storage folder
     * 
     * @param type $file 
     */
    public function delete_file($file){
        //Delete file from database
        $command = $this->db->delete('files', array('FileID' => $file->FileID));
        
        //Unset file
        unlink(STORAGE_PATH . "/" . $file->FileID);
    }

    public function insert_file($file){
        //Insert file into database
        $command = $this->db->insert('files', $file);
        return $command;
    }
}

?>
