<?php

include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];
$linkgrid="";
$grid="";
$class="";
$disableuser="";
$message="";

switch($submittype)
{
	case "save":
		$form_recid = $_POST['form_recid'];
		$form_name = $_POST['form_name'];
		$form_email = $_POST['form_email'];
		$form_username = $_POST['form_username'];
		$form_password = $_POST['form_password'];
		$transferuploadedleads = $_POST['transferuploadedleads'];
		$disablelogin = $_POST['form_disablelogin'];
		$cell = $_POST['form_cell'];
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
				$query = "SELECT * FROM lms_subadmins WHERE sadname = '".$form_name."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				$message = "2^The Sub Admin name already exists.";
			}
			if($message == "")
			{
				$query = "insert into `lms_subadmins` (sadname, sademailid, transferuploadedleads,cell,showmcacompanies)values('".$form_name."', '".$form_email."', '".$transferuploadedleads."','".$cell."','".$showmcacompanies."')";
				$result = runmysqlquery($query); 
				$query = "SELECT id FROM lms_subadmins WHERE sadname = '".$form_name."'";
				$result = runmysqlqueryfetch($query); 
				$query = "insert into `lms_users` (username, password, type, referenceid, lastlogindate, logincount,disablelogin)values('".$form_username."', '".$form_password."', 'Sub Admin', '".$result['id']."', '', '0','".$disablelogin."')";
				//$query = "insert into `lms_users` (username, password, type, referenceid, lastlogindate, logincount)values('".$form_username."', '".$form_password."', 'Sub Admin', '".$result['id']."', '', '0')";
				$result = runmysqlquery($query); 
				// Inser logs on save of Subadmin
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','6','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery($query);
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
					if($fetch['referenceid'] <> $form_recid || $fetch['type'] <> 'Sub Admin')
					$message = "2^The Username already exists for a different record.";
				}
			}
			if($message == "")
			{
				$query = "SELECT * FROM lms_subadmins WHERE sadname = '".$form_name."'";
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
				$query = "UPDATE lms_users SET username = '".$form_username."', password = '".$form_password."' ,disablelogin = '".$disablelogin."' WHERE referenceid = '".$form_recid."' and type = 'Sub Admin'";
				$result = runmysqlquery($query); 
				$query = "UPDATE lms_subadmins SET sadname = '".$form_name."', sademailid = '".$form_email."', transferuploadedleads = '".$transferuploadedleads."' , cell = '".$cell."', showmcacompanies = '".$showmcacompanies."' WHERE id = '".$form_recid."'";
				$result = runmysqlquery($query); 
				// Inser logs on update of Subadmin
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','4','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery($query);
				$message = "1^Data Updated Successfully.";
			}
		}
		echo($message);
		break;


	case "delete":
		$form_recid = $_POST['form_recid'];
		$query = "DELETE FROM lms_subadmins WHERE id = '".$form_recid."'";
		$result = runmysqlquery($query); 
		$query = "DELETE FROM lms_users WHERE referenceid = '".$form_recid."' and type = 'Sub Admin'";
		$result = runmysqlquery($query); 
		// Inser logs on delete of Subadmin
		$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','5','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
		$result = runmysqlquery($query);
		$message = "Data Deleted Successfully.";
		echo('1^'.$message);
		break;


	case "griddata":
			if (isset($_POST['startlimit']))
			{
				$startlimit = $_POST['startlimit'];
			}
			else
			{
				$startlimit='';
			}
			if (isset($_POST['slnocount']))
			{
				$slnocount = $_POST['slnocount'];
			}
			else
			{
				$slnocount='';
			}
			if (isset($_POST['showtype']))
			{
				$showtype = $_POST['showtype'];
			}
			else
			{
				$showtype='';
			}
		$query1 = "SELECT count(*) as totalcount FROM lms_subadmins";
		$result1 = runmysqlqueryfetch($query1);
		$fetchresultcount = $result1['totalcount'];
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
			$grid .= '<tr class="gridheader"><td nowrap="nowrap" class = "tdborder" width = "10px">Sl No</td><td nowrap="nowrap" class = "tdborder">ID</td><td nowrap="nowrap" class = "tdborder">Name</td><td nowrap="nowrap" class = "tdborder">Cell</td><td nowrap="nowrap" class = "tdborder">Email ID</td></tr>';
		}
		#$query = "SELECT id, sadname,cell,sademailid FROM lms_subadmins LIMIT ".$startlimit.",".$limit.";";
		$query = "SELECT lms_subadmins.id, lms_subadmins.sadname, lms_subadmins.cell, lms_subadmins.sademailid, lms_users.disablelogin FROM lms_subadmins INNER JOIN lms_users ON lms_subadmins.id=lms_users.referenceid WHERE lms_users.type='Sub Admin' ORDER BY lms_subadmins.sadname";

		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_row($result))
		{
			if($fetch[4]=='yes')
			{
				$class='disabledull';
				$disableid='disableid';
			}
			else
			{
				$class='gridrow';
				$disableid='tdborder';
			}
			$slnocount++;
			/*$class='gridrow';*/
			//Begin a row
			$grid .= '<tr class="'.$class.'" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
			
			$grid .= "<td nowrap='nowrap' class = 'tdborder'>".$slnocount."</td>";
			//Write the cell data
			for($i = 0; $i < (count($fetch)-1); $i++)
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
			$query = "SELECT * FROM lms_users WHERE referenceid = '".$form_recid."' and type = 'Sub Admin'";
			$result1 = runmysqlqueryfetch($query);
			$query = "SELECT * FROM lms_subadmins WHERE id = '".$form_recid."'";
			$result2 = runmysqlqueryfetch($query);
			$output = $result2['id']."^".$result2['sadname']."^".$result2['sademailid']."^".$result1['username']."^".$result1['password']."^".$result2['transferuploadedleads']."^".$result1['disablelogin']."^".$result2['cell']."^".$result2['showmcacompanies'];
		echo('1^'.$output);
		break;
}
?>