<?php

ini_set('memory_limit', '2048M');
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

//PHPExcel
require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
require_once '../phpgeneration/PHPExcel/IOFactory.php';

if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
{
	$cookie_username = lmsgetcookie('lmsusername');
	$cookie_usertype = lmsgetcookie('lmsusersort');
	
	$date = datetimelocal('YmdHis');
	
		$fromdate = changedateformat($_POST['fromdate']);
		$todate = changedateformat($_POST['todate']);
		$datetimepiece = "substring(leads.leaddatetime,1,10) between '".$fromdate."' AND  '".$todate."'"; 	
		$attachpiece = 'from '.$_POST['fromdate'].'  to '.$_POST['todate'].'';
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '' && checkdateformat($fromdate) && checkdateformat($todate) && ((datenumeric($todate) - datenumeric($fromdate)) >= 0))
		{
			//Check who is making the entry
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort');

			switch($cookie_usertype)
			{
				case "Admin":
				case "Sub Admin":
					$query = "select 'Web Downloads' AS dlrcompanyname, 'NA' AS username, webleads.advtleads AS advtleads, webleads.elmleads AS emlleads, webleads.whatsappcamp AS whatsappcamp, webleads.blkleads AS blkleads, webleads.ecustleads AS ecustleads, webleads.ecustcsleads AS ecustcsleads, webleads.ecustcleads AS ecustcleads, webleads.mlrleads AS mlrleads, webleads.nsdlleads AS nsdlleads, webleads.refleads AS refleads, webleads.repleads AS repleads, webleads.srchleads AS srchleads, webleads.clrleads AS clrleads, webleads.callleads AS callleads, webleads.otherleads AS otherleads, 'webmaster@relyonsoft.com' AS dlremail, '*Web Downloads' AS endmanager, 'NotAvailable' AS cellnumber from leads 
join(select count(refer = 'Advertisement' or NULL) as advtleads,count(refer = 'Email' OR NULL) as elmleads,count(refer = 'WhatsApp Campaigning' or NULL) as whatsappcamp,count(refer = 'Bulkmail' or NULL) as blkleads,count(refer = 'Existing Customer' OR NULL) as ecustleads,count(refer = 'Existing customer - Conversion' OR NULL) as ecustcleads,count(refer = 'Existing Customer – Cross sell' OR NULL) as ecustcsleads,count(refer = 'Mailer - Letter' OR NULL) as mlrleads,count(refer = 'NSDL/Income Tax website' OR NULL) as nsdlleads,count(refer = 'Reference from customer' OR NULL) as refleads,count(refer = 'Relyon Representative' OR NULL) as repleads,count(refer = 'Web Search/Search Engine' OR NULL) as srchleads,count(refer = 'Incoming Email with clear reqt'OR NULL) as clrleads,count(refer = 'Incoming Call' OR NULL) as callleads,count(refer = 'Others' OR NULL) as otherleads from leads where source = 'Product Download' and ".$datetimepiece.")as webleads limit 1

union all(select dealers.dlrcompanyname AS dlrcompanyname, lms_users.username AS username,NULLIF(dlrleads.advtleads,0) as advtleads,NULLIF(dlrleads.emlleads,0) as emlleads,NULLIF(dlrleads.whatsappcamp,0) as whatsappcamp,NULLIF(dlrleads.ecustcsleads,0) as ecustcsleads,NULLIF(dlrleads.ecustcleads,0) as ecustcleads,NULLIF(dlrleads.bulkleads,0) as blkleads,NULLIF(dlrleads.extcusleads,0) as ecusleads,NULLIF(dlrleads.mlrleads,0) as mlrleads,NULLIF(dlrleads.nsdlleads,0) as nsdlleads,NULLIF(dlrleads.refcustleads,0) as refleads,NULLIF(dlrleads.relrepleads,0) as repleads,NULLIF(dlrleads.srchleads,0) as srchleads,NULLIF(dlrleads.clrleads,0) as clrleads,NULLIF(dlrleads.icalleads,0) as callleads,NULLIF(dlrleads.otherleads,0) as otherleads ,dealers.dlremail AS dlremail, lms_managers.mgrname AS endmanager, dealers.dlrcell AS cellnumber from dealers 
left join lms_managers on lms_managers.id = dealers.managerid 
join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' 
left join(select leaduploadedby,count(refer = 'Advertisement' OR NULL) as advtleads,count(refer = 'Email' OR NULL) as emlleads,count(refer = 'WhatsApp Campaigning' OR NULL) as whatsappcamp,count(refer = 'Existing customer - Conversion' OR NULL) as ecustcleads,count(refer = 'Existing Customer – Cross sell' OR NULL) as ecustcsleads,count(refer = 'Bulkmail' OR NULL) as bulkleads,count(refer = 'Existing Customer' OR NULL) as extcusleads,count(refer = 'Mailer - Letter' OR NULL) as mlrleads,count(refer = 'NSDL/Income Tax website' OR NULL) as nsdlleads,count(refer = 'Reference from customer' OR NULL) as refcustleads,count(refer = 'Relyon Representative' OR NULL) as relrepleads,count(refer = 'Web Search/Search Engine' OR NULL) as srchleads,count(refer = 'Incoming Email with clear reqt' OR NULL) as clrleads,count(refer = 'Incoming Call' OR NULL) as icalleads,count(refer = 'Others' OR NULL) as otherleads from leads where ".$datetimepiece." group by leaduploadedby) as dlrleads on lms_users.id = dlrleads.leaduploadedby group by dealers.dlrcompanyname order by dealers.id)

union all(select lms_managers.mgrname AS dlrcompanyname, lms_users.username AS username, NULLIF(mgrleads.advtleads,0) as advtleads,NULLIF(mgrleads.emlleads,0) as emlleads,NULLIF(mgrleads.whatsappcamp,0) as whatsappcamp,NULLIF(dlrleads.ecustcsleads,0) as ecustcsleads,NULLIF(dlrleads.ecustcleads,0) as ecustcleads,NULLIF(mgrleads.bulkleads,0) as blkleads,NULLIF(mgrleads.extcusleads,0) as ecusleads,NULLIF(mgrleads.mlrleads,0) as mlrleads,NULLIF(mgrleads.nsdlleads,0) as nsdlleads,NULLIF(mgrleads.refcustleads,0) as refleads,NULLIF(mgrleads.relrepleads,0) as repleads,NULLIF(mgrleads.srchleads,0) as srchleads,NULLIF(mgrleads.clrleads,0) as clrleads,NULLIF(mgrleads.icalleads,0) as callleads,NULLIF(mgrleads.otherleads,0) as otherleads , lms_managers.mgremailid AS dlremail, lms_managers.mgrname AS endmanager, lms_managers.mgrcell AS cellnumber from lms_managers 
join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' 
left join (select leaduploadedby,count(refer = 'Advertisement' OR NULL) as advtleads,count(refer = 'Email' OR NULL) as emlleads,count(refer = 'WhatsApp Campaigning' OR NULL) as whatsappcamp,count(refer = 'Existing customer - Conversion' OR NULL) as ecustcleads,count(refer = 'Existing Customer – Cross sell' OR NULL) as ecustcsleads,count(refer = 'Bulkmail' OR NULL) as bulkleads,count(refer = 'Existing Customer' OR NULL) as extcusleads,count(refer = 'Mailer - Letter' OR NULL) as mlrleads,count(refer = 'NSDL/Income Tax website' OR NULL) as nsdlleads,count(refer = 'Reference from customer' OR NULL) as refcustleads,count(refer = 'Relyon Representative' OR NULL) as relrepleads,count(refer = 'Web Search/Search Engine' OR NULL) as srchleads,count(refer = 'Incoming Email with clear reqt' OR NULL) as clrleads,count(refer = 'Incoming Call' OR NULL) as icalleads,count(refer = 'Others' OR NULL) as otherleads from leads where ".$datetimepiece." group by leaduploadedby) as mgrleads on lms_users.id = mgrleads.leaduploadedby group by lms_managers.mgrname order by lms_managers.id)

union all(select lms_subadmins.sadname AS dlrcompanyname, lms_users.username AS username, NULLIF(saleads.advtleads,0) as advtleads,NULLIF(saleads.emlleads,0) as emlleads,NULLIF(saleads.whatsappcamp,0) as whatsappcamp,NULLIF(dlrleads.ecustcsleads,0) as ecustcsleads,NULLIF(dlrleads.ecustcleads,0) as ecustcleads,NULLIF(saleads.bulkleads,0) as blkleads,NULLIF(saleads.extcusleads,0) as ecusleads,NULLIF(saleads.mlrleads,0) as mlrleads,NULLIF(saleads.nsdlleads,0) as nsdlleads,NULLIF(saleads.refcustleads,0) as refleads,NULLIF(saleads.relrepleads,0) as repleads,NULLIF(saleads.srchleads,0) as srchleads,NULLIF(saleads.clrleads,0) as clrleads,NULLIF(saleads.icalleads,0) as callleads,NULLIF(saleads.otherleads,0) as otherleads , lms_subadmins.sademailid AS dlremail, '*Sub Admin' AS endmanager, 'NotAvailable' AS cellnumber from lms_subadmins 
join lms_users on lms_users.referenceid = lms_subadmins.id AND lms_users.type = 'Sub Admin' 
left join (select leaduploadedby,count(refer = 'Advertisement' OR NULL) as advtleads,count(refer = 'Email' OR NULL) as emlleads,count(refer = 'WhatsApp Campaigning' or NULL) as whatsappcamp,count(refer = 'Existing customer - Conversion' OR NULL) as ecustcleads,count(refer = 'Existing Customer – Cross sell' OR NULL) as ecustcsleads,count(refer = 'Bulkmail' OR NULL) as bulkleads,count(refer = 'Existing Customer' OR NULL) as extcusleads,count(refer = 'Mailer - Letter' OR NULL) as mlrleads,count(refer = 'NSDL/Income Tax website' OR NULL) as nsdlleads,count(refer = 'Reference from customer' OR NULL) as refcustleads,count(refer = 'Relyon Representative' OR NULL) as relrepleads,count(refer = 'Web Search/Search Engine' OR NULL) as srchleads,count(refer = 'Incoming Email with clear reqt' OR NULL) as clrleads,count(refer = 'Incoming Call' OR NULL) as icalleads,count(refer = 'Others' OR NULL) as otherleads from leads where ".$datetimepiece." group by leaduploadedby) as saleads on  lms_users.id = saleads.leaduploadedby group by lms_subadmins.sadname order by lms_subadmins.id)";
					break;


				case "Reporting Authority":
				
				//Check wheteher the manager is branch head or not
				$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
				$result1 = runmysqlqueryfetch($query1);
				if($result1['branchhead'] == 'yes')
				{
					$branchpiecejoin = "AND (dealers.branch = '".$result1['branch']."' OR dealers.managerid  = '".$result1['managerid']."')";
					$branchpiecejoin1 = "AND (dealers.branch = '".$result1['branch']."' OR dealers.managerid = '".$result1['managerid']."')";
					if($cookie_username == "srinivasan")
					{
						$managercheckpiece = "AND (lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj')";
						$managercheckpiece1 = "AND (lms_users2.username = '".$cookie_username."' or  lms_users2.username = 'nagaraj')";
					}
					else
					{
						$managercheckpiece = "";
						$managercheckpiece1 = "";
					}
					
				}
				else
				{
					$branchpiecejoin = "";
					$branchpiecejoin1 = "";
					if($cookie_username == "srinivasan")
					{
						$managercheckpiece = " AND (lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj')";
						$managercheckpiece1 = " AND (lms_users2.username = '".$cookie_username."' or  lms_users2.username = 'nagaraj')";
					}
					else
					{
						$managercheckpiece = " AND (lms_users.username = '".$cookie_username."')";
						$managercheckpiece1 = " AND (lms_users2.username = '".$cookie_username."')";
					}
				}

				$query = "(select 'Web Downloads' AS dlrcompanyname, 'NA' AS username, webleads.advtleads AS advtleads, webleads.elmleads AS emlleads, webleads.whatsappcamp AS whatsappcamp, webleads.blkleads AS blkleads, webleads.ecustleads AS ecustleads, webleads.ecustcsleads AS ecustcsleads, webleads.ecustcleads AS ecustcleads, webleads.mlrleads AS mlrleads, webleads.nsdlleads AS nsdlleads, webleads.refleads AS refleads, webleads.repleads AS repleads, webleads.srchleads AS srchleads, webleads.clrleads AS clrleads, webleads.callleads AS callleads, webleads.otherleads AS otherleads, 'webmaster@relyonsoft.com' AS dlremail, '*Web Downloads' AS endmanager, 'NotAvailable' AS cellnumber from leads 
join(select count(refer = 'Advertisement' or NULL) as advtleads,count(refer = 'Email' OR NULL) as elmleads,count(refer = 'WhatsApp Campaigning' or NULL) as whatsappcamp,count(refer = 'Existing customer - Conversion' OR NULL) as ecustcleads,count(refer = 'Existing Customer – Cross sell' OR NULL) as ecustcsleads,count(refer = 'Bulkmail' or NULL) as blkleads,count(refer = 'Existing Customer' OR NULL) as ecustleads,count(refer = 'Mailer - Letter' OR NULL) as mlrleads,count(refer = 'NSDL/Income Tax website' OR NULL) as nsdlleads,count(refer = 'Reference from customer' OR NULL) as refleads,count(refer = 'Relyon Representative' OR NULL) as repleads,count(refer = 'Web Search/Search Engine' OR NULL) as srchleads,count(refer = 'Incoming Email with clear reqt'OR NULL) as clrleads,count(refer = 'Incoming Call' OR NULL) as callleads,count(refer = 'Others' OR NULL) as otherleads from leads join dealers on dealers.id = leads.dealerid join lms_users on dealers.managerid = lms_users.referenceid and lms_users.type = 'Reporting Authority'  where dealers.id <> '9999999999999'
".$managercheckpiece ." and ".$datetimepiece." ".$branchpiecejoin."  AND source = 'Product Download') as webleads  limit 1)

union all(select dealers.dlrcompanyname AS dlrcompanyname, lms_users.username AS username, NULLIF(dlrleads.advtleads,0) as advtleads,NULLIF(dlrleads.emlleads,0) as emlleads,NULLIF(dlrleads.whatsappcamp,0) as whatsappcamp,count(refer = 'Existing customer - Conversion' OR NULL) as ecustcleads,count(refer = 'Existing Customer – Cross sell' OR NULL) as ecustcsleads,NULLIF(dlrleads.bulkleads,0) as blkleads,NULLIF(dlrleads.extcusleads,0) as ecusleads,NULLIF(dlrleads.mlrleads,0) as mlrleads,NULLIF(dlrleads.nsdlleads,0) as nsdlleads,NULLIF(dlrleads.refcustleads,0) as refleads,NULLIF(dlrleads.relrepleads,0) as repleads,NULLIF(dlrleads.srchleads,0) as srchleads,NULLIF(dlrleads.clrleads,0) as clrleads,NULLIF(dlrleads.icalleads,0) as callleads,NULLIF(dlrleads.otherleads,0) as otherleads, dealers.dlremail AS dlremail, lms_managers.mgrname AS endmanager, dealers.dlrcell AS cellnumber from dealers 
left join lms_managers on lms_managers.id = dealers.managerid 
join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' 
left join(select leaduploadedby,count(refer = 'Advertisement' OR NULL) as advtleads,count(refer = 'Email' OR NULL) as emlleads,count(refer = 'WhatsApp Campaigning' or NULL) as whatsappcamp,count(refer = 'Existing customer - Conversion' OR NULL) as ecustcleads,count(refer = 'Existing Customer – Cross sell' OR NULL) as ecustcsleads,count(refer = 'Bulkmail' OR NULL) as bulkleads,count(refer = 'Bulkmail' OR NULL) as bulkleads,count(refer = 'Existing Customer' OR NULL) as extcusleads,count(refer = 'Mailer - Letter' OR NULL) as mlrleads,count(refer = 'NSDL/Income Tax website' OR NULL) as nsdlleads,count(refer = 'Reference from customer' OR NULL) as refcustleads,count(refer = 'Relyon Representative' OR NULL) as relrepleads,count(refer = 'Web Search/Search Engine' OR NULL) as srchleads,count(refer = 'Incoming Email with clear reqt' OR NULL) as clrleads,count(refer = 'Incoming Call' OR NULL) as icalleads,count(refer = 'Others' OR NULL) as otherleads from leads  where ".$datetimepiece."  group by leaduploadedby ) as dlrleads on lms_users.id = dlrleads.leaduploadedby  left join lms_users as lms_users2 on lms_users2.referenceid = dealers.managerid  and lms_users2.type = 'Reporting Authority' where dealers.id <> '9999999999999'  ".$managercheckpiece1." ".$branchpiecejoin1." group by dealers.dlrcompanyname order by dealers.id)

union all(select lms_managers.mgrname AS dlrcompanyname, lms_users.username AS username, NULLIF(mgrleads.advtleads,0) as advtleads,NULLIF(mgrleads.emlleads,0) as emlleads,NULLIF(mgrleads.whatsappcamp,0) as whatsappcamp,count(refer = 'Existing customer - Conversion' OR NULL) as ecustcleads,count(refer = 'Existing Customer – Cross sell' OR NULL) as ecustcsleads,NULLIF(mgrleads.bulkleads,0) as blkleads,NULLIF(mgrleads.extcusleads,0) as ecusleads,NULLIF(mgrleads.mlrleads,0) as mlrleads,NULLIF(mgrleads.nsdlleads,0) as nsdlleads,NULLIF(mgrleads.refcustleads,0) as refleads,NULLIF(mgrleads.relrepleads,0) as repleads,NULLIF(mgrleads.srchleads,0) as srchleads,NULLIF(mgrleads.clrleads,0) as clrleads,NULLIF(mgrleads.icalleads,0) as callleads,NULLIF(mgrleads.otherleads,0) as otherleads, lms_managers.mgremailid AS dlremail, lms_managers.mgrname AS endmanager, lms_managers.mgrcell AS cellnumber from lms_managers 
join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' 
left join (select leaduploadedby,count(refer = 'Advertisement' OR NULL) as advtleads,count(refer = 'Email' OR NULL) as emlleads,count(refer = 'WhatsApp Campaigning' or NULL) as whatsappcamp,count(refer = 'Existing customer - Conversion' OR NULL) as ecustcleads,count(refer = 'Existing Customer – Cross sell' OR NULL) as ecustcsleads,count(refer = 'Bulkmail' OR NULL) as bulkleads,count(refer = 'Existing Customer' OR NULL) as extcusleads,count(refer = 'Mailer - Letter' OR NULL) as mlrleads,count(refer = 'NSDL/Income Tax website' OR NULL) as nsdlleads,count(refer = 'Reference from customer' OR NULL) as refcustleads,count(refer = 'Relyon Representative' OR NULL) as relrepleads,count(refer = 'Web Search/Search Engine' OR NULL) as srchleads,count(refer = 'Incoming Email with clear reqt' OR NULL) as clrleads,count(refer = 'Incoming Call' OR NULL) as icalleads,count(refer = 'Others' OR NULL) as otherleads from leads where ".$datetimepiece." group by leaduploadedby) as mgrleads on lms_users.id = mgrleads.leaduploadedby where lms_users.username = '".$cookie_username."'  group by lms_managers.mgrname order by lms_managers.id);";
					break;


				case "Dealer":
					$query = "(select 'Web Downloads' AS dlrcompanyname, 'NA' AS username, webleads.advtleads AS advtleads, webleads.elmleads AS emlleads, webleads.whatsappcamp AS whatsappcamp, webleads.blkleads AS blkleads, webleads.ecustleads AS ecustleads, webleads.ecustcsleads AS ecustcsleads, webleads.ecustcleads AS ecustcleads, webleads.mlrleads AS mlrleads, webleads.nsdlleads AS nsdlleads, webleads.refleads AS refleads, webleads.repleads AS repleads, webleads.srchleads AS srchleads, webleads.clrleads AS clrleads, webleads.callleads AS callleads, webleads.otherleads AS otherleads, 'webmaster@relyonsoft.com' AS dlremail, '*Web Downloads' AS endmanager, 'NotAvailable' AS cellnumber from leads 
join(select count(refer = 'Advertisement' or NULL) as advtleads,count(refer = 'Email' OR NULL) as elmleads,count(refer = 'WhatsApp Campaigning' or NULL) as whatsappcamp,count(refer = 'Existing customer - Conversion' OR NULL) as ecustcleads,count(refer = 'Existing Customer – Cross sell' OR NULL) as ecustcsleads,count(refer = 'Bulkmail' or NULL) as blkleads,count(refer = 'Existing Customer' OR NULL) as ecustleads,count(refer = 'Mailer - Letter' OR NULL) as mlrleads,count(refer = 'NSDL/Income Tax website' OR NULL) as nsdlleads,count(refer = 'Reference from customer' OR NULL) as refleads,count(refer = 'Relyon Representative' OR NULL) as repleads,count(refer = 'Web Search/Search Engine' OR NULL) as srchleads,count(refer = 'Incoming Email with clear reqt'OR NULL) as clrleads,count(refer = 'Incoming Call' OR NULL) as callleads,count(refer = 'Others' OR NULL) as otherleads from leads join lms_users on leads.dealerid = lms_users.referenceid  AND lms_users.type = 'Dealer' where lms_users.username = '".$cookie_username."' AND ".$datetimepiece." AND source = 'Product Download') as webleads limit 1)

union all(select dealers.dlrcompanyname AS dlrcompanyname, lms_users.username AS username, NULLIF(dlrleads.advtleads,0) as advtleads,NULLIF(dlrleads.emlleads,0) as emlleads,NULLIF(dlrleads.whatsappcamp,0) as whatsappcamp,count(refer = 'Existing customer - Conversion' OR NULL) as ecustcleads,count(refer = 'Existing Customer – Cross sell' OR NULL) as ecustcsleads,NULLIF(dlrleads.bulkleads,0) as blkleads,NULLIF(dlrleads.extcusleads,0) as ecusleads,NULLIF(dlrleads.mlrleads,0) as mlrleads,NULLIF(dlrleads.nsdlleads,0) as nsdlleads,NULLIF(dlrleads.refcustleads,0) as refleads,NULLIF(dlrleads.relrepleads,0) as repleads,NULLIF(dlrleads.srchleads,0) as srchleads,NULLIF(dlrleads.clrleads,0) as clrleads,NULLIF(dlrleads.icalleads,0) as callleads,NULLIF(dlrleads.otherleads,0) as otherleads, dealers.dlremail AS dlremail, lms_managers.mgrname AS endmanager, dealers.dlrcell AS cellnumber from dealers 
left join lms_managers on lms_managers.id = dealers.managerid 
join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer'
left join (select leaduploadedby,count(refer = 'Advertisement' OR NULL) as advtleads,count(refer = 'Email' OR NULL) as emlleads,count(refer = 'WhatsApp Campaigning' or NULL) as whatsappcamp,count(refer = 'Existing customer - Conversion' OR NULL) as ecustcleads,count(refer = 'Existing Customer – Cross sell' OR NULL) as ecustcsleads,count(refer = 'Bulkmail' OR NULL) as bulkleads,count(refer = 'Existing Customer' OR NULL) as extcusleads,count(refer = 'Mailer - Letter' OR NULL) as mlrleads,count(refer = 'NSDL/Income Tax website' OR NULL) as nsdlleads,count(refer = 'Reference from customer' OR NULL) as refcustleads,count(refer = 'Relyon Representative' OR NULL) as relrepleads,count(refer = 'Web Search/Search Engine' OR NULL) as srchleads,count(refer = 'Incoming Email with clear reqt' OR NULL) as clrleads,count(refer = 'Incoming Call' OR NULL) as icalleads,count(refer = 'Others' OR NULL) as otherleads from leads where ".$datetimepiece." group by leaduploadedby) as dlrleads on lms_users.id = dlrleads.leaduploadedby where lms_users.username = '".$cookie_username."' group by dealers.dlrcompanyname order by dealers.id)";
					break;
			} //echo($query); exit;
			$result = runmysqlquery($query);
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();
	
			//Set Active Sheet	
			$mySheet = $objPHPExcel->getActiveSheet();
			$styleArray = array(
					'font' => array('bold' => true),
					'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '0099CCFF')),
					'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
				); // blue - 0099CCFF
				
			// Apply style	
			$mySheet->getStyle('A3:U3')->applyFromArray($styleArray);		
			
			// Merge cells
			
			$mySheet->mergeCells('A1:U1');
			$mySheet->mergeCells('A2:U2');
			/*// To align the text to center.
			$mySheet->getStyle('A1:R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$mySheet->getStyle('A2:R2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
						->setCellValue('A2', 'Leads Source Statistics  '.' '.$attachpiece);
			$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
			$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
			$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
			//File contents for Header Row
			
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3', 'Sl No')
					->setCellValue('B3', 'Name')
					->setCellValue('C3', 'Login')
					->setCellValue('D3', 'Advertisement')
					->setCellValue('E3', 'Email')
					->setCellValue('F3', 'WhatsApp Campaigning')
					->setCellValue('G3', 'Bulk Mail')
					->setCellValue('H3', 'Existing Customer')
					->setCellValue('I3', 'Mailer Letter')
					->setCellValue('J3', 'NSDL/Income Tax Web')
					->setCellValue('K3', 'Ref from existing Customer')
					->setCellValue('L3', 'Relyon Representive')
					->setCellValue('M3', 'Search Engine')
					->setCellValue('N3', 'Incoming email with clear requirement')
					->setCellValue('O3', 'Incoming Call')
					->setCellValue('P3', 'Others')
					->setCellValue('Q3', 'Email Id')
					->setCellValue('R3', 'Cell Number')
					->setCellValue('S3', 'Manager Name')
					->setCellValue('T3', 'Existing customer - Conversion')
					->setCellValue('U3', 'Existing customer – Cross sell');
			$j = 4;
			$slno = 0;
			while($fetch = mysqli_fetch_array($result))
			{
				//set_time_limit(20);
				$slno++;
				$mySheet->setCellValue('A'.$j,$slno)
					->setCellValue('B'.$j,$fetch['dlrcompanyname'])
					->setCellValue('C'.$j,$fetch['username'])
					->setCellValue('D'.$j,$fetch['advtleads'])
					->setCellValue('E'.$j,$fetch['emlleads'])
					->setCellValue('F'.$j,$fetch['whatsappcamp'])
					->setCellValue('G'.$j,$fetch['blkleads'])
					->setCellValue('H'.$j,$fetch['ecustleads'])
					->setCellValue('I'.$j,$fetch['mlrleads'])
					->setCellValue('J'.$j,$fetch['nsdlleads'])
					->setCellValue('K'.$j,$fetch['refleads'])
					->setCellValue('L'.$j,$fetch['repleads'])
					->setCellValue('M'.$j,$fetch['srchleads'])
					->setCellValue('N'.$j,$fetch['clrleads'])
					->setCellValue('O'.$j,$fetch['callleads'])
					->setCellValue('P'.$j,$fetch['otherleads'])
					->setCellValue('Q'.$j,$fetch['dlremail'])
					->setCellValue('R'.$j,$fetch['cellnumber'])
					->setCellValue('S'.$j,$fetch['endmanager'])
					->setCellValue('T'.$j,$fetch['ecustcleads'])
					->setCellValue('U'.$j,$fetch['ecustcsleads']);
					$j++;
			}
			//Define Style for content area
			$styleArrayContent = array(
								'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
							);
			
			//Get the last cell reference
			$highestRow = $mySheet->getHighestRow(); 
			$highestColumn = $mySheet->getHighestColumn(); 
			$myLastCell = $highestColumn.$highestRow;
			
			//Deine the content range
			$myDataRange = 'A4:'.$myLastCell;
			
			
			if(mysqli_num_rows($result) <> 0)
			{
			//Apply style to content area range
				$mySheet->getStyle($myDataRange)->applyFromArray($styleArrayContent);
			}
			
				//set the default width for column
			$mySheet->getColumnDimension('A')->setWidth(6);
			$mySheet->getColumnDimension('B')->setWidth(35);
			$mySheet->getColumnDimension('C')->setWidth(35);
			$mySheet->getColumnDimension('D')->setWidth(15);
			$mySheet->getColumnDimension('E')->setWidth(15);
			$mySheet->getColumnDimension('F')->setWidth(20);
			$mySheet->getColumnDimension('G')->setWidth(15);
			$mySheet->getColumnDimension('H')->setWidth(18);
			$mySheet->getColumnDimension('I')->setWidth(15);
			$mySheet->getColumnDimension('J')->setWidth(25);
			$mySheet->getColumnDimension('K')->setWidth(25);
			$mySheet->getColumnDimension('L')->setWidth(22);
			$mySheet->getColumnDimension('M')->setWidth(15);
			$mySheet->getColumnDimension('N')->setWidth(34);
			$mySheet->getColumnDimension('O')->setWidth(15);
			$mySheet->getColumnDimension('P')->setWidth(15);
			$mySheet->getColumnDimension('Q')->setWidth(35);
			$mySheet->getColumnDimension('R')->setWidth(20);
			$mySheet->getColumnDimension('S')->setWidth(30);
			$mySheet->getColumnDimension('T')->setWidth(20);
			$mySheet->getColumnDimension('U')->setWidth(20);
			
			
			// Insert logs on Lead Source stats to excel
			$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','39','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
			$result = runmysqlquery_log($query);
			
			$filebasename = "LMS-LEADSOURCE-".$cookie_username."-".$date.".xls";
			if($_SERVER['HTTP_HOST'] == 'localhost')  
			{
				$filepath = $_SERVER['DOCUMENT_ROOT'].'/lms/filescreated/'.$filebasename;
				$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].'/lms/filescreated/'.$filebasename;
			}
			else
			{
				$filepath = $_SERVER['DOCUMENT_ROOT'].'/filescreated/'.$filebasename;
				$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].'/filescreated/'.$filebasename;
			}
			
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save($filepath);					
	
			$fp = fopen($filebasename,"wa+");
			if($fp)
			{
				downloadfile($filepath);
				fclose($fp);
			} 
			unlink($filebasename);
			exit; 
		
			
		}
}
?>
