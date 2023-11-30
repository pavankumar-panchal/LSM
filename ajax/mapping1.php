<?php
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];

switch($submittype)
{

	case "griddata":
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$query1 = "SELECT * FROM dealers";
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
			$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder">ID</td><td nowrap="nowrap"  class="tdborder">State ID</td><td nowrap="nowrap"  class="tdborder">District ID</td><td nowrap="nowrap"  class="tdborder">State</td><td nowrap="nowrap"  class="tdborder">District</td><td nowrap="nowrap"  class="tdborder">Contact Person</td><td nowrap="nowrap"  class="tdborder">Company</td><td nowrap="nowrap"  class="tdborder">Address</td><td nowrap="nowrap" class="tdborder">Cell</td><td nowrap="nowrap"  class="tdborder">Phone</td><td nowrap="nowrap"  class="tdborder">Email</td><td nowrap="nowrap"  class="tdborder">Website</td><td nowrap="nowrap"  class="tdborder">Manager Code</td></tr>';
		}
		$query = "SELECT * FROM dealers LIMIT ".$startlimit.",".$limit.";";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_row($result))
		{
			$slnocount++;
			//Begin a row
			$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
			
			//Write the cell data
			for($i = 0; $i < count($fetch); $i++)
			{
				$grid .= "<td nowrap='nowrap' class='tdborder'>&nbsp;".gridtrim30($fetch[$i])."</td>";
			}
		
			//End the Row
			$grid .= '</tr>';
		}
		//End of Table
		$grid .= '</tbody></table>';
		if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'dealer\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'dealer\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';		
		echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
		
		break;

	case "filter":
		$searchtext = $_POST['searchtext'];
		$subselection = $_POST['subselection'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
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
		switch($subselection)
		{
			case "company":
				$query1 = "SELECT * FROM dealers WHERE dlrcompanyname LIKE '%".$searchtext."%'";
				break;
			case "name":
				$query1 = "SELECT * FROM dealers WHERE dlrname LIKE '%".$searchtext."%'";
				break;
			case "cell":
				$query1 = "SELECT * FROM dealers WHERE dlrcell LIKE '%".$searchtext."%'";
				break;
			case "phone":
				$query1 = "SELECT * FROM dealers WHERE dlrphone LIKE '%".$searchtext."%'";
				break;
			case "email":
				$query1 = "SELECT * FROM dealers WHERE dlremail LIKE '%".$searchtext."%'";
				break;
			case "district":
				$query1 = "SELECT * FROM dealers WHERE district LIKE '%".$searchtext."%'";
				break;
			case "state":
				$query1 = "SELECT * FROM dealers WHERE state LIKE '%".$searchtext."%'";
				break;
			case "manager":
				$query1 = "select * from dealers right join lms_managers on dealers.managerid = lms_managers.id where dealers.id <> '' and lms_managers.mgrname LIKE '%".$searchtext."%'";
				break;
		}
		if($slnocount == '0')
		{
			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
			//Write the header Row of the table
			$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder">ID</td><td nowrap="nowrap" class="tdborder">State ID</td><td nowrap="nowrap"  class="tdborder">District ID</td><td nowrap="nowrap"  class="tdborder">State</td><td nowrap="nowrap"  class="tdborder">District</td><td nowrap="nowrap"  class="tdborder">Contact Person</td><td nowrap="nowrap"  class="tdborder">Company</td><td nowrap="nowrap"  class="tdborder">Address</td><td nowrap="nowrap"  class="tdborder">Cell</td><td nowrap="nowrap"  class="tdborder">Phone</td><td nowrap="nowrap"  class="tdborder">Email</td><td nowrap="nowrap" class="tdborder">Website</td><td nowrap="nowrap" class="tdborder">Manager Code</td></tr>';
		}
		$result1 = runmysqlquery($query1);
		$fetchresultcount = mysqli_num_rows($result1);
		$addlimit = " LIMIT ".$startlimit.",".$limit.";";
		$query = $query1.$addlimit;
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_row($result))
		{
			$slnocount++;
			//Begin a row
			$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
			
			//Write the cell data
			for($i = 0; $i < count($fetch); $i++)
			{
				$grid .= "<td nowrap='nowrap' class='tdborder'>&nbsp;".gridtrim30($fetch[$i])."</td>";
			}
		
			//End the Row
			$grid .= '</tr>';
		}
		//End of Table
		$grid .= '</tbody></table>';
		if($slnocount >= $fetchresultcount)
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'search\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'search\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
		
		
		// Insert logs on Filter of Lead Mapping
		$query2 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','20','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
		$result2 = runmysqlquery_log($query2);

		echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
		//echo($query);
		break;
		
	case "gridtoform":
		$form_recid = $_POST['form_recid'];
		$query = "SELECT * FROM dealers WHERE id = '".$form_recid."'";
		$result1 = runmysqlqueryfetch($query);
		$output = $result1['id']."^".$result1['dlrcompanyname'];
		echo('1^'.$output);
		break;
		
		
}
?>