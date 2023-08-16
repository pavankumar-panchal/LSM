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
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC";
					break;
				case "Reporting Authority":
					$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC";
					if($cookie_username == "srinivasan")
						$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj' ORDER BY leads.id DESC";
					break;
				case "Dealer":
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' WHERE lms_users.username = '".$cookie_username."' ORDER BY leads.id DESC";
					break;
			}
			if($slnocount == '0')
			{
				$grid = '<table width="100%" border="0" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
				//Write the header Row of the table
				$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder">Sl No</td><td nowrap="nowrap" class="tdborder">Lead ID</td><td nowrap="nowrap" class="tdborder">Lead Date</td><td nowrap="nowrap" class="tdborder">Product</td><td nowrap="nowrap" class="tdborder">Company</td><td nowrap="nowrap" class="tdborder">Contact</td><td nowrap="nowrap" class="tdborder">Landline</td><td nowrap="nowrap" class="tdborder">Cell</td><td nowrap="nowrap" class="tdborder">Email ID</td><td nowrap="nowrap" class="tdborder">District<td nowrap="nowrap" class="tdborder">State</td><td nowrap="nowrap" class="tdborder">Dealer</td><td nowrap="nowrap" class="tdborder">Manager</td></tr>';
			}
			$result = runmysqlquery($query);
			$fetchresultcount = mysqli_num_rows($result);
			$addlimit = " LIMIT ".$startlimit.",".$limit."; ";
			$query1 = $query.$addlimit;
			$result1 = runmysqlquery($query1);
			
			while($fetch = mysqli_fetch_row($result1))
			{
				$slnocount++;
				//Begin a row
				$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
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
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'transfer\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'transfer\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
			echo("1|^|".$grid."|^|".$linkgrid."|^|".$fetchresultcount);
		}
		else 
			echo("2|^|Your login might have expired. Please Logout and Login.");
		break;

	case "filter":
		$searchtext = $_POST['searchtext'];
		$subselection = $_POST['subselection'];
		$datatype = $_POST['datatype'];
		$datatype = ($datatype == "download")?"Product Download":(($datatype == "upload")?"Manual Upload":"");
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
		
		//Check who is making the entry
		$cookie_username = lmsgetcookie('lmsusername');
		$cookie_usertype = lmsgetcookie('lmsusersort');
		if($cookie_usertype == "Reporting Authority")
		{
			$query = "select * from lms_users where lms_users.username = '".$cookie_username."'";
			$result = runmysqlqueryfetch($query);
			$managerid = $result['referenceid'];
			$manageridcheckpiece = "(lms_managers.id = '".$managerid."')";
			if($cookie_username == "srinivasan")
				$manageridcheckpiece = "(lms_managers.id = '".$managerid."' OR lms_managers.id = '23')";
		}
		
		switch($subselection)
		{
			case "leadid":
				if($cookie_usertype == "Reporting Authority")
					$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.id LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND ".$manageridcheckpiece." join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				else
					$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.id LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				break;
			case "company":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.company LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads  join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND ".$manageridcheckpiece." join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.company LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads  join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				break;
			case "name":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.name LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND ".$manageridcheckpiece." join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.name LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				break;
			case "phone":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.phone LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND ".$manageridcheckpiece." join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.phone LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				break;
				case "cell":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.cell LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND ".$manageridcheckpiece." join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.cell LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				break;
			case "email":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.emailid LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND ".$manageridcheckpiece." join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.emailid LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				break;
			case "district":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from regions WHERE regions.distname LIKE '%".$searchtext."%') AS regions join leads on leads.regionid = regions.subdistcode AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND ".$manageridcheckpiece." join products on products.id = leads.productid ORDER BY leads.id DESC ";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from regions WHERE regions.distname LIKE '%".$searchtext."%') AS regions join leads on leads.regionid = regions.subdistcode AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid ORDER BY leads.id DESC ";
				break;
			case "state":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from regions WHERE regions.statename LIKE '%".$searchtext."%') AS regions join leads on leads.regionid = regions.subdistcode AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND ".$manageridcheckpiece." join products on products.id = leads.productid ORDER BY leads.id DESC ";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from regions WHERE regions.statename LIKE '%".$searchtext."%') AS regions join leads on leads.regionid = regions.subdistcode AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid ORDER BY leads.id DESC ";
				break;
			case "product":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from products WHERE products.productname LIKE '%".$searchtext."%') AS products join leads on products.id = leads.productid AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND ".$manageridcheckpiece." join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from products WHERE products.productname LIKE '%".$searchtext."%') AS products join leads on products.id = leads.productid AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				break;
			case "dealer":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from dealers WHERE dealers.dlrcompanyname LIKE '%".$searchtext."%') AS dealers join leads on dealers.id = leads.dealerid AND leads.source like '%".$datatype."%' join lms_managers on lms_managers.id = dealers.managerid AND ".$manageridcheckpiece." join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from dealers WHERE dealers.dlrcompanyname LIKE '%".$searchtext."%') AS dealers join leads on dealers.id = leads.dealerid AND leads.source like '%".$datatype."%' join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC ";
				break;
			case "manager":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from lms_managers WHERE lms_managers.mgrname LIKE '%".$searchtext."%' AND ".$manageridcheckpiece.") AS lms_managers join dealers on lms_managers.id = dealers.managerid join leads on dealers.id = leads.dealerid AND leads.source like '%".$datatype."%' join regions on leads.regionid = regions.subdistcode join products on products.id = leads.productid ORDER BY leads.id DESC ";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone,leads.cell, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from lms_managers WHERE lms_managers.mgrname LIKE '%".$searchtext."%') AS lms_managers join dealers on lms_managers.id = dealers.managerid join leads on dealers.id = leads.dealerid AND leads.source like '%".$datatype."%' join regions on leads.regionid = regions.subdistcode join products on products.id = leads.productid ORDER BY leads.id DESC ";
				break;
		}
		if($slnocount == '0')
		{
			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
		//Write the header Row of the table
			$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder">Sl No</td><td nowrap="nowrap" class="tdborder">Lead ID</td><td nowrap="nowrap" class="tdborder">Lead Date</td><td nowrap="nowrap" class="tdborder">Product</td><td nowrap="nowrap" class="tdborder">Company</td><td nowrap="nowrap" class="tdborder">Contact</td><td nowrap="nowrap" class="tdborder">Landline</td><td nowrap="nowrap" class="tdborder">Cell</td><td nowrap="nowrap" class="tdborder">Email ID</td><td nowrap="nowrap" class="tdborder">District<td nowrap="nowrap" class="tdborder">State</td><td nowrap="nowrap" class="tdborder">Dealer</td><td nowrap="nowrap" class="tdborder">Manager</td></tr>';
		}
		$result = runmysqlquery($query);
		$fetchresultcount = mysqli_num_rows($result);
		$addlimit = " LIMIT ".$startlimit.",".$limit.";";
		$query1 = $query.$addlimit;
		$result1 = runmysqlquery($query1);
		while($fetch = mysqli_fetch_row($result1))
		{
			$slnocount++;
			//Begin a row
			$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
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
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\',\'search\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\',\'search\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';	
		// Insert logs on filter of Lead Transfer
		$query2 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','24','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
		$result2 = runmysqlquery_log($query2);
		
		echo("1|^|".$grid."|^|".$linkgrid.'|^|'.$fetchresultcount);
		//echo(changedateformat($fetch[$i]);
		break;
		
	case "gridtoform":
		$form_recid = $_POST['form_recid'];
		$query = "select leads.id AS id, products.productname AS product, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.emailid AS emailid, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS fromdealer, leads.leaduploadedby AS leaduploadedby , leads.cell AS cell, leads.stdcode AS stdcode from (select * from leads WHERE leads.id = '".$form_recid."') AS leads join dealers on dealers.id = leads.dealerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode";
		$result = runmysqlqueryfetch($query);
		$leadid = $result['id'];
		$leaddetail = "<strong>Company</strong>: ".$result['company']."<br /><strong>Contact person</strong>: ".$result['name']."<br /><strong>District</strong>: ".$result['distname']."<br /><strong>State</strong>: ".$result['statename']."<br /><strong>STD Code</strong>: ".$result['stdcode']."<br /><strong>Landline</strong>: ".$result['phone']."<br /><strong>Cell</strong>: ".$result['cell']."<br /><strong>Email ID</strong>: ".$result['emailid'];
		if($result['leaduploadedby'] <> "")
		$leaddetail .= "<br /><strong>Uploaded by</strong>: ".getuserdisplayname($result['leaduploadedby']);
		$product = $result['product'];
		$fromdealer = $result['fromdealer'];
		$output = $leadid."|^|".$leaddetail."|^|".$product."|^|".$fromdealer;
		echo('1|^|'.$output);
		break;

	case "save":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');

			$form_recid = $_POST['form_recid'];
			$form_dealer = $_POST['form_dealer'];
			$message = "";
			if($message == "")
			{
				$query = "select * from leads join lms_users on leads.leaduploadedby = lms_users.id AND lms_users.type = 'Dealer' WHERE leads.id = '".$form_recid."' AND leads.source = 'Manual Upload'";
				$result = runmysqlquery($query);
				$count = mysqli_num_rows($result);
				if($count > 0)
				{
					if($cookie_usertype == "Reporting Authority" || $cookie_usertype == "Sub Admin")
					{
						$query = "select * from (select lms_users.username AS username, lms_managers.transferuploadedleads AS transferuploadedleads from lms_users join lms_managers on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting authority' union all select lms_users.username AS username, lms_subadmins.transferuploadedleads AS transferuploadedleads from lms_users join lms_subadmins on lms_users.referenceid = lms_subadmins.id AND lms_users.type = 'Sub Admin') AS transferpermission WHERE transferpermission.username = '".$cookie_username."' AND (transferpermission.transferuploadedleads <> '1' OR transferpermission.transferuploadedleads IS NULL)";
						$result = runmysqlquery($query);
						$count = mysqli_num_rows($result);
						if($count > 0)
							$message = "2^Cannot Transfer: You do not have permission to transfer the lead, uploaded by a Dealer.";
					}
					else
						$message = "2^Cannot Transfer: The lead is uploaded by a Dealer.";
				}
			}
			if($message == "")
			{
					//$querycheck = "select * from lms_users where id = '".$_POST['form_dealer']."' and disablelogin <> 'yes'";
					$transferdate = datetimelocal("Y-m-d");
					$transfertime = datetimelocal("H:i:s");
					$query1 = "select leads.dealerid AS fromdealer, leads.productid as productid, leads.name as name, leads.company as company, leads.phone as phone, lms_users.id AS trasferredby from leads join lms_users on lms_users.username = '".$cookie_username."' WHERE leads.id = '".$form_recid."'";
					$result = runmysqlqueryfetch($query1);
					$productid = $result['productid'];
					$name = $result['name'];
					$company = $result['company'];
					$phone = $result['phone'];
					
					$query = "insert into `lms_transferlogs` (leadid, fromdealer, todealer, transferdate, trasferredby) values('".$form_recid."', '".$result['fromdealer']."', '".$form_dealer."', '".$transferdate.' '.$transfertime."', '".$result['trasferredby']."')";
					$result = runmysqlquery($query);
					$query = "update leads set dealerid = '".$form_dealer."', dlrmbrid = '' WHERE id = '".$form_recid."'";
					$result = runmysqlquery($query);
					// Insert logs on save of Lead Transfer
					$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','25','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
					$result = runmysqlquery_log($query);
					$message = "1^Lead Transfered Successfully.";
	
					//Send SMS to concerned dealer about the lead
					$query = "SELECT * FROM dealers WHERE id = '".$form_dealer."'";
					$result = runmysqlqueryfetch($query);
					$dlrcell = $result['dlrcell'];
			
					$query = "SELECT * FROM products WHERE id = '".$productid."'";
					$result = runmysqlqueryfetch($query);
					$productname = $result['productname'];
			
					$servicename = 'LEAD Transfer';
					$tonumber = $dlrcell;
					$smstext = "Relyon LMS: ".substr($name, 0, 29)." of ".substr($company, 0, 29)." requires ".substr($productname, 0, 29).". Call ".substr($phone, 0, 29).".";
					$senddate = $transferdate;
					$sendtime = $transfertime;
					sendsms($servicename, $tonumber, $smstext, $senddate, $sendtime);
				
			}
			echo($message);
		}
		else 
			echo("2^Your login might have expired. Please Logout and Login.");
		break;
		
		
}
?>