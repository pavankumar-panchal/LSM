<?
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];

switch($submittype)
{
	case "dealerdatachart":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');
			
			$datachartperiod = $_POST['datachartperiod'];
			
			switch($datachartperiod)
			{
				case "today":
					$today = datetimelocal("Y-m-d");
					$datepiece = " leads.leaddatetime = '".$today."'  AND ";
					break;
				case "yesterday":
					$yesterday = date("Y-m-d",time()-86400);
					$datepiece = " leads.leaddatetime = '".$yesterday."'  AND ";
					break;
				case "thismonth":
					$yearnmonth = datetimelocal("Y-m");
					$datepiece = " LEFT(leads.leaddatetime,7) = '".$yearnmonth."'  AND ";
					break;
				case "lastmonth":
					$yearnmonth = date("Y-m", mktime(0, 0, 0, date("m", time())-1, date("d", time()),  date("Y", time())));
					$datepiece = " LEFT(leads.leaddatetime,7) = '".$yearnmonth."'   AND ";
					break;
				case "alltime":
					$datepiece = " ";
					break;
			}
//echo((date('m') == 1)?join('-', array(date('Y')-1, '12')):join('-', array(date('Y'), date('m')-1)));			
//echo(date("Y-m-d",time()-86400));			
//echo(date("Y-m-d", mktime(0, 0, 0, date("m", time())-1, date("d", time()),  date("Y", time()))));			
			$query = "SELECT dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' WHERE lms_users.username = '".$cookie_username."'";
			$fetch = runmysqlqueryfetch($query);

			$query3 = "(SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.dealerviewdate = '0000-00-00 00:00:00' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'SPP')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'UnAttended' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'SPP')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Fake Enquiry' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'SPP')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Not Interested' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'SPP')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Registered User' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'SPP')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Attended' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'SPP')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Demo Given' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'SPP')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Quote Sent' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'SPP')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Perusing to Purchase' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'SPP')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Order Closed' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'SPP')";
			
			//STO Category
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.dealerviewdate = '0000-00-00 00:00:00' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'STO')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'UnAttended' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'STO')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Fake Enquiry' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'STO')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Not Interested' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'STO')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Registered User' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'STO')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Attended' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'STO')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Demo Given' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'STO')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Quote Sent' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'STO')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Perusing to Purchase' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'STO')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Order Closed' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'STO')";

			//OTHERS Category
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.dealerviewdate = '0000-00-00 00:00:00' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'OTHERS')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'UnAttended' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'OTHERS')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Fake Enquiry' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'OTHERS')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Not Interested' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'OTHERS')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Registered User' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'OTHERS')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Attended' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'OTHERS')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Demo Given' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'OTHERS')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Quote Sent' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'OTHERS')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Perusing to Purchase' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'OTHERS')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id where ".$datepiece." leads.leadstatus = 'Order Closed' AND leads.dealerid = '".$fetch['id']."' AND products.category = 'OTHERS')";
			$result3 = runmysqlquery($query3);
			$output = "done";
			while($fetch3 = mysqli_fetch_array($result3))
			{
				$output .= "|^|".$fetch3['totalrecords'];
			}
			echo($output);
		}
		break;
}
?>