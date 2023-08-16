<?
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];

switch($submittype)
{
	case "save":
		$form_recid = $_POST['form_recid'];
		$form_companyname = $_POST['form_companyname'];
		$form_name = $_POST['form_name'];
		$form_address = $_POST['form_address'];
		$form_state = $_POST['form_state'];
		$form_district = $_POST['form_district'];
		$form_phone = $_POST['form_phone'];
		$form_cell = $_POST['form_cell'];
		$form_email = $_POST['form_email'];
		$form_website = $_POST['form_website'];
		$form_manager = $_POST['form_manager'];
		$form_username = $_POST['form_username'];
		$form_password = $_POST['form_password'];
		$form_disablelogin = $_POST['form_disablelogin'];
		$form_relyonexecutive = $_POST['form_relyonexecutive'];
		$form_branch = $_POST['form_branch'];
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
				$query = "SELECT * FROM dealers WHERE dlrcompanyname = '".$form_companyname."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				$message = "2^The Company name already exists.";
			}
			if($message == "")
			{
				$query = "SELECT * FROM regions WHERE statecode = '".$form_state."' and  distcode = '".$form_district."'";
				$result1 = runmysqlqueryfetch($query); 
				$query = "insert into `dealers` (stateid, districtid, state, district, dlrname, dlrcompanyname, dlraddress, dlrcell, dlrphone, dlremail, dlrwebsite, managerid ,branch,showmcacompanies) values('".$form_state."', '".$form_district."', '".$result1['statename']."', '".$result1['distname']."', '".$form_name."', '".$form_companyname."', '".$form_address."', '".$form_cell."', '".$form_phone."', '".$form_email."', '".$form_website."', '".$form_manager."', '".$form_branch."','".$showmcacompanies."')";
				$result = runmysqlquery($query); 
				$query = "SELECT id FROM dealers WHERE dlrcompanyname = '".$form_companyname."'";
				$result = runmysqlqueryfetch($query); 
				$query = "insert into `lms_users` (username, password, type, referenceid, lastlogindate, logincount,disablelogin,relyonexecutive) values('".$form_username."', '".$form_password."', 'Dealer', '".$result['id']."', '', '0','".$form_disablelogin."','".$form_relyonexecutive."')";
				$result = runmysqlquery($query); 
				// Insert logs on save of Dealer
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','13','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
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
					if($fetch['referenceid'] <> $form_recid || $fetch['type'] <> 'Dealer')
					$message = "2^The Username already exists for a different record.";
				}
			}
			if($message == "")
			{
				$query = "SELECT * FROM dealers WHERE dlrcompanyname = '".$form_dlrcompanyname."'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				while($fetch = mysqli_fetch_array($result))
				{
					if($fetch['id'] <> $form_recid)
					$message = "2^The Company Name already exists for a different record.";
				}
			}
			if($message == "")
			{
				$query = "UPDATE lms_users SET username = '".$form_username."', password = '".$form_password."',disablelogin = '".$form_disablelogin."',relyonexecutive = '".$form_relyonexecutive."' WHERE referenceid = '".$form_recid."' and type = 'Dealer'";
				$result = runmysqlquery($query); 
				$query = "SELECT * FROM regions WHERE distcode = '".$form_district."'";
				$result1 = runmysqlqueryfetch($query); 
				$query = "UPDATE dealers SET stateid = '".$form_state."', districtid = '".$form_district."', state = '".$result1['statename']."', district = '".$result1['distname']."', dlrname = '".$form_name."', dlrcompanyname = '".$form_companyname."', dlraddress = '".$form_address."', dlrcell = '".$form_cell."', dlrphone = '".$form_phone."', dlremail = '".$form_email."', dlrwebsite = '".$form_website."', managerid = '".$form_manager."', branch = '".$form_branch."', showmcacompanies ='".$showmcacompanies."' WHERE id = '".$form_recid."'";
				$result = runmysqlquery($query); 
				// Insert logs on update of Dealer
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','14','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
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
			$query = "SELECT * FROM leads WHERE dealerid = '".$form_recid."'";
			$result = runmysqlquery($query);
			$count = mysqli_num_rows($result);
			if($count > 0)
			$message = "2^Cannot Delete: There are several LEADS assigned to this Dealer.";
		}
		if($message == "")
		{
			$query = "SELECT * FROM mapping WHERE dealerid = '".$form_recid."'";
			$result = runmysqlquery($query);
			$count = mysqli_num_rows($result);
			if($count > 0)
			$message = "2^Cannot Delete: There is MAPPING data available to this Dealer.";
		}
		if($message == "")
		{
			$query = "DELETE FROM dealers WHERE id = '".$form_recid."'";
			$result = runmysqlquery($query); 
			$query = "DELETE FROM lms_users WHERE referenceid = '".$form_recid."' and type = 'Dealer'";
			$result = runmysqlquery($query); 
			// Insert logs on delete of Dealer
			$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','16','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
			$result = runmysqlquery_log($query);
			$message = "1^Data Deleted Successfully.";
		}
		echo($message);
		break;


	case "griddata":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$startlimit = $_POST['startlimit'];
			$slnocount = $_POST['slnocount'];
			$showtype = $_POST['showtype'];
			$query1 =  "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername , lms_branch.branchname as branch from dealers left join lms_branch on dealers.branch = lms_branch.slno  join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' ORDER BY dealers.dlrcompanyname";
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
				$grid = '<table width="100%" border="0"  cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
				//Write the header Row of the table
				$grid .= '<tr class="gridheader"><td nowrap="nowrap" class = "tdborder">Sl No</td><td nowrap="nowrap" class = "tdborder">Dealer ID</td><td nowrap="nowrap" class = "tdborder">Company</td><td nowrap="nowrap" class = "tdborder">Contact person</td><td nowrap="nowrap" class = "tdborder">Cell</td><td nowrap="nowrap" class = "tdborder">Phone</td><td nowrap="nowrap" class = "tdborder">Email ID</td><td nowrap="nowrap" class = "tdborder">Website<td nowrap="nowrap" class = "tdborder">District</td><td nowrap="nowrap" class = "tdborder">State</td><td nowrap="nowrap" class = "tdborder">Manager</td><td nowrap="nowrap" class = "tdborder">Username</td><td nowrap="nowrap" class = "tdborder">Branch</td></tr>';
			}
			$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername , lms_branch.branchname as branch from dealers left join lms_branch on dealers.branch = lms_branch.slno  join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' ORDER BY dealers.dlrcompanyname LIMIT ".$startlimit.",".$limit.";";
			$result = runmysqlquery($query);
			while($fetch = mysqli_fetch_array($result))
			{
				$slnocount++;
				$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch['id'].'\');">';
				$grid .= "<td nowrap='nowrap' class = 'tdborder'>".$slnocount."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['id']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['dlrcompanyname']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['dlrname']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['dlrcell']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['dlrphone']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['dlremail']."</td>"."<td nowrap='nowrap' class = 'tdborder'>&nbsp;".$fetch['dlrwebsite']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['district']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['state']."</td>"."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['mgrname']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['dlrusername']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['branch']."</td>";
				$grid .= '</tr>';
			}
			//End of Table
			$grid .= '</tbody></table>';
			if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'dealer\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'dealer\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
			echo("1|^|".$grid."|^|".$linkgrid."|^|".$fetchresultcount);
		}
		else 
			echo("2|^|Your login might have expired. Please Logout and Login.");
		break;

	case "gridtoform":
			$form_recid = $_POST['form_recid'];
			$query = "SELECT * FROM dealers WHERE id = '".$form_recid."'";
			$result1 = runmysqlqueryfetch($query);
			$query = "SELECT * FROM lms_users WHERE referenceid = '".$form_recid."' and type = 'Dealer'";
			$result2 = runmysqlqueryfetch($query);
			$output = $result1['id']."^".$result1['stateid']."^".$result1['districtid']."^".$result1['dlrname']."^".$result1['dlrcompanyname']."^".$result1['dlraddress']."^".$result1['dlrcell']."^".$result1['dlrphone']."^".$result1['dlremail']."^".$result1['dlrwebsite']."^".$result1['managerid']."^".$result2['username']."^".$result2['password']."^".$result2['disablelogin']."^".$result2['relyonexecutive']."^".$result1['branch']."^".$result1['showmcacompanies'];
		echo('1^'.$output);
		break;
		
	case "state":
		$statecode = $_POST['statecode'];
		$query = "Select distinct distname, distcode from regions where statecode = '".$statecode."'";
		$result = runmysqlquery($query);
		$count = mysqli_num_rows($result);
		if($count > 0)
		{
			echo('<select name="form_district" id="form_district" class="formfields" style="width:180px">');
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
		
	case "filter":
		$searchtext = $_POST['searchtext'];
		$subselection = $_POST['subselection'];
		$disabled = $_POST['disabled'];
		if($disabled == 'all')
		{
			$disabledpiece = '';	
		}
		else if($disabled == 'yes')
		{
			$disabledpiece  = "AND lms_users.disablelogin = 'yes'";
		}
		else if($disabled == 'no')
		{
			$disabledpiece  = "AND lms_users.disablelogin = 'no'";
		}
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
		switch($subselection)
		{
			case "dlrid":
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername, lms_branch.branchname as branch from dealers left join lms_branch on dealers.branch = lms_branch.slno  join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.id like '%".$searchtext."%' ".$disabledpiece." ORDER BY dealers.dlrcompanyname";
				break;
			case "company":
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername, lms_branch.branchname as branch from dealers left join lms_branch on dealers.branch = lms_branch.slno  join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrcompanyname like '%".$searchtext."%' ".$disabledpiece." ORDER BY dealers.dlrcompanyname";
				break;
			case "name":
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername, lms_branch.branchname as branch from dealers left join lms_branch on dealers.branch = lms_branch.slno  join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrname like '%".$searchtext."%'  ".$disabledpiece." ORDER BY dealers.dlrcompanyname";
				break;
			case "phone":
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername, lms_branch.branchname as branch from dealers left join lms_branch on dealers.branch = lms_branch.slno  join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrphone like '%".$searchtext."%'  ".$disabledpiece." ORDER BY dealers.dlrcompanyname";
				break;
			case "email":
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername, lms_branch.branchname as branch from dealers left join lms_branch on dealers.branch = lms_branch.slno  join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlremail like '%".$searchtext."%' ".$disabledpiece." ORDER BY dealers.dlrcompanyname";
				break;
			case "district":
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername, lms_branch.branchname as branch from dealers left join lms_branch on dealers.branch = lms_branch.slno  join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.district like '%".$searchtext."%' ".$disabledpiece." ORDER BY dealers.dlrcompanyname";
				break;
			case "state":
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername, lms_branch.branchname as branch from dealers left join lms_branch on dealers.branch = lms_branch.slno  join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.state like '%".$searchtext."%' ".$disabledpiece." ORDER BY dealers.dlrcompanyname";
				break;
			case "cell":
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername, lms_branch.branchname as branch from dealers left join lms_branch on dealers.branch = lms_branch.slno  join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrcell like '%".$searchtext."%' ".$disabledpiece." ORDER BY dealers.dlrcompanyname";
				break;
			case "manager":
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername, lms_branch.branchname as branch from dealers left join lms_branch on dealers.branch = lms_branch.slno  join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE lms_managers.mgrname like '%".$searchtext."%' ".$disabledpiece." ORDER BY dealers.dlrcompanyname";
				break;
		} //echo($query);exit;
		if($slnocount == '0')
		{
			$grid = '<table width="100%" border="0"  cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
		//Write the header Row of the table
			$grid .= '<tr class="gridheader"><td nowrap="nowrap" class = "tdborder">Sl No</td><td nowrap="nowrap" class = "tdborder">Dealer ID</td><td nowrap="nowrap" class = "tdborder">Company</td><td nowrap="nowrap" class = "tdborder">Contact person</td><td nowrap="nowrap" class = "tdborder">Cell</td><td nowrap="nowrap" class = "tdborder">Phone</td><td nowrap="nowrap" class = "tdborder">Email ID</td><td nowrap="nowrap" class = "tdborder">Website<td nowrap="nowrap" class = "tdborder">District</td><td nowrap="nowrap" class = "tdborder">State</td><td nowrap="nowrap" class = "tdborder">Manager</td><td nowrap="nowrap" class = "tdborder">Username</td><td nowrap="nowrap" class = "tdborder">Branch</td></tr>';
		}
		$result = runmysqlquery($query);
		$fetchresultcount = mysqli_num_rows($result);
		$addlimit = " LIMIT ".$startlimit.",".$limit.";";
		$query1 = $query.$addlimit;
		$result1 = runmysqlquery($query1);
		while($fetch = mysqli_fetch_array($result1))
		{
			$slnocount++;
			$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch['id'].'\');">';
			$grid .= "<td nowrap='nowrap' class = 'tdborder'>".$slnocount."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['id']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['dlrcompanyname']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['dlrname']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['dlrcell']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['dlrphone']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['dlremail']."</td>"."<td nowrap='nowrap' class = 'tdborder'>&nbsp;".$fetch['dlrwebsite']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['district']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['state']."</td>"."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['mgrname']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['dlrusername']."</td>"."<td nowrap='nowrap' class = 'tdborder'>".$fetch['branch']."</td>";
			$grid .= '</tr>';
		}
	//End of Table
		$grid .= '</tbody></table>';
		if($slnocount >= $fetchresultcount)
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'search\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'search\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
		// Insert logs on filter of Dealer
		$query2 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','15','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
		$result2 = runmysqlquery_log($query2);
		
		echo("1|^|".$grid."|^|".$linkgrid."|^|".$fetchresultcount);
		//echo($query1);
		
		break;
}
?>