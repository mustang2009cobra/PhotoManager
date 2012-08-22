<?php
require_once("../inc/defines.php");
require_once("../util/UID.php");
require_once('../api/utils/DB.php');

/*
 * Script here for uploading the file
 */
echo "<p>";

if($_FILES['file']['error'] != 0){
	echo "FILE_UPLOAD_FAILED";
        echo $_FILES['file']['error'];
	echo "</p>";
        return;
}

$tmpFilePath = $_FILES['file']['tmp_name'];
$fileName = $_FILES['file']['name'];
$mimeType = $_FILES['file']['type'];
$fileSize = $_FILES['file']['size'];

$description = $_POST['photoDescription'];

//Create a guid for the filename
$uid = GuidGenerator::Create();
$fileId = GuidGenerator::toGuid($uid);

$fileType = getFileType($mimeType);

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

//TODO - Analyze file with GetID3 to get file width, height, and duration

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

$dbh = new DB();
$result = $dbh->insert('files', $file);

if($result != 1){
    echo "SAVE_TO_DATABASE_FAILED";
}
else{
    echo "UPLOAD_COMPLETE";
}

echo "</p>";

function getFileType($mimeType){
    return "miscellaneous"; //Implement later
}
?>
