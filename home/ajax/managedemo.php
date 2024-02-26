<?php

include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];

switch($submittype)
{

	case "griddata":
		$grid = '<table width="100%" border="1" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
		//Write the header Row of the table
	$grid .= '<tr class="gridheader"><td nowrap="nowrap">Lead ID</td><td nowrap="nowrap">Lead Date</td><td nowrap="nowrap">Product</td><td nowrap="nowrap">Company</td><td nowrap="nowrap">Contact</td><td nowrap="nowrap">Phone</td><td nowrap="nowrap">Email ID</td><td nowrap="nowrap">District<td nowrap="nowrap">State</td><td nowrap="nowrap">Dealer</td><td nowrap="nowrap">Manager</td></tr>';
		$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.leaddatetime > DATE_SUB(CURDATE(),INTERVAL 2 DAY)) AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC";
		$result = runmysqlquery($query);
		$resultcount = mysqli_num_rows($result);
		while($fetch = mysqli_fetch_row($result))
		{
			//Begin a row
			$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
			
			//Write the cell data
			for($i = 0; $i < count($fetch); $i++)
			{
				$grid .= "<td nowrap='nowrap'>".gridtrim30($fetch[$i])."</td>";
			}
		
			//End the Row
			$grid .= '</tr>';
		}
		//End of Table
		$grid .= '</tbody></table>';
		echo($grid."|^|".$resultcount);
		break;

	case "filter":
		$searchtext = $_POST['searchtext'];
		$subselection = $_POST['subselection'];
		$datatype = $_POST['datatype'];
		$datatype = ($datatype == "download")?"Product Download":(($datatype == "upload")?"Manual Upload":"");

		//Check who is making the entry
		$cookie_username = lmsgetcookie('lmsusername');
		$cookie_usertype = lmsgetcookie('lmsusersort');
		if($cookie_usertype == "Reporting Authority")
		{
			$query = "select * from lms_users where lms_users.username = '".$cookie_username."'";
			$result = runmysqlqueryfetch($query);
			$managerid = $result['referenceid'];
		}
		
		switch($subselection)
		{
			case "leadid":
				if($cookie_usertype == "Reporting Authority")
					$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.id LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND lms_managers.id = '".$managerid."' join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC LIMIT 50";
				else
					$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.id LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC LIMIT 50";
				break;
			case "company":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.company LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads  join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND lms_managers.id = '".$managerid."' join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC LIMIT 50";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.company LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads  join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC LIMIT 50";
				break;
			case "name":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.name LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND lms_managers.id = '".$managerid."' join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC LIMIT 50";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.name LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC LIMIT 50";
				break;
			case "phone":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.phone LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND lms_managers.id = '".$managerid."' join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC LIMIT 50";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.phone LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC LIMIT 50";
				break;
			case "email":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.emailid LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND lms_managers.id = '".$managerid."' join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC LIMIT 50";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from leads WHERE leads.emailid LIKE '%".$searchtext."%' AND leads.source like '%".$datatype."%') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC LIMIT 50";
				break;
			case "district":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from regions WHERE regions.distname LIKE '%".$searchtext."%') AS regions join leads on leads.regionid = regions.subdistcode AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND lms_managers.id = '".$managerid."' join products on products.id = leads.productid ORDER BY leads.id DESC LIMIT 50";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from regions WHERE regions.distname LIKE '%".$searchtext."%') AS regions join leads on leads.regionid = regions.subdistcode AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid ORDER BY leads.id DESC LIMIT 50";
				break;
			case "state":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from regions WHERE regions.statename LIKE '%".$searchtext."%') AS regions join leads on leads.regionid = regions.subdistcode AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND lms_managers.id = '".$managerid."' join products on products.id = leads.productid ORDER BY leads.id DESC LIMIT 50";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from regions WHERE regions.statename LIKE '%".$searchtext."%') AS regions join leads on leads.regionid = regions.subdistcode AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid ORDER BY leads.id DESC LIMIT 50";
				break;
			case "product":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from products WHERE products.productname LIKE '%".$searchtext."%') AS products join leads on products.id = leads.productid AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid AND lms_managers.id = '".$managerid."' join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC LIMIT 50";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from products WHERE products.productname LIKE '%".$searchtext."%') AS products join leads on products.id = leads.productid AND leads.source like '%".$datatype."%' join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC LIMIT 50";
				break;
			case "dealer":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from dealers WHERE dealers.dlrcompanyname LIKE '%".$searchtext."%') AS dealers join leads on dealers.id = leads.dealerid AND leads.source like '%".$datatype."%' join lms_managers on lms_managers.id = dealers.managerid AND lms_managers.id = '".$managerid."' join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC LIMIT 50";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from dealers WHERE dealers.dlrcompanyname LIKE '%".$searchtext."%') AS dealers join leads on dealers.id = leads.dealerid AND leads.source like '%".$datatype."%' join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode ORDER BY leads.id DESC LIMIT 50";
				break;
			case "manager":
				if($cookie_usertype == "Reporting Authority")
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from lms_managers WHERE lms_managers.mgrname LIKE '%".$searchtext."%' AND lms_managers.id = '".$managerid."') AS lms_managers join dealers on lms_managers.id = dealers.managerid join leads on dealers.id = leads.dealerid AND leads.source like '%".$datatype."%' join regions on leads.regionid = regions.subdistcode join products on products.id = leads.productid ORDER BY leads.id DESC LIMIT 50";
				else
				$query = "select leads.id, leads.leaddatetime, products.productname, leads.company, leads.name, leads.phone, leads.emailid, regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from (select * from lms_managers WHERE lms_managers.mgrname LIKE '%".$searchtext."%') AS lms_managers join dealers on lms_managers.id = dealers.managerid join leads on dealers.id = leads.dealerid AND leads.source like '%".$datatype."%' join regions on leads.regionid = regions.subdistcode join products on products.id = leads.productid ORDER BY leads.id DESC LIMIT 50";
				break;
		}
		$grid = '<table width="100%" border="1" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
		//Write the header Row of the table
	$grid .= '<tr class="gridheader"><td nowrap="nowrap">Lead ID</td><td nowrap="nowrap">Lead Date</td><td nowrap="nowrap">Product</td><td nowrap="nowrap">Company</td><td nowrap="nowrap">Contact</td><td nowrap="nowrap">Phone</td><td nowrap="nowrap">Email ID</td><td nowrap="nowrap">District<td nowrap="nowrap">State</td><td nowrap="nowrap">Dealer</td><td nowrap="nowrap">Manager</td></tr>';
		$result = runmysqlquery($query);
		$resultcount = mysqli_num_rows($result);
		while($fetch = mysqli_fetch_row($result))
		{
			//Begin a row
			$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
			
			//Write the cell data
			for($i = 0; $i < count($fetch); $i++)
			{
				$grid .= "<td nowrap='nowrap'>".gridtrim30($fetch[$i])."</td>";
			}
		
			//End the Row
			$grid .= '</tr>';
		}
		//End of Table
		$grid .= '</tbody></table>';
		echo($grid."|^|".$resultcount);
		break;
		
	case "gridtoform":
		$form_recid = $_POST['form_recid'];
		$query = "select leads.id AS id, leads.company AS company, leads.name AS name, leads.phone AS phone, leads.emailid AS emailid, leads.address AS address, leads.refer AS refer, leads.source AS source, leads.leaddatetime AS leaddatetime, regions.distname AS distname, regions.statename AS statename, dealers.dlrcompanyname AS dealername, lms_managers.mgrname AS managername, products.productname AS product from (select * from leads WHERE leads.id = '".$form_recid."') AS leads join dealers on dealers.id = leads.dealerid join lms_managers on lms_managers.id = dealers.managerid join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode";
		$result = runmysqlqueryfetch($query);
		$leadid = $result['id'];
		$leaddetail = '<table width="100%" border="0" cellspacing="0" cellpadding="2">
                                        <tr>
                                          <td width="35%"><strong>Company [id]</strong>: </td>
                                          <td width="65%"><font color="#FF6600">'.$result['company'].' ['.$result['id'].']</font></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Contact person</strong>:</td>
                                          <td><font color="#FF6600">'.$result['name'].'</font></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Address:</strong></td>
                                          <td><font color="#FF6600">'.$result['address'].'</font></td>
                                        </tr>
                                        <tr>
                                          <td><strong>District / State</strong>: </td>
                                          <td><font color="#FF6600">'.$result['distname'].' / '.$result['statename'].'</font></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Phone</strong>: </td>
                                          <td><font color="#FF6600">'.$result['phone'].'</font></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Email ID</strong>: </td>
                                          <td><font color="#FF6600">'.$result['emailid'].'</font></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Reference [Type] :</strong></td>
                                          <td><font color="#FF6600">'.$result['refer'].' ['.$result['source'].']</font></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Date of lead:</strong></td>
                                          <td><font color="#FF6600">'.$result['leaddatetime'].'</font></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Product:</strong></td>
                                          <td><font color="#FF6600">'.$result['product'].'</font></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Dealer:</strong></td>
                                          <td><font color="#FF6600">'.$result['dealername'].'</font></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Manager:</strong></td>
                                          <td><font color="#FF6600">'.$result['managername'].'</font></td>
                                        </tr>
                                      </table>';
		$output = $leadid."|^|".$leaddetail;
		echo($output);
		break;

	case "save":
		$form_recid = $_POST['form_recid'];
		$form_dealer = $_POST['form_dealer'];
		$query = "update leads set dealerid = '".$form_dealer."' WHERE id = '".$form_recid."'";
		$result = runmysqlquery($query);
		$output = "Lead Updated Successfully.";
		echo($output);
		break;
		
		
}
?>