<?

//error_reporting(1);
ini_set('memory_limit', '2048M');
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//PHPExcel

if (lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '') {
	//Check who is making the entry
	$cookie_username = lmsgetcookie('lmsusername');
	$cookie_usertype = lmsgetcookie('lmsusersort');

	//Get the values submitted
	$searchtext = $_POST['searchcriteria'];
	$subselection = $_POST['databasefield'];
	$disabled = $_POST['disabled'];
	if ($disabled == 'all') {
		$disabledpiece = '';
	} else if ($disabled == 'yes') {
		$disabledpiece = "AND lms_users.disablelogin = 'yes'";
	} else if ($disabled == 'no') {
		$disabledpiece = "AND lms_users.disablelogin = 'no'";
	}
	//Create a File name syntax
	$date = datetimelocal('YmdHis');

	if ($cookie_usertype == "Reporting Authority") {
		//Check wheteher the manager is branch head or not
		$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '" . $cookie_username . "' AND lms_users.type = 'Reporting Authority';";
		$result1 = runmysqlqueryfetch($query1);
		if ($result1['branchhead'] == 'yes') {
			$branchpiecejoin = "AND (dealers.branch = '" . $result1['branch'] . "'  OR dealers.managerid = '" . $result1['managerid'] . "')";
			if ($cookie_username == "srinivasan")
				$managercheckpiece = "and (lms_users2.username = '" . $cookie_username . "' or  lms_users2.username = 'nagaraj')";
			else
				$managercheckpiece = "";
		} else {
			$branchpiecejoin = "";
			if ($cookie_username == "srinivasan")
				$managercheckpiece = "and (lms_users2.username = '" . $cookie_username . "' or  lms_users2.username = 'nagaraj')";
			else
				$managercheckpiece = " and (lms_users2.username = '" . $cookie_username . "')";
		}
	}

	switch ($subselection) {
		case "dlrid":

			if ($cookie_usertype == "Reporting Authority") {
				//Check wheteher the manager is branch head or not
				$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '" . $cookie_username . "' AND lms_users.type = 'Reporting Authority';";
				$result1 = runmysqlqueryfetch($query1);

				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.id like '%" . $searchtext . "%'  " . $managercheckpiece . " " . $disabledpiece . " " . $branchpiecejoin . " ORDER BY dealers.dlrcompanyname";
			} else {
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.id like '%" . $searchtext . "%' " . $disabledpiece . "  ORDER BY dealers.dlrcompanyname";
			}
			break;
		case "company":
			if ($cookie_usertype == "Reporting Authority") {

				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrcompanyname like '%" . $searchtext . "%' " . $managercheckpiece . " " . $disabledpiece . " " . $branchpiecejoin . " ORDER BY dealers.dlrcompanyname";
			} else {
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrcompanyname like '%" . $searchtext . "%' " . $disabledpiece . "  ORDER BY dealers.dlrcompanyname";
			}
			break;
		case "name":
			if ($cookie_usertype == "Reporting Authority") {

				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrname like '%" . $searchtext . "%'  " . $managercheckpiece . " " . $disabledpiece . " " . $branchpiecejoin . " ORDER BY dealers.dlrcompanyname";
			} else {
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrname like '%" . $searchtext . "%' " . $disabledpiece . " ORDER BY dealers.dlrcompanyname";
			}
			break;
		case "phone":
			if ($cookie_usertype == "Reporting Authority") {

				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrphone like '%" . $searchtext . "%'  " . $managercheckpiece . " " . $disabledpiece . " " . $branchpiecejoin . " ORDER BY dealers.dlrcompanyname";
			} else {
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrphone like '%" . $searchtext . "%' " . $disabledpiece . " ORDER BY dealers.dlrcompanyname";
			}
			break;
		case "email":
			if ($cookie_usertype == "Reporting Authority") {

				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlremail like '%" . $searchtext . "%'  " . $managercheckpiece . " " . $disabledpiece . " " . $branchpiecejoin . " ORDER BY dealers.dlrcompanyname";
			} else {
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlremail like '%" . $searchtext . "%'  " . $disabledpiece . " ORDER BY dealers.dlrcompanyname";
			}
			break;
		case "district":
			if ($cookie_usertype == "Reporting Authority") {

				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.district like '%" . $searchtext . "%'  " . $managercheckpiece . " " . $disabledpiece . " " . $branchpiecejoin . " ORDER BY dealers.dlrcompanyname";
			} else {
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.district like '%" . $searchtext . "%' " . $disabledpiece . " ORDER BY dealers.dlrcompanyname";
			}
			break;
		case "state":
			if ($cookie_usertype == "Reporting Authority") {

				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.state like '%" . $searchtext . "%'  " . $managercheckpiece . " " . $disabledpiece . " " . $branchpiecejoin . " ORDER BY dealers.dlrcompanyname";
			} else {
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.state like '%" . $searchtext . "%' " . $disabledpiece . " ORDER BY dealers.dlrcompanyname";
			}
			break;
		case "cell":
			if ($cookie_usertype == "Reporting Authority") {

				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrcell like '%" . $searchtext . "%'  " . $managercheckpiece . " " . $disabledpiece . " " . $branchpiecejoin . " ORDER BY dealers.dlrcompanyname";
			} else {
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE dealers.dlrcell like '%" . $searchtext . "%' " . $disabledpiece . " ORDER BY dealers.dlrcompanyname";
			}
			break;
		case "manager":
			if ($cookie_usertype == "Reporting Authority") {

				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE lms_managers.mgrname like '%" . $searchtext . "%'  " . $managercheckpiece . " " . $disabledpiece . " " . $branchpiecejoin . " ORDER BY dealers.dlrcompanyname";
			} else {
				$query = "select dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite, dealers.state AS state, dealers.district AS district, lms_managers.mgrname AS mgrname, lms_users.username AS dlrusername, lms_users2.username AS mgrusername from dealers join lms_managers on dealers.managerid = lms_managers.id join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' join lms_users AS lms_users2 on lms_users2.referenceid = lms_managers.id AND lms_users2.type = 'Reporting Authority' WHERE lms_managers.mgrname like '%" . $searchtext . "%' " . $disabledpiece . " ORDER BY dealers.dlrcompanyname";
			}
			break;
	}
	$result = runmysqlquery($query);

	// Create new PHPExcel object
	// $objPHPExcel = new PHPExcel();
	$objPHPExcel = new Spreadsheet();

	//Set Active Sheet	
	$mySheet = $objPHPExcel->getActiveSheet();


	$styleArray = array(
		'font' => array('bold' => true),
		'fill' => array('fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => array('argb' => '0099CCFF')),
		// 'borders' => array('allborders' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))
		'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))

	);




	$mySheet->getStyle('A3:L3')->applyFromArray($styleArray);
	//Merge the cell
	$mySheet->mergeCells('A1:L1');
	$mySheet->mergeCells('A2:L2');
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
		->setCellValue('B3', 'Dealer Id')
		->setCellValue('C3', 'Company')
		->setCellValue('D3', 'Name')
		->setCellValue('E3', 'Address')
		->setCellValue('F3', 'Cell')
		->setCellValue('G3', 'Phone')
		->setCellValue('H3', 'Emailid')
		->setCellValue('I3', 'Website')
		->setCellValue('J3', 'State')
		->setCellValue('K3', 'District')
		->setCellValue('L3', 'Manager');

	$j = 4;
	$slno = 0;
	while ($fetch = mysqli_fetch_array($result)) {
		//set_time_limit(20);
		$slno++;
		$mySheet->setCellValue('A' . $j, $slno)
			->setCellValue('B' . $j, $fetch['id'])
			->setCellValue('C' . $j, $fetch['dlrcompanyname'])
			->setCellValue('D' . $j, $fetch['dlrname'])
			->setCellValue('E' . $j, $fetch['dlraddress'])
			->setCellValue('F' . $j, $fetch['dlrcell'])
			->setCellValue('G' . $j, $fetch['dlrphone'])
			->setCellValue('H' . $j, $fetch['dlremail'])
			->setCellValue('I' . $j, $fetch['dlrwebsite'])
			->setCellValue('J' . $j, $fetch['state'])
			->setCellValue('K' . $j, $fetch['district'])
			->setCellValue('L' . $j, $fetch['mgrname']);
		$j++;
	}

	//Define Style for content area
	$styleArrayContent = array(
		'borders' => array('allBorders' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))

	);

	//Get the last cell reference
	$highestRow = $mySheet->getHighestRow();
	$highestColumn = $mySheet->getHighestColumn();
	$myLastCell = $highestColumn . $highestRow;

	//Deine the content range
	$myDataRange = 'A4:' . $myLastCell;


	if (mysqli_num_rows($result) <> 0) {
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


	// Insert logs Dealer List to excel
	$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('" . $userslno . "','" . $_SERVER['REMOTE_ADDR'] . "','32','" . datetimelocal("Y-m-d") . ' ' . datetimelocal("H:i:s") . "')";
	$result = runmysqlquery($query);


	$filebasename = "LMS-DLRS-" . $cookie_username . "-" . $date . ".xls";
	if ($_SERVER['HTTP_HOST'] == 'meghanab') {
		$filepath = $_SERVER['DOCUMENT_ROOT'].'/lms/filescreated/'.$filebasename;
		$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].'/lms/filescreated/'.$filebasename;

		

	} else {
		$filepath = $_SERVER['DOCUMENT_ROOT'].'/lms/'.$filebasename;
		$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].'/lms/'.$filebasename;
		
	}

	// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');


	ob_end_clean();
	$objWriter->save($filepath);
	$fp = fopen($filebasename, "wa+");
	if ($fp) {
		downloadfile($filepath);
		fclose($fp);
	}
	unlink($filebasename);
	exit;
}

?>