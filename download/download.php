<?php
require_once("../inc/defines.php");

if(!isset($_GET['file'])){
    throw new Exception("file must be sent");
}

if(json_decode($_GET['file']) != null){
    $file = json_decode($_GET['file']);
}

outputData($file);


function outputData($file) {
    header('Content-Description: File Transfer');

    //Eventually determine the mimeType and send that along as the Content-Type header
    if(isset($file->MimeType)){
        header("Content-Type: $file->MimeType");
    }else{
        header('Content-Type: application/octet-stream');
    }

    //Eventually once we store the filename, send that along so we don't download the file with the guid as the name
    header("Content-Disposition: filename=$file->Name");
    header('Content-Transfer-Encoding: binary');
    header('Expires:  0');
    header('Pragma: public');
    $filesize = filesize(STORAGE_PATH . '/' . $file->FileID);
    header('Content-Length:'.$filesize);

    ob_flush();
    flush();

    readfile_chunked_remote(STORAGE_PATH . '/'. $file->FileID);
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
