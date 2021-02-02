<?php
error_reporting(E_ERROR | E_PARSE);

require('Response.php');
require('includeHeaders.php');

//read parameters from request
$contactId        = $_REQUEST['contactId'];
$oldPhoneNumber   = $_REQUEST['oldPhoneNumber'];
$newPhoneNumber   = $_REQUEST['newPhoneNumber'];

//validate parameters
if(!$contactId || !$oldPhoneNumber || !$newPhoneNumber){
    returnMessage("missing parameters", 400);
    return;
}

//make sure phone numbers are different
if($oldPhoneNumber == $newPhoneNumber){
    returnMessage("Phone numbers should be different for update.", 400);
    return;
}

//connect to the database
require('connectToDatabase.php');                     


try {
    //check if this phone number exists
    if($stmt1 = $mysqli->prepare("SELECT * FROM Contact WHERE PhoneNumber = ? ")) {
        $stmt1->bind_param('s', $newPhoneNumber);
        $stmt1->execute();
        $stmt1->store_result();
        $count = $stmt1->num_rows;
    }else{
        $mysqli->close();
        returnMessage('could not get phone number', 400);
        return;
    }

    if($count == 0) {
        //update the phone number in the Contact table
        if($stmt2 = $mysqli->prepare("UPDATE Contact SET PhoneNumber = ? WHERE ContactId = ?;")) {
            $stmt2->bind_param("ss", $newPhoneNumber, $contactId);
            $stmt2->execute();
            $stmt2->store_result();
        }
        else{
            $mysqli->close();
            returnMessage('could not update phone number', 400);
            return;
        }
    }else{
        $message = "This number exists";
        echo json_encode($message);
        return;
    }

}catch (mysqli_sql_exception $e) {
    echo "MySQLi Error Code: " . $e->getCode() . "<br />";
    echo "Exception Msg: " . $e->getMessage();
    http_response_code(400);
    exit();
}

$mysqli->close();
returnMessage('updated successfully', 200);
