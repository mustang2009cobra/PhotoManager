<?php

require_once("../util/UID.php");
define("STORAGE_PATH", "C:/wamp/www/localstorage/photoManagerFiles");

/*
 * Script here for uploading the file
 */
echo "<p>";

$tmpFilePath = $_FILES['file']['tmp_name'];


//Create a guid for the filename
$uid = GuidGenerator::Create();
$fileId = GuidGenerator::toGuid($uid);

//Set the file path for where the file will be stored (currently all sits in one folder, that will change as we add users)
$fileStorageDir = STORAGE_PATH;
$filePath = $fileStorageDir. "/" . $fileId;

//Move file to the upload folder
$success = move_uploaded_file($tmpFilePath, $filePath);

//Echo back to client about upload success
if($success){
    echo "UPLOAD_COMPLETE";
}
else{
    echo "UPLOAD_FAILED";
}

echo "</p>";
?>
