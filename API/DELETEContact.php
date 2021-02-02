<?php
error_reporting(E_ERROR | E_PARSE);

require('Response.php');
require('includeHeaders.php');


//read ID from request
$id = $_REQUEST['contactId'];
if(!$id){
    returnMessage('missing parameter', 400);
    return;
}

//connect to the database
require('connectToDatabase.php');

//delete contact from Image, Contact, and Relationship tables
try{
    $stmt1 = $mysqli->prepare("DELETE FROM Image WHERE ContactId = ?");
    $stmt2 = $mysqli->prepare("DELETE FROM Relationship WHERE FromContactId = ? OR ToContactId = ?");
    $stmt3 = $mysqli->prepare("DELETE FROM Contact WHERE ContactId = ?");

    if($stmt1 && $stmt2 && $stmt3){
        $stmt1->bind_param("i", $id);
        $stmt1->execute();
        $stmt1->store_result();

        $stmt2->bind_param("ii", $id, $id);
        $stmt2->execute();
        $stmt2->store_result();

        $stmt3->bind_param("i", $id);
        $stmt3->execute();
        $stmt3->store_result();

        $message = 'ok';
        $code = 200;
    }else{
        $message = 'error';
        $code = 400;
    }

}catch (mysqli_sql_exception $e) {
    echo "MySQLi Error Code: " . $e->getCode() . "<br />";
    echo "Exception Msg: " . $e->getMessage();
    http_response_code(400);
    exit();
}


$mysqli->close();
returnMessage($message, $code);
exit();