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
	
	$searchtext = $_POST['companyname'];
	$category = $_POST['category'];
	$state = $_POST['form_state']; //echo($state);exit;
	$district = $_POST['form_district'];
	$region = $_POST['form_region'];
	$disabled = $_POST['disabled'];
	$radiovalue = $_POST['dealercompany'];//echo($radiovalue);exit;
	$orderby = $_POST['orderby'];
	$generate = $_POST['generate'];
	if($radiovalue == 'dealercompany')
	{
		$searchpiece = ($searchtext == '')?"":("AND dealers.dlrcompanyname like '%".$searchtext."%'");
	}
	else if($radiovalue == "dealername")
	{
		$searchpiece = ($searchtext == '')?"":("AND dealers.dlrname like '%".$searchtext."%'");
	}
	// conditons to orderby 
	if($orderby == 'region')
	{
		$orderbypiece = ($orderby == '')?"":("ORDER BY regions.statename,regions.distname,regions.subdistname,productcategory.prdcategory");
	}
	else if($orderby == 'product')
	{
		$orderbypiece = ($orderby == '')?"":("ORDER BY productcategory.prdcategory,regions.statename,regions.distname,regions.subdistname");
	}
	else if($orderby == 'dealer')
	{
		$orderbypiece = ($orderby == '')?"":("ORDER BY dealers.dlrcompanyname,regions.statename,regions.distname,regions.subdistname");
	}
	// condition to generate
	if($generate == '')
	{
		$generatepiece = '';
	}
	else if($generate == 'having')
	{
		$generatepiece = ' and mapping.id is not NULL';;
	}
	else if($generate == 'missing')
	{
		$generatepiece =  ' and mapping.id is  NULL';
	}
	
	$categorypiece = ($category == '')?"":("AND mapping.prdcategory = '".$category."'");
	$statepiece = ($state == '')?"":("AND regions.statecode = '".$state."'");
	$districtpiece = ($district == '')?"":("AND regions.distcode = '".$district."'");
	$regionpiece = ($region == '')?"":("AND mapping.regionid = '".$region."'"); 
	$disabledpiece = ($disabled == '')?"":("AND lms_users.disablelogin = '".$disabled."'");
	
	$query = "select regions.statename, regions.distname, regions.subdistname, productcategory.prdcategory, dealers.dlrcompanyname,dealers.dlrname,dealers.district,lms_users.disablelogin from regions  join (select distinct prdcategory from mapping) as productcategory left join mapping on regions.subdistcode = mapping.regionid and productcategory.prdcategory = mapping.prdcategory left join dealers on mapping.dealerid = dealers.id left join lms_users on lms_users.referenceid = dealers.id and lms_users.type = 'Dealer' where  regions.distname <> '' ".$categorypiece ."  ".$statepiece."  ".$districtpiece."  ".$regionpiece." ".$searchpiece." ".$disabledpiece.$generatepiece." ".$orderbypiece."";	
	
	
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
	$mySheet->getStyle('A3:I3')->applyFromArray($styleArray);	
	//Merge the cell
	$mySheet->mergeCells('A1:I1');
	$mySheet->mergeCells('A2:I2');
	// To align the text to center.
	$mySheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$mySheet->getStyle('A2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
				->setCellValue('A2', 'Mapping Information');
	$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
	$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
	$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	//File contents for Header Row
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A3', 'Sl No')
		->setCellValue('B3', 'Mapping State')
		->setCellValue('C3', 'Mapping District')
		->setCellValue('D3', 'Mapping Region')
		->setCellValue('E3', 'Product Category')
		->setCellValue('F3', 'Dealer Company')
		->setCellValue('G3', 'Dealer Person')
		->setCellValue('H3', 'Dealer Place')
		->setCellValue('I3', 'Dealer Disabled');
	$j = 4;
	$slno = 0;
	
	while($fetch = mysqli_fetch_array($result))
	{
		//set_time_limit(20);
		$slno++;
		$mySheet->setCellValue('A'.$j,$slno)
			->setCellValue('B'.$j,$fetch['statename'])
			->setCellValue('C'.$j,$fetch['distname'])
			->setCellValue('D'.$j,$fetch['subdistname'])
			->setCellValue('E'.$j,$fetch['prdcategory'])
			->setCellValue('F'.$j,$fetch['dlrcompanyname'])
			->setCellValue('G'.$j,$fetch['dlrname'])
			->setCellValue('H'.$j,$fetch['district'])
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
	$mySheet->getColumnDimension('B')->setWidth(20);
	$mySheet->getColumnDimension('C')->setWidth(20);
	$mySheet->getColumnDimension('D')->setWidth(20);
	$mySheet->getColumnDimension('E')->setWidth(20);
	$mySheet->getColumnDimension('F')->setWidth(30);
	$mySheet->getColumnDimension('G')->setWidth(20);
	$mySheet->getColumnDimension('H')->setWidth(20);
	$mySheet->getColumnDimension('I')->setWidth(20);

	// Insert logs Manager List to excel
	$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','47','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
	$result = runmysqlquery_log($query);
	
	//Create a File name syntax
	$date = datetimelocal('YmdHis');
	$filebasename = "LMS-MAPPING-".$cookie_username."-".$date.".xls";
	
	if($_SERVER['HTTP_HOST'] == 'rashmihk')  
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
