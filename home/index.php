<?php
// error_reporting(E_ALL);
// ini_set("display_errors",1);
include("../inc/checklogin.php");

switch($cookie_usertype)
{
	case "Admin": 
		header("Location:./indexadmin.php");
		break;
	case "Sub Admin": 
		header("Location:./indexsubadmin.php");
		break;
	case "Reporting Authority": 
		header("Location:./indexmanager.php");
		break;
	case "Dealer": 
		header("Location:./indexdealer.php");
		break;
	case "Implementer": 
		header("Location:./indeximplementer.php");
		break;
	case "Dealer Member": 
		header("Location:./indexdlrmbr.php");
		break;
}

?>