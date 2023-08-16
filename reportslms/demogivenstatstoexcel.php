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
		$fromdemodate = changedateformat($_POST['demofromdate']);
		$todemodate = changedateformat($_POST['demotodate']);
		$attachpiece = 'from '.$_POST['demofromdate'].'  to '.$_POST['demotodate'].'';
		$leaddatepiece = "leads.leaddatetime between '".$fromdate."' and '".$todate."'";
		
		if($fromdemodate <> '' && $todemodate <> '')
			$updatedatepiece = "and updatelogs.updatedate between '".$fromdemodate."' and '".$todemodate."'";
		else 
			$updatedatepiece = "";
		
		$query = "select leads.leaduploadedby, NULLIF(count(products.category = 'SPP' OR NULL),0) as sppcount, NULLIF(count(products.category <> 'SPP' OR NULL),0) as nonsppcount
		from leads right join (select min(updatedate) as updatedate, leadstatus, leadid from lms_updatelogs where leadstatus in ('Perusing to Purchase','Order Closed','Quote Sent','Demo Given') group by leadid) updatelogs on updatelogs.leadid = leads.id
		left join products on leads.productid = products.id left join lms_users on lms_users.id = leads.leaduploadedby where ".$leaddatepiece."  ".$updatedatepiece." group by leads.leaduploadedby";
		
		
		//echo($query);
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
		$mySheet->getStyle('A3:D3')->applyFromArray($styleArray);		
		$mySheet->setTitle('Statistics');
		// Merge cells
		
		$mySheet->mergeCells('A1:D1');
		$mySheet->mergeCells('A2:D2');

		// To align the text to center.
		$mySheet->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$mySheet->getStyle('A2:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Demo Given Statistics ' .$attachpiece);
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);

		//File contents for Header Row
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A3', 'Sl No')
			->setCellValue('B3', 'Lead UploadedBy')
			->setCellValue('C3', 'SPP')
			->setCellValue('D3', 'Non SPP');
		$j = 4;
		$slno = 0;
		$currentrow = 4;
		$databeginrow = $currentrow;
		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) > 0)
		{
			while($fetch = mysqli_fetch_array($result))
			{
				//set_time_limit(20);
				$slno++;
				$leaduploadedby = getuserdisplayname($fetch['leaduploadedby']);
				$mySheet->setCellValue('A'.$j,$slno)
					->setCellValue('B'.$j,$leaduploadedby)
					->setCellValue('C'.$j,$fetch['sppcount'])
					->setCellValue('D'.$j,$fetch['nonsppcount']);
					$j++;
					$currentrow++;
			}
			//Insert Total
			$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow - 1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow - 1).")");
			$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
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
		$mySheet->getColumnDimension('C')->setWidth(10);
		$mySheet->getColumnDimension('D')->setWidth(10);
		
		// Insert logs on Lead Source upload stats to excel
		$query1 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','48','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
		$result1 = runmysqlquery_log($query1);
		
		$filebasename = "LMS-DEMOGIVENSTATS-".$cookie_username."-".$date.".xls";
		
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