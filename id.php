<?php
session_start();
include('../functions/phpfunctions.php');
date_default_timezone_set('Asia/Kolkata');

if ($newconnection->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$currentDate = date('Y-m-d');
$currentTime = date('H:i');

// Assuming the logged-in username is stored in a variable, replace 'your_logged_in_username' with the actual variable.

$sql = "SELECT lms_followup.followupid, lms_followup.leadid, lms_followup.remarks, lms_followup.entereddate, lms_followup.followupdate, lms_followup.followuptime, lms_followup.enteredby, lms_followup.followupstatus, lms_users.type 
        FROM lms_followup 
        JOIN lms_users ON lms_followup.enteredby = lms_users.id 
        WHERE lms_followup.followupdate = '$currentDate' 
        AND lms_followup.followuptime = '$currentTime'
        AND lms_followup.enteredby = lms_users.id";

$result = $newconnection->query($sql);

if ($result->num_rows > 0) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data); // Sending data as JSON response
}
?>
