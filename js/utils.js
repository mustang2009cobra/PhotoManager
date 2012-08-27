
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
}
