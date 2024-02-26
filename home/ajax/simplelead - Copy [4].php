<?php
ini_set("memory_limit","-1");
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
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.cell,leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC";
					break;
				case "Reporting Authority":
				    //Check wheteher the manager is branch head or not
					$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
					$result1 = runmysqlqueryfetch($query1);
					if($result1['branchhead'] == 'yes')
						$branchpiecejoin = "(dealers.branch = '".$result1['branch']."' OR dealers.managerid  = '".$result1['managerid']."')";
					else
						$branchpiecejoin = "lms_users.username = '".$cookie_username."' ";
					$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE 
					".$branchpiecejoin." ORDER BY leads.id DESC";
				
					if($cookie_username == "srinivasan")
						$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.cell,leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj' ORDER BY leads.id DESC";
					break;
				case "Dealer":
					$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.cell,leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC";
					break;
				case "Dealer Member":
					$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.cell,leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 20 DAY)) AS leads join lms_dlrmembers on lms_dlrmembers.dlrmbrid = leads .dlrmbrid join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = lms_dlrmembers.dlrmbrid AND lms_users.type = 'Dealer Member' WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC";
					break;
			}//echo($query);exit;
			if($slnocount == '0')
			{
				$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead"><tbody>';
				//Write the header Row of the table
				$grid .= '<tr class="gridheader"><td nowrap="nowrap"  class="tdborderlead" style = "border-top:none;">Sl No</td><td nowrap="nowrap"  class="tdborderlead" style = "border-top:none;">Lead ID</td><td nowrap="nowrap"  class="tdborderlead" style = "border-top:none;" >Lead Date</td><td nowrap="nowrap"  class="tdborderlead" style = "border-top:none;">Product</td><td nowrap="nowrap"  class="tdborderlead" style = "border-top:none;">Company</td><td nowrap="nowrap"  class="tdborderlead" style = "border-top:none;">Contact</td><td nowrap="nowrap"  class="tdborderlead" style = "border-top:none;">Landline</td><td nowrap="nowrap"  class="tdborderlead" style = "border-top:none;">Cell</td><td nowrap="nowrap"  class="tdborderlead" style = "border-top:none;">Email ID</td><td nowrap="nowrap" class="tdborderlead" style = "border-top:none;">District<td nowrap="nowrap"  class="tdborderlead" style = "border-top:none;">State</td><td nowrap="nowrap"  class="tdborderlead" style = "border-top:none;">Dealer</td><td nowrap="nowrap" class="tdborderlead" style = "border-top:none;">Manager</td></tr>';
			}
			
			$result = runmysqlquery($query);
			$fetchresultcount = mysqli_num_rows($result);
			//$fetchresultcount = '10000';
			$addlimit = " LIMIT ".$startlimit.",".$limit."; ";
			$query1 = $query.$addlimit;
			$result1 = runmysqlquery($query1);
			if($fetchresultcount > 0)
			{
				while($fetch = mysqli_fetch_row($result1))
				{
					$slnocount++;
					//Begin a row
					$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
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
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'lead\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'lead\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
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
		$filter_followupdate1 = $_POST['filter_followupdate1'];
		$filter_followupdate2 = $_POST['filter_followupdate2'];
		$dropterminatedstatus = $_POST['dropterminatedstatus'];
		$searchtext = $_POST['searchtext'];
		$subselection = $_POST['subselection'];
		$datatype = $_POST['datatype'];
		$leadsource = $_POST['leadsource'];
		$followupcheck = $_POST['followupcheck'];
		$onlydemogiven = $_POST['onlydemogiven'];
		$leadsourcelist = ($leadsource == "")?"":("AND leads.refer = '".$leadsource."'");
		
		$lastupdatedby = $_POST['followedby'];
		$remarks = $_POST['remarks']; //echo($_POST['remarks']);exit;
		$lastfollowupcheckpiece = "AND (lms_followup.followupstatus = 'PENDING' or lms_followup.followupstatus is null )";
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
			$lastupdatedpiece = ($lastupdatedby == "")?"":("AND lms_followup.enteredby = '".$lastupdatedby."'");
			$remarkspiece = ($remarks == "")?"":("AND lms_followup.remarks like '%".$remarks."%'");
			if($followupcheck == 'followuppending')
			{
				$followuppiece = "AND lms_followup.followupdate >= '".changedateformat($filter_followupdate1)."' AND lms_followup.followupdate <= '".changedateformat($filter_followupdate2)."'";
			}
			else if($followupcheck == 'followupmade')
			{
				$followuppiece = "AND lms_followup.entereddate >= '".changedateformat($filter_followupdate1)."' AND lms_followup.entereddate <= '".changedateformat($filter_followupdate2)."'";
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
		$leaduploadedby = ($givenby == '')?"":(($givenby == 'web')?"AND leaduploadedby IS NULL":"AND leaduploadedby = '".$givenby."'");
		$consideronlydemogiven = ($onlydemogiven == 'false')?"":("and  (lms_updatelogs.leadstatus = 'Demo given' and lms_updatelogs.leadid not in (select leadid from lms_updatelogs where leadstatus = 'Not Interested' ))");
		
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
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from leads  left join lms_followup on leads.id = lms_followup.leadid join dealers on leads.dealerid = dealers.id join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join (select distinct leadid,leadstatus from lms_updatelogs ) as lms_updatelogs on lms_updatelogs.leadid = leads.id where ".$datetimepiece." ".$lastfollowupcheckpiece." ".$terminatedstatuspiece." ".$followuppiece." ".$remarkspiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece."  ".$searchpiece." ".$sourcepiece."  ".$lastupdatedpiece." ".$leadsourcelist.$consideronlydemogiven." ORDER BY leads.id DESC";
						break;
					case "Reporting Authority":
					
						//Check wheteher the manager is branch head or not
						$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
						$result1 = runmysqlqueryfetch($query1);
						if($result1['branchhead'] == 'yes')
							$branchpiecejoin = "AND (dealers.branch = '".$result1['branch']."' OR dealers.managerid = '".$result1['managerid']."')";
						else
							$branchpiecejoin = "AND lms_users.username = '".$cookie_username."'";
							
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname  from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_managers.id left join (select distinct leadid,leadstatus from lms_updatelogs ) as lms_updatelogs on lms_updatelogs.leadid = leads.id where  ".$datetimepiece." ".$lastfollowupcheckpiece." ".$terminatedstatuspiece." ".$followuppiece." ".$remarkspiece."  ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." AND lms_users.type = 'Reporting Authority' ".$branchpiecejoin." ".$searchpiece." ".$sourcepiece."  ".$lastupdatedpiece." ".$leadsourcelist.$consideronlydemogiven."  ORDER BY leads.id DESC";
						if($cookie_username == "srinivasan")
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname  from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_managers.id left join (select distinct leadid,leadstatus from lms_updatelogs ) as lms_updatelogs on lms_updatelogs.leadid = leads.id where  ".$datetimepiece."  ".$lastfollowupcheckpiece." ".$terminatedstatuspiece." ".$followuppiece." ".$remarkspiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." AND lms_users.type = 'Reporting Authority' AND (lms_users.username = 'srinivasan' or  lms_users.username = 'nagaraj') ".$searchpiece." ".$sourcepiece."  ".$lastupdatedpiece." ".$leadsourcelist.$consideronlydemogiven." ORDER BY leads.id DESC";
						break;
					case "Dealer":
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname, lms_managers.mgrname  from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = dealers.id left join (select distinct leadid,leadstatus from lms_updatelogs ) as lms_updatelogs on lms_updatelogs.leadid = leads.id where  ".$datetimepiece." ".$terminatedstatuspiece." ".$lastfollowupcheckpiece." ".$followuppiece." ".$remarkspiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." and lms_users.type = 'Dealer' and lms_users.username = '".$cookie_username."' ".$searchpiece." ".$sourcepiece."  ".$lastupdatedpiece." ".$leadsourcelist.$consideronlydemogiven." ORDER BY leads.id DESC";
					
					break;
					case "Dealer Member":
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname, lms_managers.mgrname from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid  left join  lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_dlrmembers.dlrmbrid left join (select distinct leadid,leadstatus from lms_updatelogs ) as lms_updatelogs on lms_updatelogs.leadid = leads.id where  ".$datetimepiece." ".$terminatedstatuspiece."  ".$terminatedstatuspiece." ".$followuppiece." ".$remarkspiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." and lms_users.type = 'Dealer Member' and lms_users.username = '".$cookie_username."' ".$searchpiece." ".$sourcepiece."  ".$lastupdatedpiece." ".$leadsourcelist.$consideronlydemogiven." ORDER BY leads.id DESC";
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
						$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
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
				$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'filter\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'filter\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
				// Insert logs on filter of Lead
				$query2 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','29','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result2 = runmysqlquery($query2);
				
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
		
		
	case "gridtoform":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$form_recid = $_POST['form_recid'];
			$query = "select * from leads where id = '".$form_recid."'";// echo($query);exit;
			$result = runmysqlqueryfetch($query);
			$leadid = $result['id'];
			//Update the dealer view date, if the login type is a Dealer.
			if($cookie_usertype == "Dealer")
			{
				if($result['dealerviewdate'] == '0000-00-00 00:00:00')
				{
					$viewdate = datetimelocal("Y-m-d");
					$viewtime = datetimelocal("H:i:s");
					$query2 = "UPDATE leads SET dealerviewdate = '".$viewdate.' '.$viewtime."' , leadstatus = 'UnAttended' WHERE leads.id = '".$form_recid."'";
					$result2 = runmysqlquery($query2);
					
					$query = "insert into `lms_updatelogs` (leadid, leadstatus, updatedate, updatedby) values('".$form_recid."', 'UnAttended', '".$viewdate.' '.$viewtime."', '".$userslno."')";
					$result = runmysqlquery($query);
					
				}
			}
			$query = "select leads.id AS id, leads.company AS company, leads.name AS name, leads.phone AS phone,leads.stdcode AS stdcode, leads.cell AS cell, leads.dealerviewdate AS dealerviewdate, leads.emailid AS emailid, leads.address AS address, leads.refer AS refer, leads.source AS source, leads.leaddatetime AS leaddatetime, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dealername, lms_managers.mgrname AS managername, leads.leadstatus AS leadstatus, leads.leaduploadedby AS leaduploadedby, leads.lastupdatedby AS lastupdatedby, leads.leadremarks AS leadremarks, leads.lastupdateddate AS lastupdateddate, products.productname AS product, lms_dlrmembers.dlrmbrname AS dlrmbrname,products.id as productid,dealers.id as dealerid from (select * from leads WHERE leads.id = '".$form_recid."') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid";
			$result = runmysqlqueryfetch($query);
			
			$dealerviewdate = ($result['dealerviewdate'] == '0000-00-00 00:00:00')?('Not yet Seen'):(changedateformatwithtime($result['dealerviewdate']));	
			$dlrmbrname = ($result['dlrmbrname'] == "")?"Not Assigned":$result['dlrmbrname'];
			$givenby = ($result['source'] == "Manual Upload")?getuserdisplayname($result['leaduploadedby']):"Webmaster";
			
			// Givenby tooltip text.
			if($givenby == 'Webmaster')
			{
				$givenbytext = '<table width="100%" border="0"  cellspacing="0" cellpadding="0">';
				$givenbytext .= '<tr><td>Web Downloaded.</tr></td>';	
				$givenbytext .= '</table>';
			}
			else if($givenby <> 'Webmaster')
			{
				$givenbyid = $result['leaduploadedby'];
			}
			$givenbytooltiptext = ($result['source'] == "Manual Upload")?tooltiptextdetails($result['leaduploadedby']):$givenbytext;
			//$givenbytooltiptext = tooltiptextdetails($result['leaduploadedby']);
			// Dealer tooltip text
			$query5 = "select dlrcompanyname,dlrname,district,state,dlrcell,dlrphone,dlremail from dealers where dlrcompanyname = '".$result['dealername']."'";
			$fetch5 = runmysqlqueryfetch($query5);
			$dealertooltiptext = '<table width="100%" border="0"  cellspacing="0" cellpadding="0">';
			$dealertooltiptext .= '<tr><td ><strong>Company:</strong> '.$fetch5['dlrcompanyname'].'</td></tr>';
			$dealertooltiptext .= '<tr><td ><strong>Contact Person:</strong> '.$fetch5['dlrname'].'</td></tr>';
			$dealertooltiptext .= '<tr><td ><strong>Place:</strong>'.$fetch5['district'].','.$fetch5['state'].'</td></tr>';
			$dealertooltiptext .= '<tr><td ><strong>Phone:</strong> '.$fetch5['dlrphone'].'</td></tr>';
			$dealertooltiptext .= '<tr><td ><strong>Cell:</strong> '.$fetch5['dlrcell'].'</td></tr>';
			$dealertooltiptext .= '<tr><td><strong>Email Id:</strong> '.$fetch5['dlremail'].'</td></tr>';
			$dealertooltiptext .= '</table>';
			
			// Manager  tooltip text.
			$query6 = "select mgrname,mgrlocation,mgrcell,mgremailid from lms_managers where mgrname = '".$result['managername']."'";
			$fetch6 = runmysqlqueryfetch($query6);
			$managertooltiptext = '<table width="100%" border="0"  cellspacing="0" cellpadding="0">';
			$managertooltiptext .= '<tr><td><strong>Name:</strong> '.$fetch6['mgrname'].'</td></tr>';
			$managertooltiptext .= '<tr><td><strong>Place:</strong> '.$fetch6['mgrlocation'].'</td></tr>';
			$managertooltiptext .= '<tr><td><strong>Cell:</strong> '.$fetch6['mgrcell'].'</td></tr>';
			$managertooltiptext .= '<tr><td><strong>Email:</strong> '.$fetch6['mgremailid'].'</td></tr>';
			$managertooltiptext .= '</table>';
			
			
			if($result['lastupdateddate'] <> '')
				$lastupdateddate = changedateformatwithtime($result['lastupdateddate']);
			else
				$lastupdateddate = "";
	
			if($result['lastupdatedby'] <> '')
			{
				$lastupdatedbyname = getuserdisplayname($result['lastupdatedby']);
			}
			else
				$lastupdatedbyname = "";
			$leadremarks = $result['leadremarks'];
			$leadremarks = ($leadremarks == "")?("Not Available"):($leadremarks);
			
			if($cookie_usertype == 'Admin' ||$cookie_usertype == 'Sub Admin' || $cookie_usertype == 'Reporting Authority' || $cookie_usertype == 'Dealer Member')
			{
				$dealerdisplay = $result['dealername'];
				$dealermember = $dlrmbrname;
			}
			elseif($cookie_usertype == 'Dealer')
			{
				$dealerdisplay = $result['dealername'];
				$dealermember = $dlrmbrname;
			}
			
			
			$output = $leadid."|^|".$result['company']."|^|".$result['id']."|^|".$result['name']."|^|".$result['address']."|^|".$result['distname']."|^|".$result['statename']."|^|".$result['stdcode']."|^|".$result['phone']."|^|".$result['cell']."|^|".$result['emailid']."|^|".$result['refer']."|^|".$result['source']."|^|".$givenby."|^|".changedateformatwithtime($result['leaddatetime'])."|^|".$dealerviewdate."|^|".$result['product']."|^|".$result['dealername']."|^|".$dlrmbrname."|^|".$result['managername']."|^|".$result['leadstatus']."|^|".$lastupdatedbyname."|^|".$lastupdateddate."|^|".$leadremarks."|^|".$result['lastupdatedby']."|^|".$userslno."|^|".$givenbytooltiptext."|^|".$dealertooltiptext."|^|".$managertooltiptext."|^|".$result['productid']."|^|".$result['dealerid']."|^|".$givenbyid."|^|".$dealerdisplay."|^|".$dealermember;
			//$output = $leadid."|^|".$leaddetail."|^|".$leadstatus."|^|".$lastupdatedbyname."|^|".$lastupdateddate."|^|".$leadremarks;
			echo('1|^|'.$output);
		}
		break;

	case "save":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$form_recid = $_POST['form_recid'];
			$form_leadstatus = $_POST['form_leadstatus'];
			$lastupdateddate = datetimelocal("Y-m-d");
			$lastupdatetime = datetimelocal("H:i:s");
			$query2 = "select * from lms_users WHERE username = '".$cookie_username."'";
			$result2 = runmysqlqueryfetch($query2);
			$lastupdatedby = $result2['id'];
			$query = "insert into `lms_updatelogs` (leadid, leadstatus, updatedate, updatedby) values('".$form_recid."', '".$form_leadstatus."', '".$lastupdateddate.' '.$lastupdatetime."', '".$lastupdatedby."')";
			$result = runmysqlquery($query);
			
			$query = "update leads set leadstatus = '".$form_leadstatus."', lastupdateddate = '".$lastupdateddate.' '.$lastupdatetime."', lastupdatedby = '".$lastupdatedby."' WHERE id = '".$form_recid."'";
			$result = runmysqlquery($query);
			
			// Insert logs on save of Lead
			$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','27','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
			$result = runmysqlquery($query);
			$output = "1^Lead Updated Successfully.";
		
			echo($output);
		}
		else 
			echo("2^Your login might have expired. Please Logout and Login.");
		break;

	case "addfollowup":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$leadid = $_POST['form_recid'];
			$form_leadremarks = $_POST['form_leadremarks'];
			$followupdate = $_POST['followupdate'];
			$enteredddate = datetimelocal("Y-m-d");
			
			$output = "";
			if($output == "")
			{
				if($followupdate == "")
				{
					$query = "select * from leads where id = '".$leadid."'";
					$result = runmysqlqueryfetch($query);
					if($result['leadstatus'] <> "Order Closed" && $result['leadstatus'] <> "Registered User" && $result['leadstatus'] <> "Fake Enquiry" && $result['leadstatus'] <> "Not Interested")
						$output = "1"."|^|"."Date of Followup is compulsory.";
				}
			}
			if($output == "" && $followupdate <> "")
			{
				$followupdate = changedateformat($followupdate);
				if(((datenumeric($followupdate) - datenumeric($enteredddate)) < 0))
					$output = "1"."|^|"."Date of Followup cannot be before today.";
			}
			if($output == "")
			{
				$query2 = "select * from lms_users WHERE username = '".$cookie_username."'";
				$result2 = runmysqlqueryfetch($query2);
				$enteredby = $result2['id'];
	
				$query = "UPDATE `lms_followup` SET followupstatus = 'DONE' WHERE leadid = '".$leadid."'";
				$result = runmysqlquery($query);
				
				$query = "insert into `lms_followup` (leadid, remarks, entereddate, followupdate, enteredby, followupstatus) values('".$leadid."', '".$form_leadremarks."', '".$enteredddate."', '".$followupdate."', '".$enteredby."', 'PENDING')";
				$result = runmysqlquery($query);
				
				// Insert logs on lead followup
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','26','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery($query);
			
				$output = "0"."|^|"."Lead Updated Successfully.";
			}
			echo($output);
		}
		else 
			echo("Your login might have expired. Please Logout and Login.");
		break;


	case "showfollowups":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$leadid = $_POST['form_recid'];

			$query = "select lms_followup.followupid AS id, lms_followup.entereddate AS entereddate, lms_followup.remarks AS remarks, lms_followup.followupdate AS followupdate, lms_followup.enteredby AS enteredby from lms_followup WHERE lms_followup.leadid = '".$leadid."' ORDER BY lms_followup.followupid";

			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
			//Write the header Row of the table
			$grid .= '<tr class="gridheader"><td width="9%" nowrap="nowrap"  class="tdborder">Sl No</td><td width="14%" nowrap="nowrap"  class="tdborder">Date</td><td width="37%" nowrap="nowrap"  class="tdborder">Remarks</td><td width="20%" nowrap="nowrap"  class="tdborder">Next Follow-up</td><td width="20%" nowrap="nowrap"  class="tdborder">Entered by</td></tr>';
			$result = runmysqlquery($query);
			$resultcount = mysqli_num_rows($result);
			$loopcount = 0; 
			if($resultcount > 0)
			{
				while($fetch = mysqli_fetch_array($result))
				{
					$loopcount++;
					$grid .= '<tr class="gridrow" onclick="javascript:followuptoform(\''.$fetch['id'].'\');">';
					$grid .= "<td nowrap='nowrap'  class='tdborder'>".$loopcount."</td><td nowrap='nowrap'  class='tdborder'>".changedateformat($fetch['entereddate'])."</td><td nowrap='nowrap'  class='tdborder'>".gridtrim30($fetch['remarks'])."</td><td nowrap='nowrap'  class='tdborder'>".changedateformat($fetch['followupdate'])."</td><td nowrap='nowrap'  class='tdborder'>".getuserdisplayname($fetch['enteredby'])."</td>";
					$grid .= '</tr>';
				}
			}
			//End of Table
			$grid .= '</tbody></table>';
			echo('1^'.$grid);
		}
		else 
			echo("2^Your login might have expired. Please Logout and Login.");
		break;

	case "followuptoform":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$followupid = $_POST['followupid'];
			$query = "select lms_followup.remarks AS remarks, lms_followup.followupdate AS followupdate from lms_followup WHERE lms_followup.followupid = '".$followupid."'";
			$result = runmysqlqueryfetch($query);

			$output = $result['remarks']."|^|".changedateformat($result['followupdate']);
			echo('1|^|'.$output);
		}
		break;
		
	case "updatedata" :
				$id = $_POST['id'];
				$contactperson = $_POST['contactperson'];
				$address = $_POST['address'];
				$stdcode = $_POST['stdcode'];
				$phone = $_POST['phone'];
				$cell = $_POST['cell'];
				$emailid = $_POST['emailid'];
				$query1 = "SELECT * FROM leads WHERE id = '".$id."'";
				$fetch = runmysqlqueryfetch($query1);
				
				//Update leads.
				$query7 = "UPDATE leads SET name ='".$contactperson."',address = '".$address."',stdcode = '".$stdcode."', phone = '".$phone."', cell = '".$cell."' , emailid = '".$emailid."' WHERE id = '".$id."'";
				$result7 = runmysqlquery($query7);
				
				// Insert to logs on update of lead.
				  
				$query9 = "INSERT INTO lms_logs_event(userid,system,eventtype,remarks,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','46','".$fetch['name'].'^'.$fetch['address'].'^'.$fetch['stdcode'].'^'.$fetch['phone'].'^'.$fetch['cell'].'^'.$fetch['emailid']."','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result9 = runmysqlquery($query9);
				
				//echo($query);
				echo('1^Data Updated Successfully.');
				break;
	
	case "transferlead":
				$id = $_POST['id'];
				$todealerid = $_POST['todealerid']; 
				$cookie_usertype = lmsgetcookie('lmsusersort');
				if($cookie_usertype == 'Admin' || $cookie_usertype == 'Sub Admin' || $cookie_usertype == 'Reporting Authority')
				{
					$query1 = "SELECT dealerid,name,leads.phone as phone,products.productname,leads.company  FROM leads left join products on products.id = leads.productid WHERE leads.id = '".$id."'";  //echo('1^'.$query1);exit;
					$fetch = runmysqlqueryfetch($query1); 
					$name = $fetch['name'];
					$phone = $fetch['phone'];
					$product = $fetch['productname'];
					$company = $fetch['company'];
					if($fetch['dealerid'] == $todealerid)
					{
						echo('3^Please Select different Dealer.');
					}
					else
					{
						$transferdate = datetimelocal("Y-m-d");
						$transfertime = datetimelocal("H:i:s");
						
						$query = "UPDATE leads set dealerid = '".$todealerid."',dlrmbrid = '' where leads.id = '".$id."'";  
						$result = runmysqlquery($query); 
						
						$query9 = "select dlrcompanyname,lms_managers.mgrname,dlrcell from dealers  left join lms_managers on lms_managers.id = dealers.managerid where dealers.id = '".$todealerid."'"; 
						$result9 = runmysqlqueryfetch($query9); //echo($query9);exit;
						//$company = $result9['dlrcompanyname'];
						
						// Dealer tooltip text
						$query5 = "select dlrcompanyname,dlrname,district,state,dlrcell,dlrphone,dlremail from dealers where dlrcompanyname = '".$result9['dlrcompanyname']."'";
						$fetch5 = runmysqlqueryfetch($query5);
						$dealertooltiptext = '<table width="100%" border="0"  cellspacing="0" cellpadding="0">';
						$dealertooltiptext .= '<tr><td ><strong>Company:</strong> '.$fetch5['dlrcompanyname'].'</td></tr>';
						$dealertooltiptext .= '<tr><td ><strong>Contact Person:</strong> '.$fetch5['dlrname'].'</td></tr>';
						$dealertooltiptext .= '<tr><td ><strong>Place:</strong>'.$fetch5['district'].','.$fetch5['state'].'</td></tr>';
						$dealertooltiptext .= '<tr><td ><strong>Phone:</strong> '.$fetch5['dlrphone'].'</td></tr>';
						$dealertooltiptext .= '<tr><td ><strong>Cell:</strong> '.$fetch5['dlrcell'].'</td></tr>';
						$dealertooltiptext .= '<tr><td><strong>Email Id:</strong> '.$fetch5['dlremail'].'</td></tr>';
						$dealertooltiptext .= '</table>';
						
						// Send SMS to respective Dealer.
						$servicename = 'LEAD Transfer Single';
						$tonumber = $fetch5['dlrcell'];
						$smstext = "Relyon LMS: ".substr($name, 0, 29)." of ".substr($company, 0, 29)." requires ".substr($product, 0, 29).". Call ".substr($phone, 0, 29)."."; //echo($smstext);exit;
						$senddate = $transferdate;
						$sendtime = $transfertime;
					//	sendsmsforleads($servicename, $tonumber, $smstext, $senddate, $sendtime, NULL,NULL);
						
						
						// Manager  tooltip text.
						$query6 = "select mgrname,mgrlocation,mgrcell,mgremailid from lms_managers where mgrname = '".$result9['mgrname']."'";
						$fetch6 = runmysqlqueryfetch($query6);
						$managertooltiptext = '<table width="100%" border="0"  cellspacing="0" cellpadding="0">';
						$managertooltiptext .= '<tr><td><strong>Name:</strong> '.$fetch6['mgrname'].'</td></tr>';
						$managertooltiptext .= '<tr><td><strong>Place:</strong> '.$fetch6['mgrlocation'].'</td></tr>';
						$managertooltiptext .= '<tr><td><strong>Cell:</strong> '.$fetch6['mgrcell'].'</td></tr>';
						$managertooltiptext .= '<tr><td><strong>Email:</strong> '.$fetch6['mgremailid'].'</td></tr>';
						$managertooltiptext .= '</table>';
						
						// Insert logs on save of Lead Transfer (To Dealer)
						$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','25','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
						$result = runmysqlquery($query);
						
						
						// Insert that to transfer logs
						$query8 = "insert into `lms_transferlogs` (leadid, fromdealer, todealer, transferdate, trasferredby) values('".$id."', '".$fetch['dealerid']."', '".$todealerid."', '".$transferdate.' '.$transfertime."', '".$userslno."')";
						$result8 = runmysqlquery($query8);
						
						
						
						echo('1^Lead Transfered Successfully!'.'^'.$result9['dlrcompanyname'].'^'.$result9['mgrname'].'^'.$dealertooltiptext.'^'.$managertooltiptext);
					}
				}
				else if($cookie_usertype == 'Dealer')
				{
					$transferdate = datetimelocal("Y-m-d");
					$transfertime = datetimelocal("H:i:s");
					$cookie_username = lmsgetcookie('lmsusername');
					
					// Insert logs on save of Lead Transfer (To Dealer Member)
					$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','25','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
					$result = runmysqlquery($query);
					
					
					$query = "update leads set dlrmbrid = '".$todealerid."' WHERE id = '".$id."'";
					$result = runmysqlquery($query);
					$output = "2^Lead Transfered Successfully.";
					echo($output);
				}
				break;
				
	case "updatelogs":
			$id = $_POST['id'];
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
			$query1 = "SELECT * FROM lms_updatelogs WHERE leadid = '".$id."'";
			$result1 = runmysqlquery($query1);
			$fetchresultcount = mysqli_num_rows($result1);
			if($fetchresultcount <> 0)
			{
				$query1 = "SELECT * FROM lms_updatelogs WHERE leadid = '".$id."'";
				$result1 = runmysqlqueryfetch($query1);
				//$query2 = "SELECT username from lms_users where id = '".$result1['updatedby']."'";
				//$result2 = runmysqlqueryfetch($query2);
				if($slnocount == 0)
				{
					$grid .= '<table width="100%" border="0"  cellspacing="0" cellpadding="2" id="gridtable1">';
					//Write the header Row of the table
					$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder1">Sl No</td><td nowrap="nowrap" class="tdborder1">Lead Status</td><td nowrap="nowrap" class="tdborder1">Updated Date</td><td nowrap="nowrap" class="tdborder1">Updated By</td></tr>';
				}
				
				$query = "SELECT * FROM lms_updatelogs WHERE leadid = '".$id."' LIMIT ".$startlimit.",".$limit."; ";
				$result = runmysqlquery($query);
				while($fetch = mysqli_fetch_array($result))
				{
					$slnocount++;
					$grid .= '<tr><td nowrap="nowrap"  class="tdborder1">'.$slnocount.'</td><td nowrap="nowrap" class="tdborder1">'.$fetch['leadstatus'].'</td><td nowrap="nowrap" class="tdborder1">'.changedateformatwithtime($fetch['updatedate']).'</td><td nowrap="nowrap" class="tdborder1">'.getuserdisplayname($fetch['updatedby']).'</td></tr>';
					
				}
				$grid .='</table>';
				if($slnocount >= $fetchresultcount)
					$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
				else
					$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords3(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords3(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
					echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);	
			}
			else
			{
				$grid .= '<table width="100%" border="0"  cellspacing="0" cellpadding="2" id="gridtable1">';
				//Write the header Row of the table
				$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder1">Sl No</td><td nowrap="nowrap" class="tdborder1">Lead Status</td><td nowrap="nowrap" class="tdborder1">updated Date</td><td nowrap="nowrap" class="tdborder1">Updated By</td></tr>';
				$grid .= '</table>';
				echo('2^'.$grid);
			}
			
			//echo('1^'.$fetchresultcount);
			break;
			
		case "transferlogs" :
				$id = $_POST['id'];
				$startlimit = $_POST['startlimit'];
				$slnocount = $_POST['slnocount'];
				$showtype = $_POST['showtype'];
				$cookie_usertype = lmsgetcookie('lmsusersort');
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
				
				
				$query1 = "select count(*) as totalcount from (SELECT dealers.id, dealers.dlrcompanyname,lms_transferlogs.leadid,lms_transferlogs.transferdate,lms_transferlogs.trasferredby,lms_users.username  from lms_transferlogs left join lms_users on lms_users.id = lms_transferlogs.trasferredby left join dealers on dealers.id = lms_transferlogs.fromdealer WHERE lms_transferlogs.leadid = '".$id."') as table1 left join(SELECT dealers.id, dealers.dlrcompanyname,lms_transferlogs.leadid,lms_transferlogs.transferdate,lms_transferlogs.trasferredby,lms_users.username  from lms_transferlogs left join lms_users on lms_users.id = lms_transferlogs.trasferredby left join dealers on dealers.id = lms_transferlogs.todealer WHERE lms_transferlogs.leadid = '".$id."') as table2 on table1.transferdate = table2.transferdate;";
					
					$result1 = runmysqlqueryfetch($query1);
					$fetchresultcount = $result1['totalcount'];
					
						if($slnocount == 0)
						{
							$grid .= '<table width="100%" border="0"  cellspacing="0" cellpadding="2" id="gridtable1">';
							//Write the header Row of the table
							$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder1">Sl No</td><td nowrap="nowrap" class="tdborder1">From Dealer</td><td nowrap="nowrap" class="tdborder1">To Dealer</td><td nowrap="nowrap" class="tdborder1">Transfer Date</td><td nowrap="nowrap" class="tdborder1">Transfered By</td></tr>';
						}
						$query = "select distinct  table1.id,table1.leadid,table1.transferdate,table1.dlrcompanyname as fromdealer,table2.dlrcompanyname as todealer,table1.trasferredby from (SELECT dealers.id, dealers.dlrcompanyname,lms_transferlogs.leadid,lms_transferlogs.transferdate,lms_transferlogs.trasferredby,lms_users.username  from lms_transferlogs left join lms_users on lms_users.id = lms_transferlogs.trasferredby left join dealers on dealers.id = lms_transferlogs.fromdealer WHERE lms_transferlogs.leadid = '".$id."') as table1 left join(SELECT dealers.id, dealers.dlrcompanyname,lms_transferlogs.leadid,lms_transferlogs.transferdate,lms_transferlogs.trasferredby,lms_users.username  from lms_transferlogs left join lms_users on lms_users.id = lms_transferlogs.trasferredby left join dealers on dealers.id = lms_transferlogs.todealer WHERE lms_transferlogs.leadid = '".$id."') as table2 on table1.transferdate = table2.transferdate LIMIT ".$startlimit.",".$limit.""; //echo('1^'.$query);exit;
						$result = runmysqlquery($query);
						while($fetch = mysqli_fetch_array($result))
						{
							$slnocount++;
							$grid .= '<tr>';
							$grid .= '<td nowrap="nowrap" class="tdborder1">'.$slnocount.'</td><td nowrap="nowrap" class="tdborder1">'.$fetch['fromdealer'].'</td><td nowrap="nowrap" class="tdborder1">'.$fetch['todealer'].'</td><td nowrap="nowrap" class="tdborder1">'.changedateformatwithtime($fetch['transferdate']).'</td><td nowrap="nowrap" class="tdborder1">'.getuserdisplayname($fetch['trasferredby']).'</td>';
							$grid .= '</tr>';
						}
						$grid .='</table>';	
						if($slnocount >= $fetchresultcount)
							$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
						else
							$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords2(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords2(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';		
						echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
					
					
					
				break;
				
		case 'followupforday':
				if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
				{
					$cookie_username = lmsgetcookie('lmsusername');
					$cookie_usertype = lmsgetcookie('lmsusersort');	 //echo($cookie_usertype);exit;
					$startlimit = $_POST['startlimit'];
					$slnocount = $_POST['slnocount'];
					$showtype = $_POST['showtype'];
					if($showtype == 'all')
						$limit = 1000;
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
					switch($cookie_usertype)
					{
						case 'Admin':
						case 'Sub Admin':
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join lms_followup on lms_followup.leadid = leads.id left join lms_users on lms_users.id = lms_followup.enteredby left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode  where lms_followup.followupdate BETWEEN DATE_SUB(CURDATE(),INTERVAL 2 DAY) and CURDATE() and lms_followup.followupstatus = 'PENDING' and leads.leadstatus <> 'Fake Enquiry'and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed' and leads.leadstatus <> 'Registered User' and lms_followup.followupdate <> '0000-00-00' ORDER BY lms_followup.followupdate DESC";
							break;
							
						case 'Reporting Authority':
						
						 //Check wheteher the manager is branch head or not
						$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
						$result1 = runmysqlqueryfetch($query1);
						if($result1['branchhead'] == 'yes')
							$branchpiecejoin = "AND (dealers.branch = '".$result1['branch']."' OR dealers.managerid = '".$result1['managerid']."')";
						else
							$branchpiecejoin = "and lms_users.username = '".$cookie_username."'";
							
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join lms_followup on lms_followup.leadid = leads.id left join lms_users on lms_users.id = lms_followup.enteredby left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode where lms_followup.followupdate BETWEEN DATE_SUB(CURDATE(),INTERVAL 2 DAY) and CURDATE() and lms_followup.followupstatus = 'PENDING' and leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed'and leads.leadstatus <> 'Registered User' and lms_followup.followupdate <> '0000-00-00'  and lms_users.type = 'Reporting Authority' ".$branchpiecejoin." ORDER BY lms_followup.followupdate DESC";
							//echo($query);exit;
							break;
						
						case 'Dealer':
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join lms_followup on lms_followup.leadid = leads.id left join lms_users on lms_users.id = lms_followup.enteredby left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode  where lms_followup.followupdate BETWEEN DATE_SUB(CURDATE(),INTERVAL 2 DAY) and CURDATE() and lms_followup.followupstatus = 'PENDING' and leads.leadstatus <> 'Fake Enquiry'and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed'and leads.leadstatus <> 'Registered User' and lms_followup.followupdate <> '0000-00-00' and lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer' ORDER BY lms_followup.followupdate DESC";
							break;
							
						case 'Dealer Member':
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join lms_followup on lms_followup.leadid = leads.id left join lms_users on lms_users.referenceid = leads.dlrmbrid left join dealers on dealers.id =leads.dealerid left join lms_dlrmembers on lms_users.referenceid = lms_dlrmembers.dlrmbrid left join lms_managers on lms_managers.id =dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode  where lms_followup.followupdate BETWEEN DATE_SUB(CURDATE(),INTERVAL 2 DAY) and CURDATE() and lms_followup.followupstatus = 'PENDING' and leads.leadstatus <> 'Fake Enquiry'and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed' and leads.leadstatus <> 'Registered User' and lms_followup.followupdate <> '0000-00-00' and lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer Member' ORDER BY lms_followup.followupdate DESC";
							break;
					}
					//echo($query);exit;
					if($slnocount == '0')
					{
						$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
					//Write the header Row of the table
						$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborderlead">Sl No</td><td nowrap="nowrap"  class="tdborderlead">Lead ID</td><td nowrap="nowrap"  class="tdborderlead">Lead Date</td><td nowrap="nowrap"  class="tdborderlead">Product</td><td nowrap="nowrap"  class="tdborderlead">Company</td><td nowrap="nowrap"  class="tdborderlead">Contact</td><td nowrap="nowrap"  class="tdborderlead">Landline</td><td nowrap="nowrap"  class="tdborderlead">Cell</td><td nowrap="nowrap"  class="tdborderlead">Email ID</td><td nowrap="nowrap"  class="tdborderlead">District<td nowrap="nowrap"  class="tdborderlead">State</td><td nowrap="nowrap"  class="tdborderlead">Dealer</td><td nowrap="nowrap"  class="tdborderlead">Manager</td></tr><tbody>';
					}
					$result = runmysqlquery($query);   //echo($query); exit;
					$fetchresultcount = mysqli_num_rows($result);
					$addlimit = " LIMIT ".$startlimit.",".$limit."; ";
					$query1 = $query.$addlimit; 
					$result1 = runmysqlquery($query1);
					//echo($query1); exit;
					while($fetch = mysqli_fetch_row($result1))
					{
						$slnocount++;
						//Begin a row
						$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".$slnocount."</td>";
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
					$grid .= "</tbody></table>";
					if($slnocount >= $fetchresultcount)
						$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
					else
						$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecordsfollowup(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecordsfollowup(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';		
					echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
						//echo('1^'.$query1.'^'.$limit);
				}
				break;
				
	case 'nofollowup':
			if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
			{
				$cookie_username = lmsgetcookie('lmsusername');
				$cookie_usertype = lmsgetcookie('lmsusersort');	
				$startlimit = $_POST['startlimit'];
				$slnocount = $_POST['slnocount'];
				$showtype = $_POST['showtype'];
				if($showtype == 'all')
					$limit = 1000;
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
				$month = date('m');
				if($month >= '04')
				{
					$datepiece = "AND substring(leaddatetime,1,10) between concat(year(curdate()),'-04-01') and curdate()";
				}
				else 
				{
					$datepiece = "AND substring(leaddatetime,1,10) between concat(year(curdate()) - 1,'-04-01') and curdate()";
				}
				switch($cookie_usertype)
				{
					case 'Admin':
					case 'Sub Admin':
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode where leads.id not in(select leadid from lms_followup) and leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed' and leads.leadstatus <> 'Registered User' ".$datepiece." ORDER BY leads.id DESC";
							break;
					case 'Reporting Authority':
					
							 //Check wheteher the manager is branch head or not
							$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
							$result1 = runmysqlqueryfetch($query1);
							if($result1['branchhead'] == 'yes')
								$branchpiecejoin = "AND (dealers.branch = '".$result1['branch']."' OR dealers.managerid = '".$result1['managerid']."')";
							else
								$branchpiecejoin = "and lms_users.username = '".$cookie_username."'";
							
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_managers.id  where leads.id not in(select leadid from lms_followup) and leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed' and leads.leadstatus <> 'Registered User'  and lms_users.type = 'Reporting Authority' ".$datepiece.$branchpiecejoin." ORDER BY leads.id DESC";
							break;
					
					case 'Dealer':
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid  left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = leads.dealerid where leads.id not in(select leadid from lms_followup) and leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested' and leads.leadstatus <> 'Order Closed' and leads.leadstatus <> 'Registered User' and lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer' ".$datepiece." ORDER BY leads.id DESC";
							break;
					
					case 'Dealer Member':
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = leads.dlrmbrid left join lms_dlrmembers on lms_dlrmembers.dealerid = dealers.id  where leads.id not in(select leadid from lms_followup) and leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed' and leads.leadstatus <> 'Registered User' and lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer Member' ".$datepiece." ORDER BY leads.id DESC";
							break;
					
				}
				//echo($query);exit;
				if($slnocount == '0')
				{
					$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
				//Write the header Row of the table
					$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborderlead">Sl No</td><td nowrap="nowrap" class="tdborderlead">Lead ID</td><td nowrap="nowrap"  class="tdborderlead">Lead Date</td><td nowrap="nowrap"  class="tdborderlead">Product</td><td nowrap="nowrap"  class="tdborderlead">Company</td><td nowrap="nowrap"  class="tdborderlead">Contact</td><td nowrap="nowrap"  class="tdborderlead">Landline</td><td nowrap="nowrap"  class="tdborderlead">Cell</td><td nowrap="nowrap"  class="tdborderlead">Email ID</td><td nowrap="nowrap"  class="tdborderlead">District<td nowrap="nowrap"  class="tdborderlead">State</td><td nowrap="nowrap"  class="tdborderlead">Dealer</td><td nowrap="nowrap"  class="tdborderlead">Manager</td></tr><tbody>';
				}//echo($query);exit;
				$result = runmysqlquery($query); 
				$fetchresultcount = mysqli_num_rows($result);
				$addlimit = " LIMIT ".$startlimit.",".$limit."; ";
				$query1 = $query.$addlimit; 
				$result1 = runmysqlquery($query1);
				//echo($query1); exit;
				while($fetch = mysqli_fetch_row($result1))
				{
					$slnocount++;
					//Begin a row
					$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
					$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".$slnocount."</td>";
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
				$grid .= "</tbody></table>";
				if($slnocount >= $fetchresultcount)
					$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
				else
					$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecordsnofollowup(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecordsnofollowup(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';		
				echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
					//echo('1^'.$query1.'^'.$limit);
				
			}
			break;
			
	case 'notviewed':
			if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
			{
				$cookie_username = lmsgetcookie('lmsusername');
				$cookie_usertype = lmsgetcookie('lmsusersort');	
				$startlimit = $_POST['startlimit'];
				$slnocount = $_POST['slnocount'];
				$showtype = $_POST['showtype'];
				if($showtype == 'all')
					$limit = 1000;
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
				$month = date('m');
				if($month >= '04')
				{
					$datepiece = "AND substring(leaddatetime,1,10) between concat(year(curdate()),'-04-01') and curdate()";
				}
				else 
				{
					$datepiece = "AND substring(leaddatetime,1,10) between concat(year(curdate()) - 1,'-04-01') and curdate()";
				}
				switch($cookie_usertype)
				{
					case 'Admin':
					case 'Sub Admin':
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id =leads.dealerid  left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode where leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested' and leads.leadstatus <> 'Registered User' and leads.leadstatus <> 'Order Closed' and leads.leadstatus = 'Not Viewed' ".$datepiece." order by leads.id DESC";
							break;
					case 'Reporting Authority':
					
							//Check wheteher the manager is branch head or not
							$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
							$result1 = runmysqlqueryfetch($query1);
							if($result1['branchhead'] == 'yes')
								$branchpiecejoin = "AND (dealers.branch = '".$result1['branch']."' OR dealers.managerid = '".$result1['managerid']."')";
							else
								$branchpiecejoin = "and lms_users.username = '".$cookie_username."'";
								
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id =leads.dealerid  left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_managers.id where leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested' and leads.leadstatus <> 'Registered User' and leads.leadstatus <> 'Order Closed' and leads.leadstatus = 'Not Viewed'  and lms_users.type = 'Reporting Authority' ".$datepiece.$branchpiecejoin." order by leads.id DESC";
							//echo($query);exit;
							break;
					
					case 'Dealer':
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id =leads.dealerid  left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = dealers.id  where leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested' and leads.leadstatus <> 'Registered User' and leads.leadstatus <> 'Order Closed' and leads.leadstatus = 'Not Viewed' and lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer' ".$datepiece." order by leads.id DESC";
							break;
					
					case 'Dealer Member':
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = leads.dlrmbrid left join lms_dlrmembers on lms_dlrmembers.dealerid = leads.dealerid  where leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested' and leads.leadstatus <> 'Registered User' and leads.leadstatus <> 'Order Closed' and leads.leadstatus = 'Not Viewed' and lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer Member' ".$datepiece." order by leads.id DESC";
							break;
					
				}//echo($query);exit;
				if($slnocount == '0')
				{
					$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
				//Write the header Row of the table
					$grid .= '<tr class="gridheader"><td nowrap="nowrap"  class="tdborderlead">Sl No</td><td nowrap="nowrap"  class="tdborderlead">Lead ID</td><td nowrap="nowrap"  class="tdborderlead">Lead Date</td><td nowrap="nowrap"  class="tdborderlead">Product</td><td nowrap="nowrap"  class="tdborderlead">Company</td><td nowrap="nowrap"  class="tdborderlead">Contact</td><td nowrap="nowrap"  class="tdborderlead">Landline</td><td nowrap="nowrap"  class="tdborderlead">Cell</td><td nowrap="nowrap"  class="tdborderlead">Email ID</td><td nowrap="nowrap"  class="tdborderlead">District<td nowrap="nowrap"  class="tdborderlead">State</td><td nowrap="nowrap"  class="tdborderlead">Dealer</td><td nowrap="nowrap"  class="tdborderlead">Manager</td></tr><tbody>';
				}
				$result = runmysqlquery($query);
				$fetchresultcount = mysqli_num_rows($result);
				$addlimit = " LIMIT ".$startlimit.",".$limit."; ";
				$query1 = $query.$addlimit; 
				$result1 = runmysqlquery($query1);
				//echo($query1); exit;
				while($fetch = mysqli_fetch_row($result1))
				{
					$slnocount++;
					//Begin a row
					$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
					$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".$slnocount."</td>";
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
				$grid .= "</tbody></table>";
				if($slnocount >= $fetchresultcount)
					$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
				else
					$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecordsnotviewed(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecordsnotviewed(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';		
				echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
					//echo('1^'.$query1.'^'.$limit);
				
			}
			break;
		
		case 'otherleads':
			if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
			{
				$id = $_POST['id'];
				$startlimit = $_POST['startlimit'];
				$slnocount = $_POST['slnocount'];
				$showtype = $_POST['showtype'];
				if($showtype == 'all')
					$limit = 1000;
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
				$query1 = "select * from leads where id = '".$id."'";
				$result = runmysqlqueryfetch($query1);
				$query = "select distinct leads.id, substring(leads.leaddatetime,1,10), products.productname, leads.company,dealers.dlrcompanyname from leads left join dealers on dealers.id =leads.dealerid  left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode where emailid = '".$result['emailid']."'  order by leads.id DESC";
				if($slnocount == '0')
				{
					$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
				//Write the header Row of the table
					$grid .= '<tr class="gridheader"><td nowrap="nowrap"  class="tdborderlead">Sl No</td><td nowrap="nowrap"  class="tdborderlead">Lead ID</td><td nowrap="nowrap"  class="tdborderlead">Lead Date</td><td nowrap="nowrap"  class="tdborderlead">Product</td><td nowrap="nowrap"  class="tdborderlead">Company</td><td nowrap="nowrap"  class="tdborderlead">Dealer</td></tr><tbody>';
				}
				$result = runmysqlquery($query);
				$fetchresultcount = mysqli_num_rows($result);
				$addlimit = " LIMIT ".$startlimit.",".$limit."; ";
				$query1 = $query.$addlimit; 
				$result1 = runmysqlquery($query1);
				//echo($query1); exit;
				while($fetch = mysqli_fetch_row($result1))
				{
					$slnocount++;
					//Begin a row
					$grid .= '<tr>';
					$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".$slnocount."</td>";
					//Write the cell data
					for($i = 0; $i < count($fetch); $i++)
					{
						if($i == 1)
							$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".changedateformat($fetch[$i])."</td>";
						else
							$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch[$i])."</td>";
					}
				
					//End the Row
					$grid .= '</tr>';
				}	
				$grid .= "</tbody></table>";
				if($slnocount >= $fetchresultcount)
					$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
				else
					$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreofotherleads(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreofotherleads(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';		
				echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
				
			}
			break;		
			
	case 'downloadlogs':	
			if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
			{
				$id = $_POST['id'];
				$startlimit = $_POST['startlimit'];
				$slnocount = $_POST['slnocount'];
				$showtype = $_POST['showtype'];
				if($showtype == 'all')
					$limit = 1000;
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
				$query1 = "select emailid from leads where id = '".$id."'";
				$result = runmysqlqueryfetch($query1);
				$query = "select users.company,downloads.product,downloads.date,downloads.time from downloads left join users on users.slno = downloads.userid where downloads.emailid = '".$result['emailid']."' order by downloads.slno DESC";
				if($slnocount == '0')
				{
					$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
				//Write the header Row of the table
					$grid .= '<tr class="gridheader"><td nowrap="nowrap"  class="tdborderlead">Sl No</td><td nowrap="nowrap"  class="tdborderlead">Company</td><td nowrap="nowrap"  class="tdborderlead">Product</td><td nowrap="nowrap"  class="tdborderlead">Date(Time)</td></tr><tbody>';
				}
				$result = runmysqlquery($query);
				$fetchresultcount = mysqli_num_rows($result);
				$addlimit = " LIMIT ".$startlimit.",".$limit."; ";
				$query1 = $query.$addlimit; 
				$result1 = runmysqlquery($query1);
				//echo($query1); exit;
				while($fetch = mysqli_fetch_array($result1))
				{
					$slnocount++;
					//Begin a row
					$grid .= '<tr>';
					$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".$slnocount."</td>";
					$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".$fetch['company']."</td>";
					$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".$fetch['product']."</td>";
					$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".changedateformat($fetch['date'])."(".$fetch['time'].")"."</td>";
					$grid .= '</tr>';
				}	
				$grid .= "</tbody></table>";
				if($slnocount >= $fetchresultcount)
					$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
				else
					$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreofdownloadlogs(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreofdownloadlogs(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';		
				echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
				
			}	
			break;
			
	case "statusstrip":		
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$month = date('m');
			if($month >= '04')
			{
				$datepiece = " substring(leaddatetime,1,10) between concat(year(curdate()),'-04-01') and curdate()";
			}
			else 
			{
				$datepiece = " substring(leaddatetime,1,10) between concat(year(curdate()) - 1,'-04-01') and curdate()";
			}
			switch($cookie_usertype)
			{
				case 'Admin':
				case 'Sub Admin':
					$query = "select count(leadstatus = 'Not Viewed' OR NULL) AS notviewed,count(leadstatus = 'UnAttended' OR NULL) AS unattended, count(leadstatus = 'Fake Enquiry' OR NULL) AS fakeenquiry, count(leadstatus = 'Not Interested' OR NULL) AS notinterested, count(leadstatus = 'Registered User' OR NULL) AS registereduser, count(leadstatus = 'Attended' OR NULL) AS attended, count(leadstatus = 'Demo Given' OR NULL) AS demogiven, count(leadstatus = 'Quote Sent' OR NULL) AS quotesent, count(leadstatus = 'Perusing to Purchase' OR NULL) AS persuing2purchase, count(leadstatus = 'Order Closed' OR NULL) AS orderclosed from leads where  ".$datepiece."";
					break;
				
				case "Reporting Authority":
					$query = "select count(leadstatus = 'Not Viewed' OR NULL) AS notviewed,count(leadstatus = 'UnAttended' OR NULL) AS unattended, count(leadstatus = 'Fake Enquiry' OR NULL) AS fakeenquiry, count(leadstatus = 'Not Interested' OR NULL) AS notinterested, count(leadstatus = 'Registered User' OR NULL) AS registereduser, count(leadstatus = 'Attended' OR NULL) AS attended, count(leadstatus = 'Demo Given' OR NULL) AS demogiven, count(leadstatus = 'Quote Sent' OR NULL) AS quotesent, count(leadstatus = 'Perusing to Purchase' OR NULL) AS persuing2purchase, count(leadstatus = 'Order Closed' OR NULL) AS orderclosed from leads left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid left join lms_users on lms_users.referenceid = lms_managers.id  where  ".$datepiece." and lms_users.username = '".$cookie_username."' and lms_users.type = 'Reporting Authority'";
					break;
					
				case "Dealer":
					$query = "select count(leadstatus = 'Not Viewed' OR NULL) AS notviewed,count(leadstatus = 'UnAttended' OR NULL) AS unattended, count(leadstatus = 'Fake Enquiry' OR NULL) AS fakeenquiry, count(leadstatus = 'Not Interested' OR NULL) AS notinterested, count(leadstatus = 'Registered User' OR NULL) AS registereduser, count(leadstatus = 'Attended' OR NULL) AS attended, count(leadstatus = 'Demo Given' OR NULL) AS demogiven, count(leadstatus = 'Quote Sent' OR NULL) AS quotesent, count(leadstatus = 'Perusing to Purchase' OR NULL) AS persuing2purchase, count(leadstatus = 'Order Closed' OR NULL) AS orderclosed from leads  
left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid  left join lms_users on lms_users.referenceid = dealers.id where ".$datepiece." and lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer' ";
					break;
				
				case "Dealer Member":
					$query = "select count(leadstatus = 'Not Viewed' OR NULL) AS notviewed,count(leadstatus = 'UnAttended' OR NULL) AS unattended, count(leadstatus = 'Fake Enquiry' OR NULL) AS fakeenquiry, count(leadstatus = 'Not Interested' OR NULL) AS notinterested, count(leadstatus = 'Registered User' OR NULL) AS registereduser, count(leadstatus = 'Attended' OR NULL) AS attended, count(leadstatus = 'Demo Given' OR NULL) AS demogiven, count(leadstatus = 'Quote Sent' OR NULL) AS quotesent, count(leadstatus = 'Perusing to Purchase' OR NULL) AS persuing2purchase, count(leadstatus = 'Order Closed' OR NULL) AS orderclosed  from leads 
left join dealers on dealers.id = leads.dealerid left join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid  left join  lms_managers on lms_managers.id=dealers.managerid 
left join lms_users on lms_users.referenceid = lms_dlrmembers.dlrmbrid
where  ".$datepiece." and lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer Member'";
					break;
					
			}
			$fetch = runmysqlqueryfetch($query);
			$total = $fetch['notviewed'] + $fetch['unattended'] + $fetch['fakeenquiry'] + $fetch['notinterested'] + $fetch['registereduser'] + $fetch['attended'] + $fetch['demogiven'] + $fetch['quotesent'] + $fetch['persuing2purchase'] + $fetch['orderclosed'];
			echo('1^'.$total.'^'.$fetch['notviewed'].'^'.$fetch['unattended'].'^'.$fetch['fakeenquiry'].'^'.$fetch['notinterested'].'^'.$fetch['registereduser'].'^'.$fetch['attended'].'^'.$fetch['demogiven'].'^'.$fetch['quotesent'].'^'.$fetch['persuing2purchase'].'^'.$fetch['orderclosed']);
			break;
			
	case "createlead":
			$product = $_POST['productid'];
			$leadid = $_POST['leadid'];
			$dealerselectiontype = $_POST['radiovalue'];
			$remarks = $_POST['remarks'];
			$dealerid = $_POST['dealer'];
			$emailid = $_POST['newleademailid'];
			$cellnumber = $_POST['newleadcell'];
			$contactperson = $_POST['newleadcontactperson'];
			$leadsource = $_POST['newleadsource'];
			$leaddate = datetimelocal("Y-m-d");
			$leadtime = datetimelocal("H:i:s");
			switch($dealerselectiontype)
			{
				case "samedealer":
					$query = "select * from leads where id = '".$leadid."'";
					$fetch = runmysqlqueryfetch($query);
					if($fetch['productid'] == $product)
					{
						echo('2^Please select different Product');
					}
					else if($fetch['dealerid'] == $dealerid)
					{
						$query = "SELECT * FROM leads WHERE emailid = '".$emailid."' AND productid = '".$product."'";
						$result = runmysqlquery($query);
						$count = mysqli_num_rows($result);
						if($count > 0)
						{
							$result = runmysqlqueryfetch($query);
							$leadid = $result['id'];
							echo("2^Lead already exists for same product [Lead ID: ".$leadid."].");
						}
						else
						{
							$query2 = "insert into `leads` (dealerid, productid, source, company, name, address, place, regionid, phone, emailid, refer, leaduploadedby, leadstatus, leadremarks, dealernativeid, cell,stdcode,initialcontactname,initialaddress,initialstdcode,initialphone,initialcellnumber,initialemailid,leaddatetime)values('".$fetch['dealerid']."', '".$product."','Manual Upload', '".$fetch['company']."', '".$contactperson."', '".$fetch['address']."', '".$fetch['place']."', '".$fetch['regionid']."', '".$fetch['phone']."', '".$emailid."', '".$leadsource."', '".$userslno."', 'Not Viewed', '".$remarks."', '".$dealerid."', '".$cellnumber."', '".$fetch['stdcode']."' ,'".$fetch['name']."','".$fetch['address']."','".$fetch['stdcode']."','".$fetch['phone']."','".$cellnumber."','".$emailid."','".$leaddate.' '.$leadtime."')";
							
							//echo($query2);exit;
							$result2 = runmysqlquery($query2); 
							
							 //Fetch new lead id to insert into updatelogs.
							$query3 = "select id,leadstatus from leads where source = 'Manual Upload' and company = '".$fetch['company']."' and name = '".$contactperson."' and leadremarks = '".$remarks."' and productid = '".$product."' and leaddatetime = '".$leaddate.' '.$leadtime."' ";
							$result3 = runmysqlqueryfetch($query3);
							
							//Insert Details to lms_updatelogs
							$query5 = "insert into lms_updatelogs set leadid = '".$result3['id']."',leadstatus = '".$result3['leadstatus']."',updatedate = '".$leaddate.' '.$leadtime. "',updatedby = '".$userslno."'";
							$result5 = runmysqlquery($query5);
							
							//Send SMS to concerned dealer about the lead
							$query = "SELECT * FROM dealers WHERE id = '".$dealerid."'";
							$result = runmysqlqueryfetch($query);
							$dlrcell = $result['dlrcell'];
							$query = "SELECT * FROM products WHERE id = '".$product."'";
							$result = runmysqlqueryfetch($query);
							$productname = $result['productname'];
							
							// Insert it to logs
							$query3 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','23','".$leaddate.' '.$leadtime."')";
							$result = runmysqlquery($query3);
							
							// Fetch details to send SMS
							$queryfetch = "select * from leads where dealerid = '".$dealerid."' and productid = '".$product."' and leaddatetime = '".$leaddate.' '.$leadtime."'";
							$resultfetch = runmysqlqueryfetch($queryfetch);
							
							//echo('1^'.$resultfetch['id']);exit;
							$name = $resultfetch['name'];
							$phone = $resultfetch['phone'];
							$company = $resultfetch['company'];
							
							$servicename = 'LEAD Uploaded';
							$tonumber = $dlrcell;
							$smstext = "Relyon LMS: ".substr($name, 0, 29)." of ".substr($company, 0, 29)." requires ".substr($productname, 0, 29).". Call ".substr($phone, 0, 29).".";
							$senddate = datetimelocal("Y-m-d");
							$sendtime = datetimelocal("H:i:s");
							sendsmsforleads($servicename, $tonumber, $smstext, $senddate, $sendtime,NULL,NULL);
							echo("1^Lead Uploaded Successfully.");
						}
					}
					else
					{
						echo('2^Select Manual Selection to Choose different Dealer');
					}
					break;
					
				case "aspermapping":
				
					// Fetch all details to create new lead
					$query = "select * from leads where id = '".$leadid."'";
					$fetch = runmysqlqueryfetch($query);
					$regionid = $fetch['regionid'];
					if($fetch['productid'] == $product)
					{
						echo('2^Please select different Product');
					}
					else
					{
						$querycheck = "SELECT * FROM leads WHERE emailid = '".$emailid."' AND productid = '".$product."'";
						$resultcheck = runmysqlquery($querycheck);
						$count = mysqli_num_rows($resultcheck);
						if($count > 0)
						{
							
							$result = runmysqlqueryfetch($querycheck);
							$leadid = $result['id'];
							echo("2^Lead already exists for same product [Lead ID: ".$leadid."].");
						}
						else
						{
							$query1 = "SELECT *,leads.dealerid AS id FROM leads left join lms_users on leads.dealerid = lms_users.referenceid WHERE leads.emailid = '".$emailid."' AND leads.dealerid <> '999999' AND lms_users.disablelogin <> 'yes';";
							$result1 = runmysqlquery($query1);
							$leadpresence2 = mysqli_num_rows($result1);
							
							if($leadpresence2 > 0)
							{
								//if present, then assign the lead to the same dealer, who has got the first product. Take him as dealer ID
								$fetch1 = mysqli_fetch_array($result1);
								$dealerid = $fetch1['id'];
							}
							else
							{
								//Get the category of the product
								$query2 = "SELECT * FROM products WHERE id = '".$product."'";
								$result2 = runmysqlqueryfetch($query2);
								$prdcategory = $result2['category'];
								
								//if not, then check the mapping table for respective product category/region and pick respective dealer ID.
								$query3 = "SELECT * FROM mapping left join lms_users on mapping.dealerid = lms_users.referenceid WHERE mapping.regionid = '".$fetch['region']."' AND mapping.prdcategory = '".$prdcategory."' AND lms_users.disablelogin <>'yes';";
								$result3 = runmysqlquery($query3);
								$mappingcount = mysqli_num_rows($result3);
								
								//If mapping exists for that region and product, pick respective dealer address
								if($mappingcount > 0)
								{
									$result3 = runmysqlqueryfetch($query3);
									$query4 = "SELECT * FROM dealers WHERE id = '".$result3['dealerid']."'";
									$result4 = runmysqlqueryfetch($query4);
									
									$dealerid = $result4['id'];
								}
								else
								{			
									//Get the Managed Area for this region
									$query5 = "SELECT managedarea FROM regions WHERE subdistcode = '".$regionid."'";
									$result5 = runmysqlqueryfetch($query5);
									$managedarea = $result5['managedarea'];
									
									//If mapping is not available for that product category/region, take unmapped contact as its dealer ID
									$query6 = "SELECT * FROM unmappedcontact WHERE managedarea = '".$managedarea."' and prdcategory = '".$prdcategory."'";
									$result6 = runmysqlqueryfetch($query6);
									$dealerid = "999999";
								}
							}
							$query7 = "insert into `leads` (dealerid, productid, source, company, name, address, place, regionid, phone, emailid, refer, leaduploadedby, leadstatus, leadremarks, dealernativeid, cell,stdcode,initialcontactname,initialaddress,initialstdcode,initialphone,initialcellnumber,initialemailid,leaddatetime)values('".$fetch['dealerid']."', '".$product."','Manual Upload', '".$fetch['company']."', '".$contactperson."', '".$fetch['address']."', '".$fetch['place']."', '".$fetch['regionid']."', '".$fetch['phone']."', '".$emailid."', '".$leadsource."', '".$userslno."', 'Not Viewed', '".$remarks."', '".$dealerid."', '".$cellnumber."', '".$fetch['stdcode']."' ,'".$fetch['name']."','".$fetch['address']."','".$fetch['stdcode']."','".$fetch['phone']."','".$cellnumber."','".$emailid."','".$leaddate.' '.$leadtime."')";
								
							//echo($query2);exit;
							$result7 = runmysqlquery($query7); 
							
							 //Fetch new lead id to insert into updatelogs.
							$query8 = "select id,leadstatus from leads where source = 'Manual Upload' and company = '".$fetch['company']."' and name = '".$contactperson."' and leadremarks = '".$remarks."' and productid = '".$product."' and leaddatetime = '".$leaddate.' '.$leadtime."' ";
							$result8 = runmysqlqueryfetch($query8);
							
							//Insert Details to lms_updatelogs
							$query9 = "insert into lms_updatelogs set leadid = '".$result8['id']."',leadstatus = '".$result8['leadstatus']."',updatedate = '".$leaddate.' '.$leadtime. "',updatedby = '".$userslno."'";
							$result9 = runmysqlquery($query9);
							
							
							
							// Insert it to logs
							$query3 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','23','".$leaddate.' '.$leadtime."')";
							$result = runmysqlquery($query3);
							
							//Send SMS to concerned dealer about the lead
							$query = "SELECT * FROM dealers WHERE id = '".$fetch['dealerid']."'";
							$result = runmysqlqueryfetch($query);
							$dlrcell = $result['dlrcell'];
							$query = "SELECT * FROM products WHERE id = '".$product."'";
							$result = runmysqlqueryfetch($query);
							$productname = $result['productname'];
							
							// Fetch details to send SMS
							
							$queryfetch = "select * from leads where dealerid = '".$fetch['dealerid']."' and productid = '".$product."' and leaddatetime = '".$leaddate.' '.$leadtime."'";
							$resultfetch = runmysqlqueryfetch($queryfetch);
							
							//echo('1^'.$resultfetch['id']);exit;
							$name = $resultfetch['name'];
							$phone = $resultfetch['phone'];
							$company = $resultfetch['company'];
							
							$servicename = 'LEAD Uploaded';
							$tonumber = $dlrcell;
							$smstext = "Relyon LMS: ".substr($name, 0, 29)." of ".substr($company, 0, 29)." requires ".substr($productname, 0, 29).". Call ".substr($phone, 0, 29).".";
							$senddate = datetimelocal("Y-m-d");
							$sendtime = datetimelocal("H:i:s");
							sendsmsforleads($servicename, $tonumber, $smstext, $senddate, $sendtime,NULL,NULL);
							
							echo("1^Lead Uploaded Successfully.");
											
						}
					}
					break;
					
				case "manualselection":
					$query = "select * from leads where id = '".$leadid."'";
					$fetch = runmysqlqueryfetch($query);
					if($fetch['productid'] == $product)
					{
						echo('2^Please select different Product');
					}
					else if($fetch['dealerid'] == $dealerid)
					{
						echo('2^Please select different Dealer');
					}
					else 
					{		
						$query = "SELECT * FROM leads WHERE emailid = '".$emailid."' AND productid = '".$product."'";
						$result = runmysqlquery($query);
						$count = mysqli_num_rows($result);
						if($count > 0)
						{
							$result = runmysqlqueryfetch($query);
							$leadid = $result['id'];
							echo("2^Lead already exists for same product [Lead ID: ".$leadid."].");
						}
						else
						{		
							$query7 = "insert into `leads` (dealerid, productid, source, company, name, address, place, regionid, phone, emailid, refer, leaduploadedby, leadstatus, leadremarks, dealernativeid, cell,stdcode,initialcontactname,initialaddress,initialstdcode,initialphone,initialcellnumber,initialemailid,leaddatetime)values('".$dealerid."', '".$product."','Manual Upload', '".$fetch['company']."', '".$contactperson."', '".$fetch['address']."', '".$fetch['place']."', '".$fetch['regionid']."', '".$fetch['phone']."', '".$emailid."', '".$leadsource."', '".$userslno."', 'Not Viewed', '".$remarks."', '".$dealerid."', '".$cellnumber."', '".$fetch['stdcode']."' ,'".$fetch['name']."','".$fetch['address']."','".$fetch['stdcode']."','".$fetch['phone']."','".$cellnumber."','".$emailid."','".$leaddate.' '.$leadtime."')";
									
							//echo($query2);exit;
							$result7 = runmysqlquery($query7); 
							
							 //Fetch new lead id to insert into updatelogs.
							$query8 = "select id,leadstatus from leads where source = 'Manual Upload' and company = '".$fetch['company']."' and name = '".$contactperson."' and leadremarks = '".$remarks."' and productid = '".$product."' and leaddatetime = '".$leaddate.' '.$leadtime."' ";
							$result8 = runmysqlqueryfetch($query8);
							
							//Insert Details to lms_updatelogs
							$query9 = "insert into lms_updatelogs set leadid = '".$result8['id']."',leadstatus = '".$result8['leadstatus']."',updatedate = '".$leaddate.' '.$leadtime. "',updatedby = '".$userslno."'";
							$result9 = runmysqlquery($query9);
							
							//Send SMS to concerned dealer about the lead
							$query = "SELECT * FROM dealers WHERE id = '".$dealerid."'";
							$result = runmysqlqueryfetch($query);
							$dlrcell = $result['dlrcell'];
							$query = "SELECT * FROM products WHERE id = '".$product."'";
							$result = runmysqlqueryfetch($query);
							$productname = $result['productname'];
							
							// Insert it to logs
							$query3 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','23','".$leaddate.' '.$leadtime."')";
							$result = runmysqlquery($query3);
							
							// Fetch details to send SMS
							
							$queryfetch = "select * from leads where dealerid = '".$dealerid."' and productid = '".$product."' and leaddatetime = '".$leaddate.' '.$leadtime."'";
							$resultfetch = runmysqlqueryfetch($queryfetch);
							
							//echo('1^'.$resultfetch['id']);exit;
							$name = $resultfetch['name'];
							$phone = $resultfetch['phone'];
							$company = $resultfetch['company'];
							
							$servicename = 'LEAD Uploaded';
							$tonumber = $dlrcell;
							$smstext = "Relyon LMS: ".substr($name, 0, 29)." of ".substr($company, 0, 29)." requires ".substr($productname, 0, 29).". Call ".substr($phone, 0, 29).".";
							$senddate = datetimelocal("Y-m-d");
							$sendtime = datetimelocal("H:i:s");
							sendsmsforleads($servicename, $tonumber, $smstext, $senddate, $sendtime,NULL,NULL);
							
							echo("1^Lead Uploaded Successfully.");
						}
					}
					break;
			}
			
			break;		
		
		case "sendsms":
			$cell = $_POST['cellnumber'];
			$smstext = $_POST['smstext'];
			$leadid = $_POST['leadid'];
			$senddate = datetimelocal("Y-m-d");
			$sendtime = datetimelocal("H:i:s");
			$servicename = 'Lead SMS-Single';
			sendsmsforleads($servicename, $cell, $smstext, $senddate, $sendtime,$leadid,$userslno);
			echo('1^ SMS sent Successfully.');
			
			break;
		
		case "showsmslogs":
			$leadid = $_POST['leadid'];
			$startlimit = $_POST['startlimit'];
			$slnocount = $_POST['slnocount'];
			$showtype = $_POST['showtype'];
			if($showtype == 'all')
				$limit = 1000;
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
			$query = "select * from smslogs where leadid = '".$leadid."' order by id desc";
			if($slnocount == '0')
			{
				$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
			//Write the header Row of the table
				$grid .= '<tr class="gridheader"><td nowrap="nowrap"  class="tdborderlead">Sl No</td><td nowrap="nowrap"  class="tdborderlead">SMS Date</td><td nowrap="nowrap"  class="tdborderlead">SMS Text</td><td nowrap="nowrap"  class="tdborderlead">To Number</td><td nowrap="nowrap"  class="tdborderlead">Sent By</td></tr><tbody>';
			}
			$result = runmysqlquery($query);
			$fetchresultcount = mysqli_num_rows($result);
			$addlimit = " LIMIT ".$startlimit.",".$limit."; ";
			$query1 = $query.$addlimit; //echo($query1); exit;
			$result1 = runmysqlquery($query1);
			
			while($fetch = mysqli_fetch_array($result1))
			{
				$slnocount++;
				$grid .= '<tr class="gridrow" onclick="javascript:gridtoformofsms(\''.$fetch['id'].'\');">';
				$grid .= "<td nowrap='nowrap' class='tdborderlead'>".$slnocount."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborderlead'>".changedateformat($fetch['senddate'])." (".$fetch['sendtime'].")</td>";
				$grid .= "<td nowrap='nowrap' class='tdborderlead'>".gridtrim30($fetch['smstext'])."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborderlead'>".$fetch['tonumber']."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborderlead'>".getuserdisplayname($fetch['smssentby'])."</td>";
				$grid .= '</tr>';
			}	
			$grid .= "</tbody></table>";
			if($slnocount >= $fetchresultcount)
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
				$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreofsmslogs(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreofsmslogs(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';		
			echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
			break;
		
		case "gridtoformsms":
			$smsid = $_POST['smsid'];
			$query = "select * from smslogs where id = '".$smsid."'";
			$fetch = runmysqlqueryfetch($query);
			echo('1^'.$fetch['tonumber'].'^'.$fetch['smstext']);
			break;
		
		case "getcontactnumber":
			$id = $userslno;
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$query = "select referenceid from lms_users where id = '".$id."'";
			$fetch = runmysqlqueryfetch($query);
			
			switch($cookie_usertype)
			{
				case "Admin":
					$mynumber = '';
					break;
				
				case "Sub Admin":
					$query1 = "select * from lms_subadmins where id = '".$fetch['referenceid']."'";
					$fetch1 = runmysqlqueryfetch($query1);
					if($fetch1['cell'] <> '')
						$mynumber = $fetch1['cell'];
					else 
						$mynumber = 'Not Available';
					break;
				
				case "Reporting Authority":
					$query1 = "select * from lms_managers where id = '".$fetch['referenceid']."'";
					$fetch1 = runmysqlqueryfetch($query1);
					if($fetch1['mgrcell'] <> '')
						$mynumber = $fetch1['mgrcell'];
					else 
						$mynumber = 'Not Available';
					break;
				
				case "Dealer":
					$query1 = "select * from dealers where id = '".$fetch['referenceid']."'";
					$fetch1 = runmysqlqueryfetch($query1);
					if($fetch1['dlrcell'] <> '')
						$mynumber = $fetch1['dlrcell'];
					else 
						$mynumber = 'Not Available';
					break;
			}
			if($mynumber <> '')
				echo('1^'.$mynumber);
			else
				echo('2^'.$mynumber);
			
			break;
			
			case "saveinitialremarks":
			$leadid = $_POST['form_recid'];
			$remarks = $_POST['form_remarks'];
			
			// Update Initial Remarks
			
			$query = "UPDATE leads set leadremarks = '".$remarks."' where id = '".$leadid."' ";
			$result = runmysqlquery($query);
			
			// Fetch Updated Remarks 
			$query1 = "select leadremarks from leads where id = '".$leadid."' ";
			$fetch = runmysqlqueryfetch($query1);
			
			$message = "1^Remarks Updated Successfully.".'^'.$fetch['leadremarks'];
			echo($message);
			break;
								
}
?>
