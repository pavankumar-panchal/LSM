<?php
date_default_timezone_set('Asia/Kolkata');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "relyon_lms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentDate = date('Y-m-d');
$currentTime = date('H:i');

$sql = "SELECT * FROM lms_followup WHERE followupdate = '$currentDate' AND followuptime = '$currentTime'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
        <div id="table-container" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 5px; max-height: 80%; overflow-y: auto;">
            <span class="close" onclick="hideOverlay()" style="position: absolute; top: 10px; right: 10px; cursor: pointer;">&times;</span>
            <table id="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="border-bottom: 1px solid #ccc;">Follow-up ID</th>
                        <th style="border-bottom: 1px solid #ccc;">Lead ID</th>
                        <th style="border-bottom: 1px solid #ccc;">Remarks</th>
                        <th style="border-bottom: 1px solid #ccc;">Entered Date</th>
                        <th style="border-bottom: 1px solid #ccc;">Follow-up Date</th>
                        <th style="border-bottom: 1px solid #ccc;">Follow-up Time</th>
                        <th style="border-bottom: 1px solid #ccc;">Entered By</th>
                        <th style="border-bottom: 1px solid #ccc;">Follow-up Status</th>
                    </tr>
                </thead>
                <tbody id="table-body">';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row["followupid"] . '</td>';
        echo '<td>' . $row["leadid"] . '</td>';
        echo '<td>' . $row["remarks"] . '</td>';
        echo '<td>' . $row["entereddate"] . '</td>';
        echo '<td>' . $row["followupdate"] . '</td>';
        echo '<td>' . $row["followuptime"] . '</td>';
        echo '<td>' . $row["enteredby"] . '</td>';
        echo '<td>' . $row["followupstatus"] . '</td>';
        echo '</tr>';
    }

    echo '</tbody>
            </table>
        </div>
    </div>';
    echo '<script>
            function hideOverlay() {
                var overlay = document.getElementById("overlay");
                var tableContainer = document.getElementById("table-container");
                overlay.style.display = "none";
                tableContainer.style.display = "none";
            }

            function showOverlay() {
                var overlay = document.getElementById("overlay");
                var tableContainer = document.getElementById("table-container");
                overlay.style.display = "block";
                tableContainer.style.display = "block";
            }

            document.addEventListener("DOMContentLoaded", function() {
                showOverlay();
            });
        </script>';
} else {
    echo "No rows found";
}

$conn->close();
?>
