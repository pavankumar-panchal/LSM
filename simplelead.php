<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);


include("inc/ajax-referer-security.php");
include("functions/phpfunctions.php");
include("inc/getuserslno.php");

$submittype = $_POST['submittype'];

switch ($submittype) {

	case "griddata":
		if (isset($_COOKIE['lmsusername']) && isset($_COOKIE['lmsusertype'])) {
			$cookie_username = $_COOKIE['lmsusername'];
			$cookie_usertype = $_COOKIE['lmsusertype'];
			$startlimit = $_POST['startlimit'];
			$slnocount = $_POST['slnocount'];
			$showtype = $_POST['showtype'];
			if ($showtype == 'all')
				$limit = 100000;
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
				case "Admin":
				case "Sub Admin":
					$query = "select leads.id, leads.dateoflead, products.productname, leads.company, leads.name, leads.phone, leads.cell,leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.dateoflead > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC";
					break;
				case "Reporting Authority":
					$query = "select leads.id, leads.dateoflead, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.dateoflead > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_users.username = '" . $cookie_username . "' ORDER BY leads.id DESC";
					if ($cookie_username == "srinivasan")
						$query = "select leads.id, leads.dateoflead, products.productname, leads.company, leads.name, leads.phone, leads.cell,leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.dateoflead > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_users.username = '" . $cookie_username . "' or  lms_users.username = 'nagaraj' ORDER BY leads.id DESC";
					break;
				case "Dealer":
					$query = "select leads.id, leads.dateoflead, products.productname, leads.company, leads.name, leads.phone, leads.cell,leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.dateoflead > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' WHERE lms_users.username = '" . $cookie_username . "' ORDER BY leads.id DESC";
					break;
				case "Dealer Member":
					$query = "select leads.id, leads.dateoflead, products.productname, leads.company, leads.name, leads.phone, leads.cell,leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.dateoflead > DATE_SUB(CURDATE(),INTERVAL 20 DAY)) AS leads join lms_dlrmembers on lms_dlrmembers.dlrmbrid = leads .dlrmbrid join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = lms_dlrmembers.dlrmbrid AND lms_users.type = 'Dealer Member' WHERE lms_users.username = '" . $cookie_username . "' ORDER BY leads.id DESC";
					break;
			}
			if ($slnocount == '0') {
				$grid = '<table width="100%" border="1" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
				//Write the header Row of the table
				$grid .= '<tr class="gridheader"><td nowrap="nowrap">Lead ID</td><td nowrap="nowrap">Lead Date</td><td nowrap="nowrap">Product</td><td nowrap="nowrap">Company</td><td nowrap="nowrap">Contact</td><td nowrap="nowrap">Landline</td><td nowrap="nowrap">Cell</td><td nowrap="nowrap">Email ID</td><td nowrap="nowrap">District<td nowrap="nowrap">State</td><td nowrap="nowrap">Dealer</td><td nowrap="nowrap">Manager</td></tr>';
			}
			$result = runmysqlquery($query);
			$fetchresultcount = mysqli_num_rows($result);
			$addlimit = " LIMIT " . $startlimit . "," . $limit . "; ";
			$query1 = $query . $addlimit;
			$result1 = runmysqlquery($query1);
			if ($fetchresultcount > 0) {
				while ($fetch = mysqli_fetch_row($result1)) {
					$slnocount++;
					//Begin a row
					$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\'' . $fetch[0] . '\');">';

					//Write the cell data
					for ($i = 0; $i < count($fetch); $i++) {
						if ($i == 1)
							$grid .= "<td nowrap='nowrap'>" . changedateformat($fetch[$i]) . "</td>";
						else
							$grid .= "<td nowrap='nowrap'>" . gridtrim30($fetch[$i]) . "</td>";
					}

					//End the Row
					$grid .= '</tr>';
				}
			}
			//End of Table
			$grid .= '</tbody></table>';
			if ($slnocount >= $fetchresultcount)
				$linkgrid .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
				$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\'' . $startlimit . '\',\'' . $slnocount . '\',\'more\',\'lead\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\'' . $startlimit . '\',\'' . $slnocount . '\',\'all\',\'lead\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			echo ("1|^|" . $grid . "|^|" . $linkgrid . "|^|" . $fetchresultcount);
		} else
			echo ("2|^|Your login might have expired. Please Logout and Login.");
		break;


	case "filter":
		$fromdate = changedateformat($_POST['fromdate']);
		$todate = changedateformat($_POST['todate']);
		$dealerid = $_POST['dealerid'];
		$givenby = $_POST['givenby'];
		$productid = $_POST['productid'];
		$leadstatus = $_REQUEST['leadstatus'];
		$filter_followupdate1 = $_POST['filter_followupdate1'];
		$filter_followupdate2 = $_POST['filter_followupdate2'];
		$dropterminatedstatus = $_POST['dropterminatedstatus'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		if ($showtype == 'all')
			$limit = 100000;
		else
			$limit = 10;
		if ($startlimit == '') {
			$startlimit = 0;
			$slnocount = 0;
		} else {
			$startlimit = $slnocount;
			$slnocount = $slnocount;
		}
		if ($filter_followupdate1 == 'dontconsider') {
			$followuppiece = "";
		} else {
			$followuppiece = "AND lms_followup.followupdate >= '" . changedateformat($filter_followupdate1) . "' AND lms_followup.followupdate <= '" . changedateformat($filter_followupdate2) . "'";
		}

		$dealerpiece = ($dealerid == '') ? "" : ("AND dealerid = '" . $dealerid . "'");
		$productpiece = ($productid == '') ? "" : ("AND productid = '" . $productid . "'");
		$leadstatuspiece = ($leadstatus == '') ? "" : ("AND leadstatus = '" . $leadstatus . "'");
		$leaduploadedby = ($givenby == '') ? "" : (($givenby == 'web') ? "AND leaduploadedby IS NULL" : "AND leaduploadedby = '" . $givenby . "'");
		$terminatedstatuspiece = ($dropterminatedstatus == 'true') ? ("AND leads.leadstatus <> 'Order Closed' AND leads.leadstatus <> 'Not Interested' AND leads.leadstatus <> 'Fake Enquiry' AND leads.leadstatus <> 'Registered User'") : "";

		if (isset($_COOKIE['lmsusername']) && isset($_COOKIE['lmsusertype']) && checkdateformat($fromdate) && checkdateformat($todate) && ((datenumeric($todate) - datenumeric($fromdate)) >= 0)) {
			//Check who is making the entry
			$cookie_username = $_COOKIE['lmsusername'];
			$cookie_usertype = $_COOKIE['lmsusertype'];

			switch ($cookie_usertype) {
				case "Admin":
				case "Sub Admin":
					//$query = "select leads.id, leads.dateoflead, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select leads.id AS id, leads.dateoflead AS dateoflead, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.cell AS cell, leads.emailid AS emailid, leads.dealerid AS dealerid, leads.productid AS productid, leads.regionid AS regionid, lms_followup.followupdate AS followupdate from leads left join (select leadid, followupdate from lms_followup where followupstatus = 'PENDING') AS lms_followup on leads.id = lms_followup.leadid WHERE leads.dateoflead >= '".$fromdate."' AND leads.dateoflead <= '".$todate."' ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece.") AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC";
					$query = "select leads.id, leads.dateoflead, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from leads  left join lms_followup on leads.id = lms_followup.leadid join dealers on leads.dealerid = dealers.id join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode where leads.dateoflead >= '" . $fromdate . "' AND leads.dateoflead <= '" . $todate . "'  " . $terminatedstatuspiece . " AND (lms_followup.followupstatus = 'PENDING' OR lms_followup.followupstatus IS NULL) ORDER BY leads.id ";
					break;
				case "Reporting Authority":
					$query = "select leads.id, leads.dateoflead, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select leads.id AS id, leads.dateoflead AS dateoflead, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.cell AS cell, leads.emailid AS emailid, leads.dealerid AS dealerid, leads.productid AS productid, leads.regionid AS regionid, lms_followup.followupdate AS followupdate from leads left join (select leadid, followupdate from lms_followup where followupstatus = 'PENDING') AS lms_followup on leads.id = lms_followup.leadid WHERE leads.dateoflead >= '" . $fromdate . "' AND leads.dateoflead <= '" . $todate . "' " . $terminatedstatuspiece . " " . $followuppiece . " " . $dealerpiece . " " . $productpiece . " " . $leaduploadedby . " " . $leadstatuspiece . ") AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_users.username = '" . $cookie_username . "' ORDER BY leads.id DESC";
					if ($cookie_username == "srinivasan")
						//$query = "select leads.id, leads.dateoflead, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select leads.id AS id, leads.dateoflead AS dateoflead, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.cell AS cell, leads.emailid AS emailid, leads.dealerid AS dealerid, leads.productid AS productid, leads.regionid AS regionid, lms_followup.followupdate AS followupdate from leads left join (select leadid, followupdate from lms_followup where followupstatus = 'PENDING') AS lms_followup on leads.id = lms_followup.leadid WHERE leads.dateoflead >= '".$fromdate."' AND leads.dateoflead <= '".$todate."' ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece.") AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj' ORDER BY leads.id DESC";
						$query = "select leads.id, leads.dateoflead, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname  from leads left join lms_followup on leads.id = lms_followup.leadid left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id=dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_managers.id where (followupstatus = 'PENDING' or lms_followup.followupstatus is null ) and leads.dateoflead >= '" . $fromdate . "' AND leads.dateoflead <= '" . $todate . "' " . $terminatedstatuspiece . " AND lms_users.type = 'Reporting Authority' AND (lms_users.username = 'srinivasan' or  lms_users.username = 'nagaraj') ORDER BY leads.id DESC";
					break;
				case "Dealer":
					$query = "select leads.id, leads.dateoflead, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select leads.id AS id, leads.dateoflead AS dateoflead, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.cell AS cell, leads.emailid AS emailid, leads.dealerid AS dealerid, leads.productid AS productid, leads.regionid AS regionid, lms_followup.followupdate AS followupdate from leads left join (select leadid, followupdate from lms_followup where followupstatus = 'PENDING') AS lms_followup on leads.id = lms_followup.leadid WHERE leads.dateoflead >= '" . $fromdate . "' AND leads.dateoflead <= '" . $todate . "' " . $terminatedstatuspiece . " " . $followuppiece . " " . $dealerpiece . " " . $productpiece . " " . $leaduploadedby . " " . $leadstatuspiece . ") AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' WHERE lms_users.username = '" . $cookie_username . "' ORDER BY leads.id DESC";
					break;
				case "Dealer Member":
					$query = "select leads.id, leads.dateoflead, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select leads.id AS id, leads.dateoflead AS dateoflead, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.cell AS cell, leads.emailid AS emailid, leads.dealerid AS dealerid, leads.productid AS productid, leads.regionid AS regionid, lms_followup.followupdate AS followupdate, leads.dlrmbrid AS dlrmbrid from leads left join (select leadid, followupdate from lms_followup where followupstatus = 'PENDING') AS lms_followup on leads.id = lms_followup.leadid WHERE leads.dateoflead >= '" . $fromdate . "' AND leads.dateoflead <= '" . $todate . "' " . $terminatedstatuspiece . " " . $followuppiece . " " . $dealerpiece . " " . $productpiece . " " . $leaduploadedby . " " . $leadstatuspiece . ") AS leads join lms_dlrmembers on lms_dlrmembers.dlrmbrid = leads.dlrmbrid join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = lms_dlrmembers.dlrmbrid AND lms_users.type = 'Dealer Member' WHERE lms_users.username = '" . $cookie_username . "' ORDER BY leads.id DESC";
					break;
			}
			if ($slnocount == '0') {
				$grid = '<table width="100%" border="1" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable">';
				//Write the header Row of the table
				$grid .= '<tr class="gridheader"><td nowrap="nowrap">Lead ID</td><td nowrap="nowrap">Lead Date</td><td nowrap="nowrap">Product</td><td nowrap="nowrap">Company</td><td nowrap="nowrap">Contact</td><td nowrap="nowrap">Landline</td><td nowrap="nowrap">Cell</td><td nowrap="nowrap">Email ID</td><td nowrap="nowrap">District<td nowrap="nowrap">State</td><td nowrap="nowrap">Dealer</td><td nowrap="nowrap">Manager</td></tr><tbody>';
			}
			$result = runmysqlquery($query);
			$fetchresultcount = mysqli_num_rows($result);
			$addlimit = " LIMIT " . $startlimit . "," . $limit . ";";
			$query1 = $query . $addlimit;
			$result1 = runmysqlquery($query1);
			if ($fetchresultcount > 0) {
				while ($fetch = mysqli_fetch_row($result1)) {
					$slnocount++;
					//Begin a row
					$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\'' . $fetch[0] . '\');">';

					//Write the cell data
					for ($i = 0; $i < count($fetch); $i++) {
						if ($i == 1)
							$grid .= "<td nowrap='nowrap'>" . changedateformat($fetch[$i]) . "</td>";
						else
							$grid .= "<td nowrap='nowrap'>" . gridtrim30($fetch[$i]) . "</td>";
					}

					//End the Row
					$grid .= '</tr>';
				}
			}
			//End of Table
			$grid .= '</tbody></table>';
			if ($slnocount >= $fetchresultcount)
				$linkgrid .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
				$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\'' . $startlimit . '\',\'' . $slnocount . '\',\'more\',\'filter\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\'' . $startlimit . '\',\'' . $slnocount . '\',\'all\',\'filter\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			// Insert logs on filter of Lead
			$query2 = "insert into lms_logs_login(userid,logindate,logintime,system,eventtype) values('" . $userslno . "','" . datetimelocal("Y-m-d") . "','" . datetimelocal("H:i:s") . "','" . $_SERVER['REMOTE_ADDR'] . "','29')";
			$result2 = runmysqlquery($query2);

			echo ("1|^|" . $grid . "|^|" . $linkgrid . '|^|' . $fetchresultcount);

		} else
			echo ("1|^|Unable to Process. The data [Eg: Date] may be improper or Your login might have expired. Please Logout and Login.");
		break;

	case "gridtoform":
		if (isset($_COOKIE['lmsusername']) && isset($_COOKIE['lmsusertype'])) {
			$cookie_username = $_COOKIE['lmsusername'];
			$cookie_usertype = $_COOKIE['lmsusertype'];
			$form_recid = $_POST['form_recid'];
			$query = "select leads.id AS id, leads.company AS company, leads.name AS name, leads.phone AS phone,leads.stdcode AS stdcode, leads.cell AS cell, leads.dealerviewdate AS dealerviewdate, leads.emailid AS emailid, leads.address AS address, leads.refer AS refer, leads.source AS source, leads.dateoflead AS dateoflead, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dealername, lms_managers.mgrname AS managername, leads.leadstatus AS leadstatus, leads.leaduploadedby AS leaduploadedby, leads.lastupdatedby AS lastupdatedby, leads.leadremarks AS leadremarks, leads.lastupdateddate AS lastupdateddate, products.productname AS product, lms_dlrmembers.dlrmbrname AS dlrmbrname from (select * from leads WHERE leads.id = '" . $form_recid . "') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid";
			$result = runmysqlqueryfetch($query);
			$leadid = $result['id'];
			//Update the dealer view date, if the login type is a Dealer.
			if ($cookie_usertype == "Dealer") {
				if ($result['dealerviewdate'] == '0000-00-00') {
					$viewdate = datetimelocal("Y-m-d");
					$query2 = "UPDATE leads SET dealerviewdate = '" . $viewdate . "' WHERE leads.id = '" . $form_recid . "'";
					$result2 = runmysqlquery($query2);
				}
			}
			$dealerviewdate = ($result['dealerviewdate'] == '0000-00-00') ? ('Not yet viewed') : (changedateformat($result['dealerviewdate']));
			$dlrmbrname = ($result['dlrmbrname'] == "") ? "None" : $result['dlrmbrname'];
			$givenby = ($result['source'] == "Manual Upload") ? getuserdisplayname($result['leaduploadedby']) : "Webmaster";
			$leaddetail = '<table width="100%" border="0" cellspacing="0" cellpadding="2">
											<tr>
											  <td width="35%"><strong>Company [id]</strong>: </td>
											  <td width="65%"><font color="#FF6600">' . $result['company'] . ' [' . $result['id'] . ']</font></td>
											</tr>
											<tr>
											  <td><strong>Contact person</strong>:</td>
											  <td><font color="#FF6600">' . $result['name'] . '</font></td>
											</tr>
											<tr>
											  <td><strong>Address:</strong></td>
											  <td><font color="#FF6600">' . $result['address'] . '</font></td>
											</tr>
											<tr>
											  <td><strong>District / State</strong>: </td>
											  <td><font color="#FF6600">' . $result['distname'] . ' / ' . $result['statename'] . '</font></td>
											</tr>
											<tr>
											  <td><strong>STD Code</strong>: </td>
											  <td><font color="#FF6600">' . $result['stdcode'] . '</font></td>
											</tr>
											<tr>
											  <td><strong>Landline</strong>: </td>
											  <td><font color="#FF6600">' . $result['phone'] . '</font></td>
											</tr>
											<tr>
											  <td><strong>Cell</strong>: </td>
											  <td><font color="#FF6600">' . $result['cell'] . '</font></td>
											</tr>
											<tr>
											  <td><strong>Email ID</strong>: </td>
											  <td><font color="#FF6600">' . $result['emailid'] . '</font></td>
											</tr>
											<tr>
											  <td><strong>Reference [Type] :</strong></td>
											  <td><font color="#FF6600">' . $result['refer'] . ' [' . $result['source'] . ']</font></td>
											</tr>
											<tr>
											  <td><strong>Given By :</strong></td>
											  <td><font color="#FF6600">' . $givenby . '</font></td>
											</tr>
											<tr>
											  <td><strong>Date of lead:</strong></td>
											  <td><font color="#FF6600">' . changedateformat($result['dateoflead']) . '</font></td>
											</tr>
											<tr>
											  <td><strong>Dealer viewed date:</strong></td>
											  <td><font color="#FF6600">' . $dealerviewdate . '</font></td>
											</tr>
											<tr>
											  <td><strong>Product:</strong></td>
											  <td><font color="#FF6600">' . $result['product'] . '</font></td>
											</tr>
											<tr>
											  <td><strong>Dealer:</strong></td>
											  <td><font color="#FF6600">' . $result['dealername'] . '</font></td>
											</tr>
											<tr>
											  <td><strong>Dealer Member:</strong></td>
											  <td><font color="#FF6600">' . $dlrmbrname . '</font></td>
											</tr>
											<tr>
											  <td><strong>Manager:</strong></td>
											  <td><font color="#FF6600">' . $result['managername'] . '</font></td>
											</tr>
										  </table>';
			$leadstatus = $result['leadstatus'];

			if ($result['lastupdateddate'] <> '')
				$lastupdateddate = changedateformat($result['lastupdateddate']);
			else
				$lastupdateddate = "";

			if ($result['lastupdatedby'] <> '') {
				$lastupdatedbyname = getuserdisplayname($result['lastupdatedby']);
			} else
				$lastupdatedbyname = "";
			$leadremarks = $result['leadremarks'];
			$leadremarks = ($leadremarks == "") ? ("Not Available") : ($leadremarks);
			$output = $leadid . "|^|" . $leaddetail . "|^|" . $leadstatus . "|^|" . $lastupdatedbyname . "|^|" . $lastupdateddate . "|^|" . $leadremarks;
			echo ('1|^|' . $output);
		}
		break;

	case "save":
		if (isset($_COOKIE['lmsusername']) && isset($_COOKIE['lmsusertype'])) {
			$cookie_username = $_COOKIE['lmsusername'];
			$cookie_usertype = $_COOKIE['lmsusertype'];
			$form_recid = $_POST['form_recid'];
			$form_leadstatus = $_POST['form_leadstatus'];
			$lastupdateddate = datetimelocal("Y-m-d");

			$query2 = "select * from lms_users WHERE username = '" . $cookie_username . "'";
			$result2 = runmysqlqueryfetch($query2);
			$lastupdatedby = $result2['id'];

			$query = "insert into `lms_updatelogs` (leadid, leadstatus, updatedate, updatedby) values('" . $form_recid . "', '" . $form_leadstatus . "', '" . $lastupdateddate . "', '" . $lastupdatedby . "')";
			$result = runmysqlquery($query);

			$query = "update leads set leadstatus = '" . $form_leadstatus . "', lastupdateddate = '" . $lastupdateddate . "', lastupdatedby = '" . $lastupdatedby . "' WHERE id = '" . $form_recid . "'";
			$result = runmysqlquery($query);
			// Insert logs on save of Lead
			$query = "insert into lms_logs_login(userid,logindate,logintime,system,eventtype) values('" . $userslno . "','" . datetimelocal("Y-m-d") . "','" . datetimelocal("H:i:s") . "','" . $_SERVER['REMOTE_ADDR'] . "','27')";
			$result = runmysqlquery($query);
			$output = "1^Lead Updated Successfully.";
			echo ($output);
		} else
			echo ("2^Your login might have expired. Please Logout and Login.");
		break;

	case "addfollowup":
		if (isset($_COOKIE['lmsusername']) && isset($_COOKIE['lmsusertype'])) {
			$cookie_username = $_COOKIE['lmsusername'];
			$cookie_usertype = $_COOKIE['lmsusertype'];
			$leadid = $_POST['form_recid'];
			$form_leadremarks = $_POST['form_leadremarks'];
			$followupdate = $_POST['followupdate'];
			$enteredddate = datetimelocal("Y-m-d");

			$output = "";
			if ($output == "") {
				if ($followupdate == "") {
					$query = "select * from leads where id = '" . $leadid . "'";
					$result = runmysqlqueryfetch($query);
					if ($result['leadstatus'] <> "Order Closed" && $result['leadstatus'] <> "Registered User" && $result['leadstatus'] <> "Fake Enquiry" && $result['leadstatus'] <> "Not Interested")
						$output = "1" . "|^|" . "Date of Followup is compulsory.";
				}
			}
			if ($output == "" && $followupdate <> "") {
				$followupdate = changedateformat($followupdate);
				if (((datenumeric($followupdate) - datenumeric($enteredddate)) < 0))
					$output = "1" . "|^|" . "Date of Followup cannot be before today.";
			}
			if ($output == "") {
				$query2 = "select * from lms_users WHERE username = '" . $cookie_username . "'";
				$result2 = runmysqlqueryfetch($query2);
				$enteredby = $result2['id'];

				$query = "UPDATE `lms_followup` SET followupstatus = 'DONE' WHERE leadid = '" . $leadid . "'";
				$result = runmysqlquery($query);

				$query = "insert into `lms_followup` (leadid, remarks, entereddate, followupdate, enteredby, followupstatus) values('" . $leadid . "', '" . $form_leadremarks . "', '" . $enteredddate . "', '" . $followupdate . "', '" . $enteredby . "', 'PENDING')";
				$result = runmysqlquery($query);

				// Insert logs on lead followup
				$query = "insert into lms_logs_login(userid,logindate,logintime,system,eventtype) values('" . $userslno . "','" . datetimelocal("Y-m-d") . "','" . datetimelocal("H:i:s") . "','" . $_SERVER['REMOTE_ADDR'] . "','26')";
				$result = runmysqlquery($query);

				$output = "0" . "|^|" . "Lead Updated Successfully.";
			}
			echo ($output);
		} else
			echo ("Your login might have expired. Please Logout and Login.");
		break;

	case "showfollowups":
		if (isset($_COOKIE['lmsusername']) && isset($_COOKIE['lmsusertype'])) {
			$cookie_username = $_COOKIE['lmsusername'];
			$cookie_usertype = $_COOKIE['lmsusertype'];
			$leadid = $_POST['form_recid'];

			$query = "select lms_followup.followupid AS id, lms_followup.entereddate AS entereddate, lms_followup.remarks AS remarks, lms_followup.followupdate AS followupdate, lms_followup.enteredby AS enteredby from lms_followup WHERE lms_followup.leadid = '" . $leadid . "' ORDER BY lms_followup.followupid";

			$grid = '<table width="100%" border="1" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
			//Write the header Row of the table
			$grid .= '<tr class="gridheader"><td width="9%" nowrap="nowrap">Sl No</td><td width="14%" nowrap="nowrap">Date</td><td width="37%" nowrap="nowrap">Remarks</td><td width="20%" nowrap="nowrap">Next Follow-up</td><td width="20%" nowrap="nowrap">Entered by</td></tr>';
			$result = runmysqlquery($query);
			$resultcount = mysqli_num_rows($result);
			$loopcount = 0;
			if ($resultcount > 0) {
				while ($fetch = mysqli_fetch_array($result)) {
					$loopcount++;
					$grid .= '<tr class="gridrow" onclick="javascript:followuptoform(\'' . $fetch['id'] . '\');">';
					$grid .= "<td nowrap='nowrap'>" . $loopcount . "</td><td nowrap='nowrap'>" . changedateformat($fetch['entereddate']) . "</td><td nowrap='nowrap'>" . gridtrim30($fetch['remarks']) . "</td><td nowrap='nowrap'>" . changedateformat($fetch['followupdate']) . "</td><td nowrap='nowrap'>" . getuserdisplayname($fetch['enteredby']) . "</td>";
					$grid .= '</tr>';
				}
			}
			//End of Table
			$grid .= '</tbody></table>';
			echo ('1|^|' . $grid);
		} else
			echo ("2^Your login might have expired. Please Logout and Login.");
		break;

	case "followuptoform":
		if (isset($_COOKIE['lmsusername']) && isset($_COOKIE['lmsusertype'])) {
			$cookie_username = $_COOKIE['lmsusername'];
			$cookie_usertype = $_COOKIE['lmsusertype'];
			$followupid = $_POST['followupid'];
			$query = "select lms_followup.remarks AS remarks, lms_followup.followupdate AS followupdate from lms_followup WHERE lms_followup.followupid = '" . $followupid . "'";
			$result = runmysqlqueryfetch($query);

			$output = $result['remarks'] . "|^|" . changedateformat($result['followupdate']);
			echo ('1|^|' . $output);
		}
		break;
}
?>