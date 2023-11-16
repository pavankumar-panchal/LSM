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
	
	$fromdate = changedateformat($_REQUEST['fromdate']);
	$todate = changedateformat($_REQUEST['todate']);
	$dealerid = $_REQUEST['dealerid'];
	$givenby = $_REQUEST['givenby'];
	$productid = $_REQUEST['productid'];
	$leadstatus = $_REQUEST['leadstatus']; 
	$filter_followupdate1 = $_REQUEST['filter_followupdate1hdn'];
	$filter_followupdate2 = $_REQUEST['filter_followupdate2hdn'];
	$dropterminatedstatus = $_REQUEST['dropterminatedstatus'];
	$attachpiece = 'from '.$_REQUEST['fromdate'].'  to '.$_REQUEST['todate'].'';	
	if($filter_followupdate1 == 'dontconsider')
	{
		$followuppiece = "";
	}
	else
	{
		/*$followuppiece = "AND lms_followup.followupdate >= '".changedateformat($filter_followupdate1)."' AND lms_followup.followupdate <= '".changedateformat($filter_followupdate2)."'";*/
		$leadquery0 = "select leadid from lms_followup 
		where lms_followup.followupdate >= '".changedateformat($filter_followupdate1)."' 
		AND lms_followup.followupdate <= '".changedateformat($filter_followupdate2)."' 
		AND followupstatus = 'PENDING'";
		$leadresult0 = runmysqlquery($leadquery0);
		$count = mysqli_num_rows($leadresult0);
		if($count > 0)
		{
			while($leadfetch0 = mysqli_fetch_array($leadresult0))
			{
				$follow[] = "'".$leadfetch0['leadid']."'";
			}
			$followupvalues = implode(",",$follow);
			$followuppiece = " AND lms_followup.leadid in (" . $followupvalues . ")";
		}
		
		else
		{
		   $followuppiece = "AND lms_followup.leadid = ''";
		}
	}
	
	$dealerpiece = ($dealerid == '')?"":("AND leads.dealerid = '".$dealerid."'");
	$productpiece = ($productid == '')?"":("AND productid = '".$productid."'");
	$leadstatuspiece = ($leadstatus == '')?"":("AND leadstatus = '".$leadstatus."'");
	//$leaduploadedby = ($givenby == '')?"":(($givenby == 'web')?"AND leaduploadedby IS NULL":"AND leaduploadedby = '".$givenby."'");
	$leaduploadedby = "AND leaduploadedby = '".$userslno."'";
	$datetimepiece = "substring(leads.leaddatetime,1,10) between '".$fromdate."' AND  '".$todate."'"; 
	$terminatedstatuspiece = ($dropterminatedstatus == 'true')?("AND leads.leadstatus <> 'Order Closed' AND leads.leadstatus <> 'Not Interested' AND leads.leadstatus <> 'Fake Enquiry' AND leads.leadstatus <> 'Registered User'"):"";

	if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '' && checkdateformat($fromdate) && checkdateformat($todate) && ((datenumeric($todate) - datenumeric($fromdate)) >= 0))
	{
		//Check who is making the entry
		$cookie_username = lmsgetcookie('lmsusername');
		$cookie_usertype = lmsgetcookie('lmsusersort');

		$query ="select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,leads.address,leads.place,regions.distname, regions.statename,leads.refer,dealers.dlrcompanyname,lms_managers.mgrname,lms_followup.remarks from leads left join lms_users on lms_users.id = leads.leaduploadedby left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode left join lms_followup on lms_followup.leadid = leads.id where ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." ".$leaduploadedby." ".$leadstatuspiece."  AND leads.source = 'Manual Upload' AND lms_users.username = '".$cookie_username."' AND lms_users.type = '".$cookie_usertype."' ORDER BY leads.id DESC";  
		//echo($query);exit;

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
		//Apply style for header Row
		if($followuppiece <> '')	
		{
			$mySheet->getStyle('A3:Q3')->applyFromArray($styleArray);
		}
		else
			$mySheet->getStyle('A3:P3')->applyFromArray($styleArray);	
		//Merge the cell
		$mySheet->mergeCells('A1:P1');
		$mySheet->mergeCells('A2:P2');

		/*// To align the text to center.
		$mySheet->getStyle('A1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$mySheet->getStyle('A2:P2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Leads'.' '.$attachpiece);
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
		
		//File contents for Header Row
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A3', 'Sl No')
			->setCellValue('B3', 'Lead Id')
			->setCellValue('C3', 'Lead Date')
			->setCellValue('D3', 'Product')
			->setCellValue('E3', 'Company')
			->setCellValue('F3', 'Contact')
			->setCellValue('G3', 'Phone')
			->setCellValue('H3', 'Cell')
			->setCellValue('I3', 'Emailid')
			->setCellValue('J3', 'Address')
			->setCellValue('K3', 'Place')
			->setCellValue('L3', 'District')
			->setCellValue('M3', 'State')
			->setCellValue('N3', 'Reference')
			->setCellValue('O3', 'Dealer')
			->setCellValue('P3', 'Manager');
		if($followuppiece <> '')
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q3', 'Remarks');
		
		$j = 4;
		$slno = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			//set_time_limit(20);
			$slno++;
			$mySheet->setCellValue('A'.$j,$slno)
				->setCellValue('B'.$j,$fetch['id'])
				->setCellValue('C'.$j,changedateformatwithtime($fetch['leaddatetime']))
				->setCellValue('D'.$j,$fetch['productname'])
				->setCellValue('E'.$j,$fetch['company'])
				->setCellValue('F'.$j,$fetch['name'])
				->setCellValue('G'.$j,$fetch['phone'])
				->setCellValue('H'.$j,$fetch['cell'])
				->setCellValue('I'.$j,$fetch['emailid'])
				->setCellValue('J'.$j,$fetch['address'])
				->setCellValue('K'.$j,$fetch['place'])
				->setCellValue('L'.$j,$fetch['distname'])
				->setCellValue('M'.$j,$fetch['statename'])
				->setCellValue('N'.$j,$fetch['refer'])
				->setCellValue('O'.$j,$fetch['dlrcompanyname'])
				->setCellValue('P'.$j,$fetch['mgrname']);
				if($followuppiece <> '')
				$mySheet->setCellValue('Q'.$j,$fetch['remarks']);
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
		$mySheet->getColumnDimension('B')->setWidth(15);
		$mySheet->getColumnDimension('C')->setWidth(20);
		$mySheet->getColumnDimension('D')->setWidth(25);
		$mySheet->getColumnDimension('E')->setWidth(25);
		$mySheet->getColumnDimension('F')->setWidth(30);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(18);
		$mySheet->getColumnDimension('I')->setWidth(43);
		$mySheet->getColumnDimension('J')->setWidth(50);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(25);
		$mySheet->getColumnDimension('M')->setWidth(26);
		$mySheet->getColumnDimension('N')->setWidth(25);
		$mySheet->getColumnDimension('O')->setWidth(35);
		$mySheet->getColumnDimension('P')->setWidth(30);
		if($followuppiece <> '')
			$mySheet->getColumnDimension('Q')->setWidth(100);
		
		
		// Insert logs on lead followup
		$query1 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','48','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
		$result = runmysqlquery_log($query1);
		
		$filebasename = "LMS-VIEW-GIVEN-LEADS-".$cookie_username."-".$date.".xls";
		$filepath = $_SERVER['DOCUMENT_ROOT'].'/filescreated/'.$filebasename;
		$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].'/filescreated/'.$filebasename;
		/*$filepath = $_SERVER['DOCUMENT_ROOT'].'/LMS/filescreated/'.$filebasename;
		$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].'/lms/filescreated/'.$filebasename;*/
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		$objWriter->save($filepath);					//echo('here'); exit;

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
