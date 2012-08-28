<?php
define("STORAGE_PATH", "C:/wamp/www/localstorage/photoManagerFiles");

if(!isset($_GET['file'])){
    throw new Exception("file must be sent");
}

if(json_decode($_GET['file']) != null){
    $file = json_decode($_GET['file']);
}

if(isset($file->downloadType) && $file->downloadType == "stream"){
    GET($file);
}
else{
    outputData($file);
}


function outputData($file) {
    header('Content-Description: File Transfer');

    //Eventually determine the mimeType and send that along as the Content-Type header
    if(isset($file->MimeType)){
        header("Content-Type: $file->MimeType");
    }else{
        header('Content-Type: application/octet-stream');
    }

    //Eventually once we store the filename, send that along so we don't download the file with the guid as the name
    header("Content-Disposition: attachment; filename=$file->Name");
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

/****************************************************************************/
/*************************STREAMING MEDIA FUNCTIONS**************************/
/****************************************************************************/

/*Description: Gets the file in the URI
  Author: Alberto Trevino
  Last revised: 2010-08-11
  Arguments: NONE
  Returns: NONE
*/
function GET($fileObj)
{
    /*SCRIPT VARIABLE DEFINITIONS*/
    $Method = "GET"; # The HTTP method used to make the call
    $URI = '/'. $fileObj->FileID; # The requested URI
    $LocalURI = '/'. $fileObj->FileID;;
    $Action = "GET"; # What we will be doing
    $SSL = false; # Whether we are using SSL

    # User variables
    $NetID = null; # The user's Net ID (Not utilized currently)
    $PersonID = null; # The user's Person ID (Not utilized currently)
    $ProxyName = null; # The name of the proxy agent (Not utilized currently)

    # Storage path
    $BasePath = STORAGE_PATH;

    # Download rate control settings
    $RateControl = false; # Whether to use rate control or not
    $RateSizeLimit = 1048576; # Minimum size for applying rate ctr
    $TransferRate = 2000000; # Rate in bits/sec
    $BlockSize = 4096; # Blocksize in bytes
    $RateAdjustment = 0.52; # Due to many factors, the rate
    
    # See if we are getting a file
    if (is_file($BasePath . $LocalURI))
    {
        # Get the mime type
        # TODO: remove use of deprecated function
        $mime_type = $fileObj->MimeType;

        # Get the size of the file
        $filesize = filesize($BasePath . $LocalURI);

        # See if we have a byte range
        $ranges = array();
        if (array_key_exists("HTTP_RANGE", $_SERVER))
        {
            # Parse the range header
            try
            {
                $ranges = ParseRange($_SERVER["HTTP_RANGE"],
                    $filesize);
            }
            catch (exception $e)
            {

                # Send the appropriate error
                HTTPResponse(416);
            }
        }

        # See how many bytes we will be transfering
        if (count($ranges) == 0)
        {
            $transfer_size = $filesize;
        }
        else
        {
            $transfer_size = 0;
            foreach($ranges as $range)
            {
                $transfer_size += $range["End"] - $range["Begin"] + 1;
            }
        }

        # See if rate control will apply
        $rate_control = $RateControl;
        if ($transfer_size < $RateSizeLimit)
        {
            $rate_control = false;
        }

        if ($rate_control)
        {
            # Set the block size and rate parameters
            $block_size = (float) $BlockSize;
            $transfer_rate = (float) $TransferRate;

            # Figure out the block transfer time
            $transfer_delay = ((float) $BlockSize * 8.0) /
                (float) $TransferRate;

            # For some reason, the transfer is about twice as much
            # faster than it should be. This fixes it.
            $transfer_delay = $transfer_delay / $RateAdjustment;
        }

        # See which transfer type we are dealing with
        # Full file
        if (count($ranges) == 0)
        {
            # Send the accept ranges header
            header("Accept-Ranges: bytes");

            # Send the file size
            header("Content-Length: " . (string) $filesize);

            # Set the mime type
            SetMime($mime_type);

            # Open the file
            $fhandle = fopen($BasePath . $LocalURI, "rb");
            $utime = microtime(true);

            # Read the blocks
            while ($block = fread($fhandle, $BlockSize))
            {
                # Send the block
                echo($block);

                if ($rate_control)
                {
                    # Get the new time
                    $new_utime = microtime(true);

                    # Sleep for the remaining amount of time
                    $delay = (int) (1000000.0 *
                        ($transfer_delay - ($new_utime - $utime)));
                    if ($delay > 0)
                    {
                        usleep($delay);
                    }
                    $utime = $new_utime;
                }
            }

            # Close the file
            fclose($fhandle);
        }

        # Single range
        else if (count($ranges) == 1)
        {
            # Send the partial content header
            header("HTTP/1.1 206 Partial Content");
            HTTPResponse(206);

            $begin = $ranges[0]["Begin"];
            $end = $ranges[0]["End"];

            # Send the content range and response size
            header("Content-Range: bytes " . $begin . "-" . $end . "/" .
                $filesize);
            header("Content-Length: " . ($end - $begin + 1));

            # Set the mime type
            SetMime($mime_type);

            # Open the file and jumpt to the proper spot
            $fhandle = fopen($BasePath . $LocalURI, "rb");
            fseek($fhandle, $begin);
            $utime = microtime(true);

            # Read blocks until we send the appropriate range
            $block_size = $BlockSize;
            $remaining = $end - $begin + 1;

            while ($remaining > 0)
            {
                if ($remaining < $block_size)
                {
                    $block_size = $remaining;
                }

                # Read the block
                $block = fread($fhandle, $block_size);

                # Send the block and calculate remaining size
                echo($block);
                $remaining -= $block_size;

                if ($rate_control)
                {
                    # Get the new time
                    $new_utime = microtime(true);

                    # Sleep for the remaining amount of time
                    $delay = (int) (1000000.0 *
                        ($transfer_delay - ($new_utime - $utime)));
                    if ($delay > 0)
                    {
                        usleep($delay);
                    }
                    $utime = $new_utime;
                }
            }

            # Close the file
            fclose($fhandle);
        }

        # Multiple ranges
        else
        {
            # Send the appropriate headers for multipart response
            header("HTTP/1.1 206 Partial Content");
            HTTPResponse(206);
            header("Content-Length: *");
            SetMime("multipart/byteranges; " .
                "boundary=MULTIPART_BOUNDARY");

            # Open the file
            $fhandle = fopen($BasePath . $LocalURI, "rb");

            # Go through each range
            foreach($ranges as $range)
            {
                # Send the information on this range
                echo("\n--MULTIPART_BOUNDARY\n");
                echo("Content-Type: " . $mime_type . "\n");
                echo("Content-Range: " . $range["Begin"] . "-" .
                        $range["End"] . "/" . $filesize . "\n\n");

                # Seek to the right position in the file
                fseek($fhandle, $range["Begin"]);

                # Read blocks until we send out the appropriate range
                $block_size = $BlockSize;
                $remaining = $range["End"] - $range["Begin"] + 1;

                while ($remaining > 0)
                {
                    if ($remaining < $block_size)
                    {
                        $block_size = $remaining;
                    }

                    # Read the block
                    $block = fread($fhandle, $block_size);

                    # Send the block and calculate remaining size
                    echo($block);
                    $remaining -= $block_size;

                    if ($rate_control)
                    {
                        # Get the new time
                        $new_utime = microtime(true);

                        # Sleep for the remaining amount of time
                        $delay = (int) (1000000.0 *
                                ($transfer_delay - ($new_utime - $utime)));
                        if ($delay > 0)
                        {
                                usleep($delay);
                        }
                        $utime = $new_utime;
                    }
                }
            }

            # Finish the multipart response and close the file
            echo("\n--MULTIPART_BOUNDARY--\n");
            fclose($fhandle);
        }
    }
    else
    {
        HTTPResponse(404);
    }
}

	

/*Description: Parses the range header and returns an array with the
   possible ranges
  Author: Alberto Trevino
  Last revised: 2011-01-06
  Arguments: (string) Range specification
   (int) Size of the file
  Returns: (array) Range specifications
  Throws exceptions
*/
function ParseRange($Range, $FileSize)
{
    # Separate the units and the spec
    $tmp_array = explode("=", trim($Range), 2);

    if ($tmp_array[0] != "bytes")
    {
        throw new exception("Invalid value for range bytes-unit");
    }

    $range = trim($tmp_array[1]);
    unset($tmp_array);

    # Go through the list of ranges
    $ranges = array();
    foreach(explode(",", $range) as $entry)
    {
        $range_values = explode("-", $entry);
        $begin = trim($range_values[0]);
        $end = trim($range_values[1]);

        if (count($range_values) != 2)
        {
            throw new exception("Invalid range entry: " . $entry);
        }

        # See if range begin is blank
        if ($begin != "")
        {
            if (is_numeric($begin))
            {
                # Make sure it is not bigger than the size of the file
                if ($begin < $FileSize)
                {
                    # See if the end is blank
                    if ($end != "")
                    {
                        # Make sure we have a number
                        if (is_numeric($end))
                        {
                            # Make sure it is greater than begin and smaller
                            #  than filesize
                            if ($begin <= $end && $end < $FileSize)
                            {
                                $ranges[] = array(
                                    "Begin" => (int) $begin,
                                    "End" => (int) $end);
                            }
                            else
                            {
                                throw new exception(
                                    "Invalid range entry: " . $entry);
                            }
                        }
                        else
                        {
                            throw new exception("Invalid range entry: " .
                                $entry);
                        }
                    }
                    else
                    {
                        $ranges[] = array(
                            "Begin" => (int) $begin,
                            "End" => $FileSize - 1);
                    }
                }
                else
                {
                    throw new exception("Invalid range entry: " . $entry);
                }
            }
            else
            {
                throw new exception("Invalid range entry: " . $entry);
            }
        }
        else
        {
            # Make sure the range end is not blank
            if ($end != "")
            {
                # Make sure we have a number
                if (is_numeric($end))
                {
                    # Make sure it is not bigger than the size of file
                    if ($end < $FileSize)
                    {
                        $ranges[] = array(
                            "Begin" => $FileSize - (int) $end,
                            "End" => $FileSize - 1);
                    }
                    else
                    {
                        throw new exception("Invalid range entry: " .
                            $entry);
                    }
                }
                else
                {
                    throw new exception("Invalid range entry: " . $entry);
                }
            }
            else
            {
                throw new exception("Invalid range entry: " . $entry);
            }
        }
    }

    # Return the ranges
    return $ranges;
}

/*Description: Sends the mime type to the browser
  Author: Alberto Trevino
  Last revised: 2010-08-11
  Arguments: NONE
  Returns: NONE
*/
function SetMime($MimeType)
{
    header("Content-Type: " . $MimeType);
}

/*Description: Sets the specified HTTP response code and message; if the
   code is 400 or greater, it will stop script execution
  Author: Alberto Trevino
  Last revised: 2010-08-11
  Arguments: (int) HTTP Code
   (opt. string) Message
  Returns: NONE
*/
function HTTPResponse($Code, $Message = null)
{
    $Protocol = 'HTTP/1.1'; # The protocol used for the connection
    switch($Code)
    {
            case 100:
                    $message = "Continue";
                    break;
            case 101;
                    $message = "Switching Protocols";
                    break;
            case 200;
                    $message = "OK";
                    break;
            case 201;
                    $message = "Created";
                    break;
            case 202;
                    $message = "Accepted";
                    break;
            case 203;
                    $message = "Non-Authoritative Information";
                    break;
            case 204;
                    $message = "No Content";
                    break;
            case 205;
                    $message = "Reset Content";
                    break;
            case 206;
                    $message = "Partial Content";
                    break;
            case 300;
                    $message = "Multiple Choices";
                    break;
            case 301;
                    $message = "Moved Permanently";
                    break;
            case 302;
                    $message = "Found";
                    break;
            case 303;
                    $message = "See Other";
                    break;
            case 304;
                    $message = "Not Modified";
                    break;
            case 305;
                    $message = "Use Proxy";
                    break;
            case 307;
                    $message = "Temporary Redirect";
                    break;
            case 400;
                    $message = "Bad Request";
                    break;
            case 401;
                    $message = "Unauthorized";
                    break;
            case 402;
                    $message = "Payment Required";
                    break;
            case 403;
                    $message = "Forbidden";
                    break;
            case 404;
                    $message = "Not Found";
                    break;
            case 405;
                    $message = "Method Not Allowed";
                    break;
            case 406;
                    $message = "Not Acceptable";
                    break;
            case 407;
                    $message = "Proxy Authentication Required";
                    break;
            case 408;
                    $message = "Request Timeout";
                    break;
            case 409;
                    $message = "Conflict";
                    break;
            case 410;
                    $message = "Gone";
                    break;
            case 411;
                    $message = "Length Required";
                    break;
            case 412;
                    $message = "Precondition Failed";
                    break;
            case 413;
                    $message = "Request Entity Too Large";
                    break;
            case 414;
                    $message = "Request-URI Too Large";
                    break;
            case 415;
                    $message = "Unsupported Media Type";
                    break;
            case 416;
                    $message = "Requested Range Not Satisfiable";
                    break;
            case 417;
                    $message = "Expectation Failed";
                    break;
            default:
            case 500;
                    $message = "Internal Server Error";
                    $Code = 500;
                    break;
            case 501;
                    $message = "Not Implemented";
                    break;
            case 502;
                    $message = "Bad Gateway";
                    break;
            case 503;
                    $message = "Service Unavailable";
                    break;
            case 504;
                    $message = "Gateway Timeout";
                    break;
            case 505;
                    $message = "HTTP Version not supported";
                    break;
    }

    # See if we have a custom message
    if ($Message !== null)
    {
            $message = $Message;
    }

    # Send the code
    header($Protocol . " " . (string) $Code . " " . $message, true,
        $Code);

    if ($Code >= 400)
    {
        echo("<html><body><h1>" .
            $Protocol . " " . (string) $Code . " " . $message .
            "</h1></body></html>");
        exit();
    }
}

?>
