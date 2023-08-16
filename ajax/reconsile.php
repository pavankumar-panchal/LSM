<?
ini_set("memory_limit","-1");
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];
$reconsiletype = $_POST['reconsiletype'];

switch($submittype)
{
	case "getcount":
		{
			if($reconsiletype == "disableddealers")
			{
				$query123 = "select count(*) as leadcount from leads left join lms_users on lms_users.referenceid = leads.dealerid left join products on products.id = leads.productid where lms_users.disablelogin = 'yes' and lms_users.type = 'Dealer' order by leads.id";
			}
			elseif($reconsiletype == "unmappedleads")
			{
				$query123 = "select count(*) as leadcount FROM leads  left join products on products.id = leads.productid WHERE leads.dealerid = '999999'";
			}

			$fetch = runmysqlqueryfetch($query123);
			$leadcount = $fetch['leadcount'];
			if($leadcount == 0)
			{
				$message = "2^There are no leads to reconsile.";
				echo($message);
			}
			else
			{
				$quotient = $leadcount/100;
				$totallooprun = ($leadcount % 100 == 0)?($leadcount/100):(ceil($leadcount/100));
				echo('1^'.$leadcount.'^'.$totallooprun);
			}
		}
		break;
	

	case "reconsileleads" : 
		{
			if($reconsiletype == "disableddealers")
			{
				$query123 = "select leads.id,leads.regionid,leads.productid,leads.dealerid as fromdealer, products.category from leads left join lms_users on lms_users.referenceid = leads.dealerid left join products on products.id = leads.productid where lms_users.disablelogin = 'yes' and lms_users.type = 'Dealer' order by leads.id LIMIT 100 "; //echo($query); exit;
				$result = runmysqlquery($query123);
				while($fetch = mysqli_fetch_array($result))
				{
					$leadid = $fetch['id'];
					$regionid = $fetch['regionid'];
					$fromdealer = $fetch['fromdealer'];
					$category = $fetch['category'];
					$query2 = "SELECT mapping.dealerid FROM mapping left join lms_users on lms_users.referenceid = mapping.dealerid WHERE regionid = '".$regionid."' AND prdcategory = '".$category."' and lms_users.type = 'Dealer' AND lms_users.disablelogin <> 'yes';";
					$result2 = runmysqlquery($query2);
					$mappingcount = mysqli_num_rows($result2);
					if($mappingcount > 0)
					{
						$fetch2 = mysqli_fetch_array($result2);
						$dealerid = $fetch2['dealerid'];
						// Update the details.
						$query4 = "UPDATE leads SET dealerid = '".$dealerid."' WHERE id = '".$leadid."'";
						$result4 = runmysqlquery($query4);
						// Get username
						$cookie_username = lmsgetcookie('lmsusername');	
						
						// Fetch user id.
						$query8 = "select * from lms_users where username = '".$cookie_username."'";
						$fetch = runmysqlqueryfetch($query8);
						
						// Insert the transfered lead to lms_transferlogs.	
						$query5 = "insert into lms_transferlogs (leadid, fromdealer, todealer, transferdate, trasferredby) values('".$leadid."','".$fromdealer."','".$dealerid."','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."','".$fetch['id']."')";
						$result5 = runmysqlquery($query5); //echo($query5); exit;
						$reconsilecount++;
					}
					else
					{
						$dealerid = '999999';
						$query4 = "UPDATE leads SET dealerid = '".$dealerid."' WHERE id = '".$leadid."'";
						$result4 = runmysqlquery($query4);
					}
				}	
				echo('1^'.$reconsilecount);
			}
			elseif($reconsiletype == "unmappedleads")
			{
				$query = "select leads.id,leads.regionid,leads.productid,leads.dealerid as fromdealer, products.category FROM leads  left join products on products.id = leads.productid WHERE leads.dealerid = '999999' limit 100";
				$resultmain = runmysqlquery($query);
				$leadpresence = mysqli_num_rows($resultmain);
				if($leadpresence == 0)
					$message = "2^There are no leads to reconsile.";
				else
				{
					$reconsilecount = 0;
					while($fetch = mysqli_fetch_array($resultmain))
					{
						$leadid = $fetch['id'];
						$regionid = $fetch['regionid'];
						$fromdealer = $fetch['fromdealer'];
						$category = $fetch['category'];
						$query2 = "SELECT mapping.dealerid FROM mapping left join lms_users on lms_users.referenceid = mapping.dealerid WHERE regionid = '".$regionid."' AND prdcategory = '".$category."' and lms_users.type = 'Dealer' AND lms_users.disablelogin <> 'yes';";
						$result2 = runmysqlquery($query2);
						$mappingcount = mysqli_num_rows($result2);
						
						//If mapping exists for that region and product, pick respective dealer address
						if($mappingcount > 0)
						{
							$result = runmysqlqueryfetch($query2);
							$dealerid = $result['dealerid'];
							$query = "UPDATE leads SET dealerid = '".$dealerid."' WHERE id = '".$leadid."'";
							$result = runmysqlquery($query);
							
							// Get username
							$cookie_username = lmsgetcookie('lmsusername');	
						
							// Fetch user id.
							$query = "select * from lms_users where username = '".$cookie_username."'";
							$fetch = runmysqlqueryfetch($query);
						
							// Insert the transfered lead to lms_transferlogs.	
							$query2 = "insert into lms_transferlogs (leadid, fromdealer, todealer, transferdate, trasferredby) values('".$leadid."','".$fromdealer."','".$dealerid."','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."','".$fetch['id']."')";
							$result2 = runmysqlquery($query2);  
							$reconsilecount++;
						}
					}
					$message = '1^'.$reconsilecount;
				}
				echo($message);
			}
			break;
		}
}


?>