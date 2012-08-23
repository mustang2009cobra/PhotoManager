<?php
require_once("../inc/defines.php");
require_once("../util/UID.php");
require_once('../api/utils/DB.php');
require_once('../plugins/getid3-1.9/getid3/getid3.php');

/**
 * Mapping of certain mime types to others that we will store instead of the original type
 * @var Array
 */
$mimeTypeMap = array(
    //AUDIO
    "audio/mp3" => "audio/mp3",
    "audio/x-aiff" => "audio/aiff",
    "audio/aiff" => "audio/aiff",
    "audio/mp4a-latm" => "audio",
    "audio/mpeg" => "audio/mp3",
    "audio/x-wav" => "audio/wav",
    "audio/wav" => "audio/wav",
    //VIDEO
    "video/avi" => "video/avi",
    "video/x-m4v" => "video/mp4",
    "video/mp4" => "video/mp4",
    "video/mpeg" => "video/mpeg",
    "video/x-flv" => "video/x-flv",
    "video/mov" => "video/mov",
    "video/wmv" => "video/wmv",
    //FLASH
    "application/x-shockwave-flash" => 'application/x-shockwave-flash'

);

/**
 * Mapping of mime types to file types
 * @var Array 
 */
$docTypeMap = array(
    //DOCUMENT
    "application/msword" => "document",
    "application/mswrite" => "document",
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document" => "document",
    "application/vnd.oasis.opendocument.text" => "document",
    "application/pdf" => "document",
    "application/rtf" => "document",
    "text/plain" => "document",
    "application/wordperfect6.0" => "document",
    "application/wordperfect6.1" => "document",
    "application/wordperfect" => "document",
    "text/x-setext" => "document",
    "text/html" => "document",
    "text/htm" => "document",
    "text/css" => "document",
    "text/rtf" => "document",
    "text/richtext" => "document",
    "text/sgml" => "document",
    "text/tab-separated-values" => "document",
    "text/vnd.wap.wmlscript" => "document",
    //SPREADSHEET
    "application/vnd.ms-excel" => "spreadsheet",
    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" => "spreadsheet",
    "application/vnd.oasis.opendocument.spreadsheet" => "spreadsheet",
    //PRESENTATION
    "application/vnd.ms-powerpoint" => "presentation",
    "application/vnd.openxmlformats-officedocument.presentationml.presentation" => "presentation",
    //AUDIO
    "audio/mp3" => "audio",
    "audio/x-aiff" => "audio",
    "audio/aiff" => "audio",
    "audio/basic" => "audio",
    "audio/midi" => "audio",
    "audio/x-mpegurl" => "audio",
    "audio/mp4a-latm" => "audio",
    "audio/mpeg" => "audio",
    "audio/x-pn-realaudio" => "audio",
    "audio/basic" => "audio",
    "audio/x-wav" => "audio",
            "audio/wav" => "audio",
    //VIDEO
    "video/avi" => "video",
    "video/x-msvideo" => "video",
    "video/x-ms-wmv" => "video",
    "video/x-dv" => "video",
    "video/x-m4v" => "video",
    "video/vnd.mpegurl" => "video",
    "video/x-sgi-movie" => "video",
    "video/mp4" => "video",
    "video/mpeg" => "video",
    "video/vnd.mpegurl" => "video",
    "video/quicktime" => "video",
    "video/x-flv" => "video",
    "video/mov" => "video",
    "video/wmv" => "video",
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
    //FLASH
    "application/x-shockwave-flash" => 'flash'
);

/******************************************************************************/
/*****************************FILE UPLOAD SCRIPT*******************************/
/******************************************************************************/
echo "<p>";

if($_FILES['file']['error'] != 0){
    echo "FILE_UPLOAD_FAILED";
    echo "</p>";
    return;
}

$tmpFilePath = $_FILES['file']['tmp_name'];
$fileName = $_FILES['file']['name'];
$mimeType = isset($mimeTypeMap[$_FILES['file']['type']]) ? $mimeTypeMap[$_FILES['file']['type']] : $_FILES['file']['type'];
$fileSize = $_FILES['file']['size'];

$description = $_POST['photoDescription'];

//Create a guid for the filename
$uid = GuidGenerator::Create();
$fileId = GuidGenerator::toGuid($uid);

$fileType = isset($docTypeMap[$mimeType]) ? $docTypeMap[$mimeType] : 'miscellaneous';

//Set the file path for where the file will be stored (currently all sits in one folder, that will change as we add users)
$fileStorageDir = STORAGE_PATH;
$filePath = $fileStorageDir. "/" . $fileId;

//Move file to the upload folder
$moveSuccess = move_uploaded_file($tmpFilePath, $filePath);

//Echo back to client about upload success
if(!$moveSuccess){
    echo "UPLOAD_FAILED</p>";
    return;
}

$now = time();
$owner = '757204282';

//Add file to the database
$file = array();
$file['FileID'] = $fileId;
$file['Name'] = $fileName;
$file['OwnerID'] = $owner; //TODO - Replace this with real owner information
$file['Created'] = $now;
$file['CreatedBy'] = $owner;
$file['Modified'] = $now;
$file['ModifiedBy'] = $owner;
$file['Description'] = $description;
$file['MimeType'] = $mimeType;
$file['Size'] = $fileSize;
$file['Type'] = $fileType;
$file['Width'] = -1;
$file['Height'] = -1;
$file['Duration'] = -1;

/******************************************************************************/
/************************ANALYZE WITH GETID3***********************************/
/******************************************************************************/
$getID3 = new getID3;
$fileInfo = $getID3->analyze($filePath);

if($fileType == 'image'){
    $file['Width'] = $fileInfo['video']['resolution_x'];
    $file['Height'] = $fileInfo['video']['resolution_y'];
}
else if($fileType == 'video'){
    $file['Width'] = $fileInfo['video']['resolution_x'];
    $file['Height'] = $fileInfo['video']['resolution_y'];
    $file['Duration'] = $fileInfo['playtime_seconds'];
}
else if($fileType == 'audio'){
    $file['Duration'] = $fileInfo['playtime_seconds'];
}

$dbh = new DB();
$result = $dbh->insert('files', $file);

if($result != 1){
    echo "SAVE_TO_DATABASE_FAILED";
}
else{
    echo json_encode($dbh->selectRows('files', array('FileID' => $file['FileID'])));
}

echo "</p>";

/******************************************************************************/
/***************************SCRIPT FUNCTIONS***********************************/
/******************************************************************************/



?>
