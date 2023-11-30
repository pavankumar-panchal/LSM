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
		$attachpiece = 'from '.$_POST['fromdate'].'  to '.$_POST['todate'].'';
		$datetimepiece = "substring(leads.leaddatetime,1,10) between '".$fromdate."' AND  '".$todate."'"; 
		if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '' && checkdateformat($fromdate) && checkdateformat($todate) && ((datenumeric($todate) - datenumeric($fromdate)) >= 0))
		{
			switch($cookie_usertype)
			{
				case "Admin":
				case "Sub Admin":
					$query = "select dealers.dlrcompanyname AS dlrcompanyname, NULLIF(days10.notviewed10, 0) AS notviewed10, NULLIF(days10.unattended10, 0) AS unattended10, NULLIF(days10.fakeenquiry10, 0) AS fakeenquiry10, NULLIF(days10.notinterested10, 0) AS notinterested10, NULLIF(days10.registereduser10, 0) AS registereduser10, NULLIF(days10.attended10, 0) AS attended10, NULLIF(days10.demogiven10, 0) AS demogiven10, NULLIF(days10.quotesent10, 0) AS quotesent10, NULLIF(days10.persuing2purchase10, 0) AS persuing2purchase10, NULLIF(days10.orderclosed10, 0) AS orderclosed10, NULLIF(days510.notviewed510, 0) AS notviewed510, NULLIF(days510.unattended510, 0) AS unattended510, NULLIF(days510.fakeenquiry510, 0) AS fakeenquiry510, NULLIF(days510.notinterested510, 0) AS notinterested510, NULLIF(days510.registereduser510, 0) AS registereduser510, NULLIF(days510.attended510, 0) AS attended510, NULLIF(days510.demogiven510, 0) AS demogiven510, NULLIF(days510.quotesent510, 0) AS quotesent510, NULLIF(days510.persuing2purchase510, 0) AS persuing2purchase510, NULLIF(days510.orderclosed510, 0) AS orderclosed510, NULLIF(days5.notviewed5, 0) AS notviewed5, NULLIF(days5.unattended5, 0) AS unattended5, NULLIF(days5.fakeenquiry5, 0) AS fakeenquiry5, NULLIF(days5.notinterested5, 0) AS notinterested5, NULLIF(days5.registereduser5, 0) AS registereduser5, NULLIF(days5.attended5, 0) AS attended5, NULLIF(days5.demogiven5, 0) AS demogiven5, NULLIF(days5.quotesent5, 0) AS quotesent5, NULLIF(days5.persuing2purchase5, 0) AS persuing2purchase5, NULLIF(days5.orderclosed5, 0) AS orderclosed5, dealers.dlremail AS dlremail, dealers.dlrcell AS cellnumber, lms_managers.mgrname AS mgrname
from dealers 
					 
left join lms_managers on lms_managers.id = dealers.managerid
					 
left join (select dealerid, count(leadstatus = 'Not Viewed' OR NULL) AS notviewed10, count(leadstatus = 'UnAttended' OR NULL) AS unattended10, count(leadstatus = 'Fake Enquiry' OR NULL) AS fakeenquiry10, count(leadstatus = 'Not Interested' OR NULL) AS notinterested10, count(leadstatus = 'Registered User' OR NULL) AS registereduser10, count(leadstatus = 'Attended' OR NULL) AS attended10, count(leadstatus = 'Demo Given' OR NULL) AS demogiven10, count(leadstatus = 'Quote Sent' OR NULL) AS quotesent10, count(leadstatus = 'Perusing to Purchase' OR NULL) AS persuing2purchase10, count(leadstatus = 'Order Closed' OR NULL) AS orderclosed10 from leads WHERE ((datediff(curdate(),lastupdateddate) > 10) OR (lastupdateddate IS NULL AND datediff(curdate(),leaddatetime) > 10)) AND (".$datetimepiece.") group by dealerid) AS days10 on days10.dealerid = dealers.id 
					
left join (select dealerid, count(leadstatus = 'Not Viewed' OR NULL) AS notviewed510, count(leadstatus = 'UnAttended' OR NULL) AS unattended510, count(leadstatus = 'Fake Enquiry' OR NULL) AS fakeenquiry510, count(leadstatus = 'Not Interested' OR NULL) AS notinterested510, count(leadstatus = 'Registered User' OR NULL) AS registereduser510, count(leadstatus = 'Attended' OR NULL) AS attended510, count(leadstatus = 'Demo Given' OR NULL) AS demogiven510, count(leadstatus = 'Quote Sent' OR NULL) AS quotesent510, count(leadstatus = 'Perusing to Purchase' OR NULL) AS persuing2purchase510, count(leadstatus = 'Order Closed' OR NULL) AS orderclosed510 from leads WHERE (((datediff(curdate(),lastupdateddate) >= 5) AND (datediff(curdate(),lastupdateddate) <= 10)) OR (lastupdateddate IS NULL AND datediff(curdate(),leaddatetime) >= 5  AND datediff(curdate(),leaddatetime) <= 10)) AND (".$datetimepiece.") group by dealerid) AS days510 on days510.dealerid = dealers.id 
					
left join (select dealerid, count(leadstatus = 'Not Viewed' OR NULL) AS notviewed5, count(leadstatus = 'UnAttended' OR NULL) AS unattended5, count(leadstatus = 'Fake Enquiry' OR NULL) AS fakeenquiry5, count(leadstatus = 'Not Interested' OR NULL) AS notinterested5, count(leadstatus = 'Registered User' OR NULL) AS registereduser5, count(leadstatus = 'Attended' OR NULL) AS attended5, count(leadstatus = 'Demo Given' OR NULL) AS demogiven5, count(leadstatus = 'Quote Sent' OR NULL) AS quotesent5, count(leadstatus = 'Perusing to Purchase' OR NULL) AS persuing2purchase5, count(leadstatus = 'Order Closed' OR NULL) AS orderclosed5 from leads WHERE ((datediff(curdate(),lastupdateddate) < 5) OR (lastupdateddate IS NULL AND datediff(curdate(),leaddatetime) < 5)) AND (".$datetimepiece.") group by dealerid) AS days5 on days5.dealerid = dealers.id 	order by dealers.id";
					break;
				
				case "Reporting Authority":
					if($cookie_usertype == "Reporting Authority")
					{
						//Check wheteher the manager is branch head or not
						$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
						$result1 = runmysqlqueryfetch($query1);
						if($result1['branchhead'] == 'yes')
						{
							$branchpiecejoin = " AND (dealers.branch = '".$result1['branch']."'  OR dealers.managerid = '".$result1['managerid']."')";
							if($cookie_username == "srinivasan")
								$managercheckpiece = " AND (lms_users2.username = '".$cookie_username."' or  lms_users2.username = 'nagaraj')";
							else
								$managercheckpiece = "";
						}
						else
						{
							$branchpiecejoin = "";
							if($cookie_username == "srinivasan")
								$managercheckpiece = " AND (lms_users2.username = '".$cookie_username."' or  lms_users2.username = 'nagaraj')";
							else
								$managercheckpiece = " AND(lms_users2.username = '".$cookie_username."')";
						}
					}
						
					$query = "select dealers.dlrcompanyname AS dlrcompanyname, NULLIF(days10.notviewed10, 0) AS notviewed10, NULLIF(days10.unattended10, 0) AS unattended10, NULLIF(days10.fakeenquiry10, 0) AS fakeenquiry10, NULLIF(days10.notinterested10, 0) AS notinterested10, NULLIF(days10.registereduser10, 0) AS registereduser10, NULLIF(days10.attended10, 0) AS attended10, NULLIF(days10.demogiven10, 0) AS demogiven10, NULLIF(days10.quotesent10, 0) AS quotesent10, NULLIF(days10.persuing2purchase10, 0) AS persuing2purchase10, NULLIF(days10.orderclosed10, 0) AS orderclosed10, NULLIF(days510.notviewed510, 0) AS notviewed510, NULLIF(days510.unattended510, 0) AS unattended510, NULLIF(days510.fakeenquiry510, 0) AS fakeenquiry510, NULLIF(days510.notinterested510, 0) AS notinterested510, NULLIF(days510.registereduser510, 0) AS registereduser510, NULLIF(days510.attended510, 0) AS attended510, NULLIF(days510.demogiven510, 0) AS demogiven510, NULLIF(days510.quotesent510, 0) AS quotesent510, NULLIF(days510.persuing2purchase510, 0) AS persuing2purchase510, NULLIF(days510.orderclosed510, 0) AS orderclosed510, NULLIF(days5.notviewed5, 0) AS notviewed5, NULLIF(days5.unattended5, 0) AS unattended5, NULLIF(days5.fakeenquiry5, 0) AS fakeenquiry5, NULLIF(days5.notinterested5, 0) AS notinterested5, NULLIF(days5.registereduser5, 0) AS registereduser5, NULLIF(days5.attended5, 0) AS attended5, NULLIF(days5.demogiven5, 0) AS demogiven5, NULLIF(days5.quotesent5, 0) AS quotesent5, NULLIF(days5.persuing2purchase5, 0) AS persuing2purchase5, NULLIF(days5.orderclosed5, 0) AS orderclosed5, dealers.dlremail AS dlremail, dealers.dlrcell AS cellnumber, lms_managers.mgrname AS mgrname	
					
									
from dealers 				 
left join lms_managers on lms_managers.id = dealers.managerid
left join (select dealerid, count(leadstatus = 'Not Viewed' OR NULL) AS notviewed10, count(leadstatus = 'UnAttended' OR NULL) AS unattended10, count(leadstatus = 'Fake Enquiry' OR NULL) AS fakeenquiry10, count(leadstatus = 'Not Interested' OR NULL) AS notinterested10, count(leadstatus = 'Registered User' OR NULL) AS registereduser10, count(leadstatus = 'Attended' OR NULL) AS attended10, count(leadstatus = 'Demo Given' OR NULL) AS demogiven10, count(leadstatus = 'Quote Sent' OR NULL) AS quotesent10, count(leadstatus = 'Perusing to Purchase' OR NULL) AS persuing2purchase10, count(leadstatus = 'Order Closed' OR NULL) AS orderclosed10 from leads WHERE ((datediff(curdate(),lastupdateddate) > 10) OR (lastupdateddate IS NULL AND datediff(curdate(),leaddatetime) > 10)) AND (".$datetimepiece.") group by dealerid) AS days10 on days10.dealerid = dealers.id 
					
left join (select dealerid, count(leadstatus = 'Not Viewed' OR NULL) AS notviewed510, count(leadstatus = 'UnAttended' OR NULL) AS unattended510, count(leadstatus = 'Fake Enquiry' OR NULL) AS fakeenquiry510, count(leadstatus = 'Not Interested' OR NULL) AS notinterested510, count(leadstatus = 'Registered User' OR NULL) AS registereduser510, count(leadstatus = 'Attended' OR NULL) AS attended510, count(leadstatus = 'Demo Given' OR NULL) AS demogiven510, count(leadstatus = 'Quote Sent' OR NULL) AS quotesent510, count(leadstatus = 'Perusing to Purchase' OR NULL) AS persuing2purchase510, count(leadstatus = 'Order Closed' OR NULL) AS orderclosed510 from leads WHERE (((datediff(curdate(),lastupdateddate) >= 5) AND (datediff(curdate(),lastupdateddate) <= 10)) OR (lastupdateddate IS NULL AND datediff(curdate(),leaddatetime) >= 5   AND datediff(curdate(),leaddatetime) <= 10)) AND (".$datetimepiece.") group by dealerid) AS days510 on days510.dealerid = dealers.id 
					
left join (select dealerid, count(leadstatus = 'Not Viewed' OR NULL) AS notviewed5, count(leadstatus = 'UnAttended' OR NULL) AS unattended5, count(leadstatus = 'Fake Enquiry' OR NULL) AS fakeenquiry5, count(leadstatus = 'Not Interested' OR NULL) AS notinterested5, count(leadstatus = 'Registered User' OR NULL) AS registereduser5, count(leadstatus = 'Attended' OR NULL) AS attended5, count(leadstatus = 'Demo Given' OR NULL) AS demogiven5, count(leadstatus = 'Quote Sent' OR NULL) AS quotesent5, count(leadstatus = 'Perusing to Purchase' OR NULL) AS persuing2purchase5, count(leadstatus = 'Order Closed' OR NULL) AS orderclosed5 from leads WHERE ((datediff(curdate(),lastupdateddate) < 5) OR (lastupdateddate IS NULL AND datediff(curdate(),leaddatetime) < 5)) AND (".$datetimepiece.") group by dealerid) AS days5 on days5.dealerid = dealers.id  
left join lms_users on lms_users.referenceid = dealers.managerid AND lms_users.type = 'Reporting Authority' WHERE dealers.id <> '9999999999999999999'  ".$managercheckpiece." ".$branchpiecejoin." order by dealers.id";
					break;
					
					
				case "Dealer":
					$query = "select dealers.dlrcompanyname AS dlrcompanyname, NULLIF(days10.notviewed10, 0) AS notviewed10, NULLIF(days10.unattended10, 0) AS unattended10, NULLIF(days10.fakeenquiry10, 0) AS fakeenquiry10, NULLIF(days10.notinterested10, 0) AS notinterested10, NULLIF(days10.registereduser10, 0) AS registereduser10, NULLIF(days10.attended10, 0) AS attended10, NULLIF(days10.demogiven10, 0) AS demogiven10, NULLIF(days10.quotesent10, 0) AS quotesent10, NULLIF(days10.persuing2purchase10, 0) AS persuing2purchase10, NULLIF(days10.orderclosed10, 0) AS orderclosed10, NULLIF(days510.notviewed510, 0) AS notviewed510, NULLIF(days510.unattended510, 0) AS unattended510, NULLIF(days510.fakeenquiry510, 0) AS fakeenquiry510, NULLIF(days510.notinterested510, 0) AS notinterested510, NULLIF(days510.registereduser510, 0) AS registereduser510, NULLIF(days510.attended510, 0) AS attended510, NULLIF(days510.demogiven510, 0) AS demogiven510, NULLIF(days510.quotesent510, 0) AS quotesent510, NULLIF(days510.persuing2purchase510, 0) AS persuing2purchase510, NULLIF(days510.orderclosed510, 0) AS orderclosed510, NULLIF(days5.notviewed5, 0) AS notviewed5, NULLIF(days5.unattended5, 0) AS unattended5, NULLIF(days5.fakeenquiry5, 0) AS fakeenquiry5, NULLIF(days5.notinterested5, 0) AS notinterested5, NULLIF(days5.registereduser5, 0) AS registereduser5, NULLIF(days5.attended5, 0) AS attended5, NULLIF(days5.demogiven5, 0) AS demogiven5, NULLIF(days5.quotesent5, 0) AS quotesent5, NULLIF(days5.persuing2purchase5, 0) AS persuing2purchase5, NULLIF(days5.orderclosed5, 0) AS orderclosed5, dealers.dlremail AS dlremail, dealers.dlrcell AS cellnumber, lms_managers.mgrname AS mgrname					
from dealers 				 
left join lms_managers on lms_managers.id = dealers.managerid
left join (select dealerid, count(leadstatus = 'Not Viewed' OR NULL) AS notviewed10, count(leadstatus = 'UnAttended' OR NULL) AS unattended10, count(leadstatus = 'Fake Enquiry' OR NULL) AS fakeenquiry10, count(leadstatus = 'Not Interested' OR NULL) AS notinterested10, count(leadstatus = 'Registered User' OR NULL) AS registereduser10, count(leadstatus = 'Attended' OR NULL) AS attended10, count(leadstatus = 'Demo Given' OR NULL) AS demogiven10, count(leadstatus = 'Quote Sent' OR NULL) AS quotesent10, count(leadstatus = 'Perusing to Purchase' OR NULL) AS persuing2purchase10, count(leadstatus = 'Order Closed' OR NULL) AS orderclosed10 from leads WHERE ((datediff(curdate(),lastupdateddate) > 10) OR (lastupdateddate IS NULL AND datediff(curdate(),leaddatetime) > 10)) AND (".$datetimepiece.") group by dealerid) AS days10 on days10.dealerid = dealers.id 
					
left join (select dealerid, count(leadstatus = 'Not Viewed' OR NULL) AS notviewed510, count(leadstatus = 'UnAttended' OR NULL) AS unattended510, count(leadstatus = 'Fake Enquiry' OR NULL) AS fakeenquiry510, count(leadstatus = 'Not Interested' OR NULL) AS notinterested510, count(leadstatus = 'Registered User' OR NULL) AS registereduser510, count(leadstatus = 'Attended' OR NULL) AS attended510, count(leadstatus = 'Demo Given' OR NULL) AS demogiven510, count(leadstatus = 'Quote Sent' OR NULL) AS quotesent510, count(leadstatus = 'Persuing to Purchase' OR NULL) AS persuing2purchase510, count(leadstatus = 'Order Closed' OR NULL) AS orderclosed510 from leads WHERE (((datediff(curdate(),lastupdateddate) >= 5) AND (datediff(curdate(),lastupdateddate) <= 10)) OR (lastupdateddate IS NULL AND datediff(curdate(),leaddatetime) >= 5   AND datediff(curdate(),leaddatetime) <= 10)) AND (".$datetimepiece.") group by dealerid) AS days510 on days510.dealerid = dealers.id 
					
left join (select dealerid, count(leadstatus = 'Not Viewed' OR NULL) AS notviewed5, count(leadstatus = 'UnAttended' OR NULL) AS unattended5, count(leadstatus = 'Fake Enquiry' OR NULL) AS fakeenquiry5, count(leadstatus = 'Not Interested' OR NULL) AS notinterested5, count(leadstatus = 'Registered User' OR NULL) AS registereduser5, count(leadstatus = 'Attended' OR NULL) AS attended5, count(leadstatus = 'Demo Given' OR NULL) AS demogiven5, count(leadstatus = 'Quote Sent' OR NULL) AS quotesent5, count(leadstatus = 'Perusing to Purchase' OR NULL) AS persuing2purchase5, count(leadstatus = 'Order Closed' OR NULL) AS orderclosed5 from leads WHERE ((datediff(curdate(),lastupdateddate) < 5) OR (lastupdateddate IS NULL AND datediff(curdate(),leaddatetime) < 5)) AND (".$datetimepiece.") group by dealerid) AS days5 on days5.dealerid = dealers.id  
left join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' WHERE lms_users.username = '".$cookie_username."' order by dealers.id";
					break;
			} //echo($query);exit;
			
			$objPHPExcel = new PHPExcel();
		
			//Set Active Sheet	
			$mySheet = $objPHPExcel->getActiveSheet();
			
			$mySheet->mergeCells('A1:AH1');
			$mySheet->mergeCells('A2:AH2');
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
						->setCellValue('A2', 'Dealer Delay Leads  '.' '.$attachpiece);
			$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
			$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
			$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
			
			$styleArray = array(
							'font' => array('bold' => true),
							'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FFCCFFCC')),
							'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
						);
						
			// Style for leads delayed more than 10 days
			$styleArray1 = array(
							'font' => array('bold' => true),
							'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '00FFCC00')),
							'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
						);
						
			// style for leads delayed more than 5 days and less than 10 days		
			$styleArray2 = array(
						'font' => array('bold' => true),
						'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '0099CCFF')),
						'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
					);
					
			// Style for leads delayed less than 5 days
			$styleArray3 = array(
							'font' => array('bold' => true),
							'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '00CCCCFF')),
							'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
						); 
						
			// Apply style
					
			$mySheet->getStyle('A3')->applyFromArray($styleArray);
			$mySheet->getStyle('B3:K3')->applyFromArray($styleArray1);
			$mySheet->getStyle('B4:K4')->applyFromArray($styleArray1);
			$mySheet->getStyle('L3:U3')->applyFromArray($styleArray2);
			$mySheet->getStyle('L4:U4')->applyFromArray($styleArray2);
			$mySheet->getStyle('V3:AE3')->applyFromArray($styleArray3);
			$mySheet->getStyle('V4:AE4')->applyFromArray($styleArray3);
			$mySheet->getStyle('AF3:AH3')->applyFromArray($styleArray);
			$mySheet->getStyle('AF4:AH4')->applyFromArray($styleArray);
			
			// Merge Cells
			 $mySheet->mergeCells('A3:A4');
			 $mySheet->mergeCells('AF3:AF4');
			 $mySheet->mergeCells('AG3:AG4');
			 $mySheet->mergeCells('AH3:AH4');
			
			// Merge cells for leads delayed more than 10 days.
			
			$mySheet->mergeCells('B3:K3');
			$mySheet->getStyle('B3:K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B3', 'Leads delayed by more than 10 Days');
			$mySheet->getStyle('B3:K3')->getFont()->setSize(12); 	
			$mySheet->getStyle('B3:K3')->getFont()->setBold(true); 
			$mySheet->getStyle('B3:K3')->getAlignment()->setWrapText(true);
			 
			 // Merge cells for leads delayed more than 5 days and less than 10 days.

			$mySheet->mergeCells('L3:U3');
			$mySheet->getStyle('L3:U3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('L3', 'Leads delayed by more than 5 Days, but less than 10 Days');
			$mySheet->getStyle('L3:U3')->getFont()->setSize(12); 	
			$mySheet->getStyle('L3:U3')->getFont()->setBold(true); 
			$mySheet->getStyle('L3:U3')->getAlignment()->setWrapText(true);		
			
			// Merge cells for leads delayed less tahn  5 days.
	
			$mySheet->mergeCells('V3:AE3');
			$mySheet->getStyle('V3:AE3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('V3', 'Leads delayed by less than 5 Days');
			$mySheet->getStyle('V3:AE3')->getFont()->setSize(12); 	
			$mySheet->getStyle('V3:AE3')->getFont()->setBold(true); 
			$mySheet->getStyle('V3:AE3')->getAlignment()->setWrapText(true);	
			 
			 //File contents for Header Row
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A3', 'Dealer Name')
			->setCellValue('B4', 'Not Viewed')
			->setCellValue('C4', 'UnAttended')
			->setCellValue('D4', 'Fake Enquiry')
			->setCellValue('E4', 'Not Interested')
			->setCellValue('F4', 'Registered User')
			->setCellValue('G4', 'Attended')
			->setCellValue('H4', 'Demo Given')
			->setCellValue('I4', 'Quote Sent')
			->setCellValue('J4', 'Persuing to Purchase')
			->setCellValue('K4', 'Order Closed')
			->setCellValue('L4', 'Not Viewed')
			->setCellValue('M4', 'UnAttended')
			->setCellValue('N4', 'Fake Enquiry')
			->setCellValue('O4', 'Not Interested')
			->setCellValue('P4', 'Registered User')
			->setCellValue('Q4', 'Attended')
			->setCellValue('R4', 'Demo Given')
			->setCellValue('S4', 'Quote Sent')
			->setCellValue('T4', 'Persuing to Purchase')
			->setCellValue('U4', 'Order Closed')
			->setCellValue('V4', 'Not Viewed')
			->setCellValue('W4', 'UnAttended')
			->setCellValue('X4', 'Fake Enquiry')
			->setCellValue('Y4', 'Not Interested')
			->setCellValue('Z4', 'Registered User')
			->setCellValue('AA4', 'Attended')
			->setCellValue('AB4', 'Demo Given')
			->setCellValue('AC4', 'Quote Sent')
			->setCellValue('AD4', 'Persuing to Purchase')
			->setCellValue('AE4', 'Order Closed')
			->setCellValue('AF3', 'Delaer Email')
			->setCellValue('AG3', 'Cell')
			->setCellValue('AH3', 'Manager Name');
			
			$result = runmysqlquery($query);
			$j = 5;
			$slno = 0;
			while($fetch = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('A'.$j,$fetch['dlrcompanyname'])
				->setCellValue('B'.$j,$fetch['notviewed10'])
				->setCellValue('C'.$j,$fetch['unattended10'])
				->setCellValue('D'.$j,$fetch['fakeenquiry10'])
				->setCellValue('E'.$j,$fetch['notinterested10'])
				->setCellValue('F'.$j,$fetch['registereduser10'])
				->setCellValue('G'.$j,$fetch['attended10'])
				->setCellValue('H'.$j,$fetch['demogiven10'])
				->setCellValue('I'.$j,$fetch['quotesent10'])
				->setCellValue('J'.$j,$fetch['persuing2purchase10'])
				->setCellValue('K'.$j,$fetch['orderclosed10'])
				->setCellValue('L'.$j,$fetch['notviewed510'])
				->setCellValue('M'.$j,$fetch['unattended510'])
				->setCellValue('N'.$j,$fetch['fakeenquiry510'])
				->setCellValue('O'.$j,$fetch['notinterested510'])
				->setCellValue('P'.$j,$fetch['registereduser510'])
				->setCellValue('Q'.$j,$fetch['attended510'])
				->setCellValue('R'.$j,$fetch['demogiven510'])
				->setCellValue('S'.$j,$fetch['quotesent510'])
				->setCellValue('T'.$j,$fetch['persuing2purchase510'])
				->setCellValue('U'.$j,$fetch['orderclosed510'])
				->setCellValue('V'.$j,$fetch['notviewed5'])
				->setCellValue('W'.$j,$fetch['unattended5'])
				->setCellValue('X'.$j,$fetch['fakeenquiry5'])
				->setCellValue('Y'.$j,$fetch['notinterested5'])
				->setCellValue('Z'.$j,$fetch['registereduser5'])
				->setCellValue('AA'.$j,$fetch['attended5'])
				->setCellValue('AB'.$j,$fetch['demogiven5'])
				->setCellValue('AC'.$j,$fetch['quotesent5'])
				->setCellValue('AD'.$j,$fetch['persuing2purchase5'])
				->setCellValue('AE'.$j,$fetch['orderclosed5'])
				->setCellValue('AF'.$j,$fetch['dlremail'])
				->setCellValue('AG'.$j,$fetch['cellnumber'])
				->setCellValue('AH'.$j,$fetch['mgrname']);
				$j++;
				
			}		
			//Define Style for content area
			$styleArrayContent = array(
								'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
							);
			
			//Get the last cell reference
			$highestRow = $mySheet->getHighestRow(); 
			$highestColumn = $mySheet->getHighestColumn(); //echo($highestRow.'');  echo($highestColumn);exit; 
			$myLastCell = $highestColumn.$highestRow;
			
			//Deine the content range
			$myDataRange = 'A5:'.$myLastCell; 
			
			if(mysqli_num_rows($result) <> 0)
			{
			//Apply style to content area range
				$mySheet->getStyle($myDataRange)->applyFromArray($styleArrayContent);
			}	 
			
			//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(35);
		$mySheet->getColumnDimension('B')->setWidth(15);
		$mySheet->getColumnDimension('C')->setWidth(15);
		$mySheet->getColumnDimension('D')->setWidth(15);
		$mySheet->getColumnDimension('E')->setWidth(15);
		$mySheet->getColumnDimension('F')->setWidth(15);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(15);
		$mySheet->getColumnDimension('I')->setWidth(15);
		$mySheet->getColumnDimension('J')->setWidth(20);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(15);
		$mySheet->getColumnDimension('M')->setWidth(20);
		$mySheet->getColumnDimension('N')->setWidth(15);
		$mySheet->getColumnDimension('O')->setWidth(15);
		$mySheet->getColumnDimension('P')->setWidth(15);
		$mySheet->getColumnDimension('Q')->setWidth(15);
		$mySheet->getColumnDimension('R')->setWidth(15);
		$mySheet->getColumnDimension('S')->setWidth(15);
		$mySheet->getColumnDimension('T')->setWidth(20);
		$mySheet->getColumnDimension('U')->setWidth(15);
		$mySheet->getColumnDimension('V')->setWidth(15);
		$mySheet->getColumnDimension('W')->setWidth(20);
		$mySheet->getColumnDimension('X')->setWidth(15);
		$mySheet->getColumnDimension('Y')->setWidth(15);
		$mySheet->getColumnDimension('Z')->setWidth(15);
		$mySheet->getColumnDimension('AA')->setWidth(15);
		$mySheet->getColumnDimension('AB')->setWidth(15);
		$mySheet->getColumnDimension('AC')->setWidth(15);
		$mySheet->getColumnDimension('AD')->setWidth(20);
		$mySheet->getColumnDimension('AE')->setWidth(15);
		$mySheet->getColumnDimension('AF')->setWidth(35);
		$mySheet->getColumnDimension('AG')->setWidth(20);
		$mySheet->getColumnDimension('AH')->setWidth(20);
			 
			// Insert logs on Dealer delay leads to excel
			$query1 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','41','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
			$result1 = runmysqlquery_log($query1);
			
			$filebasename = "LMS-DLRDELAYLEADS-".$cookie_username."-".$date.".xls";
		
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
