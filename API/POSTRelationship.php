<?php
error_reporting(E_ERROR | E_PARSE);

require ('Response.php');
require('includeHeaders.php');

//read parameters from request
$type   = $_REQUEST['type'];
$fromId = $_REQUEST['fromId'];
$toId   = $_REQUEST['toId'];

//make sure all parameters are provided
if(!$type || !$fromId || !$toId){
    returnMessage('missing parameters', 400);
    return;
}

//connect to the database
require('connectToDatabase.php');

try {
    //check if contact exists
    if($stmt0 = $mysqli->prepare("SELECT * FROM Contact WHERE ContactId = ? ")) {
        $stmt0->bind_param('i',  $toId);
        $stmt0->execute();
        $stmt0->store_result();
        $count = $stmt0->num_rows;
    }else{
        $mysqli->close();
        returnMessage('could not get contact', 400);
        return;
    }
    if($count!=0) {
        //check if this relationship exists
        if ($stmt1 = $mysqli->prepare("SELECT * FROM Relationship r WHERE r.RelationshipType = ? AND r.FromContactId = ? AND r.ToContactId = ? ")) {
            $stmt1->bind_param('sii', $type, $fromId, $toId);
            $stmt1->execute();
            $stmt1->store_result();
            $count = $stmt1->num_rows;
        } else {
            $mysqli->close();
            returnMessage('could not get relationship', 400);
            return;
        }

        if ($count == 0) {
            $stmt2 = $mysqli->prepare("INSERT INTO Relationship (RelationshipType, FromContactId, ToContactId) VALUES (?, ?, ?)");
            $stmt3 = $mysqli->prepare("INSERT INTO Relationship (RelationshipType, FromContactId, ToContactId) VALUES (?, ?, ?)");
            $stmt4 = $mysqli->prepare("SELECT FirstName, LastName, PhoneNumber FROM Contact WHERE ContactId = ?");

            if ($stmt2 && $stmt3 && $stmt4) {

                $stmt2->bind_param("sii", $type, $fromId, $toId);
                $stmt2->execute();
                $stmt2->store_result();

                $stmt3->bind_param("sii", $type, $toId, $fromId);
                $stmt3->execute();
                $stmt3->store_result();

                $stmt4->bind_param("i", $toId);
                $stmt4->execute();
                $stmt4->store_result();
                $stmt4->bind_result($firstName, $lastName, $phoneNumber);
                while ($stmt4->fetch()) {
                    $contactFirstName = $firstName;
                    $contactLastName = $lastName;
                    $contactNumber = $phoneNumber;
                }

                $mysqli->close();
                $output = array(
                    'message' => 'relationship successfully added',
                    'ContactName' => $contactFirstName . " " . $contactLastName,
                    'ContactPhoneNumber' => $contactNumber
                );
                returnMessage($output, 200);

            } else {
                $mysqli->close();
                returnMessage('could not add relationship', 400);
                return;
            }
        } else {
            $mysqli->close();
            returnMessage('this relationship exists', 200);
            return;
        }
    }else{
        $mysqli->close();
        returnMessage('this contact does not exist', 200);
    }

}catch (mysqli_sql_exception $e) {
    echo "MySQLi Error Code: " . $e->getCode() . "<br />";
    echo "Exception Msg: " . $e->getMessage();
    http_response_code(400);
    exit();
}

exit();