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
						$query = "(select 'Web Downloads' AS dlrcompanyname, 'NA' AS username, webleads.sppleads AS sppleads, webleads.tdsleads AS tdsleads, webleads.sitleads AS sitleads, webleads.stoleads AS stoleads, webleads.sacleads AS sacleads, webleads.otherleads AS otherleads, 'webmaster@relyonsoft.com' AS dlremail, '*Web Downloads' AS endmanager, 'NotAvailable' AS cellnumber from leads 
join (select count(productid = '1' or NULL) AS sppleads,count(productid = '5' or productid = '7' or productid = '12' or NULL) as tdsleads,count(productid = '21' or NULL) as sitleads,count(productid = '17' or NULL) as stoleads,count(productid = '22' or NULL) as sacleads,count(productid <> '1' and productid <> '21' and productid <> '17' and productid <> '22' and productid <> '5' and productid <> '7' and productid <> '12' or NULL) as otherleads from leads where leadstatus IN ('Demo Given','Order Closed','Quote Sent','Perusing to Purchase')
AND id NOT IN (select leadid from lms_updatelogs where ((updatedate < '$fromdate' AND leadstatus = 'Demo Given') OR (updatedate < '$fromdate' AND leadstatus = 'Order Closed') OR (updatedate < '$fromdate' AND leadstatus = 'Quote Sent') OR (updatedate < '$fromdate' AND leadstatus = 'Perusing to Purchase'))) AND source = 'Product Download' AND ".$datetimepiece.") AS webleads limit 1)

union all(select dealers.dlrcompanyname AS dlrcompanyname, lms_users.username AS username, NULLIF(dlrleads.sppleads,0) AS sppleads,  NULLIF(dlrleads.tdsleads,0) AS tdsleads,  NULLIF(dlrleads.sitleads,0) AS sitleads,  NULLIF(dlrleads.stoleads,0) AS stoleads,  NULLIF(dlrleads.sacleads,0) AS sacleads,  NULLIF(dlrleads.otherleads,0) AS otherleads, dealers.dlremail AS dlremail, lms_managers.mgrname AS endmanager, dealers.dlrcell AS cellnumber from dealers left join lms_managers on lms_managers.id = dealers.managerid 
join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' 
left join (select leaduploadedby, count(productid = '1' or NULL) AS sppleads,count(productid = '5' or productid = '7' or productid = '12' or NULL) as tdsleads,count(productid = '21' or NULL) as sitleads,count(productid = '17' or NULL) as stoleads,count(productid = '22' or NULL) as sacleads,count(productid <> '1' and productid <> '21' and productid <> '17' and productid <> '22' and productid <> '5' and productid <> '7' and productid <> '12' or NULL) as otherleads from leads where leadstatus IN ('Demo Given','Order Closed','Quote Sent','Perusing to Purchase')
AND id NOT IN (select leadid from lms_updatelogs where ((updatedate < '$fromdate' AND leadstatus = 'Demo Given') OR (updatedate < '$fromdate' AND leadstatus = 'Order Closed') OR (updatedate < '$fromdate' AND leadstatus = 'Quote Sent') OR (updatedate < '$fromdate' AND leadstatus = 'Perusing to Purchase'))) AND ".$datetimepiece." group by leaduploadedby) AS dlrleads on lms_users.id = dlrleads.leaduploadedby group by dealers.dlrcompanyname order by dealers.id)

union all(select lms_managers.mgrname AS dlrcompanyname, lms_users.username AS username, NULLIF(mgrleads.sppleads,0) AS sppleads,  NULLIF(mgrleads.tdsleads,0) AS tdsleads,  NULLIF(mgrleads.sitleads,0) AS sitleads,  NULLIF(mgrleads.stoleads,0) AS stoleads,  NULLIF(mgrleads.sacleads,0) AS sacleads,  NULLIF(mgrleads.otherleads,0) AS otherleads, lms_managers.mgremailid AS dlremail, lms_managers.mgrname AS endmanager, lms_managers.mgrcell AS cellnumber from lms_managers join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' 
left join(select leaduploadedby, count(productid = '1' or NULL) AS sppleads,count(productid = '5' or productid = '7' or productid = '12' or NULL) as tdsleads,count(productid = '21' or NULL) as sitleads,count(productid = '17' or NULL) as stoleads,count(productid = '22' or NULL) as sacleads,count(productid <> '1' and productid <> '21' and productid <> '17' and productid <> '22' and productid <> '5' and productid <> '7' and productid <> '12' or NULL) as otherleads from leads where leadstatus IN ('Demo Given','Order Closed','Quote Sent','Perusing to Purchase')
AND id NOT IN (select leadid from lms_updatelogs where ((updatedate < '$fromdate' AND leadstatus = 'Demo Given') OR (updatedate < '$fromdate' AND leadstatus = 'Order Closed') OR (updatedate < '$fromdate' AND leadstatus = 'Quote Sent') OR (updatedate < '$fromdate' AND leadstatus = 'Perusing to Purchase'))) AND  ".$datetimepiece." group by leaduploadedby) as mgrleads on lms_users.id = mgrleads.leaduploadedby group by lms_managers.mgrname order by lms_managers.id)

union all(select lms_subadmins.sadname AS dlrcompanyname, lms_users.username AS username, NULLIF(saleads.sppleads,0) AS sppleads,  NULLIF(saleads.tdsleads,0) AS tdsleads,  NULLIF(saleads.sitleads,0) AS sitleads,  NULLIF(saleads.stoleads,0) AS stoleads,  NULLIF(saleads.sacleads,0) AS sacleads,  NULLIF(saleads.otherleads,0) AS otherleads, lms_subadmins.sademailid AS dlremail, '*Sub Admin' AS endmanager, 'NotAvailable' AS cellnumber from lms_subadmins 
join lms_users on lms_users.referenceid = lms_subadmins.id AND lms_users.type = 'Sub Admin' 
left join (select leaduploadedby,count(productid = '1' or NULL) AS sppleads,count(productid = '5' or productid = '7' or productid = '12' or NULL) as tdsleads,count(productid = '21' or NULL) as sitleads,count(productid = '17' or NULL) as stoleads,count(productid = '22' or NULL) as sacleads,count(productid <> '1' and productid <> '21' and productid <> '17' and productid <> '22' and productid <> '5' and productid <> '7' and productid <> '12' or NULL) as otherleads from leads where leadstatus IN ('Demo Given','Order Closed','Quote Sent','Perusing to Purchase')
AND id NOT IN (select leadid from lms_updatelogs where ((updatedate < '$fromdate' AND leadstatus = 'Demo Given') OR (updatedate < '$fromdate' AND leadstatus = 'Order Closed') OR (updatedate < '$fromdate' AND leadstatus = 'Quote Sent') OR (updatedate < '$fromdate' AND leadstatus = 'Perusing to Purchase'))) AND
".$datetimepiece." group by leaduploadedby) AS saleads on lms_users.id = saleads.leaduploadedby group by lms_subadmins.sadname order by lms_subadmins.id)";
						break;
					
					case "Reporting Authority":
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
						
						$query = "(select 'Web Downloads' AS dlrcompanyname, 'NA' AS username, webleads.sppleads AS sppleads, webleads.tdsleads AS tdsleads, webleads.sitleads AS sitleads, webleads.stoleads AS stoleads, webleads.sacleads AS sacleads, webleads.otherleads AS otherleads, 'webmaster@relyonsoft.com' AS dlremail, '*Web Downloads' AS endmanager, 'NotAvailable' AS cellnumber from leads 
join(select count(productid = '1' or NULL) AS sppleads,count(productid = '5' or productid = '7' or productid = '12' or NULL) as tdsleads,count(productid = '21' or NULL) as sitleads,count(productid = '17' or NULL) as stoleads,count(productid = '22' or NULL) as sacleads,count(productid <> '1' and productid <> '21' and productid <> '17' and productid <> '22' and productid <> '5' and productid <> '7' and productid <> '12' or NULL) as otherleads from leads join dealers on dealers.id = leads.dealerid join lms_users on dealers.managerid = lms_users.referenceid AND lms_users.type = 'Reporting Authority' where dealers.id <> '9999999999999' ".$managercheckpiece." ".$branchpiecejoin." AND source = 'Product Download' AND ".$datetimepiece.") AS webleads limit 1)

union all(select dealers.dlrcompanyname AS dlrcompanyname, lms_users.username AS username, NULLIF(dlrleads.sppleads,0) AS sppleads,  NULLIF(dlrleads.tdsleads,0) AS tdsleads,  NULLIF(dlrleads.sitleads,0) AS sitleads,  NULLIF(dlrleads.stoleads,0) AS stoleads,  NULLIF(dlrleads.sacleads,0) AS sacleads,  NULLIF(dlrleads.otherleads,0) AS otherleads, dealers.dlremail AS dlremail, lms_managers.mgrname AS endmanager, dealers.dlrcell AS cellnumber from dealers left join lms_managers on lms_managers.id = dealers.managerid 
join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' 
left join (select leaduploadedby,count(productid = '1' or NULL) AS sppleads,count(productid = '5' or productid = '7' or productid = '12' or NULL) as tdsleads,count(productid = '21' or NULL) as sitleads,count(productid = '17' or NULL) as stoleads,count(productid = '22' or NULL) as sacleads,count(productid <> '1' and productid <> '21' and productid <> '17' and productid <> '22' and productid <> '5' and productid <> '7' and productid <> '12' or NULL) as otherleads from leads where leadstatus IN ('Demo Given','Order Closed','Quote Sent','Perusing to Purchase')
AND id NOT IN (select leadid from lms_updatelogs where ((updatedate < '$fromdate' AND leadstatus = 'Demo Given') OR (updatedate < '$fromdate' AND leadstatus = 'Order Closed') OR (updatedate < '$fromdate' AND leadstatus = 'Quote Sent') OR (updatedate < '$fromdate' AND leadstatus = 'Perusing to Purchase'))) AND ".$datetimepiece." group by leaduploadedby) AS dlrleads on lms_users.id = dlrleads.leaduploadedby left join lms_users AS lms_users2 on lms_users2.referenceid = dealers.managerid AND lms_users2.type = 'Reporting Authority' where dealers.id <> '9999999999999' ".$managercheckpiece1."  ".$branchpiecejoin1."  group by dealers.dlrcompanyname order by dealers.id)

union all(select lms_managers.mgrname AS dlrcompanyname, lms_users.username AS username, NULLIF(mgrleads.sppleads,0) AS sppleads,  NULLIF(mgrleads.tdsleads,0) AS tdsleads,  NULLIF(mgrleads.sitleads,0) AS sitleads,  NULLIF(mgrleads.stoleads,0) AS stoleads,  NULLIF(mgrleads.sacleads,0) AS sacleads,  NULLIF(mgrleads.otherleads,0) AS otherleads, lms_managers.mgremailid AS dlremail, lms_managers.mgrname AS endmanager, lms_managers.mgrcell AS cellnumber from lms_managers 
join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' 
left join (select leaduploadedby, count(productid = '1' or NULL) AS sppleads,count(productid = '5' or productid = '7' or productid = '12' or NULL) as tdsleads,count(productid = '21' or NULL) as sitleads,count(productid = '17' or NULL) as stoleads,count(productid = '22' or NULL) as sacleads,count(productid <> '1' and productid <> '21' and productid <> '17' and productid <> '22' and productid <> '5' and productid <> '7' and productid <> '12' or NULL) as otherleads from leads where leadstatus IN ('Demo Given','Order Closed','Quote Sent','Perusing to Purchase')
AND id NOT IN (select leadid from lms_updatelogs where ((updatedate < '$fromdate' AND leadstatus = 'Demo Given') OR (updatedate < '$fromdate' AND leadstatus = 'Order Closed') OR (updatedate < '$fromdate' AND leadstatus = 'Quote Sent') OR (updatedate < '$fromdate' AND leadstatus = 'Perusing to Purchase'))) AND ".$datetimepiece." group by leaduploadedby) AS mgrleads on lms_users.id = mgrleads.leaduploadedby where  lms_users.username = '".$cookie_username."'   group by lms_managers.mgrname order by lms_managers.id)";
						break;
					
					case "Dealer":
						$query = "(select 'Web Downloads' AS dlrcompanyname, 'NA' AS username,  webleads.sppleads AS sppleads, webleads.tdsleads AS tdsleads, webleads.sitleads AS sitleads, webleads.stoleads AS stoleads, webleads.sacleads AS sacleads, webleads.otherleads AS otherleads, 'webmaster@relyonsoft.com' AS dlremail, '*Web Downloads' AS endmanager, 'NotAvailable' AS cellnumber from leads
join (select count(productid = '1' or NULL) AS sppleads,count(productid = '5' or productid = '7' or productid = '12' or NULL) as tdsleads,count(productid = '21' or NULL) as sitleads,count(productid = '17' or NULL) as stoleads,count(productid = '22' or NULL) as sacleads,count(productid <> '1' and productid <> '21' and productid <> '17' and productid <> '22' and productid <> '5' and productid <> '7' and productid <> '12' or NULL) as otherleads from leads 
join lms_users on leads.dealerid = lms_users.referenceid  where lms_users.username = '".$cookie_username."' AND source = 'Product Download' AND ".$datetimepiece." AND lms_users.type = 'Dealer') AS webleads limit 1)

union all(select dealers.dlrcompanyname AS dlrcompanyname, lms_users.username AS username, NULLIF(dlrleads.sppleads,0) AS sppleads,  NULLIF(dlrleads.tdsleads,0) AS tdsleads,  NULLIF(dlrleads.sitleads,0) AS sitleads,  NULLIF(dlrleads.stoleads,0) AS stoleads,  NULLIF(dlrleads.sacleads,0) AS sacleads,  NULLIF(dlrleads.otherleads,0) AS otherleads, dealers.dlremail AS dlremail, lms_managers.mgrname AS endmanager, dealers.dlrcell AS cellnumber from dealers left join lms_managers on lms_managers.id = dealers.managerid 
join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' 
left join (select leaduploadedby,count(productid = '1' or NULL) AS sppleads,count(productid = '5' or productid = '7' or productid = '12' or NULL) as tdsleads,count(productid = '21' or NULL) as sitleads,count(productid = '17' or NULL) as stoleads,count(productid = '22' or NULL) as sacleads,count(productid <> '1' and productid <> '21' and productid <> '17' and productid <> '22' and productid <> '5' and productid <> '7' and productid <> '12' or NULL) as otherleads from leads where leadstatus IN ('Demo Given','Order Closed','Quote Sent','Perusing to Purchase')
AND id NOT IN (select leadid from lms_updatelogs ((updatedate < '$fromdate' AND leadstatus = 'Demo Given') OR (updatedate < '$fromdate' AND leadstatus = 'Order Closed') OR (updatedate < '$fromdate' AND leadstatus = 'Quote Sent') OR (updatedate < '$fromdate' AND leadstatus = 'Perusing to Purchase'))) AND".$datetimepiece." group by leaduploadedby) AS dlrleads on lms_users.id = dlrleads.leaduploadedby where lms_users.username = '".$cookie_username."' group by dealers.dlrcompanyname order by dealers.id)";
						break;
				} 
				echo $query;
				exit();
				$result = runmysqlquery($query);
				// Create new PHPExcel object
				$objPHPExcel = new PHPExcel();
		
				//Set Active Sheet	
				$mySheet = $objPHPExcel->getActiveSheet();
				$styleArray = array(
						'font' => array('bold' => true),
						'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '0099CCFF')),
						'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
					);
					
				// Apply style	
				$mySheet->getStyle('A3:L3')->applyFromArray($styleArray);		
				
				// Merge cells
				
				$mySheet->mergeCells('A1:L1');
				$mySheet->mergeCells('A2:L2');
	
				// To align the text to center.
				$mySheet->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$mySheet->getStyle('A2:L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
							->setCellValue('A2', 'Leads uploaded '.' '.$attachpiece);
				$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
				$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
				$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
				//File contents for Header Row
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3', 'Sl No')
					->setCellValue('B3', 'Name')
					->setCellValue('C3', 'Login')
					->setCellValue('D3', 'Saral Pay Pack')
					->setCellValue('E3', 'Saral TDS')
					->setCellValue('F3', 'Saral Income Tax')
					->setCellValue('G3', 'Saral Tax Office')
					->setCellValue('H3', 'Saral Accounts')
					->setCellValue('I3', 'Others')
					->setCellValue('J3', 'Emailid')
					->setCellValue('K3', 'Cell')
					->setCellValue('L3', 'Manager Name');
					
				$j = 4;
				$slno = 0;
				while($fetch = mysqli_fetch_array($result))
				{
					//set_time_limit(20);
					$slno++;
					$mySheet->setCellValue('A'.$j,$slno)
						->setCellValue('B'.$j,$fetch['dlrcompanyname'])
						->setCellValue('C'.$j,$fetch['username'])
						->setCellValue('D'.$j,$fetch['sppleads'])
						->setCellValue('E'.$j,$fetch['tdsleads'])
						->setCellValue('F'.$j,$fetch['sitleads'])
						->setCellValue('G'.$j,$fetch['stoleads'])
						->setCellValue('H'.$j,$fetch['sacleads'])
						->setCellValue('I'.$j,$fetch['otherleads'])
						->setCellValue('J'.$j,$fetch['dlremail'])
						->setCellValue('K'.$j,$fetch['cellnumber'])
						->setCellValue('L'.$j,$fetch['endmanager']);
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
				$mySheet->getColumnDimension('B')->setWidth(36);
				$mySheet->getColumnDimension('C')->setWidth(35);
				$mySheet->getColumnDimension('D')->setWidth(18);
				$mySheet->getColumnDimension('E')->setWidth(18);
				$mySheet->getColumnDimension('F')->setWidth(18);
				$mySheet->getColumnDimension('G')->setWidth(18);
				$mySheet->getColumnDimension('H')->setWidth(18);
				$mySheet->getColumnDimension('I')->setWidth(18);
				$mySheet->getColumnDimension('J')->setWidth(35);
				$mySheet->getColumnDimension('K')->setWidth(20);
				$mySheet->getColumnDimension('L')->setWidth(25);
				
				// Insert logs on Lead Source upload stats to excel
				$query1 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','40','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result1 = runmysqlquery($query1);
				
				$filebasename = "LMS-LEADUPLOADSTATS-".$cookie_username."-".$date.".xls";
				
				if($_SERVER['HTTP_HOST'] == 'meghanab')  
				{
					$filepath = $_SERVER['DOCUMENT_ROOT'].'/LMS/filescreated/'.$filebasename;
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