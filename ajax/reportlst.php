<?php

include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];

switch($submittype)
{
	case "getdata":
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
		{
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');

			$fromdate = changedateformat($_POST['fromdate']);
			$todate = changedateformat($_POST['todate']);
			$dealerid = $_POST['dealerid'];
			$productid = $_POST['productid'];

			$valuetype = $dealerid[0];
			$restvalue = substr($dealerid, 1);
	

			$productpiece = ($productid == '')?"":(" AND leads.productid = '".$productid."' ");
			$datetimepiece = "substring(leads.leaddatetime,1,10) between '".$fromdate."' AND  '".$todate."'";
			switch($cookie_usertype)
			{
				case "Admin":
				case "Sub Admin":
					if($valuetype == 'm')
					{
						$dealerpiece = " AND dealers.managerid = '".$restvalue."' ";
						$query3 = "select * from lms_users where referenceid = '".$restvalue."' AND type = 'Reporting Authority'";
						$result3 = runmysqlqueryfetch($query3);
						$uploadedbypiece = " AND leads.leaduploadedby = '".$result3['id']."' ";
					}
					else
					{
						if($dealerid == '')
						{
							$dealerpiece = " ";
							$uploadedbypiece = " AND leads.leaduploadedby = 'should be zero' ";
						}
						else
						{
							$dealerpiece = " AND dealers.id = '".$restvalue."' ";
							$query3 = "select * from lms_users where referenceid = '".$restvalue."' AND type = 'Dealer'";
							$result3 = runmysqlqueryfetch($query3);
							$uploadedbypiece = " AND leads.leaduploadedby = '".$result3['id']."' ";
						}
					}
					break;
				case "Reporting Authority":
					$query3 = "select * from lms_users where username = '".$cookie_username."'";
					$result3 = runmysqlqueryfetch($query3);
					$query4 = "SELECT id FROM lms_managers WHERE id = '".$result3['referenceid']."'";
					$result4 = runmysqlqueryfetch($query4);
					$thismanagerid = $result4['id'];
					if($dealerid == '')
					{
						$dealerpiece = " AND dealers.managerid = '".$thismanagerid."' ";
						$query3 = "select * from lms_users where referenceid = '".$thismanagerid."' AND type = 'Reporting Authority'";
						$result3 = runmysqlqueryfetch($query3);
						$uploadedbypiece = " AND leads.leaduploadedby = '".$result3['id']."' ";
					}
					else
					{
						$dealerpiece = " AND dealers.id = '".$restvalue."' ";
						$query3 = "select * from lms_users where referenceid = '".$restvalue."' AND type = 'Dealer'";
						$result3 = runmysqlqueryfetch($query3);
						$uploadedbypiece = " AND leads.leaduploadedby = '".$result3['id']."' ";
					}
					break;
				case "Dealer":
					$query3 = "select * from lms_users where username = '".$cookie_username."'";
					$result3 = runmysqlqueryfetch($query3);
					$query4 = "SELECT id FROM dealers WHERE id = '".$result3['referenceid']."'";
					$result4 = runmysqlqueryfetch($query4);
					$thisdealerid = $result4['id'];
					$dealerpiece = ($dealerid == '')?" AND dealers.id = '".$dealerpiece."' ":(" AND dealers.id = '".$restvalue."' ");
					$uploadedbypiece = " AND leads.leaduploadedby = '".$result3['id']."' ";
					break;
			}



			$query3 = "(SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id join dealers on leads.dealerid = dealers.id  where ".$datetimepiece." ".$productpiece."  AND leads.source = 'Product Download' ".$dealerpiece." AND products.category = 'SPP')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id join dealers on leads.dealerid = dealers.id  where ".$datetimepiece." ".$productpiece."  AND leads.source = 'Manual Upload' ".$uploadedbypiece." AND products.category = 'SPP')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id join dealers on leads.dealerid = dealers.id  ".$datetimepiece." ".$productpiece."  AND leads.source = 'Manual Upload' ".$uploadedbypiece." AND products.category = 'SPP')";
			
			//STO Category
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id join dealers on leads.dealerid = dealers.id  where ".$datetimepiece." ".$productpiece."  AND leads.source = 'Product Download' ".$dealerpiece." AND products.category = 'STO')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id join dealers on leads.dealerid = dealers.id  where leads.dateoflead >= '".$fromdate."' AND leads.dateoflead <= '".$todate."' ".$productpiece."  AND leads.source = 'Manual Upload' ".$uploadedbypiece." AND products.category = 'STO')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id join dealers on leads.dealerid = dealers.id  where leads.dateoflead >= '".$fromdate."' AND leads.dateoflead <= '".$todate."' ".$productpiece."  AND leads.source = 'Manual Upload' ".$uploadedbypiece." AND products.category = 'STO')";

			//OTHERS Category
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id join dealers on leads.dealerid = dealers.id  where ".$datetimepiece." ".$productpiece."  AND leads.source = 'Product Download' ".$dealerpiece." AND products.category = 'OTHERS')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id join dealers on leads.dealerid = dealers.id  where ".$datetimepiece." ".$productpiece."  AND leads.source = 'Manual Upload' ".$uploadedbypiece." AND products.category = 'OTHERS')";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id join dealers on leads.dealerid = dealers.id  where ".$datetimepiece." ".$productpiece."  AND leads.source = 'Manual Upload' ".$uploadedbypiece." AND products.category = 'OTHERS')";

			//TOTAL
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id join dealers on leads.dealerid = dealers.id  where ".$datetimepiece." ".$productpiece."  AND leads.source = 'Product Download' ".$dealerpiece.")";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id join dealers on leads.dealerid = dealers.id  where ".$datetimepiece." ".$productpiece."  AND leads.source = 'Manual Upload' ".$uploadedbypiece.")";
			$query3 .= " union ALL (SELECT COUNT(*) AS totalrecords from leads join products on leads.productid = products.id join dealers on leads.dealerid = dealers.id  where ".$datetimepiece." ".$productpiece."  AND leads.source = 'Manual Upload' ".$uploadedbypiece.")";

//			echo($query3);
			$result3 = runmysqlquery($query3);
			$output = "";
			while($fetch3 = mysqli_fetch_array($result3))
			{
				$output .= "|^|".$fetch3['totalrecords'];
			}
			echo('1|^|'.$output);
		}
		break;
}
?>