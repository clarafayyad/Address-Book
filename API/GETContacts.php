<?php
error_reporting(E_ERROR | E_PARSE);

require('Response.php');
require('includeHeaders.php');

//max number of records to select for pagination purposes
const MAX_RECORDS_PER_PAGE = 10;

//validate input
$inputValidationConditions =!isset($_GET['searchQuery']) ||
                            !isset($_GET['type']) ||
                            !isset($_GET['pageNumber']) ||
                            !isset($_GET['isSearching']);

if($inputValidationConditions){
    returnMessage('missing parameters', 400);
    return;
}

//read input
$pageNumber  = $_GET['pageNumber'];
$type        = $_GET['type'];
$searchQuery = strtolower($_GET['searchQuery']);
$isSearching = $_GET['isSearching'];

//validate search type
$validTypes = ['all', 'firstName', 'lastName', 'job', 'address', 'email'];
if (!in_array($type, $validTypes)){
    returnMessage('wrong search type', 400);
    return;
}

//validate isSearching
$validOptions = ['true', 'false'];
if(!in_array($isSearching, $validOptions)){
    returnMessage('wrong parameter format', 400);
    return;
}

//connect to the database
require('connectToDatabase.php');

//prepare search query for matching
$query = "%{$searchQuery}%";

//calculate offset to select only few rows from Contact table for pagination
$offset          = ($pageNumber - 1) * MAX_RECORDS_PER_PAGE;
$numberOfRecords = MAX_RECORDS_PER_PAGE;

//sql query
$mainQuery = "SELECT c.ContactId, c.FirstName, c.LastName, c.PhoneNumber, c.JobTitle, c.Email, c.Address, i.ImagePath
              FROM Contact c, Image i 
              WHERE (c.ContactId = i.contactID)";
$limitQuery = " LIMIT ?,? ";
$orderby = ' ORDER BY CONCAT(FirstName, " ", LastName) ASC';
$condition = '';

//add string matching condition for search
if($isSearching == 'true') {
    switch ($type) {
        case 'firstName':
            $condition = ' AND (LOWER(FirstName) LIKE ? )';
            break;
        case 'lastName':
            $condition = ' AND (LOWER(LastName) LIKE ? )';
            break;
        case 'job':
            $condition = ' AND (LOWER(JobTitle) LIKE ? )';
            break;
        case 'address':
            $condition = ' AND (LOWER(Address) LIKE ? )';
            break;
        case 'email':
            $condition = ' AND (LOWER(Email) LIKE ? )';
            break;
    }

    //fetch max number of records from database by matching
    $wholeQuery = $mainQuery . $condition . $orderby . $limitQuery;
    if($stmt1 = $mysqli->prepare($wholeQuery)){
        $stmt1->bind_param('sii', $query, $offset, $numberOfRecords);
    }

}else{
    //fetch max number of records from database without looking for search match
    $wholeQuery = $mainQuery . $condition . $orderby . $limitQuery;
    if($stmt1 = $mysqli->prepare($wholeQuery)){
        $stmt1->bind_param('ii',$offset, $numberOfRecords);
    }
}

if(!$stmt1){
    $mysqli->close();
    returnMessage('could not get contacts', 400);
    return;
}

$stmt1->execute();
$stmt1->store_result();
$stmt1->bind_result($contactId, $firstname, $lastname, $phoneNumber, $jobTitle, $email, $address, $image);


//count total rows for pagination
$wholeQuery = $mainQuery . $condition;
if($stmt2 = $mysqli->prepare($wholeQuery)) {
    $stmt2->bind_param('s', $query);
    $stmt2->execute();
    $stmt2->store_result();
    $countRows = $stmt2->num_rows;
}else{
    $mysqli->close();
    returnMessage('could not count contacts', 400);
    return;
}

//prepare data for response
$result = array();
$count = 0;
while($stmt1->fetch()){
    $result[$count]['id'] = $contactId;
    $result[$count]['name'] = $firstname . ' ' .$lastname;
    $result[$count]['phoneNumber'] = $phoneNumber;
    $result[$count]['email'] = $email;
    $result[$count]['job'] = $jobTitle;
    $result[$count]['address'] = $address;
    $result[$count]['imageSrc'] = $image;
    $count++;
}

$output = array(
    'data'  => $result,
    'count' =>$countRows
);

$mysqli->close();
returnMessage($output, 200);

