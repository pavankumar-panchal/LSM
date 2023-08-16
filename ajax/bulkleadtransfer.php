<?
ini_set("memory_limit","-1");
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];
switch($submittype)
{
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
		$leadsourcelist = ($leadsource == "")?"":("AND leads.refer = '".$leadsource."'");
		
		$lastupdatedby = $_POST['followedby'];
		$lastupdatedpiece = ($lastupdatedby == "")?"":("AND leads.lastupdatedby = '".$lastupdatedby."'");
		
		$datatype = ($datatype == "download")?"Product Download":(($datatype == "upload")?"Manual Upload":"");
		$sourcepiece = ($datatype == "")?"":("AND leads.source like '%".$datatype."%'");
			
		if($filter_followupdate1 == 'dontconsider')
		{
			$followuppiece = "";
			$lastupdatedby = "";
		}
		else
		{
			$followuppiece = "AND lms_followup.followupdate >= '".changedateformat($filter_followupdate1)."' AND lms_followup.followupdate <= '".changedateformat($filter_followupdate2)."'";
			$lastupdatedby = ($givenby == '')?"":("AND leads.lastupdatedby = '".$givenby."'");
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
						$query = "select leads.id, leads.leaddatetime, products.productname,leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from leads  left join lms_followup on leads.id = lms_followup.leadid join dealers on leads.dealerid = dealers.id join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode where ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." AND (lms_followup.followupstatus = 'PENDING' OR lms_followup.followupstatus IS NULL) ".$searchpiece." ".$sourcepiece." ".$lastupdatedpiece." ".$leadsourcelist." ORDER BY leads.id DESC";
						break;
					case "Reporting Authority":
						 //Check wheteher the manager is branch head or not
						$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
						$result1 = runmysqlqueryfetch($query1);
						if($result1['branchhead'] == 'yes')
							$branchpiecejoin = " AND (dealers.branch = '".$result1['branch']."' OR dealers.managerid  = '".$result1['managerid']."')";
						else
							$branchpiecejoin = " AND lms_users.username = '".$cookie_username."'";
							
						$query = "select leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname  from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_managers.id where (followupstatus = 'PENDING' or lms_followup.followupstatus is null ) and ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." AND lms_users.type = 'Reporting Authority' ".$searchpiece." ".$sourcepiece." ".$lastupdatedpiece." ".$leadsourcelist." ".$branchpiecejoin." ORDER BY leads.id DESC";
						if($cookie_username == "srinivasan")
							$query = "select leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname  from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_managers.id where (followupstatus = 'PENDING' or lms_followup.followupstatus is null ) and ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." AND lms_users.type = 'Reporting Authority' AND (lms_users.username = 'srinivasan' or  lms_users.username = 'nagaraj') ".$searchpiece." ".$sourcepiece." ".$lastupdatedpiece." ".$leadsourcelist." ORDER BY leads.id DESC";
						break;
					case "Dealer":
						$query = "select leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname, lms_managers.mgrname  from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = dealers.id where (followupstatus = 'PENDING' or lms_followup.followupstatus is null ) and ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." and lms_users.type = 'Dealer' and lms_users.username = '".$cookie_username."' ".$searchpiece." ".$sourcepiece." ".$lastupdatedpiece." ".$leadsourcelist." ORDER BY leads.id DESC";
					
					break;
					case "Dealer Member":
						$query = "select leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname, lms_managers.mgrname from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid  left join  lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_dlrmembers.dlrmbrid where (followupstatus = 'PENDING' or lms_followup.followupstatus is null ) and ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." and lms_users.type = 'Dealer Member' and lms_users.username = '".$cookie_username."' ".$searchpiece." ".$sourcepiece." ".$lastupdatedpiece." ".$leadsourcelist." ORDER BY leads.id DESC";
					break;
				}//echo($query);exit;
				
				$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
			//Write the header Row of the table
				$grid .= '<tr class="gridheader"><td nowrap="nowrap"  class="tdborderlead">&nbsp</td><td nowrap="nowrap"  class="tdborderlead">Sl No</td><td nowrap="nowrap"  class="tdborderlead">Lead ID</td><td nowrap="nowrap"  class="tdborderlead">Lead Date</td><td nowrap="nowrap"  class="tdborderlead">Product</td><td nowrap="nowrap"  class="tdborderlead">Company</td><td nowrap="nowrap"  class="tdborderlead">Contact</td><td nowrap="nowrap"  class="tdborderlead">Landline</td><td nowrap="nowrap"  class="tdborderlead">Cell</td><td nowrap="nowrap"  class="tdborderlead">Email ID</td><td nowrap="nowrap"  class="tdborderlead">District<td nowrap="nowrap"  class="tdborderlead">State</td><td nowrap="nowrap"  class="tdborderlead">Dealer</td><td nowrap="nowrap"  class="tdborderlead">Manager</td></tr><tbody>';
				//echo $query;
				$result1 = runmysqlquery($query);
				$fetchresultcount = mysqli_num_rows($result1);
				if($fetchresultcount > 0)
				{
					while($fetch = mysqli_fetch_row($result1))
					{
						$slnocount++;
						$countselected = 'countselected';
						//Begin a row
						$grid .= '<tr>';  
						$grid .= '<td class="tdborderlead"><input type="checkbox" name="transfercheckbox'.$slnocount.'" id ="transfercheckbox'.$slnocount.'" value = "'.$fetch[0].'" onclick = "javascript:selectanddeselect(\''.$countselected.'\')" />';
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
					
				// Insert logs on filter of Lead
				$query2 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','29','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result2 = runmysqlquery_log($query2);
				
				echo("1|^|".$grid.'|^|'.$fetchresultcount);
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
			
	case "transferleads":
		$id = $_POST['leadids'];
		$todealerid = $_POST['todealerid']; 
		$newarray = explode(',',$id);
		$leadcount = 0;
		for($j = 0;$j < count($newarray);$j++)
		{
			$leadcount++;
			$query1 = "SELECT dealerid,name,leads.phone as phone,products.productname,leads.company  FROM leads left join products on products.id = leads.productid WHERE leads.id = '".$newarray[$j]."' ";  //echo('1^'.$query1);exit;
			$fetch = runmysqlqueryfetch($query1); 
			$name = $fetch['name'];
			$phone = $fetch['phone'];
			$product = $fetch['productname'];
			$company = $fetch['company'];
			$id = $newarray[$j];
			
			$transferdate = datetimelocal("Y-m-d");
			$transfertime = datetimelocal("H:i:s");
			$cookie_usertype = lmsgetcookie('lmsusersort');
			if($cookie_usertype == 'Reporting Authority')
			{
				$query2 = "SELECT managerid from dealers where dealers.id = '".$todealerid."'";
				$result2 = runmysqlqueryfetch($query2);
				
				$query3 = "SELECT referenceid from lms_users where lms_users.id = '".$userslno."' and lms_users.type = 'Reporting Authority'";
				$result3 = runmysqlqueryfetch($query3);
				
				if($result2['managerid'] == $result3['referenceid'])
				{
					$todealerid = $todealerid;
				}
				else
				{
					echo('2^Sorry Cannot transfer Leads.');
				}
			}
			$query = "UPDATE leads set dealerid = '".$todealerid."',dlrmbrid = '' where leads.id = '".$id."'";  
			$result = runmysqlquery($query); 
			
			$query9 = "select dlrcompanyname,lms_managers.mgrname,dlrcell from dealers left join lms_managers on lms_managers.id = dealers.managerid where dealers.id = '".$todealerid."'"; 
			$result9 = runmysqlqueryfetch($query9); //echo($query9);exit;
			
			// Insert that to transfer logs
			$query8 = "insert into `lms_transferlogs` (leadid, fromdealer, todealer, transferdate, trasferredby) values('".$id."', '".$fetch['dealerid']."', '".$todealerid."', '".$transferdate.' '.$transfertime."', '".$userslno."')";
			$result8 = runmysqlquery($query8);
			
			// Send SMS to respective Dealer.
			$servicename = 'LEAD Transfer Bulk';
			$tonumber = $result9['dlrcell'];
			$smstext = "Relyon LMS: ".substr($name, 0, 29)." of ".substr($company, 0, 29)." requires ".substr($product, 0, 29).". Call ".substr($phone, 0, 29)."."; //echo($smstext);exit;
			$senddate = $transferdate;
			$sendtime = $transfertime;
			//sendsmsforleads($servicename, $tonumber, $smstext, $senddate, $sendtime,NULL,NULL);
		}
		echo('1^'.$leadcount);
		break;
		
		case "getloopingcount":
		$checkedcount = $_POST['checkedcount'];
		$quotient = $checkedcount/5;
		$totallooprun = ($checkedcount % 5 == 0)?($checkedcount/5):(ceil($checkedcount/5));
		echo('1^'.$checkedcount.'^'.$totallooprun);
		break;		
}
?>
