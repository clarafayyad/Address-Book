<?php
error_reporting(E_ERROR | E_PARSE);

require('Response.php');
require('includeHeaders.php');

//read ID from request
$id = $_REQUEST['id'];
if(!$id){
    returnMessage('missing parameter', 400);
    return;
}

//connect to the database
require('connectToDatabase.php');

//select relationship details query
$query = "SELECT r.RelationshipType, r.ToContactId as ContactId, CONCAT(c.FirstName,\" \",c.LastName) as Name, c.PhoneNumber
          FROM Relationship r, Contact c
          WHERE r.FromContactId = ? AND r.ToContactId = c.ContactId ";

if($stmt = $mysqli->prepare($query)){
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    $mysqli->close();
    http_response_code(200);

}else{
    $mysqli->close();
    returnMessage('could not get relationships', 400);
}


