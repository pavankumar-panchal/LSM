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
	
	//Check who is making the entry
	$cookie_username = lmsgetcookie('lmsusername');
	$cookie_usertype = lmsgetcookie('lmsusersort');
	
	//Get the values submitted
	$searchtext = $_POST['searchcriteria'];
	$subselection = $_POST['databasefield'];
	$managedarea = $_POST['managedarea'];
	$disablelogin = $_POST['disablelogin'];
	
	$managedareapiece = ($managedarea == '')?"":("AND lms_managers.managedarea = '".$managedarea."'");
	$disableloginpiece = ($disablelogin == '')?"":("AND lms_users.disablelogin = '".$disablelogin."'");
		
	switch($subselection)
	{
		case "mgrid":
		$query = "select lms_managers.id AS id, lms_managers.mgrname AS mgrname, lms_managers.mgrlocation AS mgrlocation, lms_managers.mgremailid AS mgremailid, lms_managers.mgrcell AS mgrcell, lms_users.username AS mgrusername, lms_managers.managedarea,lms_users.disablelogin from lms_managers join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_managers.id like '%".$searchtext."%' ".$managedareapiece." ".$disableloginpiece." ORDER BY lms_managers.mgrname";
			break;
		case "name":
		$query = "select lms_managers.id AS id, lms_managers.mgrname AS mgrname, lms_managers.mgrlocation AS mgrlocation, lms_managers.mgremailid AS mgremailid, lms_managers.mgrcell AS mgrcell, lms_users.username AS mgrusername, lms_managers.managedarea,lms_users.disablelogin from lms_managers join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_managers.mgrname like '%".$searchtext."%' ".$managedareapiece." ".$disableloginpiece." ORDER BY lms_managers.mgrname";
			break;
		case "location":
		$query = "select lms_managers.id AS id, lms_managers.mgrname AS mgrname, lms_managers.mgrlocation AS mgrlocation, lms_managers.mgremailid AS mgremailid, lms_managers.mgrcell AS mgrcell, lms_users.username AS mgrusername, lms_managers.managedarea,lms_users.disablelogin from lms_managers join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_managers.mgrlocation like '%".$searchtext."%' ".$managedareapiece." ".$disableloginpiece." ORDER BY lms_managers.mgrname";
			break;
		case "email":
		$query = "select lms_managers.id AS id, lms_managers.mgrname AS mgrname, lms_managers.mgrlocation AS mgrlocation, lms_managers.mgremailid AS mgremailid, lms_managers.mgrcell AS mgrcell, lms_users.username AS mgrusername, lms_managers.managedarea,lms_users.disablelogin from lms_managers join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_managers.mgremailid like '%".$searchtext."%' ".$managedareapiece." ".$disableloginpiece." ORDER BY lms_managers.mgrname";
			break;
		case "cell":
		$query = "select lms_managers.id AS id, lms_managers.mgrname AS mgrname, lms_managers.mgrlocation AS mgrlocation, lms_managers.mgremailid AS mgremailid, lms_managers.mgrcell AS mgrcell, lms_users.username AS mgrusername, lms_managers.managedarea,lms_users.disablelogin from lms_managers join lms_users on lms_users.referenceid = lms_managers.id AND lms_users.type = 'Reporting Authority' WHERE lms_managers.mgrcell like '%".$searchtext."%' ".$managedareapiece." ".$disableloginpiece." ORDER BY lms_managers.mgrname";
			break;
	}
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
	$mySheet->getStyle('A3:I3')->applyFromArray($styleArray);	
	//Merge the cell
	$mySheet->mergeCells('A1:I1');
	$mySheet->mergeCells('A2:I2');
	// To align the text to center.
	$mySheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$mySheet->getStyle('A2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
				->setCellValue('A2', 'Manager Details');
	$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
	$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
	$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
	//File contents for Header Row
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A3', 'Sl No')
		->setCellValue('B3', 'Manager Id')
		->setCellValue('C3', 'Username')
		->setCellValue('D3', 'Manager Name')
		->setCellValue('E3', 'Location')
		->setCellValue('F3', 'Cell')
		->setCellValue('G3', 'Emailid')
		->setCellValue('H3', 'Managed Area')
		->setCellValue('I3', 'Disable Login');
	$j = 4;
	$slno = 0;
	
	while($fetch = mysqli_fetch_array($result))
	{
		//set_time_limit(20);
		$slno++;
		$mySheet->setCellValue('A'.$j,$slno)
			->setCellValue('B'.$j,$fetch['id'])
			->setCellValue('C'.$j,$fetch['mgrusername'])
			->setCellValue('D'.$j,$fetch['mgrname'])
			->setCellValue('E'.$j,$fetch['mgrlocation'])
			->setCellValue('F'.$j,$fetch['mgrcell'])
			->setCellValue('G'.$j,$fetch['mgremailid'])
			->setCellValue('H'.$j,$fetch['managedarea'])
			->setCellValue('I'.$j,$fetch['disablelogin']);
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
	$mySheet->getColumnDimension('B')->setWidth(12);
	$mySheet->getColumnDimension('C')->setWidth(32);
	$mySheet->getColumnDimension('D')->setWidth(22);
	$mySheet->getColumnDimension('E')->setWidth(25);
	$mySheet->getColumnDimension('F')->setWidth(15);
	$mySheet->getColumnDimension('G')->setWidth(30);
	$mySheet->getColumnDimension('H')->setWidth(16);
	$mySheet->getColumnDimension('I')->setWidth(16);

	// Insert logs Manager List to excel
	$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','34','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
	$result = runmysqlquery_log($query);
	
	//Create a File name syntax
	$date = datetimelocal('YmdHis');
	$filebasename = "LMS-MGRS-".$cookie_username."-".$date.".xls";
	
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
}
?>
