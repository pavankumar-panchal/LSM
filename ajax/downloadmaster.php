<?
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];

switch($submittype)
{
	case "save":
		$form_recid = $_POST['form_recid'];
		$form_name = $_POST['form_name'];
		$form_description = $_POST['form_description'];
		$form_fullurl = $_POST['form_fullurl'];
		$form_categoryv = $_POST['form_categoryv'];
		$form_categoryh = $_POST['form_categoryh'];
		if($form_recid == "")
		{
			if($message == "")
			{
				$query = "insert into `lms_dlrdownloads` (name, description, fullurl, categoryv, categoryh) values('".$form_name."', '".$form_description."', '".$form_fullurl."', '".$form_categoryv."', '".$form_categoryh."')";
				$result = runmysqlquery($query); 
				// Insert logs on save of Download page
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','19','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery_log($query);
				$message = "1^Data Saved Successfully.";
			}
		}
		else
		{
			if($message == "")
			{
				$query = "UPDATE lms_dlrdownloads SET name = '".$form_name."', description = '".$form_description."', fullurl = '".$form_fullurl."', categoryv = '".$form_categoryv."', categoryh = '".$form_categoryh."' WHERE id = '".$form_recid."'";
				$result = runmysqlquery($query); 
				// Insert logs on update of Download page
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','17','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery_log($query);
				$message = "1^Data Updated Successfully.";
			}
		}
		echo($message);
		break;


	case "delete":
		$form_recid = $_REQUEST['form_recid'];
		if($message == "")
		{
			$query = "DELETE FROM lms_dlrdownloads WHERE id = '".$form_recid."'";
			$result = runmysqlquery($query); 
			// Insert logs on delete of Download page
			$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','18','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
			$result = runmysqlquery_log($query);
			$message = "1^Data Deleted Successfully.";
		}
		echo($message);
		break;


	case "griddata":
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$query1 = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads ORDER BY categoryv, categoryh";
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
			$grid .= '<tr class="gridheader" class = "tdborder"><td nowrap="nowrap" class = "tdborder">ID</td><td nowrap="nowrap" class = "tdborder">Name</td><td nowrap="nowrap" class = "tdborder">Description</td><td nowrap="nowrap" class = "tdborder">Full URL</td><td nowrap="nowrap" class = "tdborder">Category V</td><td nowrap="nowrap" class = "tdborder">Category H</td></tr>';
		}
		$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads ORDER BY categoryv, categoryh LIMIT ".$startlimit.",".$limit.";";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_row($result))
		{
			$slnocount++;
			//Begin a row
			$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
			
			//Write the cell data
			for($i = 0; $i < count($fetch); $i++)
			{
				$grid .= "<td nowrap='nowrap' class='tdborder'>".gridtrim30($fetch[$i])."</td>";
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
			$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE id = '".$form_recid."'";
			$result = runmysqlqueryfetch($query);
			$output = $result['id']."|^|".$result['name']."|^|".$result['description']."|^|".$result['fullurl']."|^|".$result['categoryv']."|^|".$result['categoryh'];
		echo('1|^|'.$output);
		break;
		
		
}
?>