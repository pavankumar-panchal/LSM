<?

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
			$query1 = "select leads.id AS leadid, leads.leaddatetime AS leaddatetime, products.productname AS productname, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.emailid AS emailid, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dlrcompanyname, lms_dlrmembers.dlrmbrname AS dlrmbrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 20 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC";
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
				//Write the header Row of the table
				$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder">Lead ID</td><td nowrap="nowrap" class="tdborder">Lead Date</td><td nowrap="nowrap" class="tdborder">Product</td><td nowrap="nowrap" class="tdborder">Company</td><td nowrap="nowrap" class="tdborder">Contact</td><td nowrap="nowrap" class="tdborder">Phone</td><td nowrap="nowrap" class="tdborder">Email ID</td><td nowrap="nowrap" class="tdborder">District<td nowrap="nowrap" class="tdborder">State</td><td nowrap="nowrap" class="tdborder">Dealer Member</td></tr>';
			}
			$query = "select leads.id AS leadid, leads.leaddatetime AS leaddatetime, products.productname AS productname, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.emailid AS emailid, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dlrcompanyname, lms_dlrmembers.dlrmbrname AS dlrmbrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 20 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC  LIMIT ".$startlimit.",".$limit.";";
			
			$result = runmysqlquery($query);
			$resultcount = mysqli_num_rows($result);
			while($fetch = mysqli_fetch_array($result))
			{
				$slnocount++;
				$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
				$grid .= "<td nowrap='nowrap' class='tdborder'>".$fetch['leadid']."</td>"."<td nowrap='nowrap' class='tdborder'>".changedateformatwithtime($fetch['leaddatetime'])."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['productname']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['company']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['name']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['phone']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['emailid']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['distname']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['statename']."</td>"."<td nowrap='nowrap' class='tdborder's>".$fetch['dlrmbrname']."</td>";
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

	case "filter":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$searchtext = $_POST['searchtext'];
			$subselection = $_POST['subselection'];
			$datatype = $_POST['datatype'];
			$datatype = ($datatype == "download")?"Product Download":(($datatype == "upload")?"Manual Upload":"");
		
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
				$startlimit = $slnocount ;
				$slnocount = $slnocount;
			}
			
			switch($subselection)
			{
				case "leadid":
					$query = "select leads.id AS leadid, leads.leaddatetime AS leaddatetime, products.productname AS productname, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.emailid AS emailid, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dlrcompanyname, lms_dlrmembers.dlrmbrname AS dlrmbrname from (select * from leads WHERE leads.id LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC ";
					break;
				case "company":
					$query = "select leads.id AS leadid, leads.leaddatetime AS leaddatetime, products.productname AS productname, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.emailid AS emailid, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dlrcompanyname, lms_dlrmembers.dlrmbrname AS dlrmbrname from (select * from leads WHERE leads.company LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads  join dealers on dealers.id = leads.dealerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC ";
					break;
				case "name":
					$query = "select leads.id AS leadid, leads.leaddatetime AS leaddatetime, products.productname AS productname, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.emailid AS emailid, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dlrcompanyname, lms_dlrmembers.dlrmbrname AS dlrmbrname from (select * from leads WHERE leads.name LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC ";
					break;
				case "phone":
					$query = "select leads.id AS leadid, leads.leaddatetime AS leaddatetime, products.productname AS productname, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.emailid AS emailid, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dlrcompanyname, lms_dlrmembers.dlrmbrname AS dlrmbrname from (select * from leads WHERE leads.phone LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC ";
					break;
				case "email":
					$query = "select leads.id AS leadid, leads.leaddatetime AS leaddatetime, products.productname AS productname, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.emailid AS emailid, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dlrcompanyname, lms_dlrmembers.dlrmbrname AS dlrmbrname from (select * from leads WHERE leads.emailid LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC ";
					break;
				case "district":
					$query = "select leads.id AS leadid, leads.leaddatetime AS leaddatetime, products.productname AS productname, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.emailid AS emailid, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dlrcompanyname, lms_dlrmembers.dlrmbrname AS dlrmbrname from (select * from regions WHERE regions.distname LIKE '%".$searchtext."%') AS regions join leads on leads.regionid = regions.subdistcode AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join products on products.id = leads.productid join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC ";
					break;
				case "state":
					$query = "select leads.id AS leadid, leads.leaddatetime AS leaddatetime, products.productname AS productname, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.emailid AS emailid, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dlrcompanyname, lms_dlrmembers.dlrmbrname AS dlrmbrname from (select * from regions WHERE regions.statename LIKE '%".$searchtext."%') AS regions join leads on leads.regionid = regions.subdistcode AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join products on products.id = leads.productid join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC ";
					break;
				case "product":
					$query = "select leads.id AS leadid, leads.leaddatetime AS leaddatetime, products.productname AS productname, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.emailid AS emailid, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dlrcompanyname, lms_dlrmembers.dlrmbrname AS dlrmbrname from (select * from products WHERE products.productname LIKE '%".$searchtext."%') AS products join leads on products.id = leads.productid AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC ";
					break;
			}
			if($slnocount == '0')
			{
			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
			//Write the header Row of the table
			$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder">Lead ID</td><td nowrap="nowrap" class="tdborder">Lead Date</td><td nowrap="nowrap" class="tdborder">Product</td><td nowrap="nowrap" class="tdborder">Company</td><td nowrap="nowrap" class="tdborder">Contact</td><td nowrap="nowrap" class="tdborder">Phone</td><td nowrap="nowrap" class="tdborder">Email ID</td><td nowrap="nowrap" class="tdborder">District<td nowrap="nowrap" class="tdborder">State</td><td nowrap="nowrap" class="tdborder">Dealer Member</td></tr>';
			}
			$result = runmysqlquery($query);
			$fetchresultcount = mysqli_num_rows($result);
			$addlimit = " LIMIT ".$startlimit.",".$limit.";";
			$query1 = $query.$addlimit;
			$result1 = runmysqlquery($query1);
			while($fetch = mysqli_fetch_array($result1))
			{
				$slnocount++;
				$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
				$grid .= "<td nowrap='nowrap' class='tdborder'>".$fetch['leadid']."</td>"."<td nowrap='nowrap' class='tdborder'>".changedateformatwithtime($fetch['leaddatetime'])."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['productname']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['company']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['name']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['phone']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['emailid']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['distname']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['statename']."</td>"."<td nowrap='nowrap' class='tdborder'>".$fetch['dlrmbrname']."</td>";
				$grid .= '</tr>';
			}
			//End of Table
			$grid .= '</tbody></table>';
			if($slnocount >= $fetchresultcount)
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'search\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'search\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
			echo("1|^|".$grid."|^|".$linkgrid.'|^|'.$fetchresultcount);
			}
		else 
			echo("2|^|Your login might have expired. Please Logout and Login.");
		break;
		
	case "gridtoform":
		$form_recid = $_POST['form_recid'];
		$query = "select leads.id AS id, products.productname AS product, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.emailid AS emailid, regions.distname AS distname, regions.statename AS statename, lms_dlrmembers.dlrmbrname AS dlrmbrname from (select * from leads WHERE leads.id = '".$form_recid."') AS leads join dealers on dealers.id = leads.dealerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode left outer join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid ";
		$result = runmysqlqueryfetch($query);
		$leadid = $result['id'];
		$leaddetail = "<strong>Company</strong>: ".$result['company']."<br /><strong>Contact person</strong>: ".$result['name']."<br /><strong>District</strong>: ".$result['distname']."<br /><strong>State</strong>: ".$result['statename']."<br /><strong>Phone</strong>: ".$result['phone']."<br /><strong>Email ID</strong>: ".$result['emailid'];
		$product = $result['product'];
		$dlrmbrname = ($result['dlrmbrname'] == "")?"None":$result['dlrmbrname'];
		$output = $leadid."|^|".$leaddetail."|^|".$product."|^|".$dlrmbrname;
		echo($output);
		break;

	case "save":
		$form_recid = $_POST['form_recid'];
		$form_dlrmbr = $_POST['form_dlrmbr'];
		$transferdate = datetimelocal("Y-m-d");
		$transfertime = datetimelocal("H:i:s");
		$cookie_username = lmsgetcookie('lmsusername');
		//Fetch dealer id
		
		/*$query1 = "SELECT leads.dealerid AS fromdealer,lms_users.id AS trasferredby from leads left join lms_users on lms_users.username = '".$cookie_username."' WHERE leads.id = '".$form_recid."' ";
		
		$result = runmysqlqueryfetch($query1);*/
		
		// Insert to transerlogs
		/*$query2 = "insert into `lms_transferlogs` (leadid, fromdealer, todealer, transferdate, trasferredby) values('".$form_recid."', '".$result['fromdealer']."', '".$form_dlrmbr."', '".$transferdate.' '.$transfertime."', '".$result['trasferredby']."')";
		$result = runmysqlquery($query2);*/
		
		// Insert logs on save of Lead Transfer
		$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','25','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
		$result = runmysqlquery_log($query);
		
		
		$query = "update leads set dlrmbrid = '".$form_dlrmbr."' WHERE id = '".$form_recid."'";
		$result = runmysqlquery($query);
		$output = "1^Lead Updated Successfully.";
		//$output = '1^'.;
		echo($output);
		break;
}
?>