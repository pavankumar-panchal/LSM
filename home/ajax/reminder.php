
<?php

session_start();
date_default_timezone_set('Asia/Kolkata');
include('../functions/phpfunctions.php');

if ($newconnection->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$currentDate = date('Y-m-d');
$currentTime = date('H:i');

// $sql = "SELECT * FROM lms_followup WHERE followupdate = '$currentDate' AND followuptime = '$currentTime'";
$sql = "SELECT lms_followup.followupid, lms_followup.leadid, lms_followup.remarks, lms_followup.entereddate, lms_followup.followupdate, lms_followup.followuptime, lms_followup.enteredby, lms_followup.followupstatus, lms_users.type 
        FROM lms_followup 
        JOIN lms_users ON lms_followup.enteredby = lms_users.id 
        WHERE DATE(lms_followup.followupdate) = CURDATE() 
        AND lms_followup.followuptime = '$currentTime'";

$result = $newconnection->query($sql);

if ($result->num_rows > 0) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data); // Sending data as JSON response
}


?>

