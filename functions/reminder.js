function fetchData() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === XMLHttpRequest.DONE) {
            if (xmlhttp.status === 200) {
                var data = JSON.parse(xmlhttp.responseText);
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

                document.getElementById('response-container').innerHTML = output;
                showOverlay();
            } else {
                console.error('There was a problem with the request.');
            }
        }
    };

    xmlhttp.open('GET', '../ajax/reminder.php', true);
    xmlhttp.send();
}

function hideOverlay() {
    var overlay = document.getElementById('overlay');
    var tableContainer = document.getElementById('table-container');
    overlay.style.display = 'none';
    tableContainer.style.display = 'none';
}

function showOverlay() {
    var overlay = document.getElementById('overlay');
    var tableContainer = document.getElementById('table-container');
    overlay.style.display = 'block';
    tableContainer.style.display = 'block';
}

// Function to fetch data every 10 seconds
function fetchDataPeriodically() {
    fetchData(); // Initial fetch
    setInterval(fetchData, 10000); // Repeat fetch every 10 seconds
}



