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
	$dealerid = $_POST['dealerid'];
	$productid = $_POST['productid'];
	
	$valuetype = $dealerid[0];
	$restvalue = substr($dealerid, 1);

	if($valuetype == 'm')
	$dealerpiece = "AND dealers.managerid = '".$restvalue."'";
	else
	$dealerpiece = ($dealerid == '')?"":("AND dealers.id = '".$restvalue."'");
	$productpiece = ($productid == '')?"":("AND leads.productid = '".$productid."'");
	$datetimepiece = "substring(leads.leaddatetime,1,10) between '".$fromdate."' AND  '".$todate."'"; //echo($datetimepiece);exit;
	
	
	if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '' && checkdateformat($fromdate) && checkdateformat($todate) && ((datenumeric($todate) - datenumeric($fromdate)) >= 0))
	{
		//Check who is making the entry
		$cookie_username = lmsgetcookie('lmsusername');
		$cookie_usertype = lmsgetcookie('lmsusersort');

		switch($cookie_usertype)
		{
			case "Admin":
			case "Sub Admin":
				$query = "select dealers.dlrcompanyname AS dlrcompanyname, dealers.dlremail AS dlremail, dealers.dlrcell AS cellnumber, lms_managers.mgrname AS mgrname,sppcount.sppnotviewed,sppcount.sppunattended,sppcount.sppfake,sppcount.sppnotinterested,sppcount.sppregestereduser,sppcount.sppattended,sppcount.sppdemogiven,sppcount.sppquotesent,sppcount.spppersuingtopurchase,sppcount.spporderclosed,stocount.stonotviewed,stocount.stounattended,stocount.stofake,stocount.stonotinterested,stocount.storegestereduser,stocount.stoattended,stocount.stodemogiven,stocount.stoquotesent,stocount.stopersuingtopurchase,stocount.stoorderclosed,saccount.sacnotviewed,saccount.sacunattended,saccount.sacfake,saccount.sacnotinterested,saccount.sacregestereduser,saccount.sacattended,saccount.sacdemogiven,saccount.sacquotesent,saccount.sacpersuingtopurchase,saccount.sacorderclosed,otherscount.othersnotviewed,otherscount.othersunattended,otherscount.othersfake,otherscount.othersnotinterested,otherscount.othersregestereduser,otherscount.othersattended,otherscount.othersdemogiven,otherscount.othersquotesent,otherscount.otherspersuingtopurchase,otherscount.othersorderclosed from dealers 
left join lms_managers on lms_managers.id = dealers.managerid 

left join(select dealerid,sum(if(leads.leadstatus = 'Not Viewed',1,NULL)) as `sppnotviewed`,sum(if(leads.leadstatus = 'UnAttended',1,NULL)) as `sppunattended`,sum(if(leads.leadstatus = 'Fake Enquiry',1,NULL)) as `sppfake`,sum(if(leads.leadstatus = 'Not Interested',1,NULL)) as `sppnotinterested`,sum(if(leads.leadstatus = 'Registered User',1,NULL)) as `sppregestereduser`,sum(if(leads.leadstatus = 'Attended',1,NULL)) as `sppattended`,sum(if(leads.leadstatus = 'Demo Given',1,NULL)) as `sppdemogiven`,sum(if(leads.leadstatus = 'Quote Sent',1,NULL)) as `sppquotesent`,sum(if(leads.leadstatus = 'Perusing to Purchase',1,NULL)) as `spppersuingtopurchase`,sum(if(leads.leadstatus = 'Order Closed',1,NULL)) as `spporderclosed` from leads join products on leads.productid = products.id where products.category = 'SPP' ".$productpiece." AND ".$datetimepiece." group by dealerid)as sppcount on sppcount.dealerid = dealers.id 

left join(select dealerid,sum(if(leads.leadstatus = 'Not Viewed',1,NULL)) as `stonotviewed`,sum(if(leads.leadstatus = 'UnAttended',1,NULL)) as `stounattended`,sum(if(leads.leadstatus = 'Fake Enquiry',1,NULL)) as `stofake`,sum(if(leads.leadstatus = 'Not Interested',1,NULL)) as `stonotinterested`,sum(if(leads.leadstatus = 'Registered User',1,NULL)) as `storegestereduser`,sum(if(leads.leadstatus = 'Attended',1,NULL)) as `stoattended`,sum(if(leads.leadstatus = 'Demo Given',1,NULL)) as `stodemogiven`,sum(if(leads.leadstatus = 'Quote Sent',1,NULL)) as `stoquotesent`,sum(if(leads.leadstatus = 'Perusing to Purchase',1,NULL)) as `stopersuingtopurchase`,sum(if(leads.leadstatus = 'Order Closed',1,NULL)) as `stoorderclosed` from leads join products on leads.productid = products.id where products.category = 'STO' ".$productpiece." AND ".$datetimepiece." group by dealerid)as stocount on stocount.dealerid = dealers.id 

left join(select dealerid,sum(if(leads.leadstatus = 'Not Viewed',1,NULL)) as `sacnotviewed`,sum(if(leads.leadstatus = 'UnAttended',1,NULL)) as `sacunattended`,sum(if(leads.leadstatus = 'Fake Enquiry',1,NULL)) as `sacfake`,sum(if(leads.leadstatus = 'Not Interested',1,NULL)) as `sacnotinterested`,sum(if(leads.leadstatus = 'Registered User',1,NULL)) as `sacregestereduser`,sum(if(leads.leadstatus = 'Attended',1,NULL)) as `sacattended`,sum(if(leads.leadstatus = 'Demo Given',1,NULL)) as `sacdemogiven`,sum(if(leads.leadstatus = 'Quote Sent',1,NULL)) as `sacquotesent`,sum(if(leads.leadstatus = 'Perusing to Purchase',1,NULL)) as `sacpersuingtopurchase`,sum(if(leads.leadstatus = 'Order Closed',1,NULL)) as `sacorderclosed` from leads join products on leads.productid = products.id where products.category = 'SAC'  ".$productpiece." AND ".$datetimepiece." group by dealerid)as saccount on saccount.dealerid = dealers.id 

left join(select dealerid,sum(if(leads.leadstatus = 'Not Viewed',1,NULL)) as `othersnotviewed`,sum(if(leads.leadstatus = 'UnAttended',1,NULL)) as `othersunattended`,sum(if(leads.leadstatus = 'Fake Enquiry',1,NULL)) as `othersfake`,sum(if(leads.leadstatus = 'Not Interested',1,NULL)) as `othersnotinterested`,sum(if(leads.leadstatus = 'Registered User',1,NULL)) as `othersregestereduser`,sum(if(leads.leadstatus = 'Attended',1,NULL)) as `othersattended`,sum(if(leads.leadstatus = 'Demo Given',1,NULL)) as `othersdemogiven`,sum(if(leads.leadstatus = 'Quote Sent',1,NULL)) as `othersquotesent`,sum(if(leads.leadstatus = 'Perusing to Purchase',1,NULL)) as `otherspersuingtopurchase`,sum(if(leads.leadstatus = 'Order Closed',1,NULL)) as `othersorderclosed` from leads join products on leads.productid = products.id where products.category = 'OTHERS'  ".$productpiece." AND ".$datetimepiece." group by dealerid)as otherscount on otherscount.dealerid = dealers.id WHERE dealers.id <> '' ".$dealerpiece."  group by dealers.dlrcompanyname order by dealers.id;";
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
						$managercheckpiece = " AND (lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj')";
					else
						$managercheckpiece = "";
				}
				else
				{
					$branchpiecejoin = "";
					if($cookie_username == "srinivasan")
						$managercheckpiece = " AND (lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj')";
					else
						$managercheckpiece = " AND(lms_users.username = '".$cookie_username."')";
				}
			}
					
				$query = "select dealers.dlrcompanyname AS dlrcompanyname, dealers.dlremail AS dlremail, dealers.dlrcell AS cellnumber, lms_managers.mgrname AS mgrname,sppcount.sppnotviewed,sppcount.sppunattended,sppcount.sppfake,sppcount.sppnotinterested,sppcount.sppregestereduser,sppcount.sppattended,sppcount.sppdemogiven,sppcount.sppquotesent,sppcount.spppersuingtopurchase,sppcount.spporderclosed,stocount.stonotviewed,stocount.stounattended,stocount.stofake,stocount.stonotinterested,stocount.storegestereduser,stocount.stoattended,stocount.stodemogiven,stocount.stoquotesent,stocount.stopersuingtopurchase,stocount.stoorderclosed,saccount.sacnotviewed,saccount.sacunattended,saccount.sacfake,saccount.sacnotinterested,saccount.sacregestereduser,saccount.sacattended,saccount.sacdemogiven,saccount.sacquotesent,saccount.sacpersuingtopurchase,saccount.sacorderclosed,otherscount.othersnotviewed,otherscount.othersunattended,otherscount.othersfake,otherscount.othersnotinterested,otherscount.othersregestereduser,otherscount.othersattended,otherscount.othersdemogiven,otherscount.othersquotesent,otherscount.otherspersuingtopurchase,otherscount.othersorderclosed from dealers 
left join lms_managers on lms_managers.id = dealers.managerid 

left join(select dealerid,sum(if(leads.leadstatus = 'Not Viewed',1,NULL)) as `sppnotviewed`,sum(if(leads.leadstatus = 'UnAttended',1,NULL)) as `sppunattended`,sum(if(leads.leadstatus = 'Fake Enquiry',1,NULL)) as `sppfake`,sum(if(leads.leadstatus = 'Not Interested',1,NULL)) as `sppnotinterested`,sum(if(leads.leadstatus = 'Registered User',1,NULL)) as `sppregestereduser`,sum(if(leads.leadstatus = 'Attended',1,NULL)) as `sppattended`,sum(if(leads.leadstatus = 'Demo Given',1,NULL)) as `sppdemogiven`,sum(if(leads.leadstatus = 'Quote Sent',1,NULL)) as `sppquotesent`,sum(if(leads.leadstatus = 'Perusing to Purchase',1,NULL)) as `spppersuingtopurchase`,sum(if(leads.leadstatus = 'Order Closed',1,NULL)) as `spporderclosed` from leads join products on leads.productid = products.id where products.category = 'SPP'  ".$productpiece." AND ".$datetimepiece." group by dealerid)as sppcount on sppcount.dealerid = dealers.id 

left join(select dealerid,sum(if(leads.leadstatus = 'Not Viewed',1,NULL)) as `stonotviewed`,sum(if(leads.leadstatus = 'UnAttended',1,NULL)) as `stounattended`,sum(if(leads.leadstatus = 'Fake Enquiry',1,NULL)) as `stofake`,sum(if(leads.leadstatus = 'Not Interested',1,NULL)) as `stonotinterested`,sum(if(leads.leadstatus = 'Registered User',1,NULL)) as `storegestereduser`,sum(if(leads.leadstatus = 'Attended',1,NULL)) as `stoattended`,sum(if(leads.leadstatus = 'Demo Given',1,NULL)) as `stodemogiven`,sum(if(leads.leadstatus = 'Quote Sent',1,NULL)) as `stoquotesent`,sum(if(leads.leadstatus = 'Perusing to Purchase',1,NULL)) as `stopersuingtopurchase`,sum(if(leads.leadstatus = 'Order Closed',1,NULL)) as `stoorderclosed` from leads join products on leads.productid = products.id where products.category = 'STO'  ".$productpiece." AND ".$datetimepiece." group by dealerid)as stocount on stocount.dealerid = dealers.id 

left join(select dealerid,sum(if(leads.leadstatus = 'Not Viewed',1,NULL)) as `sacnotviewed`,sum(if(leads.leadstatus = 'UnAttended',1,NULL)) as `sacunattended`,sum(if(leads.leadstatus = 'Fake Enquiry',1,NULL)) as `sacfake`,sum(if(leads.leadstatus = 'Not Interested',1,NULL)) as `sacnotinterested`,sum(if(leads.leadstatus = 'Registered User',1,NULL)) as `sacregestereduser`,sum(if(leads.leadstatus = 'Attended',1,NULL)) as `sacattended`,sum(if(leads.leadstatus = 'Demo Given',1,NULL)) as `sacdemogiven`,sum(if(leads.leadstatus = 'Quote Sent',1,NULL)) as `sacquotesent`,sum(if(leads.leadstatus = 'Perusing to Purchase',1,NULL)) as `sacpersuingtopurchase`,sum(if(leads.leadstatus = 'Order Closed',1,NULL)) as `sacorderclosed` from leads join products on leads.productid = products.id where products.category = 'SAC'  ".$productpiece." AND ".$datetimepiece." group by dealerid)as saccount on saccount.dealerid = dealers.id 

left join(select dealerid,sum(if(leads.leadstatus = 'Not Viewed',1,NULL)) as `othersnotviewed`,sum(if(leads.leadstatus = 'UnAttended',1,NULL)) as `othersunattended`,sum(if(leads.leadstatus = 'Fake Enquiry',1,NULL)) as `othersfake`,sum(if(leads.leadstatus = 'Not Interested',1,NULL)) as `othersnotinterested`,sum(if(leads.leadstatus = 'Registered User',1,NULL)) as `othersregestereduser`,sum(if(leads.leadstatus = 'Attended',1,NULL)) as `othersattended`,sum(if(leads.leadstatus = 'Demo Given',1,NULL)) as `othersdemogiven`,sum(if(leads.leadstatus = 'Quote Sent',1,NULL)) as `othersquotesent`,sum(if(leads.leadstatus = 'Perusing to Purchase',1,NULL)) as `otherspersuingtopurchase`,sum(if(leads.leadstatus = 'Order Closed',1,NULL)) as `othersorderclosed` from leads join products on leads.productid = products.id where products.category = 'OTHERS'  ".$productpiece." AND ".$datetimepiece." group by dealerid)as otherscount on otherscount.dealerid = dealers.id left join lms_users on lms_users.referenceid = dealers.managerid AND lms_users.type = 'Reporting Authority' WHERE dealers.id <> '9999999999999'  ".$managercheckpiece." ".$dealerpiece." ".$branchpiecejoin." group by dealers.dlrcompanyname order by dealers.id";
				break;


			case "Dealer":
				$query = "select dealers.dlrcompanyname AS dlrcompanyname, dealers.dlremail AS dlremail, dealers.dlrcell AS cellnumber, lms_managers.mgrname AS mgrname,sppcount.sppnotviewed,sppcount.sppunattended,sppcount.sppfake,sppcount.sppnotinterested,sppcount.sppregestereduser,sppcount.sppattended,sppcount.sppdemogiven,sppcount.sppquotesent,sppcount.spppersuingtopurchase,sppcount.spporderclosed,stocount.stonotviewed,stocount.stounattended,stocount.stofake,stocount.stonotinterested,stocount.storegestereduser,stocount.stoattended,stocount.stodemogiven,stocount.stoquotesent,stocount.stopersuingtopurchase,stocount.stoorderclosed,saccount.sacnotviewed,saccount.sacunattended,saccount.sacfake,saccount.sacnotinterested,saccount.sacregestereduser,saccount.sacattended,saccount.sacdemogiven,saccount.sacquotesent,saccount.sacpersuingtopurchase,saccount.sacorderclosed,otherscount.othersnotviewed,otherscount.othersunattended,otherscount.othersfake,otherscount.othersnotinterested,otherscount.othersregestereduser,otherscount.othersattended,otherscount.othersdemogiven,otherscount.othersquotesent,otherscount.otherspersuingtopurchase,otherscount.othersorderclosed from dealers 
left join lms_managers on lms_managers.id = dealers.managerid 

left join(select dealerid,sum(if(leads.leadstatus = 'Not Viewed',1,NULL)) as `sppnotviewed`,sum(if(leads.leadstatus = 'UnAttended',1,NULL)) as `sppunattended`,sum(if(leads.leadstatus = 'Fake Enquiry',1,NULL)) as `sppfake`,sum(if(leads.leadstatus = 'Not Interested',1,NULL)) as `sppnotinterested`,sum(if(leads.leadstatus = 'Registered User',1,NULL)) as `sppregestereduser`,sum(if(leads.leadstatus = 'Attended',1,NULL)) as `sppattended`,sum(if(leads.leadstatus = 'Demo Given',1,NULL)) as `sppdemogiven`,sum(if(leads.leadstatus = 'Quote Sent',1,NULL)) as `sppquotesent`,sum(if(leads.leadstatus = 'Perusing to Purchase',1,NULL)) as `spppersuingtopurchase`,sum(if(leads.leadstatus = 'Order Closed',1,NULL)) as `spporderclosed` from leads join products on leads.productid = products.id where products.category = 'SPP'  ".$productpiece." AND ".$datetimepiece." group by dealerid)as sppcount on sppcount.dealerid = dealers.id 

left join(select dealerid,sum(if(leads.leadstatus = 'Not Viewed',1,NULL)) as `stonotviewed`,sum(if(leads.leadstatus = 'UnAttended',1,NULL)) as `stounattended`,sum(if(leads.leadstatus = 'Fake Enquiry',1,NULL)) as `stofake`,sum(if(leads.leadstatus = 'Not Interested',1,NULL)) as `stonotinterested`,sum(if(leads.leadstatus = 'Registered User',1,NULL)) as `storegestereduser`,sum(if(leads.leadstatus = 'Attended',1,NULL)) as `stoattended`,sum(if(leads.leadstatus = 'Demo Given',1,NULL)) as `stodemogiven`,sum(if(leads.leadstatus = 'Quote Sent',1,NULL)) as `stoquotesent`,sum(if(leads.leadstatus = 'Perusing to Purchase',1,NULL)) as `stopersuingtopurchase`,sum(if(leads.leadstatus = 'Order Closed',1,NULL)) as `stoorderclosed` from leads join products on leads.productid = products.id where products.category = 'STO' ".$productpiece." AND ".$datetimepiece." group by dealerid)as stocount on stocount.dealerid = dealers.id 

left join(select dealerid,sum(if(leads.leadstatus = 'Not Viewed',1,NULL)) as `sacnotviewed`,sum(if(leads.leadstatus = 'UnAttended',1,NULL)) as `sacunattended`,sum(if(leads.leadstatus = 'Fake Enquiry',1,NULL)) as `sacfake`,sum(if(leads.leadstatus = 'Not Interested',1,NULL)) as `sacnotinterested`,sum(if(leads.leadstatus = 'Registered User',1,NULL)) as `sacregestereduser`,sum(if(leads.leadstatus = 'Attended',1,NULL)) as `sacattended`,sum(if(leads.leadstatus = 'Demo Given',1,NULL)) as `sacdemogiven`,sum(if(leads.leadstatus = 'Quote Sent',1,NULL)) as `sacquotesent`,sum(if(leads.leadstatus = 'Perusing to Purchase',1,NULL)) as `sacpersuingtopurchase`,sum(if(leads.leadstatus = 'Order Closed',1,NULL)) as `sacorderclosed` from leads join products on leads.productid = products.id where products.category = 'SAC' ".$productpiece." AND ".$datetimepiece." group by dealerid)as saccount on saccount.dealerid = dealers.id 

left join(select dealerid,sum(if(leads.leadstatus = 'Not Viewed',1,NULL)) as `othersnotviewed`,sum(if(leads.leadstatus = 'UnAttended',1,NULL)) as `othersunattended`,sum(if(leads.leadstatus = 'Fake Enquiry',1,NULL)) as `othersfake`,sum(if(leads.leadstatus = 'Not Interested',1,NULL)) as `othersnotinterested`,sum(if(leads.leadstatus = 'Registered User',1,NULL)) as `othersregestereduser`,sum(if(leads.leadstatus = 'Attended',1,NULL)) as `othersattended`,sum(if(leads.leadstatus = 'Demo Given',1,NULL)) as `othersdemogiven`,sum(if(leads.leadstatus = 'Quote Sent',1,NULL)) as `othersquotesent`,sum(if(leads.leadstatus = 'Perusing to Purchase',1,NULL)) as `otherspersuingtopurchase`,sum(if(leads.leadstatus = 'Order Closed',1,NULL)) as `othersorderclosed` from leads join products on leads.productid = products.id where products.category = 'OTHERS' ".$productpiece." AND ".$datetimepiece." group by dealerid)as otherscount on otherscount.dealerid = dealers.id left join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' WHERE lms_users.username = '".$cookie_username."' ".$dealerpiece."  group by dealers.dlrcompanyname order by dealers.id";
				break;
		}
		$result = runmysqlquery($query);
		
		$objPHPExcel = new PHPExcel();
		
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		$mySheet->mergeCells('A1:AR1');
		$mySheet->mergeCells('A2:AR2');
		/*// To align the text to center.
		$mySheet->getStyle('A1:AR1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$mySheet->getStyle('A2:AR2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Dealer Data Chart  '.' '.$attachpiece);
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
		
		$styleArray = array(
						'font' => array('bold' => true),
						'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FFCCFFCC')),
						'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
					);
					
		// Style for spp
		$styleArray1 = array(
						'font' => array('bold' => true),
						'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '00FFCC00')),
						'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
					);
					
		// style for STO and SIT0
		$styleArray2 = array(
						'font' => array('bold' => true),
						'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '0099CCFF')),
						'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
					);
					
		// style for Saral Accounts
		$styleArray3 = array(
						'font' => array('bold' => true),
						'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '00CCCCFF')),
						'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
					); 
		// Style for Saral Tds and others
		$styleArray4 = array(
						'font' => array('bold' => true),
						'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '099CCFF')),
						'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
					);
		//Apply style for header Row		
		$mySheet->getStyle('A3:D3')->applyFromArray($styleArray);
		$mySheet->getStyle('A4:D4')->applyFromArray($styleArray);
		$mySheet->getStyle('E3:N3')->applyFromArray($styleArray1);
		$mySheet->getStyle('E4:N4')->applyFromArray($styleArray1);
		$mySheet->getStyle('O3:X3')->applyFromArray($styleArray2);
		$mySheet->getStyle('O4:X4')->applyFromArray($styleArray2);
		$mySheet->getStyle('Y3:AH3')->applyFromArray($styleArray3);
		$mySheet->getStyle('Y4:AH4')->applyFromArray($styleArray3);
		$mySheet->getStyle('AI3:AR3')->applyFromArray($styleArray4);
		$mySheet->getStyle('AI4:AR4')->applyFromArray($styleArray4);
		
		// Merge cells 
		$mySheet->mergeCells('A3:A4');
		$mySheet->mergeCells('B3:B4');
		$mySheet->mergeCells('C3:C4');
		$mySheet->mergeCells('D3:D4');
		
		// Merge cells for SPP
		
		$mySheet->mergeCells('E3:N3');
		$mySheet->getStyle('E3:N3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('E3', 'Saral Pay Pack');
		$mySheet->getStyle('E3:N3')->getFont()->setSize(12); 	
		$mySheet->getStyle('E3:N3')->getFont()->setBold(true); 
		$mySheet->getStyle('E3:N3')->getAlignment()->setWrapText(true);
		
		// Merge cells for STO and SIT

		$mySheet->mergeCells('O3:X3');
		$mySheet->getStyle('O3:X3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('O3', 'Saral Tax Office and Saral Income Tax');
		$mySheet->getStyle('O3:X3')->getFont()->setSize(12); 	
		$mySheet->getStyle('O3:X3')->getFont()->setBold(true); 
		$mySheet->getStyle('O3:X3')->getAlignment()->setWrapText(true);		
		
		// Merge cells for Saral Accounts

		$mySheet->mergeCells('Y3:AH3');
		$mySheet->getStyle('Y3:AH3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('Y3', 'Saral Accounts');
		$mySheet->getStyle('Y3:AH3')->getFont()->setSize(12); 	
		$mySheet->getStyle('Y3:AH3')->getFont()->setBold(true); 
		$mySheet->getStyle('Y3:AH3')->getAlignment()->setWrapText(true);	
		
		// Merge cells for Saral TDS and others
		
		$mySheet->mergeCells('AI3:AR3');
		$mySheet->getStyle('AI3:AR3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('AI3', 'Saral TDS and Others');
		$mySheet->getStyle('AI3:AR3')->getFont()->setSize(12); 	
		$mySheet->getStyle('AI3:AR3')->getFont()->setBold(true); 
		$mySheet->getStyle('AI3:AR3')->getAlignment()->setWrapText(true);
		
		
		//File contents for Header Row
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A3', 'Dealer Name')
			->setCellValue('B3', 'Dealer Email')
			->setCellValue('C3', 'Cell')
			->setCellValue('D3', 'Manager Name')
			->setCellValue('E4', 'Not Viewed')
			->setCellValue('F4', 'UnAttended')
			->setCellValue('G4', 'Fake Enquiry')
			->setCellValue('H4', 'Not Interested')
			->setCellValue('I4', 'Registered User')
			->setCellValue('J4', 'Attended')
			->setCellValue('K4', 'Demo Given')
			->setCellValue('L4', 'Quote Sent')
			->setCellValue('M4', 'Persuing to Purchase')
			->setCellValue('N4', 'Order Closed')
			->setCellValue('O4', 'Not Viewed')
			->setCellValue('P4', 'UnAttended')
			->setCellValue('Q4', 'Fake Enquiry')
			->setCellValue('R4', 'Not Interested')
			->setCellValue('S4', 'Registered User')
			->setCellValue('T4', 'Attended')
			->setCellValue('U4', 'Demo Given')
			->setCellValue('V4', 'Quote Sent')
			->setCellValue('W4', 'Persuing to Purchase')
			->setCellValue('X4', 'Order Closed')
			->setCellValue('Y4', 'Not Viewed')
			->setCellValue('Z4', 'UnAttended')
			->setCellValue('AA4', 'Fake Enquiry')
			->setCellValue('AB4', 'Not Interested')
			->setCellValue('AC4', 'Registered User')
			->setCellValue('AD4', 'Attended')
			->setCellValue('AE4', 'Demo Given')
			->setCellValue('AF4', 'Quote Sent')
			->setCellValue('AG4', 'Persuing to Purchase')
			->setCellValue('AH4', 'Order Closed')
			->setCellValue('AI4', 'Not Viewed')
			->setCellValue('AJ4', 'UnAttended')
			->setCellValue('AK4', 'Fake Enquiry')
			->setCellValue('AL4', 'Not Interested')
			->setCellValue('AM4', 'Registered User')
			->setCellValue('AN4', 'Attended')
			->setCellValue('AO4', 'Demo Given')
			->setCellValue('AP4', 'Quote Sent')
			->setCellValue('AQ4', 'Persuing to Purchase')
			->setCellValue('AR4', 'Order Closed');
		
		$j = 5;
		$slno = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$slno++;
			$mySheet->setCellValue('A'.$j,$fetch['dlrcompanyname'])
				->setCellValue('B'.$j,$fetch['dlremail'])
				->setCellValue('C'.$j,$fetch['cellnumber'])
				->setCellValue('D'.$j,$fetch['mgrname'])
				->setCellValue('E'.$j,$fetch['sppnotviewed'])
				->setCellValue('F'.$j,$fetch['sppunattended'])
				->setCellValue('G'.$j,$fetch['sppfake'])
				->setCellValue('H'.$j,$fetch['sppnotinterested'])
				->setCellValue('I'.$j,$fetch['sppregestereduser'])
				->setCellValue('J'.$j,$fetch['sppattended'])
				->setCellValue('K'.$j,$fetch['sppdemogiven'])
				->setCellValue('L'.$j,$fetch['sppquotesent'])
				->setCellValue('M'.$j,$fetch['spppersuingtopurchase'])
				->setCellValue('N'.$j,$fetch['spporderclosed'])
				->setCellValue('O'.$j,$fetch['stonotviewed'])
				->setCellValue('P'.$j,$fetch['stounattended'])
				->setCellValue('Q'.$j,$fetch['stofake'])
				->setCellValue('R'.$j,$fetch['stonotinterested'])
				->setCellValue('S'.$j,$fetch['storegestereduser'])
				->setCellValue('T'.$j,$fetch['stoattended'])
				->setCellValue('U'.$j,$fetch['stodemogiven'])
				->setCellValue('V'.$j,$fetch['stoquotesent'])
				->setCellValue('W'.$j,$fetch['stopersuingtopurchase'])
				->setCellValue('X'.$j,$fetch['stoorderclosed'])
				->setCellValue('Y'.$j,$fetch['sacnotviewed'])
				->setCellValue('Z'.$j,$fetch['sacunattended'])
				->setCellValue('AA'.$j,$fetch['sacfake'])
				->setCellValue('AB'.$j,$fetch['sacnotinterested'])
				->setCellValue('AC'.$j,$fetch['sacregestereduser'])
				->setCellValue('AD'.$j,$fetch['sacattended'])
				->setCellValue('AE'.$j,$fetch['sacdemogiven'])
				->setCellValue('AF'.$j,$fetch['sacquotesent'])
				->setCellValue('AG'.$j,$fetch['sacpersuingtopurchase'])
				->setCellValue('AH'.$j,$fetch['sacorderclosed'])
				->setCellValue('AI'.$j,$fetch['othersnotviewed'])
				->setCellValue('AJ'.$j,$fetch['othersunattended'])
				->setCellValue('AK'.$j,$fetch['othersfake'])
				->setCellValue('AL'.$j,$fetch['othersnotinterested'])
				->setCellValue('AM'.$j,$fetch['othersregestereduser'])
				->setCellValue('AN'.$j,$fetch['othersattended'])
				->setCellValue('AO'.$j,$fetch['othersdemogiven'])
				->setCellValue('AP'.$j,$fetch['othersquotesent'])
				->setCellValue('AQ'.$j,$fetch['otherspersuingtopurchase'])
				->setCellValue('AR'.$j,$fetch['othersorderclosed']);		
				$j++;
				//if()
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
		$mySheet->getColumnDimension('B')->setWidth(35);
		$mySheet->getColumnDimension('C')->setWidth(20);
		$mySheet->getColumnDimension('D')->setWidth(25);
		$mySheet->getColumnDimension('E')->setWidth(15);
		$mySheet->getColumnDimension('F')->setWidth(15);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(15);
		$mySheet->getColumnDimension('I')->setWidth(20);
		$mySheet->getColumnDimension('J')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(15);
		$mySheet->getColumnDimension('M')->setWidth(20);
		$mySheet->getColumnDimension('N')->setWidth(15);
		$mySheet->getColumnDimension('O')->setWidth(15);
		$mySheet->getColumnDimension('P')->setWidth(15);
		$mySheet->getColumnDimension('Q')->setWidth(15);
		$mySheet->getColumnDimension('R')->setWidth(15);
		$mySheet->getColumnDimension('S')->setWidth(15);
		$mySheet->getColumnDimension('T')->setWidth(15);
		$mySheet->getColumnDimension('U')->setWidth(15);
		$mySheet->getColumnDimension('V')->setWidth(15);
		$mySheet->getColumnDimension('W')->setWidth(20);
		$mySheet->getColumnDimension('X')->setWidth(15);
		$mySheet->getColumnDimension('Y')->setWidth(15);
		$mySheet->getColumnDimension('Z')->setWidth(15);
		$mySheet->getColumnDimension('AA')->setWidth(15);
		$mySheet->getColumnDimension('AB')->setWidth(15);
		$mySheet->getColumnDimension('AC')->setWidth(15);
		$mySheet->getColumnDimension('AD')->setWidth(15);
		$mySheet->getColumnDimension('AE')->setWidth(15);
		$mySheet->getColumnDimension('AF')->setWidth(15);
		$mySheet->getColumnDimension('AG')->setWidth(20);
		$mySheet->getColumnDimension('AH')->setWidth(15);
		$mySheet->getColumnDimension('AI')->setWidth(15);
		$mySheet->getColumnDimension('AJ')->setWidth(15);
		$mySheet->getColumnDimension('AK')->setWidth(15);
		$mySheet->getColumnDimension('AL')->setWidth(15);
		$mySheet->getColumnDimension('AM')->setWidth(15);
		$mySheet->getColumnDimension('AN')->setWidth(15);
		$mySheet->getColumnDimension('AO')->setWidth(15);
		$mySheet->getColumnDimension('AP')->setWidth(15);
		$mySheet->getColumnDimension('AQ')->setWidth(20);
		$mySheet->getColumnDimension('AR')->setWidth(15);
				
		// Insert logs on Dealer Date to excel
		$query1 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','37','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
		$result1 = runmysqlquery_log($query1);
		$filebasename = "LMS-DLRDATACHART-".$cookie_username."-".$date.".xls";
		
		
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
