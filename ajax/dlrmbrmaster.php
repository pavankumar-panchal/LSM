<?
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
//include("../inc/checklogin.php");
include("../inc/getuserslno.php");


$submittype = $_POST['submittype'];

switch($submittype)
{
	case "save":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$form_recid = $_POST['form_recid'];
			$form_name = $_POST['form_name'];
			$form_remarks = $_POST['form_remarks'];
			$form_cell = $_POST['form_cell'];
			$form_email = $_POST['form_email'];
			$form_username = $_POST['form_username'];
			$form_password = $_POST['form_password'];
			$form_disablelogin = $_POST['form_disablelogin'];
			$query = "SELECT dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname from lms_users join dealers on lms_users.referenceid = dealers.id WHERE lms_users.username = '".$cookie_username."'";
			$result = runmysqlqueryfetch($query);
			$dlrid = $result['id'];
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
					$query = "SELECT * FROM lms_dlrmembers WHERE dlrmbrname = '".$form_name."'";
					$result = runmysqlquery($query);
					$count = mysqli_num_rows($result);
					if($count > 0)
					$message = "2^The name already exists.";
				}
				if($message == "")
				{
					$query = "insert into `lms_dlrmembers` (dlrmbrname, dlrmbrcell, dlrmbremailid, dealerid, dlrmbrremarks) values('".$form_name."', '".$form_cell."', '".$form_email."', '".$dlrid."', '".$form_remarks."')";
					$result = runmysqlquery($query); 
					$query = "SELECT dlrmbrid FROM lms_dlrmembers WHERE dlrmbrname = '".$form_name."'";
					$result = runmysqlqueryfetch($query); 
					$query = "insert into `lms_users` (username, password, type, referenceid, lastlogindate, logincount,disablelogin) values('".$form_username."', '".$form_password."', 'Dealer Member', '".$result['dlrmbrid']."', '', '0','".$form_disablelogin."')";
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
						if($fetch['referenceid'] <> $form_recid || $fetch['type'] <> 'Dealer Member')
						$message = "2^The Username already exists for a different record.";
					}
				}
				if($message == "")
				{
					$query = "SELECT * FROM lms_dlrmembers WHERE dlrmbrname = '".$form_name."'";
					$result = runmysqlquery($query);
					$count = mysqli_num_rows($result);
					if($count > 0)
					while($fetch = mysqli_fetch_array($result))
					{
						if($fetch['dlrmbrid'] <> $form_recid)
						$message = "2^The Name already exists for a different record.";
					}
				}
				if($message == "")
				{
					$query1 = "UPDATE lms_users SET username = '".$form_username."', password = '".$form_password."', disablelogin = '".$form_disablelogin."' WHERE referenceid = '".$form_recid."' and type = 'Dealer Member'";
					$result = runmysqlquery($query1); 
					$query = "UPDATE lms_dlrmembers SET dlrmbrname = '".$form_name."',dlrmbrcell = '".$form_cell."', dlrmbremailid = '".$form_email."', dlrmbrremarks = '".$form_remarks."' WHERE dlrmbrid = '".$form_recid."'";
					$result = runmysqlquery($query); 
					$message = "1^Data Updated Successfully.";
				}
			}
			echo($message);
		}
		else 
			echo("2^Your login might have expired. Please Logout and Login.");
		break;

	case "delete":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$form_recid = $_POST['form_recid'];
			$query = "SELECT lms_users.id AS id FROM lms_users join lms_dlrmembers on lms_dlrmembers.dlrmbrid = lms_users.referenceid AND lms_users.type = 'Dealer Member' WHERE dlrmbrid = '".$form_recid."'";
			$result = runmysqlqueryfetch($query);
			$userid = $result['id'];

			if($message == "")
			{
				$query = "SELECT * FROM leads WHERE dlrmbrid = '".$form_recid."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				$message = "2^Cannot Delete: There are some leads assigned to this Dealer Member.";
			}
			if($message == "")
			{
				$query = "SELECT * FROM leads WHERE lastupdatedby = '".$userid."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				$message = "2^Cannot Delete: There are some leads updated by this Dealer Member.";
			}
			if($message == "")
			{
				$query = "SELECT * FROM leads WHERE leaduploadedby = '".$userid."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				$message = "2^Cannot Delete: There are some leads uploaded by this Dealer Member.";
			}
			if($message == "")
			{
				$query = "SELECT * FROM lms_followup WHERE enteredby = '".$userid."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				$message = "2^Cannot Delete: There are some folow-ups updated by this Dealer Member.";
			}
			if($message == "")
			{
				$query = "DELETE FROM lms_dlrmembers WHERE dlrmbrid = '".$form_recid."'";
				$result = runmysqlquery($query); 
				$query = "DELETE FROM lms_users WHERE referenceid = '".$form_recid."' and type = 'Dealer Member'";
				$result = runmysqlquery($query); 
				$message = "1^Data Deleted Successfully.";
			}
			echo($message);
		}
		else 
			echo("2^Your login might have expired. Please Logout and Login.");
		break;


	case "griddata":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$startlimit = $_POST['startlimit'];
			$slnocount = $_POST['slnocount'];
			$showtype = $_POST['showtype'];
			$query = "SELECT dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname from lms_users join dealers on lms_users.referenceid = dealers.id WHERE lms_users.username = '".$cookie_username."'";
			$result = runmysqlqueryfetch($query);			
			$dlrid = $result['id'];
			$query1 = "SELECT dlrmbrid, dlrmbrname, dlrmbremailid, dlrmbrcell, dlrmbrremarks FROM lms_dlrmembers WHERE dealerid = '".$dlrid."' ORDER BY dlrmbrid";
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
				$grid .= '<tr class="gridheader" class = "tdborder"><td nowrap="nowrap" class = "tdborder">ID</td><td nowrap="nowrap" class = "tdborder">Name</td><td nowrap="nowrap" class = "tdborder">Email ID</td><td nowrap="nowrap" class = "tdborder">Phone/Mobile</td><td nowrap="nowrap" class="tdborder">Remarks</td></tr>';
			}
			$query2 = "SELECT dlrmbrid, dlrmbrname, dlrmbremailid, dlrmbrcell, dlrmbrremarks FROM lms_dlrmembers WHERE dealerid = '".$dlrid."' ORDER BY dlrmbrid LIMIT ".$startlimit.",".$limit.";";
			$result = runmysqlquery($query2);
			while($fetch = mysqli_fetch_array($result))
			{
				$slnocount++;
				$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch['dlrmbrid'].'\');">';
				$grid .= "<td nowrap='nowrap' class='tdborder'>".gridtrim30($fetch['dlrmbrid'])."</td>".
				"<td nowrap='nowrap' class='tdborder'>".gridtrim30($fetch['dlrmbrname'])."</td>".
				"<td nowrap='nowrap' class='tdborder'>".gridtrim30($fetch['dlrmbremailid'])."</td>".
				"<td nowrap='nowrap' class='tdborder'>".gridtrim30($fetch['dlrmbrcell'])."</td>".
				"<td nowrap='nowrap' class='tdborder'>".gridtrim30($fetch['dlrmbrremarks'])."</td>";
				$grid .= '</tr>';
			}
			$grid .= '</tbody></table>';
			if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';		
			echo('1^'.$grid.'^'.$linkgrid.'^'.$slnocount);
		}
		else 
			echo("2'^'Your login might have expired. Please Logout and Login.");
		break;

	case "gridtoform":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$form_recid = $_POST['form_recid'];
			$query = "SELECT * FROM lms_dlrmembers WHERE dlrmbrid = '".$form_recid."'";
			$result1 = runmysqlqueryfetch($query);
			$query = "SELECT * FROM lms_users WHERE referenceid = '".$form_recid."' and type = 'Dealer Member'";
			$result2 = runmysqlqueryfetch($query);
			$output = $result1['dlrmbrid']."|^|".$result1['dlrmbrname']."|^|".$result1['dlrmbrremarks']."|^|".$result1['dlrmbremailid']."|^|".$result1['dlrmbrcell']."|^|".$result2['username']."|^|".$result2['password']."|^|".$result2['disablelogin'];
			echo('1|^|'.$output);
		}
		else 
			echo("2|^|Your login might have expired. Please Logout and Login.");
		break;
}
?>