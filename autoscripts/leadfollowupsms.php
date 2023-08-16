<?
include("../functions/phpfunctions.php");

		
$query = "select leads.dealerid AS dealerid, leads.id AS leadid from (select * from lms_followup where followupdate = curdate() and followupstatus = 'PENDING') as lms_followup left join leads on lms_followup.leadid = leads.id";
$result = runmysqlquery($query); 
$presence = mysqli_num_rows($result);
if($presence > 0)
{
	$leaddetails = array();
	while($row = mysqli_fetch_array($result))
	{
		$dealerid = $row['dealerid'];
		$leadid = $row['leadid'];
		if(array_key_exists($dealerid, $leaddetails))
			$leaddetails[$dealerid] = $leaddetails[$dealerid].",".$leadid;
		else
			$leaddetails[$dealerid] = $leadid;
		
		//echo($dealerid." ".$leadid);
	}
	//print_r($leaddetails);
	
	while($item = current($leaddetails)) 
	{
		$dealerid = key($leaddetails);
		$leadid = $item;
		
		if(strlen($leadid) > 74)
			$leadid = substr($leadid, 0, 70) . "...";
		
		//Send SMS to concerned dealer about the lead
		$query = "SELECT * FROM dealers WHERE id = '".$dealerid."'";
		$result = runmysqlqueryfetch($query);
		$dlrcell = $result['dlrcell'];
	
		$servicename = 'Lead followup list 9AM';
		$tonumber = $dlrcell;
		//$tonumber = '9243405600';
		$smstext = "Today u are suppose to make followup call to lead ID ".$leadid." as per LMS.Update in LMS after follow up";
		$senddate = "";
		$sendtime = "";
		sendsms($servicename, $tonumber, $smstext, $senddate, $sendtime);
		next($leaddetails);
	}
}
echo("done".$presence);
exit;
?>
