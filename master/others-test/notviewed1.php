<?php
ini_set("memory_limit","-1");
include("../functions/phpfunctions.php");

$submittype = $_POST['submittype'];

if($submittype == 'runquery')
{
	
	$query = "select leads.id from leads where dealerviewdate = '0000-00-00 00:00:00' and leadstatus = 'Not Viewed' and leads.id not 		in(select leadid from lms_updatelogs) order by id desc  limit 0,10000;";
	$result = runmysqlquery($query);	//echo($query);
	while($fetch1 = mysqli_fetch_array($result))
	{
		$lastupdateddate = datetimelocal("Y-m-d");
		$lastupdatetime = datetimelocal("H:i:s");
		$query2 = "insert into lms_updatelogs (leadid, leadstatus, updatedate, updatedby) values('".$fetch1['id']."', 'Not Viewed', '".$lastupdateddate.' '.$lastupdatetime."', '151')";
		$result2 = runmysqlquery($query2); 
		
		//$id .= $fetch1['id'];
		//echo($fetch['id']);
	}
	echo('1^Done');
	//echo($id);
}
?>
