<?php

ini_set("memory_limit", "-1");
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];
switch ($submittype) {
	case "gridtoform":
		if (lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '') {
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			$form_recid = $_POST['form_recid'];
			$query = "select * from leads where id = '" . $form_recid . "'"; // echo($query);exit;
			$result = runmysqlqueryfetch($query);
			$leadid = $result['id'];
			//Update the dealer view date, if the login type is a Dealer.
			if ($cookie_usertype == "Dealer") {
				if ($result['dealerviewdate'] == '0000-00-00 00:00:00') {
					$viewdate = datetimelocal("Y-m-d");
					$viewtime = datetimelocal("H:i:s");
					$query2 = "UPDATE leads SET dealerviewdate = '" . $viewdate . ' ' . $viewtime . "' , leadstatus = 'UnAttended' WHERE leads.id = '" . $form_recid . "'";
					$result2 = runmysqlquery($query2);

					$query = "insert into `lms_updatelogs` (leadid, leadstatus, updatedate, updatedby) values('" . $form_recid . "', 'UnAttended', '" . $viewdate . ' ' . $viewtime . "', '" . $userslno . "')";
					$result = runmysqlquery($query);

				}
			}
			$query = "select leads.id AS id, leads.company AS company,leads.leadsubstatus AS leadsubstatus, leads.name AS name, leads.phone AS phone,leads.stdcode AS stdcode, leads.cell AS cell, leads.dealerviewdate AS dealerviewdate, leads.emailid AS emailid, leads.address AS address, leads.refer AS refer, 
			leads.source AS source, leads.leaddatetime AS leaddatetime, leads.leadstatusremarks ,regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dealername, lms_managers.mgrname AS managername, leads.leadstatus AS leadstatus, leads.leaduploadedby AS leaduploadedby, leads.lastupdatedby AS lastupdatedby, leads.leadremarks AS leadremarks, leads.lastupdateddate AS lastupdateddate, products.productname AS product, lms_dlrmembers.dlrmbrname AS dlrmbrname,products.id as productid,dealers.id as dealerid from (select * from leads WHERE leads.id = '" . $form_recid . "') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid";
			$result = runmysqlqueryfetch($query);

			$dealerviewdate = ($result['dealerviewdate'] == '0000-00-00 00:00:00') ? ('Not yet Seen') : (changedateformatwithtime($result['dealerviewdate']));
			$dlrmbrname = ($result['dlrmbrname'] == "") ? "Not Assigned" : $result['dlrmbrname'];
			$givenby = ($result['source'] == "Manual Upload") ? getuserdisplayname($result['leaduploadedby']) : "Webmaster";

			// Givenby tooltip text.
			if ($givenby == 'Webmaster') {
				$givenbytext = '<table width="100%" border="0"  cellspacing="0" cellpadding="0">';
				$givenbytext .= '<tr><td>Web Downloaded.</tr></td>';
				$givenbytext .= '</table>';
			} else if ($givenby <> 'Webmaster') {
				$givenbyid = $result['leaduploadedby'];
			}
			$givenbytooltiptext = ($result['source'] == "Manual Upload") ? tooltiptextdetails($result['leaduploadedby']) : $givenbytext;
			
			//$givenbytooltiptext = tooltiptextdetails($result['leaduploadedby']);
			// Dealer tooltip text
			
			$query5 = "select dlrcompanyname,dlrname,district,state,dlrcell,dlrphone,dlremail from dealers where dlrcompanyname = '" . $result['dealername'] . "'";
			$fetch5 = runmysqlqueryfetch($query5);
			$dealertooltiptext = '<table width="100%" border="0"  cellspacing="0" cellpadding="0">';
			$dealertooltiptext .= '<tr><td ><strong>Company:</strong> ' . $fetch5['dlrcompanyname'] . '</td></tr>';
			$dealertooltiptext .= '<tr><td ><strong>Contact Person:</strong> ' . $fetch5['dlrname'] . '</td></tr>';
			$dealertooltiptext .= '<tr><td ><strong>Place:</strong>' . $fetch5['district'] . ',' . $fetch5['state'] . '</td></tr>';
			$dealertooltiptext .= '<tr><td ><strong>Phone:</strong> ' . $fetch5['dlrphone'] . '</td></tr>';
			$dealertooltiptext .= '<tr><td ><strong>Cell:</strong> ' . $fetch5['dlrcell'] . '</td></tr>';
			$dealertooltiptext .= '<tr><td><strong>Email Id:</strong> ' . $fetch5['dlremail'] . '</td></tr>';
			$dealertooltiptext .= '</table>';

			// Manager  tooltip text.
			$query6 = "select mgrname,mgrlocation,mgrcell,mgremailid from lms_managers where mgrname = '" . $result['managername'] . "'";
			$fetch6 = runmysqlqueryfetch($query6);
			$managertooltiptext = '<table width="100%" border="0"  cellspacing="0" cellpadding="0">';
			$managertooltiptext .= '<tr><td><strong>Name:</strong> ' . $fetch6['mgrname'] . '</td></tr>';
			$managertooltiptext .= '<tr><td><strong>Place:</strong> ' . $fetch6['mgrlocation'] . '</td></tr>';
			$managertooltiptext .= '<tr><td><strong>Cell:</strong> ' . $fetch6['mgrcell'] . '</td></tr>';
			$managertooltiptext .= '<tr><td><strong>Email:</strong> ' . $fetch6['mgremailid'] . '</td></tr>';
			$managertooltiptext .= '</table>';


			if ($result['lastupdateddate'] <> '')
				$lastupdateddate = changedateformatwithtime($result['lastupdateddate']);
			else
				$lastupdateddate = "";

			if ($result['lastupdatedby'] <> '') {
				$lastupdatedbyname = getuserdisplayname($result['lastupdatedby']);
			} else
				$lastupdatedbyname = "";
			$leadremarks = $result['leadremarks'];
			$leadremarks = ($leadremarks == "") ? ("Not Available") : ($leadremarks);

			if ($cookie_usertype == 'Admin' || $cookie_usertype == 'Sub Admin' || $cookie_usertype == 'Reporting Authority' || $cookie_usertype == 'Dealer Member') {
				$dealerdisplay = $result['dealername'];
				$dealermember = $dlrmbrname;
			} elseif ($cookie_usertype == 'Dealer') {
				$dealerdisplay = $result['dealername'];
				$dealermember = $dlrmbrname;
			}


			$output = $leadid . "|^|" . $result['company'] . "|^|" . $result['id'] . "|^|" . $result['name'] . "|^|" . $result['address'] . "|^|" . $result['distname'] . "|^|" . $result['statename'] . "|^|" . $result['stdcode'] . "|^|" . $result['phone'] . "|^|" . $result['cell'] . "|^|" . $result['emailid'] . "|^|" . $result['refer'] . "|^|" . $result['source'] . "|^|" . $givenby . "|^|" . changedateformatwithtime($result['leaddatetime']) . "|^|" . $dealerviewdate . "|^|" . $result['product'] . "|^|" . $result['dealername'] . "|^|" . $dlrmbrname . "|^|" . $result['managername'] . "|^|" . $result['leadstatus'] . "|^|" . $lastupdatedbyname . "|^|" . $lastupdateddate . "|^|" . $leadremarks . "|^|" . $result['lastupdatedby'] . "|^|" . $userslno . "|^|" . $givenbytooltiptext . "|^|" . $dealertooltiptext . "|^|" . $managertooltiptext . "|^|" . $result['productid'] . "|^|" . $result['dealerid'] . "|^|" . $givenbyid . "|^|" . $dealerdisplay . "|^|" . $dealermember . "|^|" . $result['leadstatusremarks'] . "|^|" . $result['leadsubstatus'];
			//$output = $leadid."|^|".$leaddetail."|^|".$leadstatus."|^|".$lastupdatedbyname."|^|".$lastupdateddate."|^|".$leadremarks;
			echo ('1|^|' . $output);
		}
		break;
		case "showfollowups":
			if (lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '') {
				$cookie_username = lmsgetcookie('lmsusername');
				$cookie_usertype = lmsgetcookie('lmsusersort');
				$leadid = $_POST['form_recid'];

				// $query = "select lms_followup.followupid AS id,lms_followup.leadid, lms_followup.entereddate AS entereddate, lms_followup.remarks AS remarks, lms_followup.followupdate AS followupdate,lms_followup.followuptime AS followuptime, lms_followup.enteredby AS enteredby from lms_followup WHERE lms_followup.leadid = '" . $leadid . "' ORDER BY lms_followup.followupid";

				$query = "SELECT lms_followup.followupid AS id, lms_followup.leadid, lms_followup.entereddate AS entereddate, lms_followup.remarks AS remarks, lms_followup.followupdate AS followupdate, lms_followup.followuptime AS followuptime, lms_followup.enteredby AS enteredby FROM lms_followup WHERE lms_followup.leadid = '" . $leadid . "' ORDER BY lms_followup.followupid DESC";


				// $query = "SELECT lms_followup.followupid AS id, lms_followup.entereddate AS entereddate, lms_followup.remarks AS remarks, lms_followup.followupdate AS followupdate, lms_followup.followuptime AS followuptime, lms_followup.enteredby AS enteredby FROM lms_followup WHERE lms_followup.leadid = '".$leadid."' ORDER BY lms_followup.followupid";

				$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
				//Write the header Row of the table

				$grid .= '<tr class="gridheader"><td width="9%" nowrap="nowrap"  class="tdborder">Sl No</td><td width="9%" nowrap="nowrap"  class="tdborder">Lead ID</td>  <td width="14%" nowrap="nowrap"  class="tdborder">Date</td><td width="37%" nowrap="nowrap"  class="tdborder">Remarks</td><td width="20%" nowrap="nowrap"  class="tdborder">Next Follow-up</td><td width="20%" nowrap="nowrap"  class="tdborder">Next Follow-up Time</td><td width="20%" nowrap="nowrap"  class="tdborder">Entered by</td></tr>';
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				$loopcount = 0;
				if ($resultcount > 0) {
					while ($fetch = mysqli_fetch_array($result)) {
						$loopcount++;
						$grid .= '<tr class="gridrow" onclick="javascript:followuptoform(\'' . $fetch['id'] . '\');"  >';


						// $grid .= "<td nowrap='nowrap'  class='tdborder'>".$loopcount."</td><td nowrap='nowrap'  class='tdborder'>".changedateformat($fetch['entereddate'])."</td><td nowrap='nowrap'  class='tdborder'>".gridtrim30($fetch['remarks'])."</td><td nowrap='nowrap'  class='tdborder'>".changedateformat($fetch['followupdate'])."</td><td nowrap='nowrap'  class='tdborder'>".getuserdisplayname($fetch['enteredby'])."</td>";

						$grid .= "<td nowrap='nowrap' class='tdborder'>" . $loopcount . "</td>" .
							"<td nowrap='nowrap' class='tdborder'>" . $fetch['leadid'] . "</td>" .
							"<td nowrap='nowrap' class='tdborder'>" . changedateformat($fetch['entereddate']) . "</td>" .
							"<td nowrap='nowrap' class='tdborder'>" . gridtrim30($fetch['remarks']) . "</td>" .
							"<td nowrap='nowrap' class='tdborder'>" . changedateformat($fetch['followupdate']) . "</td>" .
							"<td nowrap='nowrap' class='tdborder'>" . changedateformat($fetch['followuptime']) . "</td>" .
							"<td nowrap='nowrap' class='tdborder'>" . getuserdisplayname($fetch['enteredby']) . "</td>";



						$grid .= '</tr>';
					}
				}
				//End of Table
				$grid .= '</tbody></table>';


				echo ('1^' . $grid);
			} else
				echo ("2^Your login might have expired. Please Logout and Login.");
			break;

		case "followuptoform":
			if (lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '') {
				$cookie_username = lmsgetcookie('lmsusername');
				$cookie_usertype = lmsgetcookie('lmsusersort');
				$followupid = $_POST['followupid'];
				$query = "SELECT lms_followup.remarks AS remarks, lms_followup.followupdate AS followupdate, lms_followup.followuptime AS followuptime FROM lms_followup WHERE lms_followup.followupid = '" . $followupid . "'";


				// $query = "select lms_followup.remarks AS remarks, lms_followup.followupdate AS followupdate lms_followup.followuptime AS followuptime from lms_followup WHERE lms_followup.followupid = '".$followupid."'";
				$result = runmysqlqueryfetch($query);

				$output = $result['remarks'] . "|^|" . changedateformat($result['followupdate']);
				echo ('1|^|' . $output);
			}
			break;
	case 'followupforday':
		if (lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '') {
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');	 //echo($cookie_usertype);exit;
			$startlimit = $_POST['startlimit'];
			$slnocount = $_POST['slnocount'];
			$showtype = $_POST['showtype'];
			if ($showtype == 'all')
				$limit = 1000;
			else
				$limit = 10;
			if ($startlimit == '') {
				$startlimit = 0;
				$slnocount = 0;
			} else {
				$startlimit = $slnocount;
				$slnocount = $slnocount;
			}
			
			switch ($cookie_usertype) {
				case 'Admin':
				case 'Sub Admin':


					// $query="SELECT DISTINCT leads.id, lms_followup.followupid AS followup_id, leads.leaddatetime, lms_followup.remarks, lms_followup.followupdate, lms_users.type AS enteredby FROM leads LEFT JOIN lms_followup ON lms_followup.leadid = leads.id LEFT JOIN lms_users ON lms_users.id = lms_followup.enteredby LEFT JOIN dealers ON dealers.id = leads.dealerid LEFT JOIN lms_managers ON lms_managers.id = dealers.managerid LEFT JOIN products ON products.id = leads.productid LEFT JOIN regions ON leads.regionid = regions.subdistcode WHERE lms_followup.followupdate BETWEEN DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND CURDATE() AND lms_followup.followupstatus = 'PENDING' AND leads.leadstatus NOT IN ('Fake Enquiry', 'Not Interested', 'Order Closed', 'Registered User') AND lms_followup.followupdate <> '0000-00-00' ORDER BY lms_followup.followupdate DESC";

					$query = "SELECT DISTINCT leads.id, lms_followup.followupid AS followup_id, leads.leaddatetime, lms_followup.remarks, lms_followup.followupdate, lms_users.type AS enteredby 
          FROM leads 
          LEFT JOIN lms_followup ON lms_followup.leadid = leads.id 
          LEFT JOIN lms_users ON lms_users.id = lms_followup.enteredby 
          LEFT JOIN dealers ON dealers.id = leads.dealerid 
          LEFT JOIN lms_managers ON lms_managers.id = dealers.managerid 	
          LEFT JOIN products ON products.id = leads.productid 
          LEFT JOIN regions ON leads.regionid = regions.subdistcode 
          WHERE lms_followup.followupdate = CURDATE() -- Only fetch records where followupdate is equal to the current date
            AND lms_followup.followupstatus = 'PENDING' 
            AND leads.leadstatus NOT IN ('Fake Enquiry', 'Not Interested', 'Order Closed', 'Registered User') 
            AND lms_followup.followupdate <> '0000-00-00' 
          ORDER BY lms_followup.followupdate DESC";




					break;

				case 'Reporting Authority':

					//Check wheteher the manager is branch head or not
					$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '" . $cookie_username . "' AND lms_users.type = 'Reporting Authority';";
					$result1 = runmysqlqueryfetch($query1);
					if ($result1['branchhead'] == 'yes')
						$branchpiecejoin = "AND (dealers.branch = '" . $result1['branch'] . "' OR dealers.managerid = '" . $result1['managerid'] . "')";
					else
						$branchpiecejoin = "and lms_users.username = '" . $cookie_username . "'";

					$query = "select distinct leads.id, lms_followup.followupid AS followup_id,leads.leaddatetime,  lms_followup.remarks, lms_followup.followupdate, lms_users.type AS enteredby from leads left join lms_followup on lms_followup.leadid = leads.id left join lms_users on lms_users.id = lms_followup.enteredby left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode where lms_followup.followupdate BETWEEN DATE_SUB(CURDATE(),INTERVAL 2 DAY) and CURDATE() and lms_followup.followupstatus = 'PENDING' and leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed'and leads.leadstatus <> 'Registered User' and lms_followup.followupdate <> '0000-00-00'  and lms_users.type = 'Reporting Authority' " . $branchpiecejoin . " ORDER BY lms_followup.followupdate DESC";
					//echo($query);exit;
					break;

				case 'Dealer':
					$query = "select distinct leads.id,lms_followup.followupid AS followup_id, leads.leaddatetime, lms_followup.remarks, lms_followup.followupdate,lms_users.type AS enteredby from leads left join lms_followup on lms_followup.leadid = leads.id left join lms_users on lms_users.id = lms_followup.enteredby left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode  where lms_followup.followupdate BETWEEN DATE_SUB(CURDATE(),INTERVAL 2 DAY) and CURDATE() and lms_followup.followupstatus = 'PENDING' and leads.leadstatus <> 'Fake Enquiry'and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed'and leads.leadstatus <> 'Registered User' and lms_followup.followupdate <> '0000-00-00' and lms_users.username = '" . $cookie_username . "' and lms_users.type = 'Dealer' ORDER BY lms_followup.followupdate DESC";
					break;

				case 'Dealer Member':
					$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join lms_followup on lms_followup.leadid = leads.id left join lms_users on lms_users.referenceid = leads.dlrmbrid left join dealers on dealers.id =leads.dealerid left join lms_dlrmembers on lms_users.referenceid = lms_dlrmembers.dlrmbrid left join lms_managers on lms_managers.id =dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode  where lms_followup.followupdate BETWEEN DATE_SUB(CURDATE(),INTERVAL 2 DAY) and CURDATE() and lms_followup.followupstatus = 'PENDING' and leads.leadstatus <> 'Fake Enquiry'and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed' and leads.leadstatus <> 'Registered User' and lms_followup.followupdate <> '0000-00-00' and lms_users.username = '" . $cookie_username . "' and lms_users.type = 'Dealer Member' ORDER BY lms_followup.followupdate DESC";
					break;
			}
			//echo($query);exit;
			if ($slnocount == '0') {
				$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
				//Write the header Row of the table
				$grid .= '<tr class="gridheader" ><td nowrap="nowrap" class="tdborderlead">Sl No</td><td nowrap="nowrap"  class="tdborderlead">Lead ID</td><td nowrap="nowrap"  class="tdborderlead">Followup ID</td><td nowrap="nowrap"  class="tdborderlead">Followup Date</td><td nowrap="nowrap"  class="tdborderlead">Remarks</td><td nowrap="nowrap"  class="tdborderlead">Next Follow-up</td><td nowrap="nowrap"  class="tdborderlead">Enterd by</td></tr><tbody>';
			}
			$result = runmysqlquery($query);
			// ?????      
			$fetchresultcount = mysqli_num_rows($result);
			$addlimit = " LIMIT " . $startlimit . "," . $limit . "; ";
			$query1 = $query . $addlimit;
			$result1 = runmysqlquery($query1);
			//echo($query1); exit;
			while ($fetch = mysqli_fetch_row($result1)) {
				$slnocount++;
				//Begin a row
				$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\'' . $fetch[0] . '\');">';
				$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;" . $slnocount . "</td>";
				//Write the cell data
				for ($i = 0; $i < count($fetch); $i++) {
					if ($i == 1)
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;" . changedateformatwithtime($fetch[$i]) . "</td>";
					else
						$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;" . gridtrim30($fetch[$i]) . "</td>";
				}

				//End the Row
				$grid .= '</tr>';
			}
			$grid .= "</tbody></table>";
			if ($slnocount >= $fetchresultcount)
				$linkgrid .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
				$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecordsfollowup(\'' . $startlimit . '\',\'' . $slnocount . '\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecordsfollowup(\'' . $startlimit . '\',\'' . $slnocount . '\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';


			$k = 0;
			while ($fetch2 = mysqli_fetch_row($result)) {

				for ($i = 0; $i < count($fetch2); $i++) {
					if ($i == 0)
						if ($k == 0)
							$leadidarray .= $fetch2[$i];
						else
							$leadidarray .= '$' . $fetch2[$i];
					$k++;
				}
			}
			echo ('1^' . $grid . '^' . $linkgrid . '^' . $fetchresultcount . '^' . $leadidarray);
			//echo('1^'.$query1.'^'.$limit);
		}
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
		switch ($dealerselectiontype) {
			case "samedealer":
				$query = "select * from leads where id = '" . $leadid . "'";
				$fetch = runmysqlqueryfetch($query);
				if ($fetch['productid'] == $product) {
					echo ('2^Please select different Product');
				} else if ($fetch['dealerid'] == $dealerid) {
					$query = "SELECT * FROM leads WHERE emailid = '" . $emailid . "' AND productid = '" . $product . "'";
					$result = runmysqlquery($query);
					$count = mysqli_num_rows($result);
					if ($count > 0) {
						$result = runmysqlqueryfetch($query);
						$leadid = $result['id'];
						echo ("2^Lead already exists for same product [Followup ID: " . $leadid . "].");
					} else {
						$query2 = "insert into `leads` (dealerid, productid, source, company, name, address, place, regionid, phone, emailid, refer, leaduploadedby, leadstatus, leadremarks, dealernativeid, cell,stdcode,initialcontactname,initialaddress,initialstdcode,initialphone,initialcellnumber,initialemailid,leaddatetime)values('" . $fetch['dealerid'] . "', '" . $product . "','Manual Upload', '" . $fetch['company'] . "', '" . $contactperson . "', '" . $fetch['address'] . "', '" . $fetch['place'] . "', '" . $fetch['regionid'] . "', '" . $fetch['phone'] . "', '" . $emailid . "', '" . $leadsource . "', '" . $userslno . "', 'Not Viewed', '" . $remarks . "', '" . $dealerid . "', '" . $cellnumber . "', '" . $fetch['stdcode'] . "' ,'" . $fetch['name'] . "','" . $fetch['address'] . "','" . $fetch['stdcode'] . "','" . $fetch['phone'] . "','" . $cellnumber . "','" . $emailid . "','" . $leaddate . ' ' . $leadtime . "')";

						//echo($query2);exit;
						$result2 = runmysqlquery($query2);

						//Fetch new Followup ID to insert into updatelogs.
						$query3 = "select id,leadstatus from leads where source = 'Manual Upload' and company = '" . $fetch['company'] . "' and name = '" . $contactperson . "' and leadremarks = '" . $remarks . "' and productid = '" . $product . "' and leaddatetime = '" . $leaddate . ' ' . $leadtime . "' ";
						$result3 = runmysqlqueryfetch($query3);

						//Insert Details to lms_updatelogs
						$query5 = "insert into lms_updatelogs set leadid = '" . $result3['id'] . "',leadstatus = '" . $result3['leadstatus'] . "',updatedate = '" . $leaddate . ' ' . $leadtime . "',updatedby = '" . $userslno . "'";
						$result5 = runmysqlquery($query5);

						//Send SMS to concerned dealer about the lead
						$query = "SELECT * FROM dealers WHERE id = '" . $dealerid . "'";
						$result = runmysqlqueryfetch($query);
						$dlrcell = $result['dlrcell'];
						$query = "SELECT * FROM products WHERE id = '" . $product . "'";
						$result = runmysqlqueryfetch($query);
						$productname = $result['productname'];

						// Insert it to logs
						$query3 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('" . $userslno . "','" . $_SERVER['REMOTE_ADDR'] . "','23','" . $leaddate . ' ' . $leadtime . "')";
						$result = runmysqlquery($query3);

						// Fetch details to send SMS
						$queryfetch = "select * from leads where dealerid = '" . $dealerid . "' and productid = '" . $product . "' and leaddatetime = '" . $leaddate . ' ' . $leadtime . "'";
						$resultfetch = runmysqlqueryfetch($queryfetch);

						//echo('1^'.$resultfetch['id']);exit;
						$name = $resultfetch['name'];
						$phone = $resultfetch['phone'];
						$company = $resultfetch['company'];

						$servicename = 'LEAD Uploaded';
						$tonumber = $dlrcell;
						$smstext = "Relyon LMS: " . substr($name, 0, 29) . " of " . substr($company, 0, 29) . " requires " . substr($productname, 0, 29) . ". Call " . substr($phone, 0, 29) . ".";
						$senddate = datetimelocal("Y-m-d");
						$sendtime = datetimelocal("H:i:s");
						//sendsmsforleads($servicename, $tonumber, $smstext, $senddate, $sendtime,NULL,NULL);
						echo ("1^Lead Uploaded Successfully.");
					}
				} else {
					echo ('2^Select Manual Selection to Choose different Dealer');
				}
				break;

		

			case "manualselection":
				$query = "select * from leads where id = '" . $leadid . "'";
				$fetch = runmysqlqueryfetch($query);
				if ($fetch['productid'] == $product) {
					echo ('2^Please select different Product');
				} else if ($fetch['dealerid'] == $dealerid) {
					echo ('2^Please select different Dealer');
				} else {
					$query = "SELECT * FROM leads WHERE emailid = '" . $emailid . "' AND productid = '" . $product . "'";
					$result = runmysqlquery($query);
					$count = mysqli_num_rows($result);
					if ($count > 0) {
						$result = runmysqlqueryfetch($query);
						$leadid = $result['id'];
						echo ("2^Lead already exists for same product [Followup ID: " . $leadid . "].");
					} else {
						$query7 = "insert into `leads` (dealerid, productid, source, company, name, address, place, regionid, phone, emailid, refer, leaduploadedby, leadstatus, leadremarks, dealernativeid, cell,stdcode,initialcontactname,initialaddress,initialstdcode,initialphone,initialcellnumber,initialemailid,leaddatetime)values('" . $dealerid . "', '" . $product . "','Manual Upload', '" . $fetch['company'] . "', '" . $contactperson . "', '" . $fetch['address'] . "', '" . $fetch['place'] . "', '" . $fetch['regionid'] . "', '" . $fetch['phone'] . "', '" . $emailid . "', '" . $leadsource . "', '" . $userslno . "', 'Not Viewed', '" . $remarks . "', '" . $dealerid . "', '" . $cellnumber . "', '" . $fetch['stdcode'] . "' ,'" . $fetch['name'] . "','" . $fetch['address'] . "','" . $fetch['stdcode'] . "','" . $fetch['phone'] . "','" . $cellnumber . "','" . $emailid . "','" . $leaddate . ' ' . $leadtime . "')";

						//echo($query2);exit;
						$result7 = runmysqlquery($query7);

						//Fetch new Followup ID to insert into updatelogs.
						$query8 = "select id,leadstatus from leads where source = 'Manual Upload' and company = '" . $fetch['company'] . "' and name = '" . $contactperson . "' and leadremarks = '" . $remarks . "' and productid = '" . $product . "' and leaddatetime = '" . $leaddate . ' ' . $leadtime . "' ";
						$result8 = runmysqlqueryfetch($query8);

						//Insert Details to lms_updatelogs
						$query9 = "insert into lms_updatelogs set leadid = '" . $result8['id'] . "',leadstatus = '" . $result8['leadstatus'] . "',updatedate = '" . $leaddate . ' ' . $leadtime . "',updatedby = '" . $userslno . "'";
						$result9 = runmysqlquery($query9);

						//Send SMS to concerned dealer about the lead
						$query = "SELECT * FROM dealers WHERE id = '" . $dealerid . "'";
						$result = runmysqlqueryfetch($query);
						$dlrcell = $result['dlrcell'];
						$query = "SELECT * FROM products WHERE id = '" . $product . "'";
						$result = runmysqlqueryfetch($query);
						$productname = $result['productname'];

						// Insert it to logs
						$query3 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('" . $userslno . "','" . $_SERVER['REMOTE_ADDR'] . "','23','" . $leaddate . ' ' . $leadtime . "')";
						$result = runmysqlquery($query3);

						// Fetch details to send SMS

						$queryfetch = "select * from leads where dealerid = '" . $dealerid . "' and productid = '" . $product . "' and leaddatetime = '" . $leaddate . ' ' . $leadtime . "'";
						$resultfetch = runmysqlqueryfetch($queryfetch);

						//echo('1^'.$resultfetch['id']);exit;
						$name = $resultfetch['name'];
						$phone = $resultfetch['phone'];
						$company = $resultfetch['company'];

						$servicename = 'LEAD Uploaded';
						$tonumber = $dlrcell;
						$smstext = "Relyon LMS: " . substr($name, 0, 29) . " of " . substr($company, 0, 29) . " requires " . substr($productname, 0, 29) . ". Call " . substr($phone, 0, 29) . ".";
						$senddate = datetimelocal("Y-m-d");
						$sendtime = datetimelocal("H:i:s");
						//	sendsmsforleads($servicename, $tonumber, $smstext, $senddate, $sendtime,NULL,NULL);

						echo ("1^Lead Uploaded Successfully.");
					}
				}
				break;
		}

		break;



	
}
?>