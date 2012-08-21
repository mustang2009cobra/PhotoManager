<?php

//require_once("../Mapper.php");
//require_once("../Model.php");

/**
 * Model for Files
 *
 * @author dsw88
 */
class FileModel {
     
    /**
     * Actual filename for the file
     * @var String 
     */
    protected $name;
    
    /**
     * Owner ID of the file (who created it)
     * @var string 
     */
    protected $ownerID;
    
    /**
     * Description for the file
     * @var string
     */
    protected $description;
    
    /**
     * MimeType of the file
     * @var string
     */
    protected $mimeType;
    
    /**
     * Type of the file
     * @var string
     */
    protected $type;
    
    /**
     * Size of the file
     * @var int
     */
    protected $size;
    
    /**
     * Width of the file (if applicable)
     * @var int
     */
    protected $width;
    
    /**
     * Height of the file (if applicable)
     * @var int
     */
    protected $height;
    
    /**
     * Duration of the file in seconds (if applicable)
     * @var int
     */
    protected $duration;
    
    /**
     * Unix timestamp for when the file was created
     * @var int
     */
    protected $created;
    
    /**
     * Id of the person who created the file
     * @var string
     */
    protected $createdBy;
    
    /**
     * Unix timestamp for when the file was last modified
     * @var int
     */
    protected $modified;
    
    /**
     * Id of the person who last modified the file
     * @var string
     */
    protected $modifiedBy;
    
    /**
     * Unix time stamp for when the file was deleted
     * @var int
     */
    protected $deleted;
    
    /**
     * Id of the person who deleted the file
     * @var string
     */
    protected $deletedBy;
    
    public function setName($name){
		$this->name = $name;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setOwnerID($ownerID){
		$this->ownerID = $ownerID;
	}
	
	public function getOwnerID(){
		return $this->ownerID;
	}
	
	public function setDescription($description){
		$this->description = $description;
	}
	
	public function getDescription(){
		return $this->description;
	}
	
	public function setMimeType($mimeType){
		$this->mimeType = $mimeType;
	}
	
	public function getMimeType(){
		return $this->mimeType;
	}
	
	public function setType($type){
		$this->type = $type;
	}
	
	public function getType(){
		return $this->type;
	}
	
	public function setSize($size){
		$this->size = $size;
	}
	
	public function getSize(){
		return $this->size;
	}
	
	public function setWidth($width){
		$this->width = $width;
	}
	
	public function getWidth(){
		return $this->width;
	}
	
	public function setHeight($height){
		$this->height = $height;
	}
	
	public function getHeight(){
		return $this->height;
	}
	
	public function setDuration($duration){
		$this->duration = $duration;
	}
	
	public function getDuration(){
		return $this->duration;
	}
	
	public function setCreated($created){
		$this->created = $created;
	}
	
	public function getCreated(){
		return $this->created;
	}
	
	public function setCreatedBy($createdBy){
		$this->createdBy = $createdBy;
	}
	
	public function getCreatedBy(){
		return $this->createdBy;
	}
	
	public function setModified($modified){
		$this->modified = $modified;
	}
	
	public function getModified(){
		return $this->modified;
	}
	
	public function setModifiedBy($modifiedBy){
		$this->modifiedBy = $modifiedBy;
	}
	
	public function getModifiedBy(){
		return $this->modifiedBy;
	}
	
	public function setDeleted($deleted){
		$this->deleted = $deleted;
	}
	
	public function getDeleted(){
		return $this->deleted;
	}
	
	public function setDeletedBy($deletedBy){
		$this->deletedBy = $deletedBy;
	}
	
	public function getDeletedBy(){
		return $this->deletedBy;
	}
	
	public function save(){
		if(isset($this->id)){ //Update existing file
			
		}
		else{ //New file
			
		}
	}
	
	public function remove(){
		
	}
	
	public static function find(){
		
	}
}

?>
