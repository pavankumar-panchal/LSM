<?

ini_set('memory_limit', '2048M');
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");


require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if($cookie_usertype == "Admin")
{
	$cookie_username = lmsgetcookie('lmsusername');

	$fromdate = changedateformat($_POST['fromdate']);
	$todate = changedateformat($_POST['todate']);
	$databasefield = $_POST['databasefield'];
	$textfield = $_POST['textfield'];
	$eventtype = $_POST['eventtype'];
	$generatedby = $_POST['username'];
	$generatedbysplit = explode('^',$generatedby);
	if($generatedbysplit[1] == "[S]")
		$generatedbypiece1 = 'Sub Admin';
	elseif($generatedbysplit[1] == "[M]")
		$generatedbypiece1 = 'Reporting Authority';
	elseif($generatedbysplit[1] == "[D]")
		$generatedbypiece1 = 'Dealer';
	elseif($generatedbysplit[1] == "[DM]")
		$generatedbypiece1 = 'Dealer Member';	
	
	$generatedpiece = ($generatedby == "")?(""):(" AND lms_users.id = '".$generatedbysplit[0]."'");
	$eventtypepiece = ($eventtype == "")?(""):(" AND lms_logs_eventtype.slno = '".$eventtype."'");
	
	$start_ts = strtotime($fromdate);
	$end_ts = strtotime($todate);
	$datediff = $end_ts - $start_ts;
	$noofdays = round($datediff / 86400);
	if($noofdays > 6)
	{
		echo('2^'.'Date limit should be within 7 days');
		exit;
	}

	switch($databasefield)
		{
			case "systemip":
				$query = "select  lms_logs_eventtype.eventtype,lms_logs_event.eventdatetime,
lms_logs_event.remarks,lms_logs_event.system,lms_users.referenceid,lms_users.type
from lms_logs_event 
left join lms_users on lms_users.id =  lms_logs_event.userid 
left join lms_logs_eventtype on lms_logs_eventtype.slno =  lms_logs_event.eventtype  
where (left(lms_logs_event.eventdatetime,10) between '".$fromdate."' AND '".$todate."') AND  lms_logs_event.system LIKE '%".$textfield."%' ".$generatedpiece.$eventtypepiece." order by lms_logs_event.slno";
				break;
			default:
				$query = "select  lms_logs_eventtype.eventtype,lms_logs_event.eventdatetime,
lms_logs_event.remarks,lms_logs_event.system,lms_users.referenceid,lms_users.type
from lms_logs_event 
left join lms_users on lms_users.id =  lms_logs_event.userid 
left join lms_logs_eventtype on lms_logs_eventtype.slno =  lms_logs_event.eventtype  
where (left(lms_logs_event.eventdatetime,10) between '".$fromdate."' AND '".$todate."') AND  lms_logs_event.userid LIKE '%".$textfield."%' ".$generatedpiece.$eventtypepiece." order by lms_logs_event.slno";
				break;
		}
	
	$result = runmysqlquery($query);
	
	// Create new PHPExcel object
	$objPHPExcel = new Spreadsheet();

	
	//Set Active Sheet	
	$mySheet = $objPHPExcel->getActiveSheet();
	$styleArray = array(
		'font' => array('bold' => true),
		'fill' => array('fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => array('argb' => '0099CCFF')),
		'borders' => array('allBorders' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))

	);

	$mySheet->getStyle('A3:G3')->applyFromArray($styleArray);	
	//Merge the cell
	$mySheet->mergeCells('A1:G1');
	$mySheet->mergeCells('A2:G2');
	/*// To align the text to center.
	$mySheet->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$mySheet->getStyle('A2:L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/
	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
				->setCellValue('A2', 'Dealer Details');
	$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
	$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
	$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
	//File contents for Header Row
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A3', 'Sl No')
		->setCellValue('B3', 'Event Type')
		->setCellValue('C3', 'Username')
		->setCellValue('D3', 'Type')
		->setCellValue('E3', 'System IP')
		->setCellValue('F3', 'Remarks')
		->setCellValue('G3', 'Date');
	
	$j = 4;
	$slno = 0;
	while($fetch = mysqli_fetch_array($result))
	{
		switch($fetch['type'])
		{
			case "Sub Admin":
				$query1 = "select * from lms_subadmins where id = '".$fetch['referenceid']."'";
				$fetch1 = runmysqlqueryfetch($query1); 
				$name = $fetch1['sadname'];
				break;
			case "Reporting Authority":
				$query1 = "select * from lms_managers where id = '".$fetch['referenceid']."' ";
				$fetch1 = runmysqlqueryfetch($query1); 
				$name = $fetch1['mgrname'];
				break;
			case "Dealer":
				$query1 = "select * from dealers where id = '".$fetch['referenceid']."'";
				$fetch1 = runmysqlqueryfetch($query1); 
				$name = $fetch1['dlrname'];
				break;
			
			case "Dealer Member":
				$query1 = "select * from lms_dlrmembers where dlrmbrid = '".$fetch['referenceid']."'";
				$fetch1 = runmysqlqueryfetch($query1); 
				$name = $fetch1['dlrmbrname'];
				break;
			default:
				$name = 'Admin';
				break;
		}
	if($fetch['remarks'] == "")
		$remarks = 'Not Avaliable';
	else
		$remarks = gridtrim30($fetch['remarks']);
		$slno++;
		$mySheet->setCellValue('A'.$j,$slno)
			->setCellValue('B'.$j,$fetch['eventtype'])
			->setCellValue('C'.$j,$name)
			->setCellValue('D'.$j,$fetch['type'])
			->setCellValue('E'.$j,$fetch['system'])
			->setCellValue('F'.$j,$remarks)
			->setCellValue('G'.$j,changedateformatwithtime($fetch['eventdatetime']));
	
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
	$mySheet->getColumnDimension('A')->setWidth(6);
	$mySheet->getColumnDimension('B')->setWidth(30);
	$mySheet->getColumnDimension('C')->setWidth(25);
	$mySheet->getColumnDimension('D')->setWidth(25);
	$mySheet->getColumnDimension('E')->setWidth(25);
	$mySheet->getColumnDimension('F')->setWidth(25);
	$mySheet->getColumnDimension('G')->setWidth(20);

	
	$filebasename = "LMS-ACTIVITYLOGS-".$cookie_username."-".$date.".xls";
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
else
{
	$url = '../reportslms/activitylog.php'; 
	header("location:".$url);
	exit;
}

?>
