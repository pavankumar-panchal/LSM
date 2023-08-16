<?

include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");
include_once '../functions/sendmail.php';

$submittype = $_REQUEST['submittype'];
$message="";
switch($submittype)
{
	case "change":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$oldpassword = $_POST['oldpassword'];
			$newpassword = $_POST['newpassword'];
			$cnewpassword = $_POST['cnewpassword'];
	
			if($message == "")
			{
				$query = "SELECT * FROM lms_users WHERE username = '".$cookie_username."'";
				$result = runmysqlqueryfetch($query);
				$oldpwd = $result['password'];
				if($oldpwd <> $oldpassword)
				$message = "2^Invalid OLD Password. Password changing failed.";
			}
			if($message == "")
			{
				if($newpassword <> $cnewpassword)
				$message = "2^New password and Confirm Password is not matching.";
			}
			if($message == "")
			{
				$query = "UPDATE lms_users SET password = '".$newpassword."' WHERE username = '".$cookie_username."'";
				$result = runmysqlquery($query); 
				
				$query = "SELECT * FROM lms_users WHERE username = '".$cookie_username."'";
				$result = runmysqlqueryfetch($query);
				$user = $result['type'];
				
				if($user == "Sub Admin")
				{
					$query1 = "SELECT lms_subadmins.id, lms_subadmins.sademailid as sademailid FROM lms_subadmins INNER JOIN lms_users ON lms_subadmins.id=lms_users.referenceid WHERE lms_users.type='Sub Admin' and lms_users.username='".$cookie_username."'";
					$result1 = runmysqlqueryfetch($query1); 
					$emailidusername = $result1['sademailid'];
				}
				elseif($user == "Reporting Authority")
				{
					$query2 = "SELECT lms_managers.id, lms_managers.mgremailid as mgremailid FROM lms_managers INNER JOIN lms_users ON lms_managers.id=lms_users.referenceid WHERE lms_users.type='Reporting Authority' and lms_users.username='".$cookie_username."'";
					$result2 = runmysqlqueryfetch($query2); 
					$emailidusername = $result2['mgremailid'];
				}
				elseif($user == "Dealer")
				{
					$query3 = "SELECT dealers.id, dealers.dlremail as dlremail FROM dealers INNER JOIN lms_users ON dealers.id=lms_users.referenceid WHERE lms_users.type='Dealer' and lms_users.username='".$cookie_username."'";
					$result3 = runmysqlqueryfetch($query3); 
					$emailidusername = $result3['dlremail'];
				}

				#echo "This is username ".$cookie_username." and email id ".$emailidusername;
				
				// Insert logs on filter of Lead
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','30','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery_log($query);
				$sub = "Change Of LMS Password";
				$file_htm = "../mailcontents/pass.htm";
				$file_txt = "../mailcontents/pass.txt";
				send_mail($sub,$file_htm,$file_txt); 
				$message = "1^Password changed successfully.";
			}
			echo($message);
		}
		else 
			echo("2^Your login might have expired. Please Logout and Login.");
		break;
		
		
}
?>