<?
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");
error_reporting(E_ALL); ini_set('display_errors', 1);
$linkgrid = "";
$showtype = "";
$startlimit = "";

$submittype = $_POST['submittype'];

switch($submittype)
{

	case "griddata":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			if(isset($_POST['startlimit']) || isset($_POST['slnocount']) || isset($_POST['showtype']))
			{
				$startlimit = $_POST['startlimit'];
				$slnocount = $_POST['slnocount'];
				$showtype = $_POST['showtype'];
			}
			if($showtype == 'all' || isset($_POST['showtype']))
			$limit = 100000;
			else
			$limit = 10;
			if($startlimit == '' || isset($_POST['slnocount']))
			{
				$startlimit = 0;
				$slnocount = 0;
			}
			else
			{
				$startlimit = $slnocount ;
				$slnocount = $slnocount;
			}
			switch($cookie_usertype)
			{
				case "Admin":
				case "Sub Admin":
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' ORDER BY dealers.dlrcompanyname";
					break;
				case "Reporting Authority":
				{
				 	//Check wheteher the manager is branch head or not
					$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
					$result1 = runmysqlqueryfetch($query1);
					if($result1['branchhead'] == 'yes')
						$branchpiecejoin = "(dealers.branch = '".$result1['branch']."' OR dealers.managerid  = '".$result1['managerid']."')";
					else
						$branchpiecejoin = "lms_users2.username = '".$cookie_username."'";
							
					$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE  ".$branchpiecejoin." ORDER BY dealers.dlrcompanyname";
					if($cookie_username == "srinivasan")
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE lms_users2.username = '".$cookie_username."' or  lms_users2.username = 'nagaraj' ORDER BY dealers.dlrcompanyname";
				}
					break;
			}
			//echo($query);exit;
			if($slnocount == '0')
			{
				$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
				//Write the header Row of the table
				$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder">Sl No</td><td nowrap="nowrap" class="tdborder">Dealer ID</td><td nowrap="nowrap" class="tdborder">Username</td><td nowrap="nowrap" class="tdborder">Company</td><td nowrap="nowrap" class="tdborder">Contact person</td><td nowrap="nowrap" class="tdborder">Address</td><td nowrap="nowrap" class="tdborder">Cell</td><td nowrap="nowrap" class="tdborder">Phone</td><td nowrap="nowrap" class="tdborder">Email ID</td><td nowrap="nowrap" class="tdborder">Website<td nowrap="nowrap" class="tdborder">District</td><td nowrap="nowrap" class="tdborder">State</td><td nowrap="nowrap" class="tdborder">Manager</td></tr>';
			}	
			$result = runmysqlquery($query);
			$fetchresultcount = mysqli_num_rows($result);
			$addlimit = " LIMIT ".$startlimit.",".$limit."; ";
			$query1 = $query.$addlimit;
			$result1 = runmysqlquery($query1);
			while($fetch = mysqli_fetch_array($result1))
			{
				$slnocount++;
				$grid .= '<tr>';
				$grid .= "<td nowrap='nowrap' class='tdborder'>".$slnocount."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['id']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlrusername']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlrcompanyname']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlrname']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlraddress']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlrcell']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlrphone']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlremail']."</td>"."<td nowrap='nowrap' class='tdborder'>&nbsp;".$fetch['dlrwebsite']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['district']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['state']."</td>"."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['mgrname']."</td>";
				$grid .= '</tr>';
			}
			//End of Table
			$grid .= '</tbody></table>';
			if($slnocount >= $fetchresultcount)
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'dealerlist\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'dealerlist\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
			echo("1|^|".$grid."|^|".$linkgrid."|^|".$fetchresultcount);
		}
		else 
			echo("2|^|.Your login might have expired. Please Logout and Login.");
		break;

	case "filter":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
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
			if($cookie_usertype == "Reporting Authority")
			{
				//Check wheteher the manager is branch head or not
				$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
				$result1 = runmysqlqueryfetch($query1);
				if($result1['branchhead'] == 'yes')
				{
					$branchpiecejoin = "AND (dealers.branch = '".$result1['branch']."'  OR dealers.managerid = '".$result1['managerid']."')";
					if($cookie_username == "srinivasan")
						$managercheckpiece = "and (lms_users2.username = '".$cookie_username."' or  lms_users2.username = 'nagaraj')";
					else
						$managercheckpiece = "";
				}
				else
				{
					$branchpiecejoin = "";
					if($cookie_username == "srinivasan")
						$managercheckpiece = "and (lms_users2.username = '".$cookie_username."' or  lms_users2.username = 'nagaraj')";
					else
						$managercheckpiece = " and (lms_users2.username = '".$cookie_username."')";
				}
			}
						
			//Check who is making the entry
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			
			$startlimit = $_POST['startlimit'];
			$slnocount = $_POST['slnocount'];
			$showtype = $_POST['showtype'];
			if($showtype == 'all')
				$limit = 10000;
			else
				$limit = 10;
			if($startlimit == '')
			{
				$startlimit = 0;
				$slnocount = 0;
			}
			else
			{
				$startlimit = $slnocount;
				$slnocount = $slnocount;
			}
			
			switch($subselection)
			{
				case "dlrid":
					if($cookie_usertype == "Reporting Authority")
					{
						
							
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.id like '%".$searchtext."%'  ".$managercheckpiece." ".$disabledpiece." ".$branchpiecejoin." ORDER BY dealers.dlrcompanyname";
					}
					else
					{
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.id like '%".$searchtext."%' ".$disabledpiece."  ORDER BY dealers.dlrcompanyname";
					}
					break;
				case "company":
					if($cookie_usertype == "Reporting Authority")
					{
						
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrcompanyname like '%".$searchtext."%'  ".$managercheckpiece." ".$disabledpiece." ".$branchpiecejoin." ORDER BY dealers.dlrcompanyname";
					}
					else
					{
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrcompanyname like '%".$searchtext."%' ".$disabledpiece."  ORDER BY dealers.dlrcompanyname";
					}
					break;
				case "name":
					if($cookie_usertype == "Reporting Authority")
					{
						
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrname like '%".$searchtext."%'  ".$managercheckpiece." ".$disabledpiece."  ".$branchpiecejoin." ORDER BY dealers.dlrcompanyname";
					}
					else
					{
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrname like '%".$searchtext."%' ".$disabledpiece." ORDER BY dealers.dlrcompanyname";
					}
					break;
				case "phone":
					if($cookie_usertype == "Reporting Authority")
					{
						
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrphone like '%".$searchtext."%'  ".$managercheckpiece." ".$disabledpiece."  ".$branchpiecejoin." ORDER BY dealers.dlrcompanyname";
					}
					else
					{
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrphone like '%".$searchtext."%' ".$disabledpiece."  ORDER BY dealers.dlrcompanyname";
					}
					break;
				case "email":
					if($cookie_usertype == "Reporting Authority")
					{
						
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlremail like '%".$searchtext."%'  ".$managercheckpiece." ".$disabledpiece." ".$branchpiecejoin." ORDER BY dealers.dlrcompanyname";
					}
					else
					{
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlremail like '%".$searchtext."%' ".$disabledpiece."  ORDER BY dealers.dlrcompanyname";
					}
					break;
				case "district":
					if($cookie_usertype == "Reporting Authority")
					{
						
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.district like '%".$searchtext."%'  ".$managercheckpiece." ".$disabledpiece." ".$branchpiecejoin." ORDER BY dealers.dlrcompanyname";
					}
					else
					{
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.district like '%".$searchtext."%' ".$disabledpiece."  ORDER BY dealers.dlrcompanyname";
					}
					break;
				case "state":
					if($cookie_usertype == "Reporting Authority")
					{
						
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.state like '%".$searchtext."%'  ".$managercheckpiece." ".$disabledpiece." ".$branchpiecejoin." ORDER BY dealers.dlrcompanyname";
					}
					else
					{
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.state like '%".$searchtext."%' ".$disabledpiece."  ORDER BY dealers.dlrcompanyname";
					}
					break;
				case "cell":
					if($cookie_usertype == "Reporting Authority")
					{
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrcell like '%".$searchtext."%'  ".$managercheckpiece." ".$disabledpiece." ".$branchpiecejoin." ORDER BY dealers.dlrcompanyname";
					}
					else
					{
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrcell like '%".$searchtext."%' ".$disabledpiece."  ORDER BY dealers.dlrcompanyname";
					}
					break;
				case "manager":
					if($cookie_usertype == "Reporting Authority")
					{
							
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE lms_managers.mgrname like '%".$searchtext."%'  ".$managercheckpiece." ".$disabledpiece." ".$branchpiecejoin." ORDER BY dealers.dlrcompanyname";
					}
					else
					{
						$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE lms_managers.mgrname like '%".$searchtext."%' ".$disabledpiece."  ORDER BY dealers.dlrcompanyname";
					}
					break;
			} //echo($query); exit;
			
			if($slnocount == '0')
			{
				$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
				//Write the header Row of the table
				$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder">Sl No</td><td nowrap="nowrap" class="tdborder">Dealer ID</td><td nowrap="nowrap" class="tdborder">Username</td><td nowrap="nowrap" class="tdborder">Company</td><td nowrap="nowrap" class="tdborder">Contact person</td><td nowrap="nowrap" class="tdborder">Address</td><td nowrap="nowrap" class="tdborder">Cell</td><td nowrap="nowrap" class="tdborder">Phone</td><td nowrap="nowrap" class="tdborder">Email ID</td><td nowrap="nowrap" class="tdborder">Website<td nowrap="nowrap" class="tdborder">District</td><td nowrap="nowrap" class="tdborder">State</td><td nowrap="nowrap" class="tdborder">Manager</td></tr>';
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
				$grid .= "<td nowrap='nowrap' class='tdborder'>".$slnocount."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['id']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlrusername']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlrcompanyname']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlrname']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlraddress']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlrcell']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlrphone']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlremail']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlrwebsite']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['district']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['state']."</td>"."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['mgrname']."</td>";
				$grid .= '</tr>';
			}
			//End of Table
			$grid .= '</tbody></table>';
			
			if($slnocount >= $fetchresultcount)
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'view\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'view\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
			// Insert logs on filter of Lead
			$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','31','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
			$result = runmysqlquery_log($query);
			echo("1|^|".$grid."|^|".$linkgrid.'|^|'.$fetchresultcount);
		}
		else 
			echo("2|^|Your login might have expired. Please Logout and Login.");
		break;
		
		
}
?>