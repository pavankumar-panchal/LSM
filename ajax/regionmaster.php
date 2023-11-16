<?php

include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$switchtype = $_POST['switchtype'];
switch($switchtype)
{

	case "save":
		$form_recid = $_POST['form_recid'];
		$form_state = $_POST['form_state'];
		$form_district = $_POST['form_district'];
		$form_region = $_POST['form_region'];
		$form_managedarea = $_POST['form_managedarea'];
		$form_fixed_added = $_POST['form_fixed_added'];
		if($form_recid == "")
		{
			if($message == "")
			{
				$query = "SELECT * FROM regions WHERE statecode = '".$form_state."' and  distcode = '".$form_district."' and subdistname = '".$form_region."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				$message = "2^The Region already exists for selected District.";
			}
			if($message == "")
			{
				$query = "SELECT * FROM regions WHERE distcode = '".$form_district."'";
				$result1 = runmysqlqueryfetch($query); 
				$query = "SELECT MAX(subdistcode) as newcode FROM regions";
				$result2 = runmysqlqueryfetch($query);
				$subdistcode = $result2['newcode'] + 1;
				$query = "insert into `regions` (statecode, statename, distcode, distname, subdistcode, subdistname, managedarea, recordtype)values('".$form_state."', '".$result1['statename']."', '".$form_district."', '".$result1['distname']."', '".$subdistcode."', '".$form_region."', '".$form_managedarea."', '".$form_fixed_added."')";
				$result = runmysqlquery($query); 
				// Inser logs on save of region
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','3','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery_log($query);
				$message = "1^Data Saved Successfully.";
			}
		}
		else
		{
			if($message == "")
			{
				if($form_fixed_added == 'fixed')
				{				
					$query = "SELECT * FROM regions WHERE slno = '".$form_recid."'";
					$result1 = runmysqlqueryfetch($query); 
					if($result1['distcode'] <> $form_district) 
					$message = "2^You cannot change the District for a FIXED type region.";
				}
			}
			if($message == "")
			{
				$query = "SELECT * FROM regions WHERE distcode = '".$form_district."'";
				$result1 = runmysqlqueryfetch($query); 
				$query = "UPDATE regions SET statecode = '".$form_state."', statename = '".$result1['statename']."', distcode = '".$form_district."', distname = '".$result1['distname']."', subdistname = '".$form_region."', managedarea = '".$form_managedarea."', recordtype = '".$form_fixed_added."' WHERE slno = '".$form_recid."'";
				$result = runmysqlquery($query); 
				// Inser logs on update of region
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','1','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
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
			$query = "SELECT * FROM regions WHERE slno = '".$form_recid."'";
			$result = runmysqlqueryfetch($query); 
			if($result['recordtype'] == "fixed") 
			$message = "2^You cannot delete a FIXED type region.";
		}
		if($message == "")
		{
			$query = "SELECT * FROM users WHERE regionid = '".$form_recid."'";
			$result = runmysqlquery($query);
			$count = mysqli_num_rows($result);
			if($count > 0)
			$message = "2^The Region is already used in USERLOGIN.";
		}
		if($message == "")
		{
			$query = "SELECT * FROM mapping WHERE regionid = '".$form_recid."'";
			$result = runmysqlquery($query);
			$count = mysqli_num_rows($result);
			if($count > 0)
			$message = "2^The Regionis already used in MAPPING.";
		}
		if($message == "")
		{
			$query = "DELETE FROM regions WHERE slno = '".$form_recid."'";
			$result = runmysqlquery($query); 
			// Inser logs on delete of region
			$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','2','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
			$result = runmysqlquery_log($query);
			$message = "1^Data Deleted Successfully.";
		}
		echo($message);
		break;


	case "griddata":
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$query1 = "SELECT * FROM regions";
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
			$grid .= '<tr class="gridheader"><td nowrap="nowrap" class = "tdborder">Sl No</td><td nowrap="nowrap" class = "tdborder">State Code</td><td nowrap="nowrap" class = "tdborder">State Name</td><td nowrap="nowrap" class = "tdborder">District Code</td><td nowrap="nowrap" class = "tdborder">District Name</td><td nowrap="nowrap" class = "tdborder">Region Code</td><td nowrap="nowrap" class = "tdborder">Region Name</td><td nowrap="nowrap" class = "tdborder">Managed Area</td><td nowrap="nowrap" class = "tdborder">Record Type</td></tr>';
		}
		
		$query = "SELECT * FROM regions LIMIT ".$startlimit.",".$limit.";";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_row($result))
		{
			$slnocount++;
			//Begin a row
			$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
			
			//Write the cell data
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
		//echo('gfhfgh');
		break;


	case "gridtoform":
			$form_recid = $_POST['form_recid'];
			$query = "SELECT * FROM regions WHERE slno = '".$form_recid."'";
			$result1 = runmysqlqueryfetch($query);
			$output = $result1['slno']."^".$result1['statecode']."^".$result1['distcode']."^".$result1['subdistname']."^".$result1['managedarea']."^".$result1['recordtype'];
		echo($output);
		break;
		
	case "state":
		$statecode = $_POST['statecode'];
		$query = "Select distinct distname, distcode from regions where statecode = '".$statecode."'";
		$result = runmysqlquery($query);
		$count = mysqli_num_rows($result);
		if($count > 0)
		{
			echo('<select name="form_district" id="form_district">');
			echo('<option value="" selected="selected"> - Make Selection - </option>');
			while($array = mysqli_fetch_array($result))
			{
				echo('<option value="'.$array['distcode'].'" >'.$array['distname'].'</option>');
			}
			echo('</select>');
		}
		else
		{
			echo('<select name="form_district" id="form_district">');
			echo('<option value="" selected="selected">- - - -Select a State First - - - -</option>');
			echo('</select>');
		}
		break;
		
}
?>