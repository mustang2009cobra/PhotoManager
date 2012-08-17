<?php

require_once("../Mapper.php");
require_once("../Model.php");

/**
 * Model for Files
 *
 * @author dsw88
 */
class FileModel extends Model {
    
    /**
     * Actual filename for the file
     * @var String
     */
    protected $name;
    
    /**
     * Parent ID of the file (for when folders are implemented)
     * @var string
     */
    protected $parentID;
    
    /**
     * Owner ID of the file (who created it)
     * @var string 
     */
    protected $ownerID;
    
    /**
     * Whether the entry is a folder 
     * @var boolean
     */
    protected $folder;
    
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
    
    
}

?>
