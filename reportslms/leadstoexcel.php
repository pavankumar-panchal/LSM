<?php
ini_set('memory_limit', '-1');
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
	$fromdate = changedateformat($_POST['fromdate']);
	$todate = changedateformat($_POST['todate']);
	$dealerid = $_POST['dealerid'];
	$givenby = $_POST['givenby'];
	$productid = $_POST['productid'];
	$grouplabel = $_POST['hiddengrouplabel'];
	$leadstatus = $_POST['leadstatus'];
	$leadsubstatus = $_POST['leadsubstatus']; 
	$filter_followupdate1 = $_POST['filter_followupdate1hdn'];
	$filter_followupdate2 = $_POST['filter_followupdate2hdn'];
	$dropterminatedstatus = $_POST['dropterminatedstatus'];
	
	$leadsource = $_POST['hiddensource'];
	$leadsourcelist = ($leadsource == "")?"":("AND leads.refer = '".$leadsource."'");
	//$lastfollowupcheckpiece = "AND (followupstatus = 'PENDING' or lms_followup.followupstatus is null )";
	$searchtext = $_POST['searchcriteria'];
	$subselection = $_POST['databasefield'];
	$datatype = $_POST['datatype'];
	
	$lastupdatedby = $_POST['followedby'];
	$followupcheck = $_POST['followupradio'];
	$remarks = $_POST['remarks'];
	$datatype = ($datatype == "download")?"Product Download":(($datatype == "upload")?"Manual Upload":"");
	$sourcepiece = ($datatype == "")?"":("AND leads.source like '%".$datatype."%'");
	
	
	$attachpiece = 'from '.$_POST['fromdate'].'  to '.$_POST['todate'].'';
	if($filter_followupdate1 == 'dontconsider')
	{
		$followuppiece = "";
		$followupcheck = "";
		$remarkspeice = "";
	}
	else
	{
		$lastupdatedpiece = ($lastupdatedby == "")?"":(" AND lms_followup.enteredby = '".$lastupdatedby."'");
		$remarkspeice = ($remarks == "")?"":(" AND lms_followup.remarks like '%".$remarks."%'");
		if($followupcheck == 'followuppending')
		{
			$followuppiece = "AND followupgroupwise.followupdate >= '".changedateformat($filter_followupdate1)."' AND 
			followupgroupwise.followupdate <= '".changedateformat($filter_followupdate2)."'";
			
			$tempfollowuppiece = " AND lms_followup.followupdate >= '".changedateformat($filter_followupdate1)."' AND 
			lms_followup.followupdate <= '".changedateformat($filter_followupdate2)."' 
			AND lms_followup.followupstatus='PENDING'";
		}
		else if($followupcheck == 'followupmade')
		{
			$followuppiece = "AND followupgroupwise.entereddate >= '".changedateformat($filter_followupdate1)."' AND 
			followupgroupwise.entereddate <= '".changedateformat($filter_followupdate2)."'";
			
			$tempfollowuppiece = " AND lms_followup.entereddate >= '".changedateformat($filter_followupdate1)."' AND 
			lms_followup.entereddate <= '".changedateformat($filter_followupdate2)."'";
		}
	}
	switch($subselection)
	{
		case "leadid":
				$searchpiece = ($searchtext == '')?"":("AND leads.id like '%".$searchtext."%'");
				break;
		
		case "company":
				$searchpiece = ($searchtext == '')?"":("AND leads.company like '%".$searchtext."%'");
				break;
				
		case "name": 
				$searchpiece = ($searchtext == '')?"":("AND leads.name like '%".$searchtext."%'");
				break;
				
		case "phone":
				$searchpiece = ($searchtext == '')?"":("AND leads.phone like '%".$searchtext."%'");
				break;
		
		case "cell":
				$searchpiece = ($searchtext == '')?"":("AND leads.cell like '%".$searchtext."%'");
				break;
				
		case "email":
				$searchpiece = ($searchtext == '')?"":("AND leads.emailid like '%".$searchtext."%'");
				break;
		
		case "district":
				$searchpiece = ($searchtext == '')?"":("AND regions.distname like '%".$searchtext."%'");
				break;
		
		case "state":
				$searchpiece = ($searchtext == '')?"":("AND regions.statename like '%".$searchtext."%'"); 
				break;
		
		case "manager":
				$searchpiece = ($searchtext == '')?"":("AND lms_managers.mgrname like '%".$searchtext."%'");
				break;
	}
	
	$dealerpiece = ($dealerid == '')?"":("AND leads.dealerid = '".$dealerid."'");
	
	// Product 0r category piece
	if($grouplabel == 'Products')
	{
		$productpiece = ($productid == '')?"":("AND productid = '".$productid."'");
	}
	else if($grouplabel == 'Groups')
	{
		$productpiece = ($productid == '')?"":("AND products.category = '".$productid."'");
	}
	
	$leadstatuspiece = ($leadstatus == '')?"":("AND leadstatus = '".$leadstatus."'");
	$leadsubstatuspiece = ($leadsubstatus == '')?"":("AND leadsubstatus = '".$leadsubstatus."'");
	$leaduploadedby = ($givenby == '')?"":(($givenby == 'web')?"AND leaduploadedby IS NULL":"AND leaduploadedby = '".$givenby."'");
	$datetimepiece = "substring(leads.leaddatetime,1,10) between '".$fromdate."' AND  '".$todate."'"; 
	$terminatedstatuspiece = ($dropterminatedstatus == 'true')?("AND leads.leadstatus <> 'Order Closed' AND leads.leadstatus <> 'Not Interested' AND leads.leadstatus <> 'Fake Enquiry' AND leads.leadstatus <> 'Registered User'"):"";

	if(lmsgetcookie('lmsusername') <> '' && lmsgetcookie('lmsusersort') <> '' && checkdateformat($fromdate) && checkdateformat($todate) && ((datenumeric($todate) - datenumeric($fromdate)) >= 0))
	{
		//Check who is making the entry
		$cookie_username = lmsgetcookie('lmsusername');
		$cookie_usertype = lmsgetcookie('lmsusersort');
		
		$query3 = "Drop table if exists followupgroupwise;";
		$result3 = runmysqlquery($query3);
		
		$query2 = "CREATE TEMPORARY TABLE if not exists followupgroupwise(select 
		lms_followup.followupdate,lms_followup.remarks,lms_followup.leadid,lms_followup.entereddate,lms_followup.enteredby
        from lms_followup
       inner join (select leadid, max(followupid) as tfollowup
       from lms_followup
       left join leads on leads.id = lms_followup.leadid where ".$datetimepiece." ".$tempfollowuppiece." ".$lastupdatedpiece." 
	   group by leadid) maxt
       on (lms_followup.leadid = maxt.leadid and lms_followup.followupid = maxt.tfollowup));";
		$result2 = runmysqlquery($query2);
		
		switch($cookie_usertype)
		{
			case "Admin":
			case "Sub Admin":
				$query = "select leads.id, leads.leaddatetime, lms_users.username AS leaduploadedby, products.productname, leads.company, leads.name, leads.phone, leads.cell,leads.emailid,leads.address,leads.place,regions.distname, 
				regions.statename,leads.refer,dealers.dlrcompanyname, 
				lms_managers.mgrname,followupgroupwise.followupdate,followupgroupwise.enteredby,followupgroupwise.remarks,
				leads.leadstatus,leads.leadsubstatus,leads.leadstatusremarks,followupgroupwise.entereddate,leads.leadremarks as initialremarks 
				from leads 
				left join followupgroupwise on leads.id = followupgroupwise.leadid 
				join dealers on leads.dealerid = dealers.id 
				join lms_managers on lms_managers.id = dealers.managerid 
				join products on products.id = leads.productid 
				join regions on leads.regionid = regions.subdistcode 
				LEFT JOIN lms_users ON leads.leaduploadedby = lms_users.id 
				where ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." 
				".$dealerpiece." ".$productpiece." 
				".$leaduploadedby." ".$leadstatuspiece."  ".$searchpiece." 
				".$sourcepiece." ".$leadsourcelist." ".$leadsubstatuspiece." ORDER BY leads.id DESC";
				
				break;
			case "Reporting Authority":
						//Check wheteher the manager is branch head or not
						$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
						$result1 = runmysqlqueryfetch($query1);
						if($result1['branchhead'] == 'yes')
							$branchpiecejoin = "AND (dealers.branch = '".$result1['branch']."' OR dealers.managerid = '".$result1['managerid']."')";
						else
							$branchpiecejoin = "AND lms_users.username = '".$cookie_username."'";
							
				$query = "select leads.id, leads.leaddatetime, products.productname,lms_users.username AS leaduploadedby, 
				leads.company,leads.name,leads.phone,leads.cell, leads.emailid,leads.address,leads.place,regions.distname, 
				regions.statename,leads.refer,dealers.dlrcompanyname, 
				lms_managers.mgrname,followupgroupwise.followupdate,followupgroupwise.enteredby,followupgroupwise.remarks,
				leads.leadstatus,leads.leadsubstatus,leads.leadstatusremarks,followupgroupwise.entereddate,leads.leadremarks as initialremarks   
				from leads 
				left join followupgroupwise on leads.id = followupgroupwise.leadid 
				left join dealers on dealers.id = leads.dealerid 
				left join lms_managers on lms_managers.id=dealers.managerid 
				left join products on products.id = leads.productid 
				join regions on leads.regionid = regions.subdistcode 
				left join lms_users on lms_users.referenceid = lms_managers.id 
				where  ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece." 
				".$leaduploadedby." ".$leadstatuspiece." 
				AND lms_users.type = 'Reporting Authority' ".$searchpiece." ".$sourcepiece." ".
				$leadsourcelist." ".$branchpiecejoin." ".$leadsubstatuspiece." ORDER BY leads.id DESC";
				//echo($query);exit;
				break;
			
			case "Dealer":
				$query = "select leads.id, leads.leaddatetime, products.productname, lms_users.username AS leaduploadedby,
				leads.company,leads.name,leads.phone,leads.cell, leads.emailid,leads.address,leads.place,
				regions.distname, regions.statename,leads.refer,dealers.dlrcompanyname, 
				lms_managers.mgrname,followupgroupwise.followupdate,followupgroupwise.enteredby,followupgroupwise.remarks,
				leads.leadstatus,leads.leadsubstatus,leads.leadstatusremarks,followupgroupwise.entereddate ,leads.leadremarks as initialremarks  
				from leads 
				left join followupgroupwise on leads.id = followupgroupwise.leadid 
				left join dealers on dealers.id = leads.dealerid 
				left join lms_managers on lms_managers.id=dealers.managerid 
				left join products on products.id = leads.productid 
				join regions on leads.regionid = regions.subdistcode 
				left join lms_users on lms_users.referenceid = dealers.id 
				where  ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".$productpiece."
				 ".$leaduploadedby." ".$leadstatuspiece." 
				and lms_users.type = 'Dealer' and lms_users.username = '".$cookie_username."' ".$searchpiece." 
				".$sourcepiece." ".$leadsourcelist." ".$leadsubstatuspiece." ORDER BY leads.id DESC";
				break;
			case "Dealer Member":
				$query = "select leads.id, leads.leaddatetime, products.productname, lms_users.username AS leaduploadedby,
				leads.company,leads.name,leads.phone,leads.cell, leads.emailid,leads.address,leads.place,regions.distname, 
				regions.statename,leads.refer,dealers.dlrcompanyname, 
				lms_managers.mgrname,followupgroupwise.followupdate,followupgroupwise.enteredby,followupgroupwise.remarks,
				leads.leadstatus,leads.leadsubstatus,leads.leadstatusremarks,followupgroupwise.entereddate ,leads.leadremarks as initialremarks 
				from leads 
				left join followupgroupwise on leads.id = followupgroupwise.leadid 
				left join dealers on dealers.id = leads.dealerid 
				left join lms_dlrmembers on leads.dlrmbrid = lms_dlrmembers.dlrmbrid  
				left join  lms_managers on lms_managers.id=dealers.managerid 
				left join products on products.id = leads.productid 
				join regions on leads.regionid = regions.subdistcode 
				left join lms_users on lms_users.referenceid = lms_dlrmembers.dlrmbrid 
				where  ".$datetimepiece." ".$terminatedstatuspiece." ".$followuppiece." ".$dealerpiece." ".
				$productpiece." ".$leaduploadedby." ".$leadstatuspiece." and lms_users.type = 'Dealer Member'  
				and lms_users.username = '".$cookie_username."' ".$searchpiece." ".$sourcepiece." 
				".$leadsourcelist." ".$leadsubstatuspiece." ORDER BY leads.id DESC";
				break;
		} 
		//echo($query);
		//exit;
		$result = runmysqlquery($query); 
		$fetchcount =  mysql_num_rows($result);
		$quotient = $fetchcount/5000;
		$totallooprun = ($fetchcount % 5000 == 0)?($fetchcount/5000):(ceil($fetchcount/5000));
		$slno =0;
		$limit = 5000;
		for($i = 0; $i < $totallooprun ; $i++)
		{
			if($i == 0)
			{
				$startlimit = 0;
				$slno = 0;
			}
			else
			{
				$startlimit = $slno;
			}
			$addlimit = " LIMIT ".$startlimit.",".$limit."; ";
			$query1 = $query.$addlimit;
			$result1 = runmysqlquery($query1);
			
			
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
			/*if($followuppiece <> '')	
			{
				$mySheet->getStyle('A3:V3')->applyFromArray($styleArray);
			}
			else*/
				$mySheet->getStyle('A3:Z3')->applyFromArray($styleArray);	
			//Merge the cell
			/*if($followuppiece <> '')	
			{
				$mySheet->mergeCells('A1:V1');
				$mySheet->mergeCells('A2:V2');
				// To align the text to center.
				//$mySheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				//$mySheet->getStyle('A2:Q2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
							->setCellValue('A2', 'Leads'.' '.$attachpiece);
				$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
				$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
				$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
			}
			else
			{*/
				$mySheet->mergeCells('A1:Z1');
				$mySheet->mergeCells('A2:Z2');
				// To align the text to center.
				//$mySheet->getStyle('A1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				//$mySheet->getStyle('A2:P2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
							->setCellValue('A2', 'Leads'.' '.$attachpiece);
				$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
				$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
				$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
			//}
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
				->setCellValue('S3', 'Next followup Date')
				->setCellValue('T3', 'Lead Status')
				->setCellValue('U3', 'Lead Sub Status')
				->setCellValue('V3', 'Lead Status Remarks')
				->setCellValue('W3', 'Last Followup Remarks')
				->setCellValue('X3', 'Initial Remarks')
				->setCellValue('Y3', 'Uploaded By')
				->setCellValue('Z3', 'Lead Source');
			/*if($followuppiece <> '')
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q3', 'Remarks');*/
			
			$j = 4;
			while($fetch = mysql_fetch_array($result1))
			{
				$followupdate = ($fetch['entereddate'] == "")?"Not Avaliable":changedateformat($fetch['entereddate']);
				$nextfollowdate = ($fetch['followupdate'] == "")?"Not Avaliable":changedateformat($fetch['followupdate']);
				$enteredby = ($fetch['enteredby'] == "")?"Not Avaliable":getuserdisplayname($fetch['enteredby']);
				$lastfollremarks = ($fetch['remarks'] == "")?"Not Avaliable":$fetch['remarks'];
				$initialremarks = ($fetch['initialremarks'] == "")?"Not Avaliable":$fetch['initialremarks'];
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
					->setCellValue('Q'.$j,$followupdate)
					->setCellValue('R'.$j,$enteredby)
					->setCellValue('S'.$j,$nextfollowdate)
					->setCellValue('T'.$j,$fetch['leadstatus'])
					->setCellValue('U'.$j,$fetch['leadsubstatus'])
					->setCellValue('V'.$j,$fetch['leadstatusremarks'])
					->setCellValue('W'.$j,$lastfollremarks)
					->setCellValue('X'.$j,$initialremarks)
					->setCellValue('Y'.$j,$fetch['leaduploadedby'])
					->setCellValue('Z'.$j,$fetch['refer']);
				$flag = 1;
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
			
			
			if(mysql_num_rows($result1) <> 0)
			{
			//Apply style to content area range
				$mySheet->getStyle($myDataRange)->applyFromArray($styleArrayContent);
			}
			
		
			//set the default width for column
			$mySheet->getColumnDimension('A')->setWidth(6);
			$mySheet->getColumnDimension('B')->setWidth(15);
			$mySheet->getColumnDimension('C')->setWidth(18);
			$mySheet->getColumnDimension('D')->setWidth(22);
			$mySheet->getColumnDimension('E')->setWidth(25);
			$mySheet->getColumnDimension('F')->setWidth(30);
			$mySheet->getColumnDimension('G')->setWidth(15);
			$mySheet->getColumnDimension('H')->setWidth(18);
			$mySheet->getColumnDimension('I')->setWidth(43);
			$mySheet->getColumnDimension('J')->setWidth(45);
			$mySheet->getColumnDimension('K')->setWidth(15);
			$mySheet->getColumnDimension('L')->setWidth(25);
			$mySheet->getColumnDimension('M')->setWidth(26);
			$mySheet->getColumnDimension('N')->setWidth(25);
			$mySheet->getColumnDimension('O')->setWidth(35);
			$mySheet->getColumnDimension('P')->setWidth(30);
			$mySheet->getColumnDimension('Q')->setWidth(20);
			$mySheet->getColumnDimension('R')->setWidth(30);
			$mySheet->getColumnDimension('S')->setWidth(30);
			$mySheet->getColumnDimension('T')->setWidth(30);
			$mySheet->getColumnDimension('U')->setWidth(30);
			$mySheet->getColumnDimension('V')->setWidth(30);
			$mySheet->getColumnDimension('W')->setWidth(80);
			$mySheet->getColumnDimension('X')->setWidth(80);
			$mySheet->getColumnDimension('Y')->setWidth(80);
			$mySheet->getColumnDimension('Z')->setWidth(80);
			
			$query1 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','36','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
			$result = runmysqlquery($query1);	
			$filebasename = "LMS-LEADS-".$i.$cookie_username."-".$date.".xls";
			if($_SERVER['HTTP_HOST'] == '192.168.2.79' || $_SERVER['HTTP_HOST'] == 'bhumika')  
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
			
			$filearray[] = $filebasename;
			$filepatharray[] = $filepath;
		}	
		$filezipname = "LMS-LEADS-".$cookie_username."-".$date.".zip";
		if($_SERVER['HTTP_HOST'] == '192.168.2.79' || $_SERVER['HTTP_HOST'] == 'bhumika')  
		{
			$filezipnamepath = $_SERVER['DOCUMENT_ROOT'].'/LMS/filescreated/'.$filezipname;
			$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].'/LMS/filescreated/'.$filezipname;
		}
		else
		{
			$filezipnamepath = $_SERVER['DOCUMENT_ROOT'].'/filescreated/'.$filezipname;
			$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].'/filescreated/'.$filezipname; 
		}
			
		$zip = new ZipArchive;
		$newzip = $zip->open($filezipnamepath, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
		if ($newzip === TRUE)
		 {
				for($i = 0;$i <count($filearray);$i++)
				{
					$zip->addFile($filepatharray[$i], $filearray[$i]);
				}
				$zip->close();
		}
		for($i = 0;$i <count($filearray);$i++)
		{
			unlink($filepatharray[$i]) ;
		}
		downloadfile($filezipnamepath);
	}
}
?>