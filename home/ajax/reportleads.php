<?php

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
			switch($cookie_usertype)
			{
				case "Admin":
				case "Sub Admin":
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC";
					break;
				case "Reporting Authority":
					//Check wheteher the manager is branch head or not
					$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
					$result1 = runmysqlqueryfetch($query1);
					if($result1['branchhead'] == 'yes')
						$branchpiecejoin = "(dealers.branch = '".$result1['branch']."'  OR dealers.managerid = '".$result1['managerid']."')";
					else
						$branchpiecejoin = "lms_users.username = '".$cookie_username."'";
							
					$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE  ".$branchpiecejoin." ORDER BY leads.id DESC";
				if($cookie_username == "srinivasan")
					$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj' ORDER BY leads.id DESC";
					break;
				case "Dealer":
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC";
					break;
			}
     		if($slnocount == '0')
			{
				$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
				//Write the header Row of the table
				$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder">Sl No</td><td nowrap="nowrap" class="tdborder">Lead ID</td><td nowrap="nowrap" class="tdborder">Lead Date</td><td nowrap="nowrap" class="tdborder">Product</td><td nowrap="nowrap" class="tdborder">Company</td><td nowrap="nowrap" class="tdborder">Contact</td><td nowrap="nowrap" class="tdborder">Phone</td><td nowrap="nowrap" class="tdborder">Email ID</td><td nowrap="nowrap" class="tdborder">District<td nowrap="nowrap" class="tdborder">State</td><td nowrap="nowrap" class="tdborder">Dealer</td><td nowrap="nowrap" class="tdborder">Manager</td></tr>';
			}

			
			$result = runmysqlquery($query);
			$fetchresultcount = mysqli_num_rows($result);
			$addlimit = " LIMIT ".$startlimit.",".$limit."; ";
			$query1 = $query.$addlimit;
			$result1 = runmysqlquery($query1);
			while($fetch = mysqli_fetch_row($result1))
			{
				//Begin a row
				$slnocount++;
				$grid .= '<tr>';
				$grid .= "<td nowrap='nowrap' class='tdborder'>&nbsp;".$slnocount."</td>";
				//Write the cell data
				for($i = 0; $i < count($fetch); $i++)
				{
					if($i == 1)
						$grid .= "<td nowrap='nowrap' class='tdborder'>&nbsp;".changedateformatwithtime($fetch[$i])."</td>";
					else
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
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'reportlead\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'reportlead\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';		
		
		// Insert logs on Filter of Lead Mapping
		$query2 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','20','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
		$result2 = runmysqlquery_log($query2);
		
			echo("1|^|".$grid."|^|".$linkgrid."|^|".$fetchresultcount);
		}
		else 
			echo("2|^|Your login might have expired. Please Logout and Login.");
		break;

	case "filter":
		$fromdate = changedateformat($_POST['fromdate']);
		$todate = changedateformat($_POST['todate']);
		$dealerid = $_POST['dealerid'];
		$givenby = $_POST['givenby'];
		$productid = $_POST['productid'];
		$grouplabel = $_POST['grouplabel'];
		$leadstatus = $_POST['leadstatus'];
		$leadsubstatus = $_POST['leadsubstatus']; 
		$filter_followupdate1 = $_POST['filter_followupdate1'];
		$filter_followupdate2 = $_POST['filter_followupdate2'];
		$dropterminatedstatus = $_POST['dropterminatedstatus'];
		$searchtext = $_POST['searchtext'];
		$subselection = $_POST['subselection'];
		$datatype = $_POST['datatype'];
		$leadsource = $_POST['leadsource'];
		$followupcheck = $_POST['followupcheck'];
		$leadsourcelist = ($leadsource == "")?"":("AND leads.refer = '".$leadsource."'");
		
		$lastupdatedby = $_POST['followedby'];
		$remarks = $_POST['remarks']; //echo($_POST['remarks']);exit;
		//$lastfollowupcheckpiece = "AND (followupstatus = 'PENDING' or lms_followup.followupstatus is null )";
		$datatype = ($datatype == "download")?"Product Download":(($datatype == "upload")?"Manual Upload":"");
		$sourcepiece = ($datatype == "")?"":("AND leads.source like '%".$datatype."%'");
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		if($showtype == 'all')
			$limit = 2000;
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
		if($filter_followupdate1 == 'dontconsider')
		{
			$followuppiece = "";
			$followupcheck = "";
			$remarkspiece = "";
		}
		else
		{
			$lastupdatedpiece = ($lastupdatedby == "")?"":(" AND lms_followup.enteredby = '".$lastupdatedby."'");
			$remarkspiece = ($remarks == "")?"":(" AND lms_followup.remarks like '%".$remarks."%'");
			if($followupcheck == 'followuppending')
			{
				/*$followuppiece = "AND lms_followup.followupdate >= '".changedateformat($filter_followupdate1)."' AND lms_followup.followupdate <= '".changedateformat($filter_followupdate2)."'";*/
				$leadquery0 = "select leadid from lms_followup 
				where lms_followup.followupdate >= '".changedateformat($filter_followupdate1)."' 
				AND lms_followup.followupdate <= '".changedateformat($filter_followupdate2)."' 
				AND followupstatus = 'PENDING'". $lastupdatedpiece;
				$leadresult0 = runmysqlquery($leadquery0);
				$count = mysqli_num_rows($leadresult0);
				if($count > 0)
				{
					while($leadfetch0 = mysqli_fetch_array($leadresult0))
					{
						$follow[] = "'".$leadfetch0['leadid']."'";
					}
					$followupvalues = implode(",",$follow);
					$followuppiece = " AND lms_followup.leadid in (" . $followupvalues . ")";
				}
				
				else
				{
				   $followuppiece = "AND lms_followup.leadid = ''";
				}
			}
			else if($followupcheck == 'followupmade')
			{
				$followuppiece = "AND lms_followup.entereddate >= '".changedateformat($filter_followupdate1)."' AND 
				lms_followup.entereddate <= '".changedateformat($filter_followupdate2)."'". $lastupdatedpiece;
			}
		}
		
		if($searchtext <> '')
		{
			switch($subselection)
			{
				case "leadid":
						$searchpiece = ($searchtext == '')?"":("AND leads.id like '%".$searchtext."%'");
						break;
				
				case "company":
						$searchpiece = ($searchtext == '')?"":("AND leads.company like '%".$searchtext."%'");
						break;
						
				case "name": 
						$searchpiece = ($searchtext == '')?"":("AND leads.name like '%".$searchtext."%'");
						break;
						
				case "phone":
						$searchpiece = ($searchtext == '')?"":("AND leads.phone like '%".$searchtext."%'");
						break;
				
				case "cell":
						$searchpiece = ($searchtext == '')?"":("AND leads.cell like '%".$searchtext."%'");
						break;
						
				case "email":
						$searchpiece = ($searchtext == '')?"":("AND leads.emailid like '%".$searchtext."%'");
						break;
				
				case "district":
						$searchpiece = ($searchtext == '')?"":("AND regions.distname like '%".$searchtext."%'");
						break;
				
				case "state":
						$searchpiece = ($searchtext == '')?"":("AND regions.statename like '%".$searchtext."%'"); 
						break;
				
				case "manager":
						$searchpiece = ($searchtext == '')?"":("AND lms_managers.mgrname like '%".$searchtext."%'");
						break;
			}
		}
		else
		{
			$searchpiece = '';
		}
		$dealerpiece = ($dealerid == '')?"":("AND leads.dealerid = '".$dealerid."'");
		// Product 0r category piece
		if($grouplabel == 'Products')
		{
			$productpiece = ($productid == '')?"":("AND productid = '".$productid."'");
		}
		else if($grouplabel == 'Groups')
		{
			$productpiece = ($productid == '')?"":("AND products.category = '".$productid."'");
		}
		
		$leadstatuspiece = ($leadstatus == '')?"":("AND leads.leadstatus = '".$leadstatus."'");
		$leadsubstatuspiece = ($leadsubstatus == '')?"":("AND leads.leadsubstatus = '".$leadsubstatus."'");
		$leaduploadedby = ($givenby == '')?"":(($givenby == 'web')?"AND leaduploadedby IS NULL":"AND leaduploadedby = '".$givenby."'");
		
		$datetimepiece = "substring(leads.leaddatetime,1,10) between '".$fromdate."' AND  '".$todate."'"; 
		$terminatedstatuspiece = ($dropterminatedstatus == 'true')?("AND leads.leadstatus <> 'Order Closed' AND leads.leadstatus <> 'Not Interested' AND leads.leadstatus <> 'Fake Enquiry' AND leads.leadstatus <> 'Registered User'"):"";
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			if(checkdateformat($fromdate) && checkdateformat($todate) && ((datenumeric($todate) - datenumeric($fromdate)) >= 0))
			{
					//Check who is making the entry
				$cookie_username = lmsgetcookie('lmsusername');
				$cookie_usertype = lmsgetcookie('lmsusersort');
	
				switch($cookie_usertype)
				{
					case "Admin":
					case "Sub Admin":
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from leads  left join lms_followup on leads.id = lms_followup.leadid join dealers on leads.dealerid = dealers.id join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode where ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece."  ".$searchpiece." ".$sourcepiece."  ".$lastupdatedpiece." ".$leadsourcelist." ".$leadsubstatuspiece." ORDER BY leads.id DESC";
						break;
					case "Reporting Authority":
						//Check wheteher the manager is branch head or not
						$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
						$result1 = runmysqlqueryfetch($query1);
						if($result1['branchhead'] == 'yes')
							$branchpiecejoin = " AND (dealers.branch = '".$result1['branch']."' OR dealers.managerid = '".$result1['managerid']."')";
						else
							$branchpiecejoin = " AND lms_users.username = '".$cookie_username."'";
							
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname  from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_managers.id where  ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." AND lms_users.type = 'Reporting Authority'  ".$searchpiece." ".$sourcepiece."  ".$lastupdatedpiece." ".$leadsourcelist." ".$branchpiecejoin." ".$leadsubstatuspiece." ORDER BY leads.id DESC";
						if($cookie_username == "srinivasan")
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname  from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_managers.id where  ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." AND lms_users.type = 'Reporting Authority' AND (lms_users.username = 'srinivasan' or  lms_users.username = 'nagaraj') ".$searchpiece." ".$sourcepiece."  ".$lastupdatedpiece." ".$leadsourcelist." ".$leadsubstatuspiece." ORDER BY leads.id DESC";
							
						break;
					case "Dealer":
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname, lms_managers.mgrname  from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = dealers.id where  ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." and lms_users.type = 'Dealer' and lms_users.username = '".$cookie_username."' ".$searchpiece." ".$sourcepiece."  ".$lastupdatedpiece." ".$leadsourcelist." ".$leadsubstatuspiece." ORDER BY leads.id DESC";
					
					break;
					case "Dealer Member":
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname, lms_managers.mgrname from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid  left join  lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_dlrmembers.dlrmbrid where  ".$datetimepiece." ".$terminatedstatuspiece."  ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." ".$leadsubstatuspiece." and lms_users.type = 'Dealer Member' and lms_users.username = '".$cookie_username."' ".$searchpiece." ".$sourcepiece."  ".$lastupdatedpiece." ".$leadsourcelist." ".$leadsubstatuspiece." ORDER BY leads.id DESC";
					break;
				}
                //echo($query);exit;
				if($slnocount == '0')
				{
					$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
				//Write the header Row of the table
					$grid .= '<tr class="gridheader"><td nowrap="nowrap"  class="tdborderlead">Sl No</td><td nowrap="nowrap"  class="tdborderlead">Lead ID</td><td nowrap="nowrap"  class="tdborderlead">Lead Date</td><td nowrap="nowrap"  class="tdborderlead">Product</td><td nowrap="nowrap"  class="tdborderlead">Company</td><td nowrap="nowrap"  class="tdborderlead">Contact</td><td nowrap="nowrap"  class="tdborderlead">Landline</td><td nowrap="nowrap"  class="tdborderlead">Cell</td><td nowrap="nowrap"  class="tdborderlead">Email ID</td><td nowrap="nowrap"  class="tdborderlead">District<td nowrap="nowrap"  class="tdborderlead">State</td><td nowrap="nowrap"  class="tdborderlead">Dealer</td><td nowrap="nowrap"  class="tdborderlead">Manager</td></tr><tbody>';
				}
				$result = runmysqlquery($query);
				$fetchresultcount = mysqli_num_rows($result);
				$addlimit = " LIMIT ".$startlimit.",".$limit.";";
				$query1 = $query.$addlimit;
				$result1 = runmysqlquery($query1);
				if($fetchresultcount > 0)
				{
					while($fetch = mysqli_fetch_row($result1))
					{
						$slnocount++;
						//Begin a row
						$grid .= '<tr>';
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>".$slnocount."</td>";
						//Write the cell data
						for($i = 0; $i < count($fetch); $i++)
						{
							if($i == 1)
								$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".changedateformatwithtime($fetch[$i])."</td>";
							else
								$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch[$i])."</td>";
						}
					
						//End the Row
						$grid .= '</tr>';
					}
				}
				//End of Table
				$grid .= '</tbody></table>';
				if($slnocount >= $fetchresultcount)
					$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
				$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'view\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'view\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
				// Insert logs on filter of Lead
				$query2 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','29','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result2 = runmysqlquery_log($query2);
				
				echo("1|^|".$grid."|^|".$linkgrid.'|^|'.$fetchresultcount);
				//echo(datenumeric($todate) - datenumeric($fromdate));
				//echo("1|^|".$query);
			
			}
			else
			{
				echo("2|^|"."Please Enter Valid Date");
			}
		}
		else 
		//echo(datenumeric($todate) - datenumeric($fromdate));
			echo("3|^|Unable to Process. The data [Eg: Date] may be improper or Your login might have expired. Please Logout and Login.");
		break;
		
		
}
?>