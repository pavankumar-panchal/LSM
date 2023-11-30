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
	$date = datetimelocal('YmdHis');
	$cookie_username = lmsgetcookie('lmsusername');
	$cookie_usertype = lmsgetcookie('lmsusersort');	
	$command = $_REQUEST['command'];
	$month = date('m');
	if($month >= '04')
	{
		$datepiece = "AND substring(leaddatetime,1,10) between concat(year(curdate()),'-04-01') and curdate()";
	}
	else 
	{
		$datepiece = "AND substring(leaddatetime,1,10) between concat(year(curdate()) - 1,'-04-01') and curdate()";
	}
	switch($command)
	{
		case 'zerofollowup':
		{

			  switch($cookie_usertype)
			  {
				case 'Admin':
				case 'Sub Admin':
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname,leads.emailid,leads.address,leads.place,leads.refer from leads left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode where leads.id not in(select leadid from lms_followup) and leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed' and leads.leadstatus <> 'Registered User' ".$datepiece." ORDER BY leads.id DESC";
						break;
				case 'Reporting Authority':
				
						 //Check wheteher the manager is branch head or not
						$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
						$result1 = runmysqlqueryfetch($query1);
						if($result1['branchhead'] == 'yes')
							$branchpiecejoin = "AND (dealers.branch = '".$result1['branch']."' OR dealers.managerid = '".$result1['managerid']."')";
						else
							$branchpiecejoin = "and lms_users.username = '".$cookie_username."'";
						
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_managers.id  where leads.id not in(select leadid from lms_followup) and leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed' and leads.leadstatus <> 'Registered User'  and lms_users.type = 'Reporting Authority' ".$datepiece.$branchpiecejoin." ORDER BY leads.id DESC";
						break;
					
					case 'Dealer':
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid  left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = leads.dealerid where leads.id not in(select leadid from lms_followup) and leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested' and leads.leadstatus <> 'Order Closed' and leads.leadstatus <> 'Registered User' and lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer' ".$datepiece." ORDER BY leads.id DESC";
							break;
					
					case 'Dealer Member':
							$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = leads.dlrmbrid left join lms_dlrmembers on lms_dlrmembers.dealerid = dealers.id  where leads.id not in(select leadid from lms_followup) and leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed' and leads.leadstatus <> 'Registered User' and lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer Member' ".$datepiece." ORDER BY leads.id DESC";
							break;
			  }
			  $filebasename = "LMS-ZEROFOLLOWUPS-".$cookie_username."-".$date.".xls";
			  $heading = 'Zero follow up';
		}
		break;
		case 'followupdue':
		{
			  switch($cookie_usertype)
			  {
				  case 'Admin':
				  case 'Sub Admin':
					  $query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname,lms_followup.followupdate,lms_followup.followupstatus,lms_followup.remarks,lms_users.username as enteredby from leads left join lms_followup on lms_followup.leadid = leads.id left join lms_users on lms_users.id = lms_followup.enteredby left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode  where lms_followup.followupdate BETWEEN DATE_SUB(CURDATE(),INTERVAL 2 DAY) and CURDATE() and lms_followup.followupstatus = 'PENDING' and leads.leadstatus <> 'Fake Enquiry'and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed' and leads.leadstatus <> 'Registered User' and lms_followup.followupdate <> '0000-00-00' ORDER BY lms_followup.followupdate DESC";
					  break;
					  
				  case 'Reporting Authority':
				  
				   //Check wheteher the manager is branch head or not
				  $query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
				  $result1 = runmysqlqueryfetch($query1);
				  if($result1['branchhead'] == 'yes')
					  $branchpiecejoin = "AND (dealers.branch = '".$result1['branch']."' OR dealers.managerid = '".$result1['managerid']."')";
				  else
					  $branchpiecejoin = "and lms_users.username = '".$cookie_username."'";
					  
					  $query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join lms_followup on lms_followup.leadid = leads.id left join lms_users on lms_users.id = lms_followup.enteredby left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode where lms_followup.followupdate BETWEEN DATE_SUB(CURDATE(),INTERVAL 2 DAY) and CURDATE() and lms_followup.followupstatus = 'PENDING' and leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed'and leads.leadstatus <> 'Registered User' and lms_followup.followupdate <> '0000-00-00'  and lms_users.type = 'Reporting Authority' ".$branchpiecejoin." ORDER BY lms_followup.followupdate DESC";
					  //echo($query);exit;
					  break;
				  
				  case 'Dealer':
					  $query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join lms_followup on lms_followup.leadid = leads.id left join lms_users on lms_users.id = lms_followup.enteredby left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode  where lms_followup.followupdate BETWEEN DATE_SUB(CURDATE(),INTERVAL 2 DAY) and CURDATE() and lms_followup.followupstatus = 'PENDING' and leads.leadstatus <> 'Fake Enquiry'and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed'and leads.leadstatus <> 'Registered User' and lms_followup.followupdate <> '0000-00-00' and lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer' ORDER BY lms_followup.followupdate DESC";
					  break;
					  
				  case 'Dealer Member':
					  $query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join lms_followup on lms_followup.leadid = leads.id left join lms_users on lms_users.referenceid = leads.dlrmbrid left join dealers on dealers.id =leads.dealerid left join lms_dlrmembers on lms_users.referenceid = lms_dlrmembers.dlrmbrid left join lms_managers on lms_managers.id =dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode  where lms_followup.followupdate BETWEEN DATE_SUB(CURDATE(),INTERVAL 2 DAY) and CURDATE() and lms_followup.followupstatus = 'PENDING' and leads.leadstatus <> 'Fake Enquiry'and leads.leadstatus <> 'Not Interested'and leads.leadstatus <> 'Order Closed' and leads.leadstatus <> 'Registered User' and lms_followup.followupdate <> '0000-00-00' and lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer Member' ORDER BY lms_followup.followupdate DESC";
					  break;
			  }
			  $filebasename = "LMS-FOLLOWUPDUE-".$cookie_username."-".$date.".xls";
			  $heading = 'Follow up due';
			  
		}
		break;
		case 'notviewed':
		{
			switch($cookie_usertype)
			{
				case 'Admin':
				case 'Sub Admin':
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id =leads.dealerid  left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode where leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested' and leads.leadstatus <> 'Registered User' and leads.leadstatus <> 'Order Closed' and leads.leadstatus = 'Not Viewed' ".$datepiece." order by leads.id DESC";
						break;
				case 'Reporting Authority':
				
						//Check wheteher the manager is branch head or not
						$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
						$result1 = runmysqlqueryfetch($query1);
						if($result1['branchhead'] == 'yes')
							$branchpiecejoin = "AND (dealers.branch = '".$result1['branch']."' OR dealers.managerid = '".$result1['managerid']."')";
						else
							$branchpiecejoin = "and lms_users.username = '".$cookie_username."'";
							
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id =leads.dealerid  left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = lms_managers.id where leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested' and leads.leadstatus <> 'Registered User' and leads.leadstatus <> 'Order Closed' and leads.leadstatus = 'Not Viewed'  and lms_users.type = 'Reporting Authority' ".$datepiece.$branchpiecejoin." order by leads.id DESC";
						//echo($query);exit;
						break;
				
				case 'Dealer':
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id =leads.dealerid  left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = dealers.id  where leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested' and leads.leadstatus <> 'Registered User' and leads.leadstatus <> 'Order Closed' and leads.leadstatus = 'Not Viewed' and lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer' ".$datepiece." order by leads.id DESC";
						break;
				
				case 'Dealer Member':
						$query = "select distinct leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,regions.distname, regions.statename,dealers.dlrcompanyname,lms_managers.mgrname from leads left join dealers on dealers.id =leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = leads.dlrmbrid left join lms_dlrmembers on lms_dlrmembers.dealerid = leads.dealerid  where leads.leadstatus <> 'Fake Enquiry' and leads.leadstatus <> 'Not Interested' and leads.leadstatus <> 'Registered User' and leads.leadstatus <> 'Order Closed' and leads.leadstatus = 'Not Viewed' and lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer Member' ".$datepiece." order by leads.id DESC";
						break;
				
			}
			 $filebasename = "LMS-LEADSNOTVIEWED-".$cookie_username."-".$date.".xls";
			 $heading = 'Leads Not Viewed';
		}
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
	$mySheet->getStyle('A3:T3')->applyFromArray($styleArray);	
	//Merge the cell
	$mySheet->mergeCells('A1:T1');
	$mySheet->mergeCells('A2:T2');
	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
				->setCellValue('A2', $heading);
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
				->setCellValue('P3', 'Manager')
				->setCellValue('Q3', 'Last Followed Date')
				->setCellValue('R3', 'Last Followed By')
				->setCellValue('S3', 'Last Followup Remarks')
				->setCellValue('T3', 'Lead Status');

	$j = 4;
	$slno = 0;
	while($fetch = mysqli_fetch_array($result))
	{
		$lastfollowup = $fetch['followupdate'];
		$lastfollowupby = $fetch['enteredby'];
		$lastfollowupremarks = $fetch['remarks'];
		$lastfollowupstatus = $fetch['followupstatus'];
		
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
			->setCellValue('P'.$j,$fetch['mgrname'])
			->setCellValue('Q'.$j,$lastfollowup)
			->setCellValue('R'.$j,$lastfollowupby)
			->setCellValue('S'.$j,$lastfollowupremarks)
			->setCellValue('T'.$j,$lastfollowupstatus);
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
	$mySheet->getColumnDimension('C')->setWidth(40);
	$mySheet->getColumnDimension('D')->setWidth(40);
	$mySheet->getColumnDimension('E')->setWidth(40);
	$mySheet->getColumnDimension('F')->setWidth(20);
	$mySheet->getColumnDimension('G')->setWidth(25);
	$mySheet->getColumnDimension('H')->setWidth(33);
	$mySheet->getColumnDimension('I')->setWidth(25);
	$mySheet->getColumnDimension('J')->setWidth(20);
	$mySheet->getColumnDimension('K')->setWidth(20);
	$mySheet->getColumnDimension('L')->setWidth(25);
	$mySheet->getColumnDimension('M')->setWidth(25);
	$mySheet->getColumnDimension('N')->setWidth(25);
	$mySheet->getColumnDimension('O')->setWidth(25);
	$mySheet->getColumnDimension('P')->setWidth(25);
	$mySheet->getColumnDimension('Q')->setWidth(25);
	$mySheet->getColumnDimension('R')->setWidth(25);
	$mySheet->getColumnDimension('S')->setWidth(25);
	$mySheet->getColumnDimension('T')->setWidth(25);
		
		
	// Insert logs Dealer List to excel
	$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','32','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
	$result = runmysqlquery_log($query);
	
	
	
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
