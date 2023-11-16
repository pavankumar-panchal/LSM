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
				$startlimit = $slnocount ;
				$slnocount = $slnocount;
			}
			
			$query = "select  leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join lms_users on lms_users.id = leads.leaduploadedby left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode where leads.source = 'Manual Upload' and lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC";
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
			$query1 = $query.$addlimit; //echo($query1);exit;
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
		$dealerid = $_POST['dealerid']; //echo($dealerid);exit;
		$givenby = $_POST['givenby'];
		$productid = $_POST['productid'];
		$leadstatus = $_POST['leadstatus']; 
		$filter_followupdate1 = $_POST['filter_followupdate1'];
		$filter_followupdate2 = $_POST['filter_followupdate2'];
		$dropterminatedstatus = $_POST['dropterminatedstatus']; 
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
			$startlimit = $slnocount ;
			$slnocount = $slnocount;
		}
		if($filter_followupdate1 == 'dontconsider')
		{
			$followuppiece = "";
		}
		else
		{
			/*$followuppiece = "AND lms_followup.followupdate >= '".changedateformat($filter_followupdate1)."' AND lms_followup.followupdate <= '".changedateformat($filter_followupdate2)."'";*/
			
			$leadquery0 = "select leadid from lms_followup 
			where lms_followup.followupdate >= '".changedateformat($filter_followupdate1)."' 
			AND lms_followup.followupdate <= '".changedateformat($filter_followupdate2)."' 
			AND followupstatus = 'PENDING'";
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
		
		$dealerpiece = ($dealerid == '')?"":("AND leads.dealerid = '".$dealerid."'");
		$productpiece = ($productid == '')?"":("AND productid = '".$productid."'");
		$leadstatuspiece = ($leadstatus == '')?"":("AND leadstatus = '".$leadstatus."'");
		//$leaduploadedby = ($givenby == '')?"":(($givenby == 'web')?"AND leaduploadedby IS NULL":"AND leaduploadedby = '".$givenby."'");
		$leaduploadedby = "AND leaduploadedby = '".$userslno."'";  
		$datetimepiece = "substring(leads.leaddatetime,1,10) between '".$fromdate."' AND  '".$todate."'"; 
		$terminatedstatuspiece = ($dropterminatedstatus == 'true')?("AND leads.leadstatus <> 'Order Closed' AND leads.leadstatus <> 'Not Interested' AND leads.leadstatus <> 'Fake Enquiry' AND leads.leadstatus <> 'Registered User'"):"";
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			if(checkdateformat($fromdate) && checkdateformat($todate) && ((datenumeric($todate) - datenumeric($fromdate)) >= 0))
			{
					//Check who is making the entry
				$cookie_username = lmsgetcookie('lmsusername');
				$cookie_usertype = lmsgetcookie('lmsusersort');
				$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname,leads.leadremarks from leads left join lms_users on lms_users.id = leads.leaduploadedby left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode left join lms_followup on lms_followup.leadid = leads.id where ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece."  AND leads.source = 'Manual Upload' AND lms_users.username = '".$cookie_username."' AND lms_users.type = '".$cookie_usertype."' ORDER BY leads.id DESC";//echo($query);exit;
				

				if($slnocount == '0')
				{
					$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
				//Write the header Row of the table
					$grid .= '<tr class="gridheader"><td nowrap="nowrap"  class="tdborderlead">Sl No</td><td nowrap="nowrap"  class="tdborderlead">Lead ID</td><td nowrap="nowrap"  class="tdborderlead">Lead Date</td><td nowrap="nowrap"  class="tdborderlead">Product</td><td nowrap="nowrap"  class="tdborderlead">Company</td><td nowrap="nowrap"  class="tdborderlead">Contact</td><td nowrap="nowrap"  class="tdborderlead">Landline</td><td nowrap="nowrap"  class="tdborderlead">Cell</td><td nowrap="nowrap"  class="tdborderlead">Email ID</td><td nowrap="nowrap"  class="tdborderlead">District<td nowrap="nowrap"  class="tdborderlead">State</td><td nowrap="nowrap"  class="tdborderlead">Dealer</td><td nowrap="nowrap"  class="tdborderlead">Manager</td><td nowrap="nowrap"  class="tdborderlead">Remarks</td></tr><tbody>';
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
				$query2 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','47','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result2 = runmysqlquery_log($query2);
				
				echo("1|^|".$grid."|^|".$linkgrid.'|^|'.$fetchresultcount);
				
			
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
			$query = "select leads.id AS id, leads.company AS company, leads.name AS name, leads.phone AS phone,leads.stdcode AS stdcode, leads.cell AS cell, leads.dealerviewdate AS dealerviewdate, leads.emailid AS emailid, leads.address AS address, leads.refer AS refer, leads.source AS source, leads.leaddatetime AS leaddatetime, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dealername, lms_managers.mgrname AS managername, leads.leadstatus AS leadstatus, leads.leaduploadedby AS leaduploadedby, leads.lastupdatedby AS lastupdatedby, leads.leadremarks AS leadremarks, leads.lastupdateddate AS lastupdateddate, products.productname AS product, lms_dlrmembers.dlrmbrname AS dlrmbrname from (select * from leads WHERE leads.id = '".$form_recid."') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid";
			$result = runmysqlqueryfetch($query);
			$leadid = $result['id'];
			//Update the dealer view date, if the login type is a Dealer.
			if($cookie_usertype == "Dealer")
			{
				if($result['dealerviewdate'] == '0000-00-00 00:00:00')
				{
					$viewdate = datetimelocal("Y-m-d");
					$viewtime = datetimelocal("H:i:s");
					$query2 = "UPDATE leads SET dealerviewdate = '".$viewdate.' '.$viewtime."' WHERE leads.id = '".$form_recid."'";
					$result2 = runmysqlquery($query2);
				}
			}
			$dealerviewdate = ($result['dealerviewdate'] == '0000-00-00 00:00:00')?('Not yet viewed'):(changedateformatwithtime($result['dealerviewdate']));	
			$dlrmbrname = ($result['dlrmbrname'] == "")?"None":$result['dlrmbrname'];
			$givenby = ($result['source'] == "Manual Upload")?getuserdisplayname($result['leaduploadedby']):"Webmaster";
			
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
			$output = $leadid."|^|".$result['company']."|^|".$result['id']."|^|".$result['name']."|^|".$result['address']."|^|".$result['distname']."|^|".$result['statename']."|^|".$result['stdcode']."|^|".$result['phone']."|^|".$result['cell']."|^|".$result['emailid']."|^|".$result['refer']."|^|".$result['source']."|^|".$givenby."|^|".changedateformatwithtime($result['leaddatetime'])."|^|".$dealerviewdate."|^|".$result['product']."|^|".$result['dealername']."|^|".$dlrmbrname."|^|".$result['managername']."|^|".$result['leadstatus']."|^|".$lastupdatedbyname."|^|".$lastupdateddate."|^|".$leadremarks;
			//$output = $leadid."|^|".$leaddetail."|^|".$leadstatus."|^|".$lastupdatedbyname."|^|".$lastupdateddate."|^|".$leadremarks;
			echo('1|^|'.$output);
		}
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
				
				$query1 = "select distinct table1.id,table1.leadid,table1.transferdate,table1.dlrcompanyname as fromdealer,table2.dlrcompanyname as todealer,table1.username from (SELECT dealers.id, dealers.dlrcompanyname,lms_transferlogs.leadid,lms_transferlogs.transferdate,lms_transferlogs.trasferredby,lms_users.username  from lms_transferlogs left join lms_users on lms_users.id = lms_transferlogs.trasferredby left join dealers on dealers.id = lms_transferlogs.fromdealer WHERE lms_transferlogs.leadid = '".$id."') as table1 left join(SELECT dealers.id, dealers.dlrcompanyname,lms_transferlogs.leadid,lms_transferlogs.transferdate,lms_transferlogs.trasferredby,lms_users.username  from lms_transferlogs left join lms_users on lms_users.id = lms_transferlogs.trasferredby left join dealers on dealers.id = lms_transferlogs.todealer WHERE lms_transferlogs.leadid = '".$id."') as table2 on table1.transferdate = table2.transferdate;";
					
					$result1 = runmysqlquery($query1);
					$fetchresultcount = mysqli_num_rows($result1);
					if($fetchresultcount <> 0)
					{
						if($slnocount == 0)
						{
							$grid .= '<table width="100%" border="0"  cellspacing="0" cellpadding="2" id="gridtable1">';
							//Write the header Row of the table
							$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder1">Sl No</td><td nowrap="nowrap" class="tdborder1">From Dealer</td><td nowrap="nowrap" class="tdborder1">To Dealer</td><td nowrap="nowrap" class="tdborder1">Transfer Date</td><td nowrap="nowrap" class="tdborder1">Transfered By</td></tr>';
						}
						$query = "select distinct  table1.id,table1.leadid,table1.transferdate,table1.dlrcompanyname as fromdealer,table2.dlrcompanyname as todealer,table1.trasferredby from (SELECT dealers.id, dealers.dlrcompanyname,lms_transferlogs.leadid,lms_transferlogs.transferdate,lms_transferlogs.trasferredby,lms_users.username  from lms_transferlogs left join lms_users on lms_users.id = lms_transferlogs.trasferredby left join dealers on dealers.id = lms_transferlogs.fromdealer WHERE lms_transferlogs.leadid = '".$id."') as table1 left join(SELECT dealers.id, dealers.dlrcompanyname,lms_transferlogs.leadid,lms_transferlogs.transferdate,lms_transferlogs.trasferredby,lms_users.username  from lms_transferlogs left join lms_users on lms_users.id = lms_transferlogs.trasferredby left join dealers on dealers.id = lms_transferlogs.todealer WHERE lms_transferlogs.leadid = '".$id."') as table2 on table1.transferdate = table2.transferdate LIMIT ".$startlimit.",".$limit.";";
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
					}
					else
					{
						$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="2" id="gridtable1">';
							//Write the header Row of the table
						$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder1">Sl No</td><td nowrap="nowrap" class="tdborder1">From Dealer</td><td nowrap="nowrap" class="tdborder1">To Dealer</td><td nowrap="nowrap" class="tdborder1">Transfer Date</td><td nowrap="nowrap" class="tdborder1">Transfered By</td></tr>';
						$grid .= '</table>';
						echo('2^'.$grid);
					}
				break;
	
}


?>