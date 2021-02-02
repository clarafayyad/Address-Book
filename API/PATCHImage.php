<?php
error_reporting(E_ERROR | E_PARSE);

require('Response.php');
require('includeHeaders.php');

//validate parameters
if(!isset($_FILES['file']['name']) || !isset($_POST['contactId'])){
    returnMessage('missing parameters', 400);
    return;
}

//read parameters
$filename = $_FILES['file']['name'];
$id       = $_POST['contactId'];

//validate file extension
$valid_extensions = array("jpg","jpeg","png","pdf");
$extension = pathinfo($filename, PATHINFO_EXTENSION);
if(in_array(strtolower($extension),$valid_extensions) ) {

   // Upload file
   if(move_uploaded_file($_FILES['file']['tmp_name'], "uploads/".$filename)){
       //connect to the database
       require('connectToDatabase.php');

       //file destination
       $fileDestination = 'http://localhost/addressbook/API/uploads/'.$filename;

       //upload image to database
        try {
            if($stmt = $mysqli->prepare("UPDATE Image SET ImagePath = ? WHERE ContactId = ?;")) {
                $stmt->bind_param("ss", $fileDestination, $id);
                $stmt->execute();
                $stmt->store_result();
            }else{
                $mysqli->close();
                returnMessage('could not update image', 400);
                return;
            }

            $output = array(
                'message' => 'successfully updated',
                'fileDestination' => $fileDestination
            );
            returnMessage($output, 200);

        }catch (mysqli_sql_exception $e) {
            echo "MySQLi Error Code: " . $e->getCode() . "<br />";
            echo "Exception Msg: " . $e->getMessage();
            http_response_code(400);
            exit();
        }

        $mysqli->close();

   }else{
      http_response_code(400);
   }
}else{
   http_response_code(400);
}


?>