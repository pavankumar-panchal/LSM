<?php
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
		$followupcheck = $_POST['followupcheck'];
		$leadsourcelist = ($leadsource == "")?"":("AND leads.refer = '".$leadsource."'");
		
		$lastupdatedby = $_POST['followedby'];
		$remarks = $_POST['remarks']; //echo($_POST['remarks']);exit;
		$lastfollowupcheckpiece = "AND (followupstatus = 'PENDING' or lms_followup.followupstatus is null )";
		$datatype = ($datatype == "download")?"Product Download":(($datatype == "upload")?"Manual Upload":"");
		$sourcepiece = ($datatype == "")?"":("AND leads.source like '%".$datatype."%'");
		$cellfieldpiece = "AND leads.cell <> '' AND leads.cell <> '9999999999'";
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
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from leads  left join lms_followup on leads.id = lms_followup.leadid join dealers on leads.dealerid = dealers.id join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode where ".$datetimepiece." ".$lastfollowupcheckpiece." ".$terminatedstatuspiece." ".$followuppiece." ".$remarkspiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece."  ".$searchpiece." ".$sourcepiece."  ".$lastupdatedpiece." ".$leadsourcelist." ".$cellfieldpiece." ORDER BY leads.id DESC";
						break;
					case "Reporting Authority":
					
						 //Check wheteher the manager is branch head or not
						$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
						$result1 = runmysqlqueryfetch($query1);
						if($result1['branchhead'] == 'yes')
							$branchpiecejoin = " AND (dealers.branch = '".$result1['branch']."' OR dealers.managerid  = '".$result1['managerid']."')";
						else
							$branchpiecejoin = " AND lms_users.username = '".$cookie_username."'";
							
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname  from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_managers.id where  ".$datetimepiece." ".$lastfollowupcheckpiece." ".$terminatedstatuspiece." ".$followuppiece." ".$remarkspiece."  ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." AND lms_users.type = 'Reporting Authority'  ".$searchpiece." ".$sourcepiece."  ".$lastupdatedpiece." ".$leadsourcelist." ".$cellfieldpiece." ".$branchpiecejoin." ORDER BY leads.id DESC";
						if($cookie_username == "srinivasan")
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname  from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_managers.id where  ".$datetimepiece."  ".$lastfollowupcheckpiece." ".$terminatedstatuspiece." ".$followuppiece." ".$remarkspiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." AND lms_users.type = 'Reporting Authority' AND (lms_users.username = 'srinivasan' or  lms_users.username = 'nagaraj') ".$searchpiece." ".$sourcepiece."  ".$lastupdatedpiece." ".$leadsourcelist." ".$cellfieldpiece." ORDER BY leads.id DESC";
						break;
					case "Dealer":
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname, lms_managers.mgrname  from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = dealers.id where  ".$datetimepiece." ".$terminatedstatuspiece." ".$lastfollowupcheckpiece." ".$followuppiece." ".$remarkspiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece." and lms_users.type = 'Dealer' and lms_users.username = '".$cookie_username."' ".$searchpiece." ".$sourcepiece."  ".$lastupdatedpiece." ".$leadsourcelist." ".$cellfieldpiece." ORDER BY leads.id DESC";
					
					break;
				}//echo('1^'.$query);exit;
				
				$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
			//Write the header Row of the table
				$grid .= '<tr class="gridheader"><td nowrap="nowrap"  class="tdborderlead">&nbsp</td><td nowrap="nowrap"  class="tdborderlead">Sl No</td><td nowrap="nowrap"  class="tdborderlead">Lead ID</td><td nowrap="nowrap"  class="tdborderlead">Lead Date</td><td nowrap="nowrap"  class="tdborderlead">Product</td><td nowrap="nowrap"  class="tdborderlead">Company</td><td nowrap="nowrap"  class="tdborderlead">Contact</td><td nowrap="nowrap"  class="tdborderlead">Landline</td><td nowrap="nowrap"  class="tdborderlead">Cell</td><td nowrap="nowrap"  class="tdborderlead">Email ID</td><td nowrap="nowrap"  class="tdborderlead">District<td nowrap="nowrap"  class="tdborderlead">State</td><td nowrap="nowrap"  class="tdborderlead">Dealer</td><td nowrap="nowrap"  class="tdborderlead">Manager</td></tr><tbody>';
				
				$result1 = runmysqlquery($query);
				$fetchresultcount = mysqli_num_rows($result1);	
				if($fetchresultcount > 0)
				{
					while($fetch = mysqli_fetch_array($result1))
					{
						$slnocount++;
						$countselected = 'countselected';
						//Begin a row
						$grid .= '<tr>';  
						$grid .= '<td class="tdborderlead"><input type="checkbox" name="smscheckbox'.$slnocount.'" id ="smscheckbox'.$slnocount.'" value = "'.$fetch['id'].'" onclick = "javascript:selectanddeselect(\''.$countselected.'\')" />';
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>".$slnocount."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>".$fetch['id']."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".changedateformatwithtime($fetch['leaddatetime'])."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch['productname'])."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch['company'])."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch['name'])."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch['phone'])."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch['cell'])."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch['emailid'])."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch['distname'])."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch['statename'])."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch['dlrcompanyname'])."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch['mgrname'])."</td>";
						//End the Row
						$grid .= '</tr>';
					}
				}
				$companyselect .= "</select>";
				$contactselect .= "</select>";
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
			
	case "sendsms":
		$id = $_POST['leadids'];
		$smstext = $_POST['smstext']; 
		$newarray = explode(',',$id);
		$sentsmscount = 0;

		for($j = 0;$j < count($newarray);$j++)
		{
			
			$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, dealers.dlrname,dealers.dlrcell,dealers.dlremail,lms_managers.mgrname,lms_managers.mgrcell,lms_managers.mgremailid from leads  left join lms_followup on leads.id = lms_followup.leadid join dealers on leads.dealerid = dealers.id join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode where leads.id = '".$newarray[$j]."'";
			$fetch = runmysqlqueryfetch($query);
			$leadid = $fetch['id'];
			$date = changedateformatwithtime($fetch['leaddatetime']);
			$finaldate = explode('(',$date);
			$product = $fetch['productname'];
			$company = $fetch['company'];
			$contact = $fetch['name'];
			$cell = $fetch['cell'];
			$phone = $fetch['phone'];
			$emailid = $fetch['emailid'];
			$district = $fetch['distname'];
			$state = $fetch['state'];
			$dealercompany = $fetch['dlrcompanyname'];
			$dealername = $fetch['dlrname'];
			$dealercell = $fetch['dlrcell'];
			$dealeremail = $fetch['dlremail'];
			$managername = $fetch['mgrname'];
			$managercell = $fetch['mgrcell'];
			$managermail = $fetch['mgremailid'];
			$senddate = datetimelocal("Y-m-d");
			$sendtime = datetimelocal("H:i:s");
			$servicename = 'Lead SMS-Bulk';
			$cellsplit = explode(',',$cell); //echo(count($cellsplit));exit;
			$array = array();
			$array[] = "#LeadID#%^%".$leadid;
			$array[] = "#LeadDate#%^%".$finaldate[0];
			$array[] = "#LeadProduct#%^%".$product;
			$array[] = "#LeadCompany#%^%".$company;
			$array[] = "#LeadContact#%^%".$contact;		
			$array[] = "#LeadDistrict#%^%".$district;	
			$array[] = "#LeadState#%^%".$state;	
			$array[] = "#LeadPhone#%^%".$phone;
			$array[] = "#LeadEmail#%^%".$emailid;
			$array[] = "#DealerCompany#%^%".$dealercompany;
			$array[] = "#DealerName#%^%".$dealername;
			$array[] = "#DealerCell#%^%".$dealercell;
			$array[] = "#DealerEmail#%^%".$dealeremail;
			$array[] = "#ManagerName#%^%".$managername;
			$array[] = "#ManagerCell#%^%".$managercell;
			$array[] = "#ManagerEmail#%^%".$managermail;
			if(count($cellsplit) > 1)
			{
				for($k=0;$k<count($cellsplit);$k++)
				{
					$sentsmscount++;
					$array[] = "#LeadCell#%^%".$cellsplit[$k];
					$message = replacemailvariablenew($smstext,$array);	
					$tonumber = $cellsplit[$k];
					//sendsmsforleads($servicename, $tonumber, $message, $senddate, $sendtime,$leadid,$userslno);
				}
			}
			else
			{
				$sentsmscount++;
				$array[] = "#LeadCell#%^%".$cell;
				$message = replacemailvariablenew($smstext,$array);	
				$tonumber = $cell;
				//sendsmsforleads($servicename, $tonumber, $message, $senddate, $sendtime,$leadid,$userslno);
			}
		}
		echo('1^'.$sentsmscount);
		break;
		
		case "getloopingcount":
		$checkedcount = $_POST['checkedcount'];
		$quotient = $checkedcount/5;
		$totallooprun = ($checkedcount % 5 == 0)?($checkedcount/5):(ceil($checkedcount/5));
		echo('1^'.$checkedcount.'^'.$totallooprun);
		break;		
		
		case "getalldetails":
			$id = $_POST['leadids'];
			$smstext = $_POST['smstext']; 
			$newarray = explode(',',$id);
		
			$grid .= '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" id="gridtablelead" >';
			
			//Write the header Row of the table
			$grid .= '<tr class="gridheader"><td nowrap="nowrap"  class="tdborderlead">Sl No</td><td nowrap="nowrap"  class="tdborderlead">Contact No</td><td nowrap="nowrap"  class="tdborderlead">Message Length</td><td nowrap="nowrap"  class="tdborderlead">SMS Credits</td><td nowrap="nowrap"  class="tdborderlead">SMS Text</td></tr>';
			$slno = 0;
			for($j = 0;$j < count($newarray);$j++)
			{
				$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, dealers.dlrname,dealers.dlrcell,dealers.dlremail,lms_managers.mgrname,lms_managers.mgrcell,lms_managers.mgremailid from leads  left join lms_followup on leads.id = lms_followup.leadid join dealers on leads.dealerid = dealers.id join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode where leads.id = '".$newarray[$j]."'";
				$fetch = runmysqlqueryfetch($query);
				$leadid = $fetch['id'];
				$date = changedateformatwithtime($fetch['leaddatetime']);
				$finaldate = explode('(',$date);
				$product = $fetch['productname'];
				$company = $fetch['company'];
				$contact = $fetch['name'];
				$cell = $fetch['cell'];
				$phone = $fetch['phone'];
				$emailid = $fetch['emailid'];
				$district = $fetch['distname'];
				$state = $fetch['state'];
				$dealercompany = $fetch['dlrcompanyname'];
				$dealername = $fetch['dlrname'];
				$dealercell = $fetch['dlrcell'];
				$dealeremail = $fetch['dlremail'];
				$managername = $fetch['mgrname'];
				$managercell = $fetch['mgrcell'];
				$managermail = $fetch['mgremailid'];
				$senddate = datetimelocal("Y-m-d");
				$sendtime = datetimelocal("H:i:s");
				$servicename = 'Lead SMS-Bulk';
				$cellsplit = explode(',',$cell); //echo(count($cellsplit));exit;
				$array = array();
				$array[] = "#LeadID#%^%".$leadid;
				$array[] = "#LeadDate#%^%".$finaldate[0];
				$array[] = "#LeadProduct#%^%".$product;
				$array[] = "#LeadCompany#%^%".$company;
				$array[] = "#LeadContact#%^%".$contact;		
				$array[] = "#LeadDistrict#%^%".$district;	
				$array[] = "#LeadState#%^%".$state;	
				$array[] = "#LeadPhone#%^%".$phone;
				$array[] = "#LeadEmail#%^%".$emailid;
				$array[] = "#DealerCompany#%^%".$dealercompany;
				$array[] = "#DealerName#%^%".$dealername;
				$array[] = "#DealerCell#%^%".$dealercell;
				$array[] = "#DealerEmail#%^%".$dealeremail;
				$array[] = "#ManagerName#%^%".$managername;
				$array[] = "#ManagerCell#%^%".$managercell;
				$array[] = "#ManagerEmail#%^%".$managermail;
				if(count($cellsplit) > 1)
				{
					for($k=0;$k<count($cellsplit);$k++)
					{
						$slno++;
						$array[] = "#LeadCell#%^%".$cellsplit[$k];
						$message = replacemailvariablenew($smstext,$array);							
						$tonumber = $cellsplit[$k];
						// Get SMS text Length
						$smslength = strlen($message);
						$displaylength = $smslength . '/ 160';
						$smscredits = ceil($smslength/160);
						$grid .= '<tr>';
						$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$slno."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$tonumber."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$displaylength."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead' >&nbsp;".$smscredits."</td>";
						$grid .= "<td nowrap='nowrap' class='tdborderlead' >&nbsp;".stripslashes($message)."</td>";
						//End the Row
						$grid .= '</tr>';
					}
				}
				else
				{
					$slno++;
					$array[] = "#LeadCell#%^%".$cell;
					$message = replacemailvariablenew($smstext,$array);	
					$tonumber = $cell;
					// Get SMS text Length
					$smslength = strlen($message);
					$displaylength = $smslength . '/ 160';
					$smscredits = ceil($smslength/160);
					$grid .= '<tr>';
					$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$slno."</td>";
					$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$tonumber."</td>";
					$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$displaylength."</td>";
					$grid .= "<td nowrap='nowrap' class='tdborderlead' >&nbsp;".$smscredits."</td>";
					$grid .= "<td nowrap='nowrap' class='tdborderlead' >&nbsp;".stripslashes($message)."</td>";
					//End the Row
					$grid .= '</tr>';
				}
			}
			//End of Table
			$grid .= '</table>';
		
			echo('1^'.$grid.'^'.$slno);
				
			
			break;
		
		
		
}
?>
