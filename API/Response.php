<?php

function returnMessage($message, $code){
    echo json_encode($message);
    http_response_code($code);
    return;
}

?>