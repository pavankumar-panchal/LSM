<?php

include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];

switch($submittype)
{
	case "save":
		$form_recid = $_POST['form_recid'];
		$form_name = $_POST['form_name'];
		$form_location = $_POST['form_location'];
		$form_email = $_POST['form_email'];
		$form_cell = $_POST['form_cell'];
		$form_username = $_POST['form_username'];
		$form_password = $_POST['form_password'];
		$transferuploadedleads = $_POST['transferuploadedleads'];
		$disablelogin = $_POST['form_disablelogin'];
		$managedarea = $_POST['managedarea']; 
		$branchhead = $_POST['branchhead']; 
		$branch = $_POST['branch']; 
		$showmcacompanies = $_POST['showmcacompanies']; 
		
		if($form_recid == "")
		{
			if($message == "")
			{
				$query = "SELECT * FROM lms_users WHERE username = '".$form_username."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				$message = "2^The Username already exists.";
			}
			if($message == "")
			{
				$query = "SELECT * FROM lms_managers WHERE mgrname = '".$form_name."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				$message = "2^The Manager name already exists.";
			}
			if($message == "")
			{
				$query = "insert into `lms_managers` (mgrname, mgrlocation, mgremailid, mgrcell, transferuploadedleads,managedarea,branchhead,branch,showmcacompanies)values('".$form_name."', '".$form_location."', '".$form_email."', '".$form_cell."', '".$transferuploadedleads."','".$managedarea."', '".$branchhead."', '".$branch."', '".$showmcacompanies."')";
				$result = runmysqlquery($query); 
				$query = "SELECT id FROM lms_managers WHERE mgrname = '".$form_name."'";
				$result = runmysqlqueryfetch($query); 
				$query = "insert into `lms_users` (username, password, type, referenceid, lastlogindate, logincount,disablelogin)values('".$form_username."', '".$form_password."', 'Reporting Authority', '".$result['id']."', '', '0','".$disablelogin."')";
				//$query = "insert into `lms_users` (username, password, type, referenceid, lastlogindate, logincount)values('".$form_username."', '".$form_password."', 'Reporting Authority', '".$result['id']."', '', '0')";
				$result = runmysqlquery($query); 
				// Insert logs on save of Reporting authority
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','11','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery_log($query);
				$message = "1^Data Saved Successfully.";
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
					if($fetch['referenceid'] <> $form_recid || $fetch['type'] <> 'Reporting Authority')
					$message = "2^The Username already exists for a different record.";
				}
			}
			if($message == "")
			{
				$query = "SELECT * FROM lms_managers WHERE mgrname = '".$form_name."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				while($fetch = mysqli_fetch_array($result))
				{
					if($fetch['id'] <> $form_recid)
					$message = "2^The Name already exists for a different record.";
				}
			}
			if($message == "")
			{
				$query = "UPDATE lms_users SET username = '".$form_username."', password = '".$form_password."',disablelogin = '".$disablelogin."' WHERE referenceid = '".$form_recid."' and type = 'Reporting Authority' ";
				$result = runmysqlquery($query); 
				$query = "UPDATE lms_managers SET mgrname = '".$form_name."', mgrlocation = '".$form_location."', mgremailid = '".$form_email."', mgrcell = '".$form_cell."', transferuploadedleads = '".$transferuploadedleads."', managedarea = '".$managedarea."', branch = '".$branch."' , branchhead = '".$branchhead."', showmcacompanies = '".$showmcacompanies."' WHERE id = '".$form_recid."'";
				$result = runmysqlquery($query); 
				// Insert logs on update of Reporting authority
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','10','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery_log($query);
				$message = "1^Data Updated Successfully.";
			}
		}
		echo($message);
		break;


	case "delete":
		$form_recid = $_POST['form_recid'];
		if($message == "")
		{
			$query = "SELECT * FROM dealers WHERE managerid = '".$form_recid."'";
			$result = runmysqlquery($query);
			$count = mysqli_num_rows($result);
			if($count > 0)
			$message = "2^Data Cannot be deleted as entries found in Dealer Master.";
		}
		if($message == "")
		{
			$query = "DELETE FROM lms_managers WHERE id = '".$form_recid."'";
			$result = runmysqlquery($query); 
			$query = "DELETE FROM lms_users WHERE referenceid = '".$form_recid."' and type = 'Reporting Authority'";
			$result = runmysqlquery($query); 
			// Insert logs on delete of Reporting authority
			$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','12','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
			$result = runmysqlquery_log($query);
			$message = "1^Data Deleted Successfully.";
		}
		
		
		echo($message);
		break;


	case "griddata":
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$query1 = "SELECT id, mgrname, mgrlocation, mgremailid, mgrcell FROM lms_managers";
		$result1 = runmysqlquery($query1);
		$fetchresultcount = mysqli_num_rows($result1);
		if($showtype == 'all')
		$limit = 100000;
		else
		$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slnocount = 0;
		}
		else
		{
			$startlimit = $slnocount ;
			$slnocount = $slnocount;
		}
		if($slnocount == '0')
		{
			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
			//Write the header Row of the table
			$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder" width = "10px">Sl No</td><td nowrap="nowrap" class="tdborder">ID</td><td nowrap="nowrap" class="tdborder">Name</td><td nowrap="nowrap" class="tdborder">Location</td><td nowrap="nowrap" class="tdborder">Email ID</td><td nowrap="nowrap" class="tdborder">Cell</td></tr>';
		}
		$query = "SELECT id, mgrname, mgrlocation, mgremailid, mgrcell FROM lms_managers LIMIT ".$startlimit.",".$limit.";";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_row($result))
		{
			$slnocount++;
			//Begin a row
			$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
			$grid .= "<td nowrap='nowrap' class = 'tdborder'>".$slnocount."</td>";			//Write the cell data
			for($i = 0; $i < count($fetch); $i++)
			{
				$grid .= "<td nowrap='nowrap' class = 'tdborder'>".gridtrim30($fetch[$i])."</td>";
			}
		
			//End the Row
			$grid .= '</tr>';
		}
		//End of Table
		$grid .= '</tbody></table>';
		if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';		
		echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
		break;


	case "gridtoform":
			$form_recid = $_POST['form_recid'];
			$query = "SELECT * FROM lms_users  WHERE referenceid = '".$form_recid."' and type = 'Reporting Authority'";
			$result1 = runmysqlqueryfetch($query);
			$query = "SELECT * FROM lms_managers WHERE id = '".$form_recid."'";
			$result2 = runmysqlqueryfetch($query);
			$output = $result2['id']."^".$result2['mgrname']."^".$result2['mgrlocation']."^".$result2['mgremailid']."^".$result2['mgrcell']."^".$result1['username']."^".$result1['password']."^".$result2['transferuploadedleads']."^".$result1['disablelogin']."^".$result2['managedarea']."^".$result2['branch']."^".$result2['branchhead']."^".$result2['showmcacompanies'];
		echo('1^'.$output);
		break;
}
?>