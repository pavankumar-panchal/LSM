<?

ini_set('memory_limit', '2048M');
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '')
{
	$cookie_username = lmsgetcookie('lmsusername');
	$cookie_usertype = lmsgetcookie('lmsusersort');
	
	$date = datetimelocal('YmdHis');
	
	$fromdate = changedateformat($_POST['fromdate']);
	$todate = changedateformat($_POST['todate']);
	$givenby = $_POST['givenby'];
	$leadstatus = $_POST['leadstatus']; 
	$filter_followupdate1 = $_POST['filter_followupdate1hdn'];
	$filter_followupdate2 = $_POST['filter_followupdate2hdn'];
	$dropterminatedstatus = $_POST['dropterminatedstatus'];
	$attachpiece = 'from '.$_POST['fromdate'].'  to '.$_POST['todate'].'';
	$datetimepiece = "substring(leads.leaddatetime,1,10) between '".$fromdate."' AND  '".$todate."'"; 
	if($filter_followupdate1 == 'dontconsider')
	{
		$followuppiece = "";
	}
	else
	{
		$followuppiece = "AND lms_followup.followupdate >= '".changedateformat($filter_followupdate1)."' AND lms_followup.followupdate <= '".changedateformat($filter_followupdate2)."'";
	}
	
	$leadstatuspiece = ($leadstatus == '')?"":("AND leadstatus = '".$leadstatus."'");
	$leaduploadedby = ($givenby == '')?"":(($givenby == 'web')?"AND leads.leaduploadedby IS NULL":"AND leads.leaduploadedby = '".$givenby."'");
	$terminatedstatuspiece = ($dropterminatedstatus == 'true')?("AND leads.leadstatus <> 'Order Closed' AND leads.leadstatus <> 'Not Interested' AND leads.leadstatus <> 'Fake Enquiry' AND leads.leadstatus <> 'Registered User'"):"";

	switch($cookie_usertype)
	{
		case "Admin":
		case "Sub Admin":
			$query = "select dealers.dlrcompanyname AS dlrcompanyname,NULLIF(productscount.sppleads,0) as sppleads,NULLIF(productscount.tdspleads,0) as tdsplaeads,NULLIF(productscount.tdscleads,0) as tdscleads,NULLIF(productscount.tdsileads,0) as tdsileads ,NULLIF(productscount.sitleads,0) as sitleads,NULLIF(productscount.stoleads,0) as stoleads,NULLIF(productscount.sacleads,0) as sacleads,NULLIF(productscount.otherleads,0) as otherleads ,dealers.dlremail AS dlremail, dealers.dlrcell AS cellnumber, lms_managers.mgrname AS mgrname from dealers 

left join lms_managers on lms_managers.id = dealers.managerid 

left join (select dealerid,count(leads.productid='1' or  null) as sppleads,count(leads.productid = '5' or null) as tdspleads,count(leads.productid = '7' or null) as tdscleads,count(leads.productid = '12' or null) as tdsileads,count(leads.productid = '21' or null) as sitleads,count(leads.productid = '17' or null) as stoleads,count(leads.productid = '22' or null) as sacleads,count(leads.productid <> '1' and leads.productid <> '5' and leads.productid <> '7' and leads.productid <> '12' and leads.productid <> '21' and leads.productid <> '17' and leads.productid <> '22' or null) as otherleads from leads left join lms_followup on lms_followup.leadid = leads.id  where lms_followup.followupstatus = 'PENDING' and  ".$datetimepiece." ".$terminatedstatuspiece." ".$leaduploadedby." ".$leadstatuspiece." ".$followuppiece." group by leads.dealerid) as productscount on dealers.id = productscount.dealerid group by dealers.dlrcompanyname order by dealers.id";
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
				
			$query = "select dealers.dlrcompanyname AS dlrcompanyname,NULLIF(productscount.sppleads,0) as sppleads,NULLIF(productscount.tdspleads,0) as tdsplaeads,NULLIF(productscount.tdscleads,0) as tdscleads,NULLIF(productscount.tdsileads,0) as tdsileads ,NULLIF(productscount.sitleads,0) as sitleads,NULLIF(productscount.stoleads,0) as stoleads,NULLIF(productscount.sacleads,0) as sacleads,NULLIF(productscount.otherleads,0) as otherleads ,dealers.dlremail AS dlremail, dealers.dlrcell AS cellnumber, lms_managers.mgrname AS mgrname from dealers 

left join lms_managers on lms_managers.id = dealers.managerid 

left join (select dealerid,count(leads.productid='1' or  NULL) as sppleads,count(leads.productid = '5' or null) as tdspleads,count(leads.productid = '7' or null) as tdscleads,count(leads.productid = '12' or null) as tdsileads,count(leads.productid = '21' or null) as sitleads,count(leads.productid = '17' or null) as stoleads,count(leads.productid = '22' or null) as sacleads,count(leads.productid <> '1' AND leads.productid <> '5' AND leads.productid <> '7' AND leads.productid <> '12' AND leads.productid <> '21' AND leads.productid <> '17' AND leads.productid <> '22' or null) as otherleads from leads left join lms_followup on lms_followup.leadid = leads.id  where lms_followup.followupstatus = 'PENDING' AND ".$datetimepiece." ".$terminatedstatuspiece." ".$leaduploadedby." ".$leadstatuspiece." ".$followuppiece." group by leads.dealerid) as productscount on dealers.id = productscount.dealerid  
left join lms_users on lms_users.referenceid = dealers.managerid  where  lms_users.type = 'Reporting Authority' ".$managercheckpiece."  ".$branchpiecejoin." group by dealers.dlrcompanyname order by dealers.id";
			break;
			
		case "Dealer":
			$query = "select dealers.dlrcompanyname AS dlrcompanyname,NULLIF(productscount.sppleads,0) as sppleads,NULLIF(productscount.tdspleads,0) as tdsplaeads,NULLIF(productscount.tdscleads,0) as tdscleads,NULLIF(productscount.tdsileads,0) as tdsileads ,NULLIF(productscount.sitleads,0) as sitleads,NULLIF(productscount.stoleads,0) as stoleads,NULLIF(productscount.sacleads,0) as sacleads,NULLIF(productscount.otherleads,0) as otherleads ,dealers.dlremail AS dlremail, dealers.dlrcell AS cellnumber, lms_managers.mgrname AS mgrname from dealers 

left join lms_managers on lms_managers.id = dealers.managerid 

left join(select dealerid,count(leads.productid='1' or  NULL) as sppleads,count(leads.productid = '5' or null) as tdspleads,count(leads.productid = '7' or null) as tdscleads,count(leads.productid = '12' or null) as tdsileads,count(leads.productid = '21' or null) as sitleads,count(leads.productid = '17' or null) as stoleads,count(leads.productid = '22' or null) as sacleads,count(leads.productid <> '1' AND leads.productid <> '5' AND leads.productid <> '7' AND leads.productid <> '12' AND leads.productid <> '21' AND leads.productid <> '17' AND leads.productid <> '22' or null) as otherleads from leads left join lms_followup on lms_followup.leadid = leads.id  where lms_followup.followupstatus = 'PENDING' AND ".$datetimepiece." ".$terminatedstatuspiece." ".$leaduploadedby." ".$leadstatuspiece." ".$followuppiece." group by leads.dealerid) as productscount on dealers.id = productscount.dealerid 
left join lms_users on lms_users.referenceid = dealers.id  where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Dealer' group by dealers.dlrcompanyname order by dealers.id";
			break;
	} //echo($query); exit;
	$result = runmysqlquery($query);
	
	$objPHPExcel = new Spreadsheet();

		
	//Set Active Sheet	
	$mySheet = $objPHPExcel->getActiveSheet();
	$styleArray = array(
		'font' => array('bold' => true),
		'fill' => array('fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => array('argb' => '0099CCFF')),
		'borders' => array('allBorders' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))

	);

	//Apply style for header Row	
	$mySheet->getStyle('A3:M3')->applyFromArray($styleArray);
	
	//Merge cells
	$mySheet->mergeCells('A1:M1');
	$mySheet->mergeCells('A2:M2');
	
	// To align the text to center.
	$mySheet->getStyle('A1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$mySheet->getStyle('A2:M2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

	// $mySheet->getStyle('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	// $mySheet->getStyle('A2:M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
				->setCellValue('A2', 'Leads Alloted '.' '.$attachpiece);
	$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
	$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
	$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
	//File contents for Header Row
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A3', 'Sl No')
		->setCellValue('B3', 'Dealer Name')
		->setCellValue('C3', 'Saral Pay Pack')
		->setCellValue('D3', 'TDS - Professional')
		->setCellValue('E3', 'TDS - Corporate')
		->setCellValue('F3', 'TDS - Institutional')
		->setCellValue('G3', 'Saral IncomeTax')
		->setCellValue('H3', 'Saral TaxOffice')
		->setCellValue('I3', 'Saral Accounts')
		->setCellValue('J3', 'Others')
		->setCellValue('K3', 'Dealer Email')
		->setCellValue('L3', 'Cell')
		->setCellValue('M3', 'Manager Name');
	
	$j = 4;
	$slno = 0;
	while($fetch = mysqli_fetch_array($result))
	{
		//set_time_limit(20);
		$slno++;
		$mySheet->setCellValue('A'.$j,$slno)
			->setCellValue('B'.$j,$fetch['dlrcompanyname'])
			->setCellValue('C'.$j,$fetch['sppleads'])
			->setCellValue('D'.$j,$fetch['tdspleads'])
			->setCellValue('E'.$j,$fetch['tdscleads'])
			->setCellValue('F'.$j,$fetch['tdsileads'])
			->setCellValue('G'.$j,$fetch['sitleads'])
			->setCellValue('H'.$j,$fetch['stoleads'])
			->setCellValue('I'.$j,$fetch['sacleads'])
			->setCellValue('J'.$j,$fetch['otherleads'])
			->setCellValue('K'.$j,$fetch['dlremail'])
			->setCellValue('L'.$j,$fetch['cellnumber'])
			->setCellValue('M'.$j,$fetch['mgrname']);
			$j++;
	}	
	
	//Define Style for content area
	$styleArrayContent = array(
		'borders' => array('allBorders' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))
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
	$mySheet->getColumnDimension('A')->setWidth(10);
	$mySheet->getColumnDimension('B')->setWidth(36);
	$mySheet->getColumnDimension('C')->setWidth(14);
	$mySheet->getColumnDimension('D')->setWidth(18);
	$mySheet->getColumnDimension('E')->setWidth(16);
	$mySheet->getColumnDimension('F')->setWidth(18);
	$mySheet->getColumnDimension('G')->setWidth(18);
	$mySheet->getColumnDimension('H')->setWidth(16);
	$mySheet->getColumnDimension('I')->setWidth(16);
	$mySheet->getColumnDimension('J')->setWidth(10);
	$mySheet->getColumnDimension('K')->setWidth(35);
	$mySheet->getColumnDimension('L')->setWidth(20);
	$mySheet->getColumnDimension('M')->setWidth(25);	
	
	$query1 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','38','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
	$result1 = runmysqlquery($query1);
	
	$filebasename = "LMS-DLRWISELEADS-".$cookie_username."-".$date.".xls";
	
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
	
	$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');

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
