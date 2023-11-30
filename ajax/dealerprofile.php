<?php
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];
switch($submittype)
{
	case "save":
			$form_name = $_POST['form_name'];
			$form_address = $_POST['form_address'];
			$form_phone = $_POST['form_phone'];
			$form_mobile = $_POST['form_cell'];
			$form_website = $_POST['form_website'];
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
	
			
			if($form_name == "" || $form_address == "" || $form_phone == "" || $form_mobile == "")
				$message = "2^Mandatory fields missing. *";
			else
			{
				$query = "SELECT dealers.id AS id from lms_users join dealers on lms_users.referenceid = dealers.id WHERE lms_users.username = '".$cookie_username."'";
				$result = runmysqlqueryfetch($query);
				$dlrid = $result['id'];
				
				$query = "UPDATE dealers SET dlrname = '".$form_name."', dlraddress = '".$form_address."', dlrcell = '".$form_mobile."', dlrphone = '".$form_phone."', dlrwebsite = '".$form_website."' WHERE id  = '".$dlrid."'";
				$result = runmysqlquery($query);
				
				$message = "1^Profile updated successfully.";
			}	
			echo($message);
			break;
}
?>
