<?php
ini_set('memory_limit', '2048M');
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

//PHPExcel
require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
require_once '../phpgeneration/PHPExcel/IOFactory.php';

$fromdate = changedateformat($_POST['fromdate']);
$todate = changedateformat($_POST['todate']);
$leadsource = $_POST['leadsource'];
$leadreference = $_POST['leadreference'];
$givenby = $_POST['givenby'];
$reporttype = $_POST['reporttype'];

$leadsourcepiece = ($leadsource == '')?"":("AND leads.source = '".$leadsource."'");
$leadreferencepiece = ($leadreference == '')?"":("AND leads.refer = '".$leadreference."'");
$leaduploadedby = ($givenby == '')?"":(($givenby == 'web')?"AND leaduploadedby IS NULL":"AND leaduploadedby = '".$givenby."'");
$attachpiece = 'from '.$_POST['fromdate'].'  to '.$_POST['todate'].'';
$leaddatepiece = "leads.leaddatetime between '".$fromdate."' and '".$todate."'";

switch($reporttype)
{
	case "reference":
	$query = "select leads.refer,NULLIF(count(leads.leadstatus = 'Not Viewed' or NULL),0) as notviewed,NULLIF(count(leads.leadstatus = 'UnAttended' or NULL),0) as unattended, NULLIF(count(leads.leadstatus = 'Fake Enquiry' or NULL),0) as fakeenquiry,NULLIF(count(leads.leadstatus = 'Not Interested'),0) as notinterested,NULLIF(count(leads.leadstatus = 'Registered User' or NULL),0) as registereduser,NULLIF(count(leads.leadstatus = 'Attended' or NULL),0) as attended,NULLIF(count(leads.leadstatus = 'Demo Given' or NULL),0) as demogiven, NULLIF(count(leads.leadstatus = 'Quote Sent' or NULL),0) as quotesent,NULLIF(count(leads.leadstatus = 'Perusing to Purchase' or NULL),0) as persuingtopurchase,NULLIF(count(leads.leadstatus = 'Order Closed' or NULL),0) as orderclosed from leads where ".$leaddatepiece."  ".$leadsourcepiece."  ".$leaduploadedby." ".$leadreferencepiece." and leads.refer <> '' group by leads.refer";
	$result = runmysqlquery($query);
	
	$query1 = "select leads.refer,NULLIF(count(leads.leadstatus = 'Not Viewed' or NULL),0) as notviewed,NULLIF(count(leads.leadstatus = 'UnAttended' or NULL),0) as unattended, NULLIF(count(leads.leadstatus = 'Fake Enquiry' or NULL),0) as fakeenquiry,NULLIF(count(leads.leadstatus = 'Not Interested'),0) as notinterested,NULLIF(count(leads.leadstatus = 'Registered User' or NULL),0) as registereduser,NULLIF(count(leads.leadstatus = 'Attended' or NULL),0) as attended,NULLIF(count(leads.leadstatus = 'Demo Given' or NULL),0) as demogiven, NULLIF(count(leads.leadstatus = 'Quote Sent' or NULL),0) as quotesent,NULLIF(count(leads.leadstatus = 'Perusing to Purchase' or NULL),0) as persuingtopurchase,NULLIF(count(leads.leadstatus = 'Order Closed' or NULL),0) as orderclosed from leads left join products on products.id = leads.productid where products.category = 'SPP' and ".$leaddatepiece."  ".$leadsourcepiece."  ".$leaduploadedby." ".$leadreferencepiece." and leads.refer <> '' group by leads.refer";
	$result1 = runmysqlquery($query1);
	
	
	$query2 = "select leads.refer,NULLIF(count(leads.leadstatus = 'Not Viewed' or NULL),0) as notviewed,NULLIF(count(leads.leadstatus = 'UnAttended' or NULL),0) as unattended, NULLIF(count(leads.leadstatus = 'Fake Enquiry' or NULL),0) as fakeenquiry,NULLIF(count(leads.leadstatus = 'Not Interested'),0) as notinterested,NULLIF(count(leads.leadstatus = 'Registered User' or NULL),0) as registereduser,NULLIF(count(leads.leadstatus = 'Attended' or NULL),0) as attended,NULLIF(count(leads.leadstatus = 'Demo Given' or NULL),0) as demogiven, NULLIF(count(leads.leadstatus = 'Quote Sent' or NULL),0) as quotesent,NULLIF(count(leads.leadstatus = 'Perusing to Purchase' or NULL),0) as persuingtopurchase,NULLIF(count(leads.leadstatus = 'Order Closed' or NULL),0) as orderclosed from leads left join products on products.id = leads.productid where products.category = 'STO' and ".$leaddatepiece."  ".$leadsourcepiece."  ".$leaduploadedby." ".$leadreferencepiece." and leads.refer <> '' group by leads.refer";
		$result2 = runmysqlquery($query2);
	
	$query3 = "select leads.refer,NULLIF(count(leads.leadstatus = 'Not Viewed' or NULL),0) as notviewed,NULLIF(count(leads.leadstatus = 'UnAttended' or NULL),0) as unattended, NULLIF(count(leads.leadstatus = 'Fake Enquiry' or NULL),0) as fakeenquiry,NULLIF(count(leads.leadstatus = 'Not Interested'),0) as notinterested,NULLIF(count(leads.leadstatus = 'Registered User' or NULL),0) as registereduser,NULLIF(count(leads.leadstatus = 'Attended' or NULL),0) as attended,NULLIF(count(leads.leadstatus = 'Demo Given' or NULL),0) as demogiven, NULLIF(count(leads.leadstatus = 'Quote Sent' or NULL),0) as quotesent,NULLIF(count(leads.leadstatus = 'Perusing to Purchase' or NULL),0) as persuingtopurchase,NULLIF(count(leads.leadstatus = 'Order Closed' or NULL),0) as orderclosed from leads left join products on products.id = leads.productid where products.category = 'SAC' and ".$leaddatepiece."  ".$leadsourcepiece."  ".$leaduploadedby." ".$leadreferencepiece." and leads.refer <> '' group by leads.refer";
		$result3 = runmysqlquery($query3);
	
	
	$query4 = "select leads.refer,NULLIF(count(leads.leadstatus = 'Not Viewed' or NULL),0) as notviewed,NULLIF(count(leads.leadstatus = 'UnAttended' or NULL),0) as unattended, NULLIF(count(leads.leadstatus = 'Fake Enquiry' or NULL),0) as fakeenquiry,NULLIF(count(leads.leadstatus = 'Not Interested'),0) as notinterested,NULLIF(count(leads.leadstatus = 'Registered User' or NULL),0) as registereduser,NULLIF(count(leads.leadstatus = 'Attended' or NULL),0) as attended,NULLIF(count(leads.leadstatus = 'Demo Given' or NULL),0) as demogiven, NULLIF(count(leads.leadstatus = 'Quote Sent' or NULL),0) as quotesent,NULLIF(count(leads.leadstatus = 'Perusing to Purchase' or NULL),0) as persuingtopurchase,NULLIF(count(leads.leadstatus = 'Order Closed' or NULL),0) as orderclosed from leads left join products on products.id = leads.productid where products.category = 'OTHERS' and ".$leaddatepiece."  ".$leadsourcepiece."  ".$leaduploadedby." ".$leadreferencepiece." and leads.refer <> '' group by leads.refer";
		$result4 = runmysqlquery($query4);
	
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	
	$pageindex = 0;
	//Set Active Sheet	
	$mySheet = $objPHPExcel->getActiveSheet();
	$mySheet->setTitle('All Products');
	$styleArray = array(
					'font' => array('bold' => true),
					'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '0099CCFF')),
					'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
				);
	$mySheet->getStyle('A3:L3')->applyFromArray($styleArray);
	$mySheet->mergeCells('A1:L1');
	$mySheet->mergeCells('A2:L2');
	$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
				->setCellValue('A2', 'Lead Status Chart '.$attachpiece);
	$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
	$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
	$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
	//File contents for Header Row
	$objPHPExcel->setActiveSheetIndex($pageindex)
		->setCellValue('A3', 'Sl No')
		->setCellValue('B3', 'Lead Reference')
		->setCellValue('C3', 'Not Viewed')
		->setCellValue('D3', 'Unattended')
		->setCellValue('E3', 'Fake Enquiry')
		->setCellValue('F3', 'Not Interested')
		->setCellValue('G3', 'Registered User')
		->setCellValue('H3', 'Attended')
		->setCellValue('I3', 'Demo Given')
		->setCellValue('J3', 'Quote Sent')
		->setCellValue('K3', 'Persuing to Purchase')
		->setCellValue('L3', 'Order Closed');
	$j = 4;
	$slno = 0;	
	$currentrow = 4;
	$databeginrow = $currentrow;
	while($fetch = mysqli_fetch_array($result))
	{
		//set_time_limit(20);
		$slno++;		
		$mySheet->setCellValue('A'.$j,$slno)
			->setCellValue('B'.$j,$fetch['refer'])
			->setCellValue('C'.$j,$fetch['notviewed'])
			->setCellValue('D'.$j,$fetch['unattended'])
			->setCellValue('E'.$j,$fetch['fakeenquiry'])
			->setCellValue('F'.$j,$fetch['notinterested'])
			->setCellValue('G'.$j,$fetch['registereduser'])
			->setCellValue('H'.$j,$fetch['attended'])
			->setCellValue('I'.$j,$fetch['demogiven'])
			->setCellValue('J'.$j,$fetch['quotesent'])
			->setCellValue('K'.$j,$fetch['persuingtopurchase'])
			->setCellValue('L'.$j,$fetch['orderclosed']);
			$j++;
			$currentrow++;
	}
	
	// TO add total
	
	$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow - 1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow - 1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")")
				->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow - 1).")")
				->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow - 1).")")
				->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow - 1).")")
				->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow - 1).")")
				->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow - 1).")");
	$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
	
	
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
	$mySheet->getColumnDimension('B')->setWidth(32);
	$mySheet->getColumnDimension('C')->setWidth(15);
	$mySheet->getColumnDimension('D')->setWidth(15);
	$mySheet->getColumnDimension('E')->setWidth(15);
	$mySheet->getColumnDimension('F')->setWidth(15);
	$mySheet->getColumnDimension('G')->setWidth(15);
	$mySheet->getColumnDimension('H')->setWidth(15);
	$mySheet->getColumnDimension('I')->setWidth(15);
	$mySheet->getColumnDimension('J')->setWidth(15);
	$mySheet->getColumnDimension('K')->setWidth(15);
	$mySheet->getColumnDimension('L')->setWidth(15);
	
		// Increment Sheet Index and add content
		
		// Content for SPP
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet($pageindex);
		
		//Set the worksheet name
		$mySheet->setTitle('SPP Products');
		
		$result1 = runmysqlquery($query1);
		$currentrow = 1;
		$slno1 = 0;
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'Lead Status Chart for SPP');
		
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A'.$currentrow,'SL No')
				->setCellValue('B'.$currentrow,'Lead Reference')
				->setCellValue('C'.$currentrow,'Not Viewed')
				->setCellValue('D'.$currentrow,'Unattended')
				->setCellValue('E'.$currentrow,'Fake Enquiry')
				->setCellValue('F'.$currentrow,'Not Interested')
				->setCellValue('G'.$currentrow,'Registered User')
				->setCellValue('H'.$currentrow,'Attended')
				->setCellValue('I'.$currentrow,'Demo Given')
				->setCellValue('J'.$currentrow,'Quote Sent')
				->setCellValue('K'.$currentrow,'Persuing to Purchase')
				->setCellValue('L'.$currentrow,'Order Closed');
		
		$j = 3;		
		//Apply style for header Row
		$mySheet->getStyle('A'.$currentrow.':L'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;
		
		while($fetchdetails = mysqli_fetch_array($result1))
		{
			
			$slno1++;
			//$leaduploadedbyname = getuserdisplayname($fetchdetails['leaduploadedby']);
			$mySheet->setCellValue('A'.$j,$slno1)
			->setCellValue('B'.$j,$fetchdetails['refer'])
			->setCellValue('C'.$j,$fetchdetails['notviewed'])
			->setCellValue('D'.$j,$fetchdetails['unattended'])
			->setCellValue('E'.$j,$fetchdetails['fakeenquiry'])
			->setCellValue('F'.$j,$fetchdetails['notinterested'])
			->setCellValue('G'.$j,$fetchdetails['registereduser'])
			->setCellValue('H'.$j,$fetchdetails['attended'])
			->setCellValue('I'.$j,$fetchdetails['demogiven'])
			->setCellValue('J'.$j,$fetchdetails['quotesent'])
			->setCellValue('K'.$j,$fetchdetails['persuingtopurchase'])
			->setCellValue('L'.$j,$fetchdetails['orderclosed']);
			$j++;
			$currentrow++;
		}
		// Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow - 1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow - 1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")")
				->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow - 1).")")
				->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow - 1).")")
				->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow - 1).")")
				->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow - 1).")")
				->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow - 1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
		
		//Apply style for Content row
		$mySheet->getStyle('A'.$databeginrow.':L'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('A')->setWidth(10);
		$mySheet->getColumnDimension('B')->setWidth(35);
		$mySheet->getColumnDimension('C')->setWidth(15);
		$mySheet->getColumnDimension('D')->setWidth(15);
		$mySheet->getColumnDimension('E')->setWidth(15);
		$mySheet->getColumnDimension('F')->setWidth(15);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(15);
		$mySheet->getColumnDimension('I')->setWidth(15);
		$mySheet->getColumnDimension('J')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(15);	
		
		// Increment Sheet Index and add content
		
		// Content for STO
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet($pageindex);
		
		//Set the worksheet name
		$mySheet->setTitle('STO Products');
		
		$result1 = runmysqlquery($query1);
		$currentrow = 1;
		$slno1 = 0;
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'Lead Status Chart for STO');
		
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A'.$currentrow,'SL No')
				->setCellValue('B'.$currentrow,'Lead Reference')
				->setCellValue('C'.$currentrow,'Not Viewed')
				->setCellValue('D'.$currentrow,'Unattended')
				->setCellValue('E'.$currentrow,'Fake Enquiry')
				->setCellValue('F'.$currentrow,'Not Interested')
				->setCellValue('G'.$currentrow,'Registered User')
				->setCellValue('H'.$currentrow,'Attended')
				->setCellValue('I'.$currentrow,'Demo Given')
				->setCellValue('J'.$currentrow,'Quote Sent')
				->setCellValue('K'.$currentrow,'Persuing to Purchase')
				->setCellValue('L'.$currentrow,'Order Closed');
		
		$j = 3;		
		//Apply style for header Row
		$mySheet->getStyle('A'.$currentrow.':L'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;
		
		while($fetchdetails = mysqli_fetch_array($result2))
		{
			
			$slno1++;
			$mySheet->setCellValue('A'.$j,$slno1)
			->setCellValue('B'.$j,$fetchdetails['refer'])
			->setCellValue('C'.$j,$fetchdetails['notviewed'])
			->setCellValue('D'.$j,$fetchdetails['unattended'])
			->setCellValue('E'.$j,$fetchdetails['fakeenquiry'])
			->setCellValue('F'.$j,$fetchdetails['notinterested'])
			->setCellValue('G'.$j,$fetchdetails['registereduser'])
			->setCellValue('H'.$j,$fetchdetails['attended'])
			->setCellValue('I'.$j,$fetchdetails['demogiven'])
			->setCellValue('J'.$j,$fetchdetails['quotesent'])
			->setCellValue('K'.$j,$fetchdetails['persuingtopurchase'])
			->setCellValue('L'.$j,$fetchdetails['orderclosed']);
			$j++;
			$currentrow++;
		}
		// Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow - 1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow - 1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")")
				->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow - 1).")")
				->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow - 1).")")
				->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow - 1).")")
				->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow - 1).")")
				->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow - 1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
		
		//Apply style for Content row
		$mySheet->getStyle('A'.$databeginrow.':L'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('A')->setWidth(10);
		$mySheet->getColumnDimension('B')->setWidth(35);
		$mySheet->getColumnDimension('C')->setWidth(15);
		$mySheet->getColumnDimension('D')->setWidth(15);
		$mySheet->getColumnDimension('E')->setWidth(15);
		$mySheet->getColumnDimension('F')->setWidth(15);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(15);
		$mySheet->getColumnDimension('I')->setWidth(15);
		$mySheet->getColumnDimension('J')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(15);	
		
		// Increment Sheet Index and add content
		
		// Content for SAC
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet($pageindex);
		
		//Set the worksheet name
		$mySheet->setTitle('SAC Products');
		
		$result1 = runmysqlquery($query1);
		$currentrow = 1;
		$slno1 = 0;
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'Lead Status Chart for SAC');
		
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A'.$currentrow,'SL No')
				->setCellValue('B'.$currentrow,'Lead Reference')
				->setCellValue('C'.$currentrow,'Not Viewed')
				->setCellValue('D'.$currentrow,'Unattended')
				->setCellValue('E'.$currentrow,'Fake Enquiry')
				->setCellValue('F'.$currentrow,'Not Interested')
				->setCellValue('G'.$currentrow,'Registered User')
				->setCellValue('H'.$currentrow,'Attended')
				->setCellValue('I'.$currentrow,'Demo Given')
				->setCellValue('J'.$currentrow,'Quote Sent')
				->setCellValue('K'.$currentrow,'Persuing to Purchase')
				->setCellValue('L'.$currentrow,'Order Closed');
		
		$j = 3;		
		//Apply style for header Row
		$mySheet->getStyle('A'.$currentrow.':L'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;
		
		while($fetchdetails = mysqli_fetch_array($result3))
		{
			
			$slno1++;
			$mySheet->setCellValue('A'.$j,$slno1)
			->setCellValue('B'.$j,$fetchdetails['refer'])
			->setCellValue('C'.$j,$fetchdetails['notviewed'])
			->setCellValue('D'.$j,$fetchdetails['unattended'])
			->setCellValue('E'.$j,$fetchdetails['fakeenquiry'])
			->setCellValue('F'.$j,$fetchdetails['notinterested'])
			->setCellValue('G'.$j,$fetchdetails['registereduser'])
			->setCellValue('H'.$j,$fetchdetails['attended'])
			->setCellValue('I'.$j,$fetchdetails['demogiven'])
			->setCellValue('J'.$j,$fetchdetails['quotesent'])
			->setCellValue('K'.$j,$fetchdetails['persuingtopurchase'])
			->setCellValue('L'.$j,$fetchdetails['orderclosed']);
			$j++;
			$currentrow++;
		}
		// Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow - 1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow - 1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")")
				->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow - 1).")")
				->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow - 1).")")
				->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow - 1).")")
				->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow - 1).")")
				->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow - 1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
		
		//Apply style for Content row
		$mySheet->getStyle('A'.$databeginrow.':L'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('A')->setWidth(10);
		$mySheet->getColumnDimension('B')->setWidth(35);
		$mySheet->getColumnDimension('C')->setWidth(15);
		$mySheet->getColumnDimension('D')->setWidth(15);
		$mySheet->getColumnDimension('E')->setWidth(15);
		$mySheet->getColumnDimension('F')->setWidth(15);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(15);
		$mySheet->getColumnDimension('I')->setWidth(15);
		$mySheet->getColumnDimension('J')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(15);	
		
		// Increment Sheet Index and add content
		
		// Content for Others
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet($pageindex);
		
		//Set the worksheet name
		$mySheet->setTitle('Others Chart');
		
		$result1 = runmysqlquery($query1);
		$currentrow = 1;
		$slno1 = 0;
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'Lead Status Chart for Others');
		
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A'.$currentrow,'SL No')
				->setCellValue('B'.$currentrow,'Lead Reference')
				->setCellValue('C'.$currentrow,'Not Viewed')
				->setCellValue('D'.$currentrow,'Unattended')
				->setCellValue('E'.$currentrow,'Fake Enquiry')
				->setCellValue('F'.$currentrow,'Not Interested')
				->setCellValue('G'.$currentrow,'Registered User')
				->setCellValue('H'.$currentrow,'Attended')
				->setCellValue('I'.$currentrow,'Demo Given')
				->setCellValue('J'.$currentrow,'Quote Sent')
				->setCellValue('K'.$currentrow,'Persuing to Purchase')
				->setCellValue('L'.$currentrow,'Order Closed');
		
		$j = 3;		
		//Apply style for header Row
		$mySheet->getStyle('A'.$currentrow.':L'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;
		
		
		while($fetchdetails = mysqli_fetch_array($result4))
		{
			
			$slno1++;
			$mySheet->setCellValue('A'.$j,$slno1)
			->setCellValue('B'.$j,$fetchdetails['refer'])
			->setCellValue('C'.$j,$fetchdetails['notviewed'])
			->setCellValue('D'.$j,$fetchdetails['unattended'])
			->setCellValue('E'.$j,$fetchdetails['fakeenquiry'])
			->setCellValue('F'.$j,$fetchdetails['notinterested'])
			->setCellValue('G'.$j,$fetchdetails['registereduser'])
			->setCellValue('H'.$j,$fetchdetails['attended'])
			->setCellValue('I'.$j,$fetchdetails['demogiven'])
			->setCellValue('J'.$j,$fetchdetails['quotesent'])
			->setCellValue('K'.$j,$fetchdetails['persuingtopurchase'])
			->setCellValue('L'.$j,$fetchdetails['orderclosed']);
			$j++;
			$currentrow++;
		}
		// Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow - 1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow - 1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")")
				->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow - 1).")")
				->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow - 1).")")
				->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow - 1).")")
				->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow - 1).")")
				->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow - 1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
		//Apply style for Content row
		$mySheet->getStyle('A'.$databeginrow.':L'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('A')->setWidth(10);
		$mySheet->getColumnDimension('B')->setWidth(35);
		$mySheet->getColumnDimension('C')->setWidth(15);
		$mySheet->getColumnDimension('D')->setWidth(15);
		$mySheet->getColumnDimension('E')->setWidth(15);
		$mySheet->getColumnDimension('F')->setWidth(15);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(15);
		$mySheet->getColumnDimension('I')->setWidth(15);
		$mySheet->getColumnDimension('J')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(15);	
		break;
		
	case "givenby":
		
		$query = "select leads.leaduploadedby,NULLIF(count(leads.leadstatus = 'Not Viewed' or NULL),0) as notviewed,NULLIF(count(leads.leadstatus = 'UnAttended' or NULL),0) as unattended, NULLIF(count(leads.leadstatus = 'Fake Enquiry' or NULL),0) as fakeenquiry,NULLIF(count(leads.leadstatus = 'Not Interested'),0) as notinterested,NULLIF(count(leads.leadstatus = 'Registered User' or NULL),0) as registereduser,NULLIF(count(leads.leadstatus = 'Attended' or NULL),0) as attended,NULLIF(count(leads.leadstatus = 'Demo Given' or NULL),0) as demogiven, NULLIF(count(leads.leadstatus = 'Quote Sent' or NULL),0) as quotesent,NULLIF(count(leads.leadstatus = 'Perusing to Purchase' or NULL),0) as persuingtopurchase,NULLIF(count(leads.leadstatus = 'Order Closed' or NULL),0) as orderclosed from leads left join products on products.id = leads.productid where ".$leaddatepiece."  ".$leadsourcepiece."  ".$leaduploadedby." ".$leadreferencepiece." group by leads.leaduploadedby";
		$result = runmysqlquery($query);
		
		
		$query1 = "select leads.leaduploadedby,NULLIF(count(leads.leadstatus = 'Not Viewed' or NULL),0) as notviewed,NULLIF(count(leads.leadstatus = 'UnAttended' or NULL),0) as unattended, NULLIF(count(leads.leadstatus = 'Fake Enquiry' or NULL),0) as fakeenquiry,NULLIF(count(leads.leadstatus = 'Not Interested'),0) as notinterested,NULLIF(count(leads.leadstatus = 'Registered User' or NULL),0) as registereduser,NULLIF(count(leads.leadstatus = 'Attended' or NULL),0) as attended,NULLIF(count(leads.leadstatus = 'Demo Given' or NULL),0) as demogiven, NULLIF(count(leads.leadstatus = 'Quote Sent' or NULL),0) as quotesent,NULLIF(count(leads.leadstatus = 'Perusing to Purchase' or NULL),0) as persuingtopurchase,NULLIF(count(leads.leadstatus = 'Order Closed' or NULL),0) as orderclosed from leads left join products on products.id = leads.productid where products.category = 'SPP' and ".$leaddatepiece."  ".$leadsourcepiece."  ".$leaduploadedby." ".$leadreferencepiece." group by leads.leaduploadedby";
		$result1 = runmysqlquery($query1);
	
	
		$query2 = "select leads.leaduploadedby,NULLIF(count(leads.leadstatus = 'Not Viewed' or NULL),0) as notviewed,NULLIF(count(leads.leadstatus = 'UnAttended' or NULL),0) as unattended, NULLIF(count(leads.leadstatus = 'Fake Enquiry' or NULL),0) as fakeenquiry,NULLIF(count(leads.leadstatus = 'Not Interested'),0) as notinterested,NULLIF(count(leads.leadstatus = 'Registered User' or NULL),0) as registereduser,NULLIF(count(leads.leadstatus = 'Attended' or NULL),0) as attended,NULLIF(count(leads.leadstatus = 'Demo Given' or NULL),0) as demogiven, NULLIF(count(leads.leadstatus = 'Quote Sent' or NULL),0) as quotesent,NULLIF(count(leads.leadstatus = 'Perusing to Purchase' or NULL),0) as persuingtopurchase,NULLIF(count(leads.leadstatus = 'Order Closed' or NULL),0) as orderclosed from leads left join products on products.id = leads.productid where products.category = 'STO' and ".$leaddatepiece."  ".$leadsourcepiece."  ".$leaduploadedby." ".$leadreferencepiece." group by leads.leaduploadedby";
		$result2 = runmysqlquery($query2);
	
		$query3 = "select leads.leaduploadedby,NULLIF(count(leads.leadstatus = 'Not Viewed' or NULL),0) as notviewed,NULLIF(count(leads.leadstatus = 'UnAttended' or NULL),0) as unattended, NULLIF(count(leads.leadstatus = 'Fake Enquiry' or NULL),0) as fakeenquiry,NULLIF(count(leads.leadstatus = 'Not Interested'),0) as notinterested,NULLIF(count(leads.leadstatus = 'Registered User' or NULL),0) as registereduser,NULLIF(count(leads.leadstatus = 'Attended' or NULL),0) as attended,NULLIF(count(leads.leadstatus = 'Demo Given' or NULL),0) as demogiven, NULLIF(count(leads.leadstatus = 'Quote Sent' or NULL),0) as quotesent,NULLIF(count(leads.leadstatus = 'Perusing to Purchase' or NULL),0) as persuingtopurchase,NULLIF(count(leads.leadstatus = 'Order Closed' or NULL),0) as orderclosed from leads left join products on products.id = leads.productid where products.category = 'SAC' and ".$leaddatepiece."  ".$leadsourcepiece."  ".$leaduploadedby." ".$leadreferencepiece." group by leads.leaduploadedby";
		$result3 = runmysqlquery($query3);
	
	
		$query4 = "select leads.leaduploadedby,NULLIF(count(leads.leadstatus = 'Not Viewed' or NULL),0) as notviewed,NULLIF(count(leads.leadstatus = 'UnAttended' or NULL),0) as unattended, NULLIF(count(leads.leadstatus = 'Fake Enquiry' or NULL),0) as fakeenquiry,NULLIF(count(leads.leadstatus = 'Not Interested'),0) as notinterested,NULLIF(count(leads.leadstatus = 'Registered User' or NULL),0) as registereduser,NULLIF(count(leads.leadstatus = 'Attended' or NULL),0) as attended,NULLIF(count(leads.leadstatus = 'Demo Given' or NULL),0) as demogiven, NULLIF(count(leads.leadstatus = 'Quote Sent' or NULL),0) as quotesent,NULLIF(count(leads.leadstatus = 'Perusing to Purchase' or NULL),0) as persuingtopurchase,NULLIF(count(leads.leadstatus = 'Order Closed' or NULL),0) as orderclosed from leads left join products on products.id = leads.productid where products.category = 'OTHERS' and ".$leaddatepiece."  ".$leadsourcepiece."  ".$leaduploadedby." ".$leadreferencepiece." group by leads.leaduploadedby";
		$result4 = runmysqlquery($query4);
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		$pageindex = 0;
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		$mySheet->setTitle('All Products');
		$styleArray = array(
						'font' => array('bold' => true),
						'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '0099CCFF')),
						'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
					);
		$mySheet->getStyle('A3:L3')->applyFromArray($styleArray);
		$mySheet->mergeCells('A1:L1');
		$mySheet->mergeCells('A2:L2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Lead Status Chart '.$attachpiece);
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
		
		//File contents for Header Row
		$objPHPExcel->setActiveSheetIndex($pageindex)
			->setCellValue('A3', 'Sl No')
			->setCellValue('B3', 'Lead UploadedBy')
			->setCellValue('C3', 'Not Viewed')
			->setCellValue('D3', 'Unattended')
			->setCellValue('E3', 'Fake Enquiry')
			->setCellValue('F3', 'Not Interested')
			->setCellValue('G3', 'Registered User')
			->setCellValue('H3', 'Attended')
			->setCellValue('I3', 'Demo Given')
			->setCellValue('J3', 'Quote Sent')
			->setCellValue('K3', 'Persuing to Purchase')
			->setCellValue('L3', 'Order Closed');
		$j = 4;
		$slno = 0;	
		$currentrow = 4;
		$databeginrow = $currentrow;
		while($fetch = mysqli_fetch_array($result))
		{
			//set_time_limit(20);
			$leaduploadedby = getuserdisplayname($fetch['leaduploadedby']);
			$slno++;		
			$mySheet->setCellValue('A'.$j,$slno)
				->setCellValue('B'.$j,$leaduploadedby)
				->setCellValue('C'.$j,$fetch['notviewed'])
				->setCellValue('D'.$j,$fetch['unattended'])
				->setCellValue('E'.$j,$fetch['fakeenquiry'])
				->setCellValue('F'.$j,$fetch['notinterested'])
				->setCellValue('G'.$j,$fetch['registereduser'])
				->setCellValue('H'.$j,$fetch['attended'])
				->setCellValue('I'.$j,$fetch['demogiven'])
				->setCellValue('J'.$j,$fetch['quotesent'])
				->setCellValue('K'.$j,$fetch['persuingtopurchase'])
				->setCellValue('L'.$j,$fetch['orderclosed']);
				$j++;
				$currentrow++;
		}
		
		// TO add total
		
		$mySheet->setCellValue('B'.$currentrow,'Total')
					->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow - 1).")")
					->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow - 1).")")
					->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
					->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
					->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")")
					->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow - 1).")")
					->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow - 1).")")
					->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow - 1).")")
					->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow - 1).")")
					->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow - 1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
		
		
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
		$mySheet->getColumnDimension('B')->setWidth(32);
		$mySheet->getColumnDimension('C')->setWidth(15);
		$mySheet->getColumnDimension('D')->setWidth(15);
		$mySheet->getColumnDimension('E')->setWidth(15);
		$mySheet->getColumnDimension('F')->setWidth(15);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(15);
		$mySheet->getColumnDimension('I')->setWidth(15);
		$mySheet->getColumnDimension('J')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(15);
		
		// Increment Sheet Index and add content
		
		// Content for SPP
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet($pageindex);
		
		//Set the worksheet name
		$mySheet->setTitle('SPP Products');
		
		$result1 = runmysqlquery($query1);
		$currentrow = 1;
		$slno1 = 0;
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'Lead Status Chart for SPP');
		
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A'.$currentrow,'SL No')
				->setCellValue('B'.$currentrow,'Lead UploadedBy')
				->setCellValue('C'.$currentrow,'Not Viewed')
				->setCellValue('D'.$currentrow,'Unattended')
				->setCellValue('E'.$currentrow,'Fake Enquiry')
				->setCellValue('F'.$currentrow,'Not Interested')
				->setCellValue('G'.$currentrow,'Registered User')
				->setCellValue('H'.$currentrow,'Attended')
				->setCellValue('I'.$currentrow,'Demo Given')
				->setCellValue('J'.$currentrow,'Quote Sent')
				->setCellValue('K'.$currentrow,'Persuing to Purchase')
				->setCellValue('L'.$currentrow,'Order Closed');
		
		$j = 3;		
		//Apply style for header Row
		$mySheet->getStyle('A'.$currentrow.':L'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;
		
		while($fetchdetails = mysqli_fetch_array($result1))
		{
			
			$slno1++;
			$leaduploadedby = getuserdisplayname($fetchdetails['leaduploadedby']);		
			$mySheet->setCellValue('A'.$j,$slno1)
			->setCellValue('B'.$j,$leaduploadedby)
			->setCellValue('C'.$j,$fetchdetails['notviewed'])
			->setCellValue('D'.$j,$fetchdetails['unattended'])
			->setCellValue('E'.$j,$fetchdetails['fakeenquiry'])
			->setCellValue('F'.$j,$fetchdetails['notinterested'])
			->setCellValue('G'.$j,$fetchdetails['registereduser'])
			->setCellValue('H'.$j,$fetchdetails['attended'])
			->setCellValue('I'.$j,$fetchdetails['demogiven'])
			->setCellValue('J'.$j,$fetchdetails['quotesent'])
			->setCellValue('K'.$j,$fetchdetails['persuingtopurchase'])
			->setCellValue('L'.$j,$fetchdetails['orderclosed']);
			$j++;
			$currentrow++;
		}
		// Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow - 1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow - 1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")")
				->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow - 1).")")
				->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow - 1).")")
				->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow - 1).")")
				->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow - 1).")")
				->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow - 1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
		
		//Apply style for Content row
		$mySheet->getStyle('A'.$databeginrow.':L'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('A')->setWidth(10);
		$mySheet->getColumnDimension('B')->setWidth(35);
		$mySheet->getColumnDimension('C')->setWidth(15);
		$mySheet->getColumnDimension('D')->setWidth(15);
		$mySheet->getColumnDimension('E')->setWidth(15);
		$mySheet->getColumnDimension('F')->setWidth(15);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(15);
		$mySheet->getColumnDimension('I')->setWidth(15);
		$mySheet->getColumnDimension('J')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(15);	
		
		// Increment Sheet Index and add content
		
		// Content for STO
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet($pageindex);
		
		//Set the worksheet name
		$mySheet->setTitle('STO Products');
		
		$result1 = runmysqlquery($query1);
		$currentrow = 1;
		$slno1 = 0;
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'Lead Status Chart for STO');
		
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A'.$currentrow,'SL No')
				->setCellValue('B'.$currentrow,'Lead UploadedBy')
				->setCellValue('C'.$currentrow,'Not Viewed')
				->setCellValue('D'.$currentrow,'Unattended')
				->setCellValue('E'.$currentrow,'Fake Enquiry')
				->setCellValue('F'.$currentrow,'Not Interested')
				->setCellValue('G'.$currentrow,'Registered User')
				->setCellValue('H'.$currentrow,'Attended')
				->setCellValue('I'.$currentrow,'Demo Given')
				->setCellValue('J'.$currentrow,'Quote Sent')
				->setCellValue('K'.$currentrow,'Persuing to Purchase')
				->setCellValue('L'.$currentrow,'Order Closed');
		
		$j = 3;		
		//Apply style for header Row
		$mySheet->getStyle('A'.$currentrow.':L'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;
		
		while($fetchdetails = mysqli_fetch_array($result2))
		{
			
			$slno1++;
			$leaduploadedby = getuserdisplayname($fetchdetails['leaduploadedby']);		
			$mySheet->setCellValue('A'.$j,$slno1)
			->setCellValue('B'.$j,$leaduploadedby)
			->setCellValue('C'.$j,$fetchdetails['notviewed'])
			->setCellValue('D'.$j,$fetchdetails['unattended'])
			->setCellValue('E'.$j,$fetchdetails['fakeenquiry'])
			->setCellValue('F'.$j,$fetchdetails['notinterested'])
			->setCellValue('G'.$j,$fetchdetails['registereduser'])
			->setCellValue('H'.$j,$fetchdetails['attended'])
			->setCellValue('I'.$j,$fetchdetails['demogiven'])
			->setCellValue('J'.$j,$fetchdetails['quotesent'])
			->setCellValue('K'.$j,$fetchdetails['persuingtopurchase'])
			->setCellValue('L'.$j,$fetchdetails['orderclosed']);
			$j++;
			$currentrow++;
		}
		// Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow - 1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow - 1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")")
				->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow - 1).")")
				->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow - 1).")")
				->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow - 1).")")
				->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow - 1).")")
				->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow - 1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
		
		//Apply style for Content row
		$mySheet->getStyle('A'.$databeginrow.':L'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('A')->setWidth(10);
		$mySheet->getColumnDimension('B')->setWidth(35);
		$mySheet->getColumnDimension('C')->setWidth(15);
		$mySheet->getColumnDimension('D')->setWidth(15);
		$mySheet->getColumnDimension('E')->setWidth(15);
		$mySheet->getColumnDimension('F')->setWidth(15);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(15);
		$mySheet->getColumnDimension('I')->setWidth(15);
		$mySheet->getColumnDimension('J')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(15);		
		
		// Increment Sheet Index and add content
		
		// Content for SAC
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet($pageindex);
		
		//Set the worksheet name
		$mySheet->setTitle('SAC Products');
		
		$result1 = runmysqlquery($query1);
		$currentrow = 1;
		$slno1 = 0;
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'Lead Status Chart for SAC');
		
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A'.$currentrow,'SL No')
				->setCellValue('B'.$currentrow,'Lead UploadedBy')
				->setCellValue('C'.$currentrow,'Not Viewed')
				->setCellValue('D'.$currentrow,'Unattended')
				->setCellValue('E'.$currentrow,'Fake Enquiry')
				->setCellValue('F'.$currentrow,'Not Interested')
				->setCellValue('G'.$currentrow,'Registered User')
				->setCellValue('H'.$currentrow,'Attended')
				->setCellValue('I'.$currentrow,'Demo Given')
				->setCellValue('J'.$currentrow,'Quote Sent')
				->setCellValue('K'.$currentrow,'Persuing to Purchase')
				->setCellValue('L'.$currentrow,'Order Closed');
		
		$j = 3;		
		//Apply style for header Row
		$mySheet->getStyle('A'.$currentrow.':L'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;
		
		while($fetchdetails = mysqli_fetch_array($result3))
		{
			
			$slno1++;
			$leaduploadedby = getuserdisplayname($fetchdetails['leaduploadedby']);		
			$mySheet->setCellValue('A'.$j,$slno1)
			->setCellValue('B'.$j,$leaduploadedby)
			->setCellValue('C'.$j,$fetchdetails['notviewed'])
			->setCellValue('D'.$j,$fetchdetails['unattended'])
			->setCellValue('E'.$j,$fetchdetails['fakeenquiry'])
			->setCellValue('F'.$j,$fetchdetails['notinterested'])
			->setCellValue('G'.$j,$fetchdetails['registereduser'])
			->setCellValue('H'.$j,$fetchdetails['attended'])
			->setCellValue('I'.$j,$fetchdetails['demogiven'])
			->setCellValue('J'.$j,$fetchdetails['quotesent'])
			->setCellValue('K'.$j,$fetchdetails['persuingtopurchase'])
			->setCellValue('L'.$j,$fetchdetails['orderclosed']);
			$j++;
			$currentrow++;
		}
		// Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow - 1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow - 1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")")
				->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow - 1).")")
				->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow - 1).")")
				->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow - 1).")")
				->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow - 1).")")
				->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow - 1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
		
		//Apply style for Content row
		$mySheet->getStyle('A'.$databeginrow.':L'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('A')->setWidth(10);
		$mySheet->getColumnDimension('B')->setWidth(35);
		$mySheet->getColumnDimension('C')->setWidth(15);
		$mySheet->getColumnDimension('D')->setWidth(15);
		$mySheet->getColumnDimension('E')->setWidth(15);
		$mySheet->getColumnDimension('F')->setWidth(15);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(15);
		$mySheet->getColumnDimension('I')->setWidth(15);
		$mySheet->getColumnDimension('J')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(15);		
		
		// Increment Sheet Index and add content
		
		// Content for Others
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet($pageindex);
		
		//Set the worksheet name
		$mySheet->setTitle('OTHER Products');
		
		$result1 = runmysqlquery($query1);
		$currentrow = 1;
		$slno1 = 0;
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'Lead Status Chart for OTHERS');
		
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A'.$currentrow,'SL No')
				->setCellValue('B'.$currentrow,'Lead UploadedBy')
				->setCellValue('C'.$currentrow,'Not Viewed')
				->setCellValue('D'.$currentrow,'Unattended')
				->setCellValue('E'.$currentrow,'Fake Enquiry')
				->setCellValue('F'.$currentrow,'Not Interested')
				->setCellValue('G'.$currentrow,'Registered User')
				->setCellValue('H'.$currentrow,'Attended')
				->setCellValue('I'.$currentrow,'Demo Given')
				->setCellValue('J'.$currentrow,'Quote Sent')
				->setCellValue('K'.$currentrow,'Persuing to Purchase')
				->setCellValue('L'.$currentrow,'Order Closed');
		
		$j = 3;		
		//Apply style for header Row
		$mySheet->getStyle('A'.$currentrow.':L'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;
		
		while($fetchdetails = mysqli_fetch_array($result4))
		{
			
			$slno1++;
			$leaduploadedby = getuserdisplayname($fetchdetails['leaduploadedby']);		
			$mySheet->setCellValue('A'.$j,$slno1)
			->setCellValue('B'.$j,$leaduploadedby)
			->setCellValue('C'.$j,$fetchdetails['notviewed'])
			->setCellValue('D'.$j,$fetchdetails['unattended'])
			->setCellValue('E'.$j,$fetchdetails['fakeenquiry'])
			->setCellValue('F'.$j,$fetchdetails['notinterested'])
			->setCellValue('G'.$j,$fetchdetails['registereduser'])
			->setCellValue('H'.$j,$fetchdetails['attended'])
			->setCellValue('I'.$j,$fetchdetails['demogiven'])
			->setCellValue('J'.$j,$fetchdetails['quotesent'])
			->setCellValue('K'.$j,$fetchdetails['persuingtopurchase'])
			->setCellValue('L'.$j,$fetchdetails['orderclosed']);
			$j++;
			$currentrow++;
		}
		// Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow - 1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow - 1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")")
				->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow - 1).")")
				->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow - 1).")")
				->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow - 1).")")
				->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow - 1).")")
				->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow - 1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
		
		//Apply style for Content row
		$mySheet->getStyle('A'.$databeginrow.':L'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('A')->setWidth(10);
		$mySheet->getColumnDimension('B')->setWidth(35);
		$mySheet->getColumnDimension('C')->setWidth(15);
		$mySheet->getColumnDimension('D')->setWidth(15);
		$mySheet->getColumnDimension('E')->setWidth(15);
		$mySheet->getColumnDimension('F')->setWidth(15);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(15);
		$mySheet->getColumnDimension('I')->setWidth(15);
		$mySheet->getColumnDimension('J')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(15);		
		
		break;
}
		
	// Insert logs Manager List to excel
		$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','49','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
		$result = runmysqlquery_log($query);
		
		//Create a File name syntax
		$date = datetimelocal('YmdHis');
		$filebasename = "LEAD-STATUS-REPORT".$cookie_username."-".$date.".xls";
		
		if($_SERVER['HTTP_HOST'] == 'archanaab')  
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
?>