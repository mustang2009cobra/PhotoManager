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
        
        $query = $this->db->get_where('files', array('FileID' => $file['FileID']));
        $insertedFile = $query->result_array();
        return $insertedFile;
    }
    
    public function update_file($file){
        $data = array(
            'Description' => $file->Description,
            'Name' => $file->Name,
            'Modified' => time()
        );
        
        $this->db->where('FileID', $file->FileID);
        $this->db->update('files', $data);
        
        $query = $this->db->get_where('files', array('FileID' => $file->FileID));
        $updatedFile = $query->result_array();
        return $updatedFile;
    }
    
    public function replace_file($updates, $FileID){
        $this->db->where('FileID', $FileID);
        $this->db->update('files', $updates);
        
        $query = $this->db->get_where('files', array('FileID' => $FileID));
        $replacedFile = $query->result_array();
        return $replacedFile;
    }
}

?>
