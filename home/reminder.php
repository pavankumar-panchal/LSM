<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999;">
        <div id="table-container" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 5px; max-height: 80%; overflow-y: auto; box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);">
            <span class="close" onclick="hideOverlay()" style="position: absolute; top: 10px; right: 10px; cursor: pointer; font-size: 20px; color: red;">&times;</span>
            <div id="response-container"></div>
        </div>
    </div>

    <script>
        function fetchData() {
            $.ajax({
                url: 'ajax/reminder.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var output = '';

                    if (data.length > 0) {
                        output += '<table style="width: 100%; border-collapse: collapse;">';
                        output += '<tr style="background-color: #f2f2f2;">';
                        output += '<th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Follow-up ID</th>';
                        output += '<th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Lead ID</th>';
                        output += '<th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Remarks</th>';
                        output += '<th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Entered Date</th>';
                        output += '<th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Follow-up Date</th>';
                        output += '<th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Follow-up Time</th>';
                        output += '<th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Entered By</th>';
                        output += '<th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Follow-up Status</th>';
                        output += '</tr>';

                        data.forEach(function (item) {
                            output += '<tr>';
                            output += '<td style="border: 1px solid #ccc; padding: 8px; text-align: left;">' + item.followupid + '</td>';
                            output += '<td style="border: 1px solid #ccc; padding: 8px; text-align: left;">' + item.leadid + '</td>';
                            output += '<td style="border: 1px solid #ccc; padding: 8px; text-align: left;">' + item.remarks + '</td>';
                            output += '<td style="border: 1px solid #ccc; padding: 8px; text-align: left;">' + item.entereddate + '</td>';
                            output += '<td style="border: 1px solid #ccc; padding: 8px; text-align: left;">' + item.followupdate + '</td>';
                            output += '<td style="border: 1px solid #ccc; padding: 8px; text-align: left;">' + item.followuptime + '</td>';
                            output += '<td style="border: 1px solid #ccc; padding: 8px; text-align: left;">' + item.enteredby + '</td>';
                            output += '<td style="border: 1px solid #ccc; padding: 8px; text-align: left;">' + item.followupstatus + '</td>';
                            output += '</tr>';
                        });

                        output += '</table>';
                    } else {
                        output = '<p>No reminders for today.</p>';
                    }

                    $('#response-container').html(output);
                    showOverlay();
                },
                error: function (xhr, status, error) {
                    console.error('There was a problem with the request: ' + error);
                }
            });
        }

        function hideOverlay() {
            var overlay = $('#overlay');
            var tableContainer = $('#table-container');
            overlay.hide();
            tableContainer.hide();
        }

        function showOverlay() {
            var overlay = $('#overlay');
            var tableContainer = $('#table-container');
            overlay.show();
            tableContainer.show();
        }

        function fetchDataPeriodically() {
            fetchData();
            setInterval(fetchData, 5000); // Repeat fetch every 5 seconds
        }

        $(document).ready(function () {
            fetchData();
            fetchDataPeriodically();
        });
    </script>
</body>

</html>
