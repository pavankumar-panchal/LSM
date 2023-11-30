<?php
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$reqtype = $_POST['reqtype'];

switch($reqtype)
{
	case "save":
		$form_recid = $_POST['form_recid'];
		$form_name = $_POST['form_name'];
		$form_location = $_POST['form_location'];
		$form_email = $_POST['form_email'];
		$form_cell = $_POST['form_cell'];
		$form_username = $_POST['form_username'];
		$form_password = $_POST['form_password'];
		if($form_recid == "")
		{
			if($message == "")
			{
				$query = "SELECT * FROM lms_users WHERE username = '".$form_username."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				$message = "The Username already exists.";
			}
			if($message == "")
			{
				$query = "SELECT * FROM lms_implementers WHERE impname = '".$form_name."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				$message = "The implementer name already exists.";
			}
			if($message == "")
			{
				$query = "insert into `lms_implementers` (impname, implocation, impemailid, impcell)values('".$form_name."', '".$form_location."', '".$form_email."', '".$form_cell."')";
				$result = runmysqlquery($query); 
				$query = "SELECT id FROM lms_implementers WHERE impname = '".$form_name."'";
				$result = runmysqlqueryfetch($query); 
				$query = "insert into `lms_users` (username, password, type, referenceid, lastlogindate, logincount)values('".$form_username."', '".$form_password."', 'Implementer', '".$result['id']."', '', '0')";
				$result = runmysqlquery($query); 
				// Inser logs on save of Implementer
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','7','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery_log($query);
				$message = "Data Saved Successfully.";
			}
		}
		else
		{
			if($message == "")
			{
				$query = "SELECT * FROM lms_users WHERE username = '".$form_username."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				while($fetch = mysqli_fetch_array($result))
				{
					if($fetch['referenceid'] <> $form_recid || $fetch['type'] <> 'Implementer')
					$message = "The Username already exists for a different record.";
				}
			}
			if($message == "")
			{
				$query = "SELECT * FROM lms_implementers WHERE impname = '".$form_name."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				while($fetch = mysqli_fetch_array($result))
				{
					if($fetch['id'] <> $form_recid)
					$message = "The Name already exists for a different record.";
				}
			}
			if($message == "")
			{
				$query = "UPDATE lms_users SET username = '".$form_username."', password = '".$form_password."' WHERE referenceid = '".$form_recid."' and type = 'Implementer'";
				$result = runmysqlquery($query); 
				$query = "UPDATE lms_implementers SET impname = '".$form_name."', implocation = '".$form_location."', impemailid = '".$form_email."', impcell = '".$form_cell."' WHERE id = '".$form_recid."'";
				$result = runmysqlquery($query); 
				// Inser logs on update of Implementer
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','8','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery_log($query);
				$message = "Data Updated Successfully.";
			}
		}
		echo($message);
		break;


	case "delete":
		$form_recid = $_POST['form_recid'];
		$query = "DELETE FROM lms_implementers WHERE id = '".$form_recid."'";
		$result = runmysqlquery($query); 
		$query = "DELETE FROM lms_users WHERE referenceid = '".$form_recid."' and type = 'Implementer'";
		$result = runmysqlquery($query); 
		// Insert logs on delete of Implementer
		$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','9','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
		$result = runmysqlquery_log($query);
		$message = "Data Deleted Successfully.";
		echo($message);
		break;


	case "griddata":
		$grid = '<table width="100%" border="1" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
		//Write the header Row of the table
		$grid .= '<tr class="gridheader"><td nowrap="nowrap">ID</td><td nowrap="nowrap">Name</td><td nowrap="nowrap">Location</td><td nowrap="nowrap">Email ID</td><td nowrap="nowrap">Cell</td></tr>';
		$query = "SELECT * FROM lms_implementers";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_row($result))
		{
			//Begin a row
			$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
			
			//Write the cell data
			for($i = 0; $i < count($fetch); $i++)
			{
				$grid .= "<td nowrap='nowrap'>".gridtrim30($fetch[$i])."</td>";
			}
		
			//End the Row
			$grid .= '</tr>';
		}
		//End of Table
		$grid .= '</tbody></table>';
		echo($grid);
		break;


	case "gridtoform":
			$form_recid = $_POST['form_recid'];
			$query = "SELECT * FROM lms_users WHERE referenceid = '".$form_recid."' and type = 'Implementer'";
			$result1 = runmysqlqueryfetch($query);
			$query = "SELECT * FROM lms_implementers WHERE id = '".$form_recid."'";
			$result2 = runmysqlqueryfetch($query);
			$output = $result2['id']."^".$result2['impname']."^".$result2['implocation']."^".$result2['impemailid']."^".$result2['impcell']."^".$result1['username']."^".$result1['password'];
		echo($output);
		break;
}
?>