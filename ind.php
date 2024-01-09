<!DOCTYPE html>
<html>
<head>
    <title>Follow-up Records</title>
    <style>
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        #table-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            max-height: 80%;
            overflow-y: auto;
        }
        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div id="overlay">
    <div id="table-container">
        <span class="close" onclick="hideOverlay()">&times;</span>
        <table id="data-table">
            <thead>
                <tr>
                    <th>Follow-up ID</th>
                    <th>Lead ID</th>
                    <th>Remarks</th>
                    <th>Entered Date</th>
                    <th>Follow-up Date</th>
                    <th>Follow-up Time</th>
                    <th>Entered By</th>
                    <th>Follow-up Status</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php

                date_default_timezone_set('Asia/Kolkata');
                // Your database connection code
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

                // Fetch records where followupdate = current date AND followuptime = current time
                $sql = "SELECT * FROM lms_followup WHERE followupdate = '$currentDate' AND followuptime = '$currentTime'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["followupid"] . "</td>";
                        echo "<td>" . $row["leadid"] . "</td>";
                        echo "<td>" . $row["remarks"] . "</td>";
                        echo "<td>" . $row["entereddate"] . "</td>";
                        echo "<td>" . $row["followupdate"] . "</td>";
                        echo "<td>" . $row["followuptime"] . "</td>";
                        echo "<td>" . $row["enteredby"] . "</td>";
                        echo "<td>" . $row["followupstatus"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No records found</td></tr>";
                }

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function hideOverlay() {
        var overlay = document.getElementById('overlay');
        overlay.style.display = 'none';
    }

    // Show the overlay when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        var overlay = document.getElementById('overlay');
        overlay.style.display = 'block';
    });
</script>

</body>
</html>
