<?php
error_reporting(E_ERROR | E_PARSE);

require('Response.php');
require('includeHeaders.php');


//validate parameters
$id1 = $_REQUEST['contactId1'];
$id2 = $_REQUEST['contactId2'];
$relationshipType = $_REQUEST['relationshipType'];
if(!$id1 || !$id2 || !$relationshipType){
    returnMessage('missing parameters', 400);
    return;
}

//connect to the database
require('connectToDatabase.php');

//delete relationship from Relationship table
try{
    $stmt = $mysqli->prepare("DELETE FROM Relationship 
                                    WHERE (FromContactId = ? AND ToContactId = ? AND RelationshipType = ?) OR 
                                          (FromContactId = ? AND ToContactId = ? AND RelationshipType = ?)");

    if($stmt){
        $stmt->bind_param("iisiis", $id1, $id2, $relationshipType, $id2, $id1, $relationshipType);
        $stmt->execute();
        $stmt->store_result();

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