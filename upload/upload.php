<?php
require_once("../inc/defines.php");
require_once("../util/UID.php");
require_once("../models/file/FileModel.php");

/*
 * Script here for uploading the file
 */
echo "<p>";

if($_FILES['file']['error'] != 0){
	return "FILE_UPLOAD_FAILED";
	echo "</p>";
}

$tmpFilePath = $_FILES['file']['tmp_name'];
$fileName = $_FILES['file']['name'];
$mimeType = $_FILES['file']['type'];
$fileSize = $_FILES['file']['size'];

$description = $_POST['photoDescription'];

//Create a guid for the filename
$uid = GuidGenerator::Create();
$fileId = GuidGenerator::toGuid($uid);

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

//Create file model
$file = new FileModel();
$file->setName($fileName);
$file->setDescription($description);
$file->setCreated($now);
$file->setModified($now);
$file->setMimeType($mimeType);
$file->setSize($fileSize);

//Set ownerid and createdby and modifiedby once we get users up and running
//Use GetID3 to find duration, height, and width

//Save to database
$saveSuccess = $file->save();

if($saveSuccess){
    echo "UPLOAD_COMPLETE";
}
else{
    unset($filePath); //Delete stored file that was there
    echo "SAVE_TO_DATABASE_FAILED";
}


echo "</p>";
?>
