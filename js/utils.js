
/**
 * A rough equivalent of PHP's isset function
 */
function isset(object){
    if(typeof object != 'undefined'){
        return true;
    }
    else{
        return false;
    }
}

/**
 * Shows an alert dialog at the top of the page.
 * Requires the following options to be passed in a JS object:
 *  -type: Determines which alert to show. Acceptable values: "info", "error", or "success"
 *  -message: A string value of the message to show in the alert
 */
function showAlert(options){
    Router.alert.render(options);
    if(options.type == "success" || options.type == "info"){
        setTimeout(removeAlert, "3000");
    }
}

/**
 * Removes the alert dialog from the top of the page by fading it out slowly
 */
function removeAlert(){
    $("#alertArea").fadeOut('slow', function(){
        //Nothing here yet
    });
}

/*
 * Takes a raw number of bytes and formats it into a more human-readable format
 */
function formatBytes(bytes, precision) {
    var kilobyte = 1024;
    var megabyte = kilobyte * 1024;
    var gigabyte = megabyte * 1024;
    var terabyte = gigabyte * 1024;

    if ((bytes >= 0) && (bytes < kilobyte)) {
        return bytes + ' B';
    } else if ( (bytes >= kilobyte) && (bytes < megabyte) ) {
        return Math.round((bytes / kilobyte), precision) + ' KB';
    } else if ((bytes >= megabyte) && (bytes < gigabyte)) {
        return Math.round((bytes / megabyte), precision) + ' MB';
    } else if ((bytes >=gigabyte) && (bytes < terabyte)) {
        return Math.round((bytes / gigabyte), precision) +' GB';
    } else if (bytes >= terabyte) {
        return Math.round((bytes / terabyte), precision) + ' TB';
    } else {
        return bytes + ' B';
    }
}

/**
 * Converts a unix timestamp into a human readable format
 */
function formatDates(dateString){
    if(dateString != null){
        var date = new Date();
        date.setTime( dateString*1000 );
        var readableString = date.toDateString();
        var array = readableString.split(' ');
        return array[2] + ' ' + array[1] + ' ' + array[3];
    }else{
        console.log("There was a problem converting dates");
    }
}


