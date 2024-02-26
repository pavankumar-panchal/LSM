<?php
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$type = $_POST['submittype'];

switch($type)
{
	case "save":
		$cell = $_POST['cell'];
		$emailid =  $_POST['emailid'];
		$name = $_POST['name'];
		$cookie_usertype = lmsgetcookie('lmsusersort');
		$cookie_username =  lmsgetcookie('lmsusername');
		$query = "select * from lms_users where username = '".$cookie_username."'";
		$resultfetch = runmysqlqueryfetch($query);
		switch($cookie_usertype)
		{
			case "Sub Admin":
				$query = "UPDATE lms_subadmins set sadname = '".$name."',cell = '".$cell."', sademailid = '".$emailid."' where id = '".$resultfetch['referenceid']."'";
				$fetch = runmysqlquery($query);
				// Update Status 
				
				$query1 = "UPDATE lms_users set confirmation = 'yes' where username = '".$cookie_username."'";
				$fetch = runmysqlquery($query1);
				break;
			
			case "Reporting Authority":
				$query = "UPDATE lms_managers set mgrname = '".$name."',mgrcell = '".$cell."', mgremailid = '".$emailid."'  where id = '".$resultfetch['referenceid']."'";
				$fetch = runmysqlquery($query);
				// Update Status 
				
				$query1 = "UPDATE lms_users set confirmation = 'yes' where username = '".$cookie_username."'";
				$fetch = runmysqlquery($query1);
			
				break;
				
			case "Dealer":
				$query = "UPDATE dealers set dlrname = '".$name."',dlrcell = '".$cell."', dlremail = '".$emailid."'  where id = '".$resultfetch['referenceid']."'";
				$fetch = runmysqlquery($query);
				// Update Status 
				
				$query1 = "UPDATE lms_users set confirmation = 'yes' where username = '".$cookie_username."'";
				$fetch = runmysqlquery($query1);
				break;
			
			case "Dealer Member":
				$query = "UPDATE lms_dlrmembers set dlrmbrname = '".$name."',dlrmbrcell = '".$cell."', dlrmbremailid = '".$emailid."'  where dlrmbrid = '".$resultfetch['referenceid']."'";
				$fetch = runmysqlquery($query);
				// Update Status 
				
				$query1 = "UPDATE lms_users set confirmation = 'yes' where username = '".$cookie_username."'";
				$fetch = runmysqlquery($query1);
				break;
			
		}
		
		echo('1^Details are Confirmed Successfully');
		break;
		
}		

?>