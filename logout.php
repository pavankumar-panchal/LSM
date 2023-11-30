<?php

include("functions/phpfunctions.php");
lmsuserlogout();
header("Location:./index.php");

if (lmsgetcookie('lmsusername') == false || lmsgetcookie('lmsusersort') == false || lmsgetcookie('lmslastlogindate') == false || lmsgetcookie('applicationid') == false) {
	lmsuserlogout();
	header("Location:./index.php");
} else {
	//Insert logs to login table
	if ($userslno <> '') {
		$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('" . $userslno . "','" . $_SERVER['REMOTE_ADDR'] . "','42','" . datetimelocal("Y-m-d") . ' ' . datetimelocal("H:i:s") . "')";
		$result = runmysqlquery_log($query);
		lmsuserlogout();
		header("Location:./index.php");
	} else {
		lmsuserlogout();
		header("Location:./index.php");
	}
}
?>