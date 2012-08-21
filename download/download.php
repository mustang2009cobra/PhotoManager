<?php
require_once("../inc/defines.php");

if(!isset($_GET['fileId'])){
    throw new Exception("fileId GET param must be sent");
}

if(json_decode($_GET['fileId']) != null){
    $id = json_decode($_GET['fileId']);
}else{
    $id = $_GET['fileId'];
}

outputData($id);


function outputData($fileId) {
    header('Content-Description: File Transfer');

    //Eventually determine the mimeType and send that along as the Content-Type header
    header('Content-Type: application/octet-stream');

    //Eventually once we store the filename, send that along so we don't download the file with the guid as the name
    //header('Content-Disposition: filename="'.$this->name.'"');
    header('Content-Transfer-Encoding: binary');
    header('Expires:  0');
    header('Pragma: public');
    $filesize = filesize(STORAGE_PATH . '/' . $fileId);
    header('Content-Length:'.$filesize);

    ob_flush();
    flush();

    readfile_chunked_remote(STORAGE_PATH . '/'. $fileId);
}

function readfile_chunked_remote($filename, $seek = 0, $retbytes = true, $timeout = 3) { 
    set_time_limit(0); 
    $defaultchunksize = 1024*1024; 
    $chunksize = $defaultchunksize; 
    $buffer = ''; 
    $cnt = 0; 
    $remotereadfile = false; 

    if (preg_match('/[a-zA-Z]+:\/\//', $filename)) 
        $remotereadfile = true; 

    $handle = @fopen($filename, 'rb'); 

    if ($handle === false) { 
        return false; 
    } 

    stream_set_timeout($handle, $timeout); 

    if ($seek != 0 && !$remotereadfile) 
        fseek($handle, $seek); 

    while (!feof($handle)) { 

        if ($remotereadfile && $seek != 0 && $cnt+$chunksize > $seek) 
            $chunksize = $seek-$cnt; 
        else 
            $chunksize = $defaultchunksize; 

        $buffer = @fread($handle, $chunksize); 

        if ($retbytes || ($remotereadfile && $seek != 0)) { 
            $cnt += strlen($buffer); 
        } 

        if (!$remotereadfile || ($remotereadfile && $cnt > $seek)) 
            echo $buffer; 

        ob_flush(); 
        flush(); 
    } 

    $info = stream_get_meta_data($handle); 

    $status = fclose($handle); 

    if ($info['timed_out']) 
        return false; 

    if ($retbytes && $status) { 
        return $cnt; 
    } 

    return $status; 
}

?>
