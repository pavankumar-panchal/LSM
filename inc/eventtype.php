<?
	$query = "select distinct lms_logs_eventtype.slno,lms_logs_eventtype.eventtype from lms_logs_eventtype order by lms_logs_eventtype.eventtype ;
";
	$result = runmysqlquery_log($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['eventtype'].'</option>');
	}
?>
