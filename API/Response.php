<?php

//function used by all endpoints to return a response message with a proper http response code
function returnMessage($message, $code){
    echo json_encode($message);
    http_response_code($code);
    return;
}

?>