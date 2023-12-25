<?

ini_set('memory_limit', '-1');
set_time_limit(0);
include("../functions/phpfunctions.php");
//PHPExcel
require_once '../phpexcel174/PHPExcel.php';

//PHPExcel_IOFactory
require_once '../phpexcel174/PHPExcel/IOFactory.php';

//Define the file creation path
if($_SERVER['HTTP_HOST'] == 'archanaab' || $_SERVER['HTTP_HOST'] == 'vijaykumar')  
{
$filepath = $_SERVER['DOCUMENT_ROOT'].'/LMS/autoscripts/files/';
}
else
{
$filepath = getcwd().'/files/';
}

//Define managed area
/*$managedareaarray = array(
						"BKG" => array("area" => "BKG", "emailid" => array("archana.ab@relyonsoft.com","rashmi.hk@relyonsoft.com"),
										 "name" => array("Paramesh N","Nitin S Patel")),
						"BKM" => array("area" => "BKM", "emailid" => array("archana.ab@relyonsoft.com"),
										 "name" => array("Raghavendra N")),
						"CSD" => array("area" => "CSD", "emailid" => array("archana.ab@relyonsoft.com"),
										 "name" => array("Vijay Hebbar"))
					);*/
				//changed by bhavesh patel	
$managedareaarray = array(
					"BKG" => array("area" => "BKG", "emailid" => array("paramesh.n@relyonsoft.com","nitinall@relyonsoft.com"),
									 "name" => array("Paramesh N","Nitin S Patel")),
					"BKM" => array("area" => "BKM", "emailid" => array("raghavendra.n@relyonsoft.com"),
									 "name" => array("Raghavendra N")),
					"CSD" => array("area" => "CSD", "emailid" => array("nitinall@relyonsoft.com"),
									 "name" => array("Nitin S Patel"))
				);


// To fetch current financial year
$month = date('m');
$fybegin = (date('m') >= '04')?(date('Y').'-04-01'):((date('Y')-1).'-04-01');


/*---------------------------Email to Managers----------------------------------*/


$interval = 4;

// Check the date difference

$convertedfinancialyear = strtotime($fybegin);
$today = strtotime(date('Y-m-d'));

$dateDiff = $today - $convertedfinancialyear;

$fullDays = floor($dateDiff/(60*60*24));


if($fullDays > $interval)
{
	//Select the Managers who have the data
	$query = "select dealers.managerid,lms_managers.managedarea,lms_managers.mgrname,lms_managers.mgremailid,count(leadstatus = 'Not Viewed' OR NULL) AS notviewed, count(leadstatus = 'UnAttended' OR NULL) AS unattended,lms_managers.branchhead as branchhead,lms_managers.branch as branch from leads left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid WHERE leaddatetime between '".$fybegin."' and DATE_SUB(CURDATE(),INTERVAL ".$interval." DAY) and dealerid <> '999999' and leads.dealerid <> '' group by dealers.managerid order by lms_managers.managedarea,lms_managers.mgrname";
	//echo($query);exit;
	$result = runmysqlquery($query);
	while($resultfetch = mysqli_fetch_array($result))
	{
		$managerid = $resultfetch['managerid'];
		$mgrname = $resultfetch['mgrname'];
		$emailid = $resultfetch['mgremailid'];
		$managedarea = $resultfetch['managedarea'];
		$branchhead = $resultfetch['branchhead'];
		$branch = $resultfetch['branch'];
		//Check whether the manager is branch head or not
		if($branchhead == 'yes')
			$branchpiecejoin = "AND (dealers.branch = '".$branch."' OR dealers.managerid = '".$managerid."')";
		else
			$branchpiecejoin = "";
		
		// Prepare Dealer wise Summary for selected manager. HTML Table for Email content
		$query1 = "select lms_managers.managedarea,lms_managers.mgrname,dealers.dlrcompanyname, ifnull(count(leadstatus = 'Not Viewed' OR NULL),0) AS notviewed, ifnull(count(leadstatus = 'UnAttended' OR NULL),0) AS unattended from leads left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid WHERE leaddatetime between '".$fybegin."' and DATE_SUB(CURDATE(),INTERVAL ".$interval." DAY) and dealerid <> '999999' and lms_managers.id = '".$managerid ."' ".$branchpiecejoin." group by dealers.id order by lms_managers.managedarea,lms_managers.mgrname,dealers.dlrcompanyname"; 
		$result1 = runmysqlquery($query1);
		
		
		
		// put the details to table to display in email content.
		$grid = '<table width="100%" style="font-family:calibri;" cellspacing="0" cellpadding="0" border= "1" ><tbody>';
		//Write the header Row of the table
		$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" class="tdborderlead">Sl No</td><td nowrap="nowrap" class="tdborderlead">Managed Area</td><td nowrap="nowrap" class="tdborderlead">Manager Name</td><td nowrap="nowrap" class="tdborderlead">Dealer Name</td><td nowrap="nowrap" class="tdborderlead" >Not Viewed</td><td nowrap="nowrap" class="tdborderlead">UnAttended</td><td nowrap="nowrap" class="tdborderlead">Total</td></tr>';
		$slnocount = 0;
		$totalnotviewed = 0;
		$totalunattended = 0;
		$totalsum = 0;
		while($fetch1 = mysqli_fetch_array($result1))
		{
			$slnocount++;
			//Calculate the Sum
			//$notviewed = ($fetch1['notviewed'] == 0)?'0':($fetch1['notviewed']);
			//$unattended = ($fetch1['unattended'] == 0)?'0':($fetch1['unattended']);
			$sum = $fetch1['notviewed'] + $fetch1['unattended'];
			
			//Add the totals for this Manager
			$totalnotviewed += $fetch1['notviewed'];
			$totalunattended += $fetch1['unattended'];
			$totalsum += $sum;
			
			//if($sum > 0)
			//{
				//Begin a row
				$grid .= '<tr>';
				$grid .= "<td nowrap='nowrap' class='tdborderlead'>".$slnocount."</td>";				
				$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$fetch1['managedarea']."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$fetch1['mgrname']."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$fetch1['dlrcompanyname']."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborderlead' >&nbsp;".$fetch1['notviewed']."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborderlead' >&nbsp;".$fetch1['unattended']."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborderlead' >&nbsp;".$sum."</td>";
				$grid .= '</tr>';
			//}
		}
		//Add the total row
		$grid .= '<tr>';
		$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;</td>";				
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >&nbsp;</td>";
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >&nbsp;</td>";
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >Total</td>";
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$totalnotviewed."</td>";
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$totalunattended."</td>";
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$totalsum."</td>";
		$grid .= '</tr>';
		//End of Table
		$grid .= '</tbody></table>';
		
		// Fetch complete lead details.
		$query2 = "select leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,leads.address,leads.place, regions.distname, regions.statename,leads.refer, dealers.dlrcompanyname, lms_managers.mgrname from leads  left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode WHERE leaddatetime between '".$fybegin."' and DATE_SUB(CURDATE(),INTERVAL ".$interval." DAY) and dealerid <> '999999' and (leadstatus = 'Not Viewed' or leadstatus = 'UnAttended') and lms_managers.id = '".$managerid ."' ".$branchpiecejoin." order by leaddatetime";	
		$result2 = runmysqlquery($query2);
		$leadcount = mysqli_num_rows($result2);
	
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		$pageindex = 0;
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		$mySheet->setTitle('List of Leads');
		$styleArray = array(
						'font' => array('bold' => true),
						'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '0099CCFF')),
						'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
					);
		$mySheet->getStyle('A3:P3')->applyFromArray($styleArray);
		$mySheet->mergeCells('A1:P1');
		$mySheet->mergeCells('A2:P2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'List of Leads');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
		
		//File contents for Header Row
		$objPHPExcel->setActiveSheetIndex($pageindex)
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
		$j = 4;
		$slno = 0;	
		
		while($fetch = mysqli_fetch_array($result2))
		{
			//set_time_limit(20);
			$manager = $fetch['mgrname'];
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
		
		
		if(mysqli_num_rows($result2) <> 0)
		{
		//Apply style to content area range
			$mySheet->getStyle($myDataRange)->applyFromArray($styleArrayContent);
		}
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(6);
		$mySheet->getColumnDimension('B')->setWidth(10);
		$mySheet->getColumnDimension('C')->setWidth(20);
		$mySheet->getColumnDimension('D')->setWidth(25);
		$mySheet->getColumnDimension('E')->setWidth(25);
		$mySheet->getColumnDimension('F')->setWidth(30);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(18);
		$mySheet->getColumnDimension('I')->setWidth(43);
		$mySheet->getColumnDimension('J')->setWidth(45);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(25);
		$mySheet->getColumnDimension('M')->setWidth(24);
		$mySheet->getColumnDimension('N')->setWidth(25);
		$mySheet->getColumnDimension('O')->setWidth(35);
		$mySheet->getColumnDimension('P')->setWidth(30);
		
		
		// Increment Sheet Index and add content
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet($pageindex);
		
		//Set the worksheet name
		$mySheet->setTitle('Dealerwise summary');
		
		$result1 = runmysqlquery($query1);
		$currentrow = 1;
		$slno1 = 0;
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'Dealerwise summary');
		
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('B'.$currentrow,'SL No')
				->setCellValue('C'.$currentrow,'Managed Area')
				->setCellValue('D'.$currentrow,'Manager Name')
				->setCellValue('E'.$currentrow,'Dealer Company')
				->setCellValue('F'.$currentrow,'Not Viewed')
				->setCellValue('G'.$currentrow,'Un Attended')
				->setCellValue('H'.$currentrow,'Total');
		
		$j = 3;		
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow.':H'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;
		$result1 = runmysqlquery($query1);
		while($fetchdetails = mysqli_fetch_array($result1))
		{
			
			$slno1++;
			$total = $fetchdetails['notviewed'] + $fetchdetails['unattended'];
			$mySheet->setCellValue('B'.$j,$slno1)
			->setCellValue('C'.$j,$fetchdetails['managedarea'])
			->setCellValue('D'.$j,$fetchdetails['mgrname'])
			->setCellValue('E'.$j,$fetchdetails['dlrcompanyname'])
			->setCellValue('F'.$j,$fetchdetails['notviewed'])
			->setCellValue('G'.$j,$fetchdetails['unattended'])
			->setCellValue('H'.$j,$total);
			$j++;
			$currentrow++;
		}
		//Insert Total
		$mySheet->setCellValue('E'.$currentrow,'Total')
			->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
			->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")")
			->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow - 1).")");
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
	
		//Apply style for Content row
		$mySheet->getStyle('B'.$databeginrow.':H'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('B')->setWidth(10);
		$mySheet->getColumnDimension('C')->setWidth(10);
		$mySheet->getColumnDimension('D')->setWidth(20);
		$mySheet->getColumnDimension('E')->setWidth(40);
		$mySheet->getColumnDimension('F')->setWidth(20);
		$mySheet->getColumnDimension('G')->setWidth(20);
		$mySheet->getColumnDimension('H')->setWidth(10);
		
		$filebasename = "LEADS REPORT-".$mgrname.".xls";
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($filepath.$filebasename);
		
		//Empty the RAM by clearing the ExcelObject
		$objPHPExcel->disconnectWorksheets();
		unset($objPHPExcel);
		
		//Convert the file to ZIP format
		$filezipname = "LEADS REPORT-".$mgrname.".zip";
		$zip = new ZipArchive;
		$newzip = $zip->open($filepath.$filezipname, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
		if ($newzip === TRUE) {
			$zip->addFile($filepath.$filebasename, $filebasename);
			$zip->close();
		}
		
	
		//Prepare for emailing
		$days = $interval.' Days';
		$array = array();
		$array[] = "##NAME##%^%".$mgrname;
		$array[] = "##COUNT##%^%".$leadcount;
		$array[] = "##DAYS##%^%".$days;
		$array[] = "##TABLE##%^%".$grid;
	
		$message = file_get_contents("../inc/managermail.htm");
		$message = replacemailvariablenew($message,$array);	
		
		// eMailing.
		require_once("../inc/RSLMAIL_MAIL.php");
		$FromAddress=  "lms@relyon.co.in"; 
		$fromname = "Relyon-LMS";
		$toarray = array($mgrname => $emailid);
		//$toarray = array($mgrname =>'archana.ab@relyonsoft.com');
		$bccarray = array('Relyonimax' =>'relyonimax@gmail.com');
		unset($ccarray);
		for($emailidcount = 0; $emailidcount < count($managedareaarray[$managedarea]['emailid']); $emailidcount++)
		{
			$ccarray[$managedareaarray[$managedarea]['name'][$emailidcount]] = $managedareaarray[$managedarea]['emailid'][$emailidcount];
		}
		$mailsubject = 'Leads due at LMS left NotViewed/UnAttended ('.$mgrname.')';
		$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
		$html = $message;
		$filearray = array(
		array($filepath.$filezipname,'attachment','1234567891')
		);
		rslmail($fromname,$FromAddress,$toarray,$mailsubject,$text,$html,$ccarray,$bccarray,$filearray);
		fileDelete($filepath,$filebasename) ;
		fileDelete($filepath,$filezipname) ;
		
	}
}

/*---------------------------Email to Areaheads----------------------------------*/

$interval = 7;
if($fullDays > $interval)
{
	$count = 0;
	foreach ($managedareaarray as $currentarea => $arrayvalue)
	{
		$managedarea = $arrayvalue['area'];
		$emailid = $arrayvalue['emailid'];
		$name = $arrayvalue['name'];
		
		// Query to fetch dealerwise summary of that particular area.
		$query5 = "select lms_managers.managedarea,lms_managers.mgrname,dealers.dlrcompanyname, ifnull(count(leadstatus = 'Not Viewed' OR NULL),0) AS notviewed, ifnull(count(leadstatus = 'UnAttended' OR NULL),0) AS unattended from leads left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid WHERE leaddatetime between '".$fybegin."' and DATE_SUB(CURDATE(),INTERVAL ".$interval." DAY) and dealerid <> '999999' and lms_managers.managedarea = '".$managedarea."' group by dealers.id order by lms_managers.managedarea,lms_managers.mgrname,dealers.dlrcompanyname";
		$result5 = runmysqlquery($query5);
		
		// Query to fetch managerwise summary.
		$query6 = "select lms_managers.managedarea,lms_managers.mgrname,count(leadstatus = 'Not Viewed' OR NULL) AS notviewed, count(leadstatus = 'UnAttended' OR NULL) AS unattended from leads left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid WHERE leaddatetime between '".$fybegin."' and DATE_SUB(CURDATE(),INTERVAL ".$interval." DAY) and dealerid <> '999999' and lms_managers.managedarea = '".$managedarea."' group by dealers.managerid order by lms_managers.managedarea,lms_managers.mgrname";
		$result6 = runmysqlquery($query6);
		
		// put the details to table to display in mail content.
		$grid = '<table width="100%" style="font-family:calibri;" cellspacing="0" cellpadding="0" border= "1" ><tbody>';
			//Write the header Row of the table
		$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" class="tdborderlead">Sl No</td><td nowrap="nowrap" class="tdborderlead">Managed Area</td><td nowrap="nowrap" class="tdborderlead">Manager Name</td><td nowrap="nowrap" class="tdborderlead" >Not Viewed</td><td nowrap="nowrap" class="tdborderlead">UnAttended</td><td nowrap="nowrap" class="tdborderlead">Total</td></tr>';
		$slnocount = 0;
		$totalnotviewed = 0;
		$totalunattended = 0;
		$totalsum = 0;
		while($fetch1 = mysqli_fetch_array($result6))
		{
			$slnocount++;
			$sum = $fetch1['notviewed'] + $fetch1['unattended'];
			
			//Add the totals for this Account
			$totalnotviewed += $fetch1['notviewed'];
			$totalunattended += $fetch1['unattended'];
			$totalsum += $sum;
			
			//Begin a row
			$grid .= '<tr>';
			$grid .= "<td nowrap='nowrap' class='tdborderlead'>".$slnocount."</td>";		
			$grid .= "<td nowrap='nowrap' class='tdborderlead'>".$fetch1['managedarea']."</td>";		
			$grid .= "<td nowrap='nowrap' class='tdborderlead'>".$fetch1['mgrname']."</td>";
			$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".$fetch1['notviewed']."</td>";
			$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".$fetch1['unattended']."</td>";
			$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".$sum."</td>";
			$grid .= '</tr>';
		}
		//Add the total row
		$grid .= '<tr>';
		$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;</td>";				
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >&nbsp;</td>";
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >Total</td>";
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$totalnotviewed."</td>";
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$totalunattended."</td>";
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$totalsum."</td>";
		$grid .= '</tr>';
		//End of Table
		$grid .= '</tbody></table>';
		
		// Query to fetch all lead details.
		$query7 = "select leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,leads.address,leads.place, regions.distname, regions.statename,leads.refer,dealers.dlrcompanyname, lms_managers.mgrname from leads  left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode where leaddatetime between '".$fybegin."' and DATE_SUB(CURDATE(),INTERVAL ".$interval." DAY) and dealerid <> '999999' and (leadstatus = 'Not Viewed' or leadstatus = 'UnAttended') and lms_managers.managedarea = '".$managedarea."' order by leaddatetime";
		$result7 = runmysqlquery($query7);
		$leadcountmanagers = mysqli_num_rows($result7);

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		$pageindex = 0;
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		$mySheet->setTitle('List of Leads');
		$styleArray = array(
						'font' => array('bold' => true),
						'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '0099CCFF')),
						'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
					);
		$mySheet->getStyle('A3:P3')->applyFromArray($styleArray);
		$mySheet->mergeCells('A1:P1');
		$mySheet->mergeCells('A2:P2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'List of Leads');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
		
		//File contents for Header Row
		$objPHPExcel->setActiveSheetIndex($pageindex)
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
			
		$j = 4;
		$slno = 0;	
		while($fetch = mysqli_fetch_array($result7))
		{
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
		
		if(mysqli_num_rows($result7) <> 0)
		{
		//Apply style to content area range
			$mySheet->getStyle($myDataRange)->applyFromArray($styleArrayContent);
		}
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(6);
		$mySheet->getColumnDimension('B')->setWidth(10);
		$mySheet->getColumnDimension('C')->setWidth(20);
		$mySheet->getColumnDimension('D')->setWidth(25);
		$mySheet->getColumnDimension('E')->setWidth(25);
		$mySheet->getColumnDimension('F')->setWidth(30);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(18);
		$mySheet->getColumnDimension('I')->setWidth(43);
		$mySheet->getColumnDimension('J')->setWidth(45);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(25);
		$mySheet->getColumnDimension('M')->setWidth(24);
		$mySheet->getColumnDimension('N')->setWidth(25);
		$mySheet->getColumnDimension('O')->setWidth(35);
		$mySheet->getColumnDimension('P')->setWidth(30);
		
		// Dealerwise summary.
		// Increment Sheet Index and add content
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet($pageindex);
		
		//Set the worksheet name
		$mySheet->setTitle('Dealerwise Summary');
		$currentrow = 1;
		$slno1 = 0;
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'Dealerwise Summary');
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('B'.$currentrow,'SL No')
				->setCellValue('C'.$currentrow,'Managed Area')
				->setCellValue('D'.$currentrow,'Dealer Company')
				->setCellValue('E'.$currentrow,'Not Viewed')
				->setCellValue('F'.$currentrow,'Un Attended')
				->setCellValue('G'.$currentrow,'Total');
		
		$j = 3;		
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow.':G'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;
		while($fetchdetails = mysqli_fetch_array($result5))
		{
			
			$slno1++;
			$total = $fetchdetails['notviewed'] + $fetchdetails['unattended'];
			if($total == '0')$total = '';
			else $total = $total;
			$mySheet->setCellValue('B'.$j,$slno1)
			->setCellValue('C'.$j,$fetchdetails['managedarea'])
			->setCellValue('D'.$j,$fetchdetails['dlrcompanyname'])
			->setCellValue('E'.$j,$fetchdetails['notviewed'])
			->setCellValue('F'.$j,$fetchdetails['unattended'])
			->setCellValue('G'.$j,$total);
			$j++;
			$currentrow++;
		}
		
		//Insert Total
		$mySheet->setCellValue('D'.$currentrow,'Total')
			->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
			->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
			->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")");
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		
		//Apply style for Content row
		$mySheet->getStyle('B'.$databeginrow.':G'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('B')->setWidth(10);
		$mySheet->getColumnDimension('C')->setWidth(10);
		$mySheet->getColumnDimension('D')->setWidth(40);
		$mySheet->getColumnDimension('E')->setWidth(20);
		$mySheet->getColumnDimension('F')->setWidth(20);
		$mySheet->getColumnDimension('G')->setWidth(10);
			
		//Managerwise Summary
		// Increment Sheet Index and add content
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet($pageindex);
		
		//Set the worksheet name
		$mySheet->setTitle('Managerwise Summary');
		$currentrow = 1;
		$slno1 = 0;
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'Managerwise Summary');
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('B'.$currentrow,'SL No')
				->setCellValue('C'.$currentrow,'Managed Area')
				->setCellValue('D'.$currentrow,'Manager Name')
				->setCellValue('E'.$currentrow,'Not Viewed')
				->setCellValue('F'.$currentrow,'Un Attended')
				->setCellValue('G'.$currentrow,'Total');
		
		$j = 3;		
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow.':G'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;
		$result6 = runmysqlquery($query6);
		while($fetchdetails = mysqli_fetch_array($result6))
		{
			
			$slno1++;
			$total = $fetchdetails['notviewed'] + $fetchdetails['unattended'];
			if($total == '0')$total = '';
			else $total = $total;
			$mySheet->setCellValue('B'.$j,$slno1)
			->setCellValue('C'.$j,$fetchdetails['managedarea'])
			->setCellValue('D'.$j,$fetchdetails['mgrname'])
			->setCellValue('E'.$j,$fetchdetails['notviewed'])
			->setCellValue('F'.$j,$fetchdetails['unattended'])
			->setCellValue('G'.$j,$total);
			$j++;
			$currentrow++;
			
		}
		
		//Insert Total
		$mySheet->setCellValue('D'.$currentrow,'Total')
			->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
			->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
			->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")");
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		
		//Apply style for Content row
		$mySheet->getStyle('B'.$databeginrow.':G'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('B')->setWidth(10);
		$mySheet->getColumnDimension('C')->setWidth(10);
		$mySheet->getColumnDimension('D')->setWidth(40);
		$mySheet->getColumnDimension('E')->setWidth(20);
		$mySheet->getColumnDimension('F')->setWidth(20);
		$mySheet->getColumnDimension('G')->setWidth(10);
			
		//echo($message);exit;
		$filebasename = "LEAD REPORT-".$managedarea.".xls";
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');//echo($message);exit;
		$objWriter->save($filepath.$filebasename);

		//Empty the RAM by clearing the ExcelObject
		$objPHPExcel->disconnectWorksheets();
		unset($objPHPExcel);
	
		//Convert the file to ZIP format
		$filezipname = "LEADS REPORT-".$managedarea.".zip";
		$zip = new ZipArchive;
		$newzip = $zip->open($filepath.$filezipname, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
		if ($newzip === TRUE) {
			$zip->addFile($filepath.$filebasename, $filebasename);
			$zip->close();
		}

		// Begin email 
		$days = $interval." Days";
		$array = array();
		$array[] = "##NAME##%^%".$name[0];
		$array[] = "##COUNT##%^%".$leadcountmanagers;
		$array[] = "##DAYS##%^%".$days;
		$array[] = "##TABLE##%^%".$grid;
		
		$message = file_get_contents("../inc/managermail.htm");
		$message = replacemailvariablenew($message,$array);	
		
		
		// Mailing.
		require_once("../inc/RSLMAIL_MAIL.php");
		$FromAddress=  "lms@relyon.co.in"; 
		$fromname = "Relyon-LMS";
		unset($toarray);
		for($emailidcount = 0; $emailidcount < count($emailid); $emailidcount++)
		{
			$toarray[$name[$emailidcount]] = $emailid[$emailidcount];
		}
		$bccarray = array('Relyonimax' =>'relyonimax@gmail.com');
		//$toarray = "archana.ab@relyonsoft.com";
		$mailsubject = 'Leads due at your area left NotViewed/UnAttended ('.$managedarea.')';
		$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
		$html = $message; //echo($_SERVER['DOCUMENT_ROOT'].'/LMS/filescreated/'.$filebasename);exit;
		$filearray1 = array(
		array($filepath.$filezipname,'attachment','1234567891')
		);
		

		rslmail($fromname,$FromAddress,$toarray,$mailsubject,$text,$html,null,$bccarray,$filearray1);
		fileDelete($filepath,$filebasename) ;
		fileDelete($filepath,$filezipname) ;
			
	}
}	
	
/*---------------------------Email to HSN----------------------------------*/

$interval = 10;
if($fullDays > $interval)
{
	// Dealerwise summary
	$query8 = "select lms_managers.managedarea,lms_managers.mgrname,dealers.dlrcompanyname, count(leadstatus = 'Not Viewed' OR NULL) AS notviewed, count(leadstatus = 'UnAttended' OR NULL) AS unattended from leads left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid WHERE leaddatetime between '".$fybegin."' and DATE_SUB(CURDATE(),INTERVAL ".$interval." DAY) and dealerid <> '999999' group by dealers.id order by lms_managers.managedarea,lms_managers.mgrname,dealers.dlrcompanyname";
	$result8 = runmysqlquery($query8);
	
	// Managerwise summary
	$query9 = "select lms_managers.managedarea,lms_managers.mgrname,count(leadstatus = 'Not Viewed' OR NULL) AS notviewed, count(leadstatus = 'UnAttended' OR NULL) AS unattended from leads left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid WHERE leaddatetime between '".$fybegin."' and DATE_SUB(CURDATE(),INTERVAL ".$interval." DAY) and dealerid <> '999999' group by dealers.managerid order by lms_managers.managedarea,lms_managers.mgrname";

	$result9 = runmysqlquery($query9);
	
	
	// Regionwise summary
	$query10 = "select lms_managers.managedarea,count(leadstatus = 'Not Viewed' OR NULL) AS notviewed, count(leadstatus = 'UnAttended' OR NULL) AS unattended from leads left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid WHERE leaddatetime between '".$fybegin."' and DATE_SUB(CURDATE(),INTERVAL ".$interval." DAY) and dealerid <> '999999' group by lms_managers.managedarea order by lms_managers.managedarea";
	$result10 = runmysqlquery($query10);
		
	// put the details to table to display in mail content.
	
	$grid = '<table width="100%" style="font-family:calibri;" cellspacing="0" cellpadding="0" border= "1" ><tbody>';
		//Write the header Row of the table
	$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" class="tdborderlead">Sl No</td><td nowrap="nowrap" class="tdborderlead">Area</td><td nowrap="nowrap" class="tdborderlead" >Not Viewed</td><td nowrap="nowrap" class="tdborderlead">UnAttended</td><td nowrap="nowrap" class="tdborderlead">Total</td></tr>';
	$slnocount = 0;
	$totalnotviewed = 0;
	$totalunattended = 0;
	$totalsum = 0;
	while($fetch1 = mysqli_fetch_array($result10))
	{
		$slnocount++;
		$sum = $fetch1['notviewed'] + $fetch1['unattended'];
		
		//Add the totals for this Account
		$totalnotviewed += $fetch1['notviewed'];
		$totalunattended += $fetch1['unattended'];
		$totalsum += $sum;
		
		//Begin a row
		$grid .= '<tr>';
		$grid .= "<td nowrap='nowrap' class='tdborderlead'>".$slnocount."</td>";		
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$fetch1['managedarea']."</td>";		
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >&nbsp;".$fetch1['notviewed']."</td>";
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >&nbsp;".$fetch1['unattended']."</td>";
		$grid .= "<td nowrap='nowrap' class='tdborderlead' >&nbsp;".$sum."</td>";
		$grid .= '</tr>';
	}
	//Add the total row
	$grid .= '<tr>';
	$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;</td>";				
	$grid .= "<td nowrap='nowrap' class='tdborderlead' >Total</td>";
	$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$totalnotviewed."</td>";
	$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$totalunattended."</td>";
	$grid .= "<td nowrap='nowrap' class='tdborderlead' >".$totalsum."</td>";
	$grid .= '</tr>';
	//End of Table
	$grid .= '</tbody></table>';
	
	// Detailed Leds report
	$query11 = "select leads.id, leads.leaddatetime, products.productname, leads.company,leads.name,leads.phone,leads.cell, leads.emailid,leads.address,leads.place,regions.distname, regions.statename, dealers.dlrcompanyname, lms_managers.mgrname from leads  left join dealers on dealers.id = leads.dealerid left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid join regions on leads.regionid = regions.subdistcode WHERE leaddatetime between '".$fybegin."' and DATE_SUB(CURDATE(),INTERVAL ".$interval." DAY) and dealerid <> '999999' and (leadstatus = 'Not Viewed' or leadstatus = 'UnAttended')  order by leaddatetime";
	$result11 = runmysqlquery($query11);
	$leadcount = mysqli_num_rows($result11);

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	
	$pageindex = 0;
	//Set Active Sheet	
	$mySheet = $objPHPExcel->getActiveSheet();
	$mySheet->setTitle('List of Leads');
	$styleArray = array(
					'font' => array('bold' => true),
					'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '0099CCFF')),
					'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
				);
	$mySheet->getStyle('A3:P3')->applyFromArray($styleArray);
	$mySheet->mergeCells('A1:P1');
	$mySheet->mergeCells('A2:P2');
	$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
				->setCellValue('A2', 'List of Leads');
	$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
	$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
	$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
	//File contents for Header Row
	$objPHPExcel->setActiveSheetIndex($pageindex)
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
		
	$j = 4;
	$slno = 0;	

	while($fetch = mysqli_fetch_array($result11))
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
	
	
	if(mysqli_num_rows($result11) <> 0)
	{
	//Apply style to content area range
		$mySheet->getStyle($myDataRange)->applyFromArray($styleArrayContent);
	}
	
	
	//set the default width for column
	$mySheet->getColumnDimension('A')->setWidth(6);
	$mySheet->getColumnDimension('B')->setWidth(10);
	$mySheet->getColumnDimension('C')->setWidth(20);
	$mySheet->getColumnDimension('D')->setWidth(25);
	$mySheet->getColumnDimension('E')->setWidth(25);
	$mySheet->getColumnDimension('F')->setWidth(30);
	$mySheet->getColumnDimension('G')->setWidth(15);
	$mySheet->getColumnDimension('H')->setWidth(18);
	$mySheet->getColumnDimension('I')->setWidth(43);
	$mySheet->getColumnDimension('J')->setWidth(45);
	$mySheet->getColumnDimension('K')->setWidth(15);
	$mySheet->getColumnDimension('L')->setWidth(25);
	$mySheet->getColumnDimension('M')->setWidth(24);
	$mySheet->getColumnDimension('N')->setWidth(25);
	$mySheet->getColumnDimension('O')->setWidth(35);
	$mySheet->getColumnDimension('P')->setWidth(30);
		
	
	// Increment page Index and add new sheet
	$pageindex++;
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($pageindex);

	//Set Active Sheet	
	$mySheet = $objPHPExcel->getActiveSheet($pageindex);
	
	//Set the worksheet name
	$mySheet->setTitle('Dealerwise Summary');
	
	
	$currentrow = 1;
	$slno1 = 0;
	//Set heading
	$mySheet->setCellValue('A'.$currentrow,'Dealerwise Summary');
	
	$currentrow++;
	//Set table headings
	$objPHPExcel->setActiveSheetIndex($pageindex)
			->setCellValue('B'.$currentrow,'SL No')
			->setCellValue('C'.$currentrow,'Managed Area')
			->setCellValue('D'.$currentrow,'Dealer Company')
			->setCellValue('E'.$currentrow,'Not Viewed')
			->setCellValue('F'.$currentrow,'Un Attended')
			->setCellValue('G'.$currentrow,'Total');
	
	$j = 3;		
	//Apply style for header Row
	$mySheet->getStyle('B'.$currentrow.':G'.$currentrow)->applyFromArray($styleArray);
	$currentrow++;
	$databeginrow = $currentrow;
	while($fetchdetails = mysqli_fetch_array($result8))
	{
		
		$slno1++;
		$total = $fetchdetails['notviewed'] + $fetchdetails['unattended'];
		$mySheet->setCellValue('B'.$j,$slno1)
		->setCellValue('C'.$j,$fetchdetails['managedarea'])
		->setCellValue('D'.$j,$fetchdetails['dlrcompanyname'])
		->setCellValue('E'.$j,$fetchdetails['notviewed'])
		->setCellValue('F'.$j,$fetchdetails['unattended'])
		->setCellValue('G'.$j,$total);
		$j++;
		$currentrow++;
		
	}
	
	//Insert Total
	$mySheet->setCellValue('D'.$currentrow,'Total')
		->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
		->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
		->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")");
	$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
	
	
	//Apply style for Content row
	$mySheet->getStyle('B'.$databeginrow.':G'.$currentrow)->applyFromArray($styleArrayContent);
	$mySheet->getColumnDimension('B')->setWidth(10);
	$mySheet->getColumnDimension('C')->setWidth(10);
	$mySheet->getColumnDimension('D')->setWidth(40);
	$mySheet->getColumnDimension('E')->setWidth(20);
	$mySheet->getColumnDimension('F')->setWidth(20);
	$mySheet->getColumnDimension('G')->setWidth(10);
	
	
	// Increment page Index and add new sheet
	$pageindex++;
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($pageindex);

	//Set Active Sheet	
	$mySheet = $objPHPExcel->getActiveSheet($pageindex);
	
	//Set the worksheet name
	$mySheet->setTitle('Managerwise Summary');
	
	
	$currentrow = 1;
	$slno1 = 0;
	//Set heading
	$mySheet->setCellValue('A'.$currentrow,'Managerwise Summary');
	
	$currentrow++;
	//Set table headings
	$objPHPExcel->setActiveSheetIndex($pageindex)
			->setCellValue('B'.$currentrow,'SL No')
			->setCellValue('C'.$currentrow,'Managed Area')
			->setCellValue('D'.$currentrow,'Manager Name')
			->setCellValue('E'.$currentrow,'Not Viewed')
			->setCellValue('F'.$currentrow,'Un Attended')
			->setCellValue('G'.$currentrow,'Total');
	
	$j = 3;		
	//Apply style for header Row
	$mySheet->getStyle('B'.$currentrow.':G'.$currentrow)->applyFromArray($styleArray);
	$currentrow++;
	$databeginrow = $currentrow;
	while($fetchdetails = mysqli_fetch_array($result9))
	{
		
		$slno1++;
		$total = $fetchdetails['notviewed'] + $fetchdetails['unattended'];
		if($total == '0')$total = '';
		else $total = $total;
		$mySheet->setCellValue('B'.$j,$slno1)
		->setCellValue('C'.$j,$fetchdetails['managedarea'])
		->setCellValue('D'.$j,$fetchdetails['mgrname'])
		->setCellValue('E'.$j,$fetchdetails['notviewed'])
		->setCellValue('F'.$j,$fetchdetails['unattended'])
		->setCellValue('G'.$j,$total);
		$j++;
		$currentrow++;
		
	}

	//Insert Total
	$mySheet->setCellValue('D'.$currentrow,'Total')
		->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
		->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")")
		->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow - 1).")");
	$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
	
	
	//Apply style for Content row
	$mySheet->getStyle('B'.$databeginrow.':G'.$currentrow)->applyFromArray($styleArrayContent);
	$mySheet->getColumnDimension('B')->setWidth(10);
	$mySheet->getColumnDimension('C')->setWidth(10);
	$mySheet->getColumnDimension('D')->setWidth(40);
	$mySheet->getColumnDimension('E')->setWidth(20);
	$mySheet->getColumnDimension('F')->setWidth(20);
	$mySheet->getColumnDimension('G')->setWidth(10);
	
	// Increment page Index and add new sheet
	$pageindex++;
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($pageindex);

	//Set Active Sheet	
	$mySheet = $objPHPExcel->getActiveSheet($pageindex);
	
	//Set the worksheet name
	$mySheet->setTitle('Regionwise Summary');
	$currentrow = 1;
	$slno1 = 0;
	//Set heading
	$mySheet->setCellValue('A'.$currentrow,'Regionwise Summary');
	
	$currentrow++;
	//Set table headings
	$objPHPExcel->setActiveSheetIndex($pageindex)
			->setCellValue('B'.$currentrow,'SL No')
			->setCellValue('C'.$currentrow,'Managed Area')
			->setCellValue('D'.$currentrow,'Not Viewed')
			->setCellValue('E'.$currentrow,'Un Attended')
			->setCellValue('F'.$currentrow,'Total');
			
	$j = 3;		
	//Apply style for header Row
	$mySheet->getStyle('B'.$currentrow.':F'.$currentrow)->applyFromArray($styleArray);
	$currentrow++;
	$databeginrow = $currentrow;
	$result10 = runmysqlquery($query10);
	while($fetchdetails = mysqli_fetch_array($result10))
	{
		
		$slno1++;
		$total = $fetchdetails['notviewed'] + $fetchdetails['unattended'];
		if($total == '0')$total = '';
		else $total = $total;
		$mySheet->setCellValue('B'.$j,$slno1)
		->setCellValue('C'.$j,$fetchdetails['managedarea'])
		->setCellValue('D'.$j,$fetchdetails['notviewed'])
		->setCellValue('E'.$j,$fetchdetails['unattended'])
		->setCellValue('F'.$j,$total);
		$j++;
		$currentrow++;
		
	}
	
	//Insert Total
	$mySheet->setCellValue('C'.$currentrow,'Total')
		->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow - 1).")")
		->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow - 1).")")
		->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow - 1).")");
	$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
	$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
	
	//Apply style for Content row
	$mySheet->getStyle('B'.$databeginrow.':G'.$currentrow)->applyFromArray($styleArrayContent);
	$mySheet->getColumnDimension('B')->setWidth(10);
	$mySheet->getColumnDimension('C')->setWidth(10);
	$mySheet->getColumnDimension('D')->setWidth(40);
	$mySheet->getColumnDimension('E')->setWidth(20);
	$mySheet->getColumnDimension('F')->setWidth(20);
	$mySheet->getColumnDimension('G')->setWidth(10);
		
	$filebasename = "LEAD REPORT SUMMARY-H S Nagendra.xls";
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save($filepath.$filebasename);
		
	//Empty the RAM by clearing the ExcelObject
	$objPHPExcel->disconnectWorksheets();
	unset($objPHPExcel);
	
	//Convert the file to ZIP format
	$filezipname = "LEAD REPORT SUMMARY-H S Nagendra.zip";
	$zip = new ZipArchive;
	$newzip = $zip->open($filepath.$filezipname, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
	if ($newzip === TRUE) {
		$zip->addFile($filepath.$filebasename, $filebasename);
		$zip->close();
	}

	// Begin email 
	$name = 'H S Nagendra';
	$days = $interval.' Days';
	$array = array();
	$array[] = "##NAME##%^%".$name;
	$array[] = "##COUNT##%^%".$leadcount;
	$array[] = "##DAYS##%^%".$days;
	$array[] = "##TABLE##%^%".$grid;

	$message = file_get_contents("../inc/managermail.htm");
	$message = replacemailvariablenew($message,$array);
	
	
	// Mailing.
	require_once("../inc/RSLMAIL_MAIL.php");
	$FromAddress=  "lms@relyon.co.in"; 
	$fromname = "Relyon-LMS";
	//$toarray = array($name => 'archana.ab@relyonsoft.com');
	$toarray = array($name => 'hsn@relyonsoft.com');
	$bccarray = array('Relyonimax' =>'relyonimax@gmail.com');
	$mailsubject = 'Leads due at LMS left NotViewed/UnAttended';
	$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
	$html = $message; 
	$filearray1 = array(
	array($filepath.$filezipname,'attachment','1234567891')
	);
	
	rslmail($fromname,$FromAddress,$toarray,$mailsubject,$text,$html,null,$bccarray,$filearray1);
	fileDelete($filepath,$filezipname) ;
	fileDelete($filepath,$filebasename) ;
}

?>