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
$query = "SELECT r.RelationshipId, r.RelationshipType, r.ToContactId, c.LastName, c.FirstName, c.PhoneNumber
                                    FROM Relationship r, Contact c
                                    WHERE r.FromContactId = ? AND 
                                          r.ToContactId = c.ContactId ";

if($stmt = $mysqli->prepare($query)){
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($relationshipId, $type, $toContactId, $toContactLastName, $toContactFirstName, $toContactPhoneNumber
    );

    //prepare data for response
    $result = array();
    $count = 0;
    while($stmt->fetch()){
        $result[$count]['type'] = $type;
        $result[$count]['ContactId'] = $toContactId;
        $result[$count]['ContactName'] = $toContactFirstName . " " . $toContactLastName;
        $result[$count]['ContactPhoneNumber'] = $toContactPhoneNumber;
        $count++;
    }

    $mysqli->close();
    returnMessage($result, 200);

}else{
    $mysqli->close();
    returnMessage('could not get relationships', 400);
}


