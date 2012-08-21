
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


