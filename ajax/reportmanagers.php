<?

include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];

switch($submittype)
{

	case "griddata":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$startlimit = $_POST['startlimit'];
			$slnocount = $_POST['slnocount'];
			$showtype = $_POST['showtype'];
			$query1 = "select lms_managers.id AS id, lms_managers.mgrname AS mgrname, lms_managers.mgrlocation AS mgrlocation, lms_managers.mgremailid AS mgremailid, lms_managers.mgrcell AS mgrcell, lms_users.username AS mgrusername from lms_managers join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' ORDER BY lms_managers.mgrname";
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
				$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder">Sl No</td><td nowrap="nowrap" class="tdborder">Manager ID</td><td nowrap="nowrap" class="tdborder">Username</td><td nowrap="nowrap" class="tdborder">Manager Name</td><td nowrap="nowrap" class="tdborder">Location</td><td nowrap="nowrap" class="tdborder">Cell</td><td nowrap="nowrap" class="tdborder">Email ID</td></tr>';
			}
			$query = "select lms_managers.id AS id, lms_managers.mgrname AS mgrname, lms_managers.mgrlocation AS mgrlocation, lms_managers.mgremailid AS mgremailid, lms_managers.mgrcell AS mgrcell, lms_users.username AS mgrusername from lms_managers join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' ORDER BY lms_managers.mgrname LIMIT ".$startlimit.",".$limit.";";
			$result = runmysqlquery($query);
			$resultcount = mysqli_num_rows($result);
			while($fetch = mysqli_fetch_array($result))
			{
				$slnocount++;
				$grid .= '<tr>';
				$grid .= "<td nowrap='nowrap' class='tdborder'>".$slnocount."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['id']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['mgrusername']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['mgrname']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['mgrlocation']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['mgrcell']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['mgremailid']."</td>";
				$grid .= '</tr>';
			}
			//End of Table
			$grid .= '</tbody></table>';
			if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'managerlist\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'managerlist\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
			echo("1|^|".$grid."|^|".$linkgrid."|^|".$fetchresultcount);
		}
		else 
			echo("2|^|Your login might have expired. Please Logout and Login.");
		break;

	case "filter":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$searchtext = $_POST['searchtext'];
			$subselection = $_POST['subselection'];
			$managedarea = $_POST['managedarea'];
			$disablelogin = $_POST['disablelogin'];
			//Check who is making the entry
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
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
			$managedareapiece = ($managedarea == '')?"":("AND lms_managers.managedarea = '".$managedarea."'");
			$disableloginpiece = ($disablelogin == '')?"":("AND lms_users.disablelogin = '".$disablelogin."'");
			switch($subselection)
			{
				case "mgrid":
				$query = "select lms_managers.id AS id, lms_managers.mgrname AS mgrname, lms_managers.mgrlocation AS mgrlocation, lms_managers.mgremailid AS mgremailid, lms_managers.mgrcell AS mgrcell, lms_users.username AS mgrusername from lms_managers join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_managers.id like '%".$searchtext."%'  ".$managedareapiece." ".$disableloginpiece." ORDER BY lms_managers.mgrname";
					break;
				case "name":
				$query = "select lms_managers.id AS id, lms_managers.mgrname AS mgrname, lms_managers.mgrlocation AS mgrlocation, lms_managers.mgremailid AS mgremailid, lms_managers.mgrcell AS mgrcell, lms_users.username AS mgrusername from lms_managers join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_managers.mgrname like '%".$searchtext."%' ".$managedareapiece." ".$disableloginpiece." ORDER BY lms_managers.mgrname";
					break;
				case "location":
				$query = "select lms_managers.id AS id, lms_managers.mgrname AS mgrname, lms_managers.mgrlocation AS mgrlocation, lms_managers.mgremailid AS mgremailid, lms_managers.mgrcell AS mgrcell, lms_users.username AS mgrusername from lms_managers join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_managers.mgrlocation like '%".$searchtext."%' ".$managedareapiece." ".$disableloginpiece." ORDER BY lms_managers.mgrname";
					break;
				case "email":
				$query = "select lms_managers.id AS id, lms_managers.mgrname AS mgrname, lms_managers.mgrlocation AS mgrlocation, lms_managers.mgremailid AS mgremailid, lms_managers.mgrcell AS mgrcell, lms_users.username AS mgrusername from lms_managers join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_managers.mgremailid like '%".$searchtext."%' ".$managedareapiece." ".$disableloginpiece." ORDER BY lms_managers.mgrname";
					break;
				case "cell":
				$query = "select lms_managers.id AS id, lms_managers.mgrname AS mgrname, lms_managers.mgrlocation AS mgrlocation, lms_managers.mgremailid AS mgremailid, lms_managers.mgrcell AS mgrcell, lms_users.username AS mgrusername from lms_managers join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_managers.mgrcell like '%".$searchtext."%' ".$managedareapiece." ".$disableloginpiece." ORDER BY lms_managers.mgrname";
					break;
			}//echo($query); exit;
			if($slnocount == '0')
			{
				$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
				//Write the header Row of the table
				$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder">Sl No</td><td nowrap="nowrap" class="tdborder">Manager ID</td><td nowrap="nowrap" class="tdborder">Username</td><td nowrap="nowrap" class="tdborder">Manager Name</td><td nowrap="nowrap" class="tdborder">Location</td><td nowrap="nowrap" class="tdborder">Cell</td><td nowrap="nowrap" class="tdborder">Email ID</td></tr>';
			}
			$result = runmysqlquery($query);
			$fetchresultcount = mysqli_num_rows($result);
			$addlimit = " LIMIT ".$startlimit.",".$limit.";";
			$query1 = $query.$addlimit;
			$result1 = runmysqlquery($query1);
			while($fetch = mysqli_fetch_array($result1))
			{
				$slnocount++;
				$grid .= '<tr>';
				$grid .= "<td nowrap='nowrap' class='tdborder'>".$slnocount."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['id']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['mgrusername']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['mgrname']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['mgrlocation']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['mgrcell']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['mgremailid']."</td>";
				$grid .= '</tr>';
			}
			//End of Table
			$grid .= '</tbody></table>';
			if($slnocount >= $fetchresultcount)
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
				$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'view\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'view\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
			// Insert logs Manager List view
			$query2 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','33','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
			$result2 = runmysqlquery_log($query2);
			
			echo("1|^|".$grid."|^|".$linkgrid.'|^|'.$fetchresultcount);
		}
		else 
			echo("2|^|Your login might have expired. Please Logout and Login.");
		break;
		
		
}
?>