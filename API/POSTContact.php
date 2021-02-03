<?php
error_reporting(E_ERROR | E_PARSE);

require ('Response.php');
require('includeHeaders.php');

//read parameters from request
$firstName   = $_REQUEST['firstName'];
$lastName    = $_REQUEST['lastName'];
$phoneNumber = $_REQUEST['phoneNumber'];
$email       = $_REQUEST['email'];
$address     = $_REQUEST['address'];
$jobTitle    = $_REQUEST['job'];

//make sure all parameters are provided
if(!$firstName || !$lastName || !$phoneNumber){
    returnMessage('missing parameters', 400);
    return;
}

//connect to the database
require('connectToDatabase.php');                     

try {
    //check if this phone number exists
    if($stmt1 = $mysqli->prepare("SELECT * FROM Contact WHERE PhoneNumber = ? ")) {
        $stmt1->bind_param('s', $phoneNumber);
        $stmt1->execute();
        $stmt1->store_result();
        $count = $stmt1->num_rows;
    }else{
        $mysqli->close();
        returnMessage('could not get phone number', 400);
        return;
    }

    if($count == 0) {
        $stmt2 = $mysqli->prepare("INSERT INTO Contact (FirstName, LastName, PhoneNumber, Email, Address, JobTitle) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt3 = $mysqli->prepare("INSERT INTO Image (ImagePath, ContactId) VALUES (?, ?)");

        if ($stmt2 && $stmt3){

            //insert in contact table
            $stmt2->bind_param("ssssss", $firstName, $lastName, $phoneNumber, $email, $address, $jobTitle);
            $stmt2->execute();
            $stmt2->store_result();

            //get the last inserted Id
            $lastID = $mysqli->insert_id;

            //set image for new contacts
            $image = 'http://localhost/addressbook/API/uploads/contact-icon.png';

            //insert in image table
            $stmt3->bind_param("ss", $image, $lastID);
            $stmt3->execute();
            $stmt3->store_result();

            $mysqli->close();
            $output = array(
                'message' => 'successfully added',
                'id' => $lastID
            );
            returnMessage($output, 200);

        } else {
            $mysqli->close();
            returnMessage('could not add contact', 400);
            return;
        }
    }else{
        $mysqli->close();
        returnMessage('this number exists', 200);
        return;
    }

}catch (mysqli_sql_exception $e) {
    echo "MySQLi Error Code: " . $e->getCode() . "<br />";
    echo "Exception Msg: " . $e->getMessage();
    http_response_code(400);
    exit();
}

exit();