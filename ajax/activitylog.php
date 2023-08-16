<?

include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'searchactivity':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
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

		
		$resultcount = "select  count(lms_logs_event.slno) as count
from lms_logs_event 
left join lms_users on lms_users.id =  lms_logs_event.userid 
left join lms_logs_eventtype on lms_logs_eventtype.slno =  lms_logs_event.eventtype
where (left(lms_logs_event.eventdatetime,10) between '".$fromdate."' AND '".$todate."')   ".$generatedpiece.$eventtypepiece." order by lms_logs_event.slno;";
		$fetch10 = runmysqlqueryfetch_log($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		if($showtype == 'all')
		$limit = 100000;
		else
		$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slnocount = 0;
		}
		else
		{
			$startlimit = $slnocount ;
			$slnocount = $slnocount;
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
		$result = runmysqlquery_log($query);
		$fetchresultcount = mysqli_num_rows($result);
		$addlimit = " LIMIT ".$startlimit.",".$limit.";";
		$query1 = $query.$addlimit;
		$result1 = runmysqlquery($query1);
	//	echo($query1);exit;
		$grid = '';
			
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0"  id="gridtable">';
		$grid .= '<tr class="gridheader"><td nowrap = "nowrap" class="tdborder" align="left"  width="10%" >Sl No</td><td nowrap = "nowrap" class="tdborder" align="left" width="20%">Event Type</td><td nowrap = "nowrap" class="tdborder" align="left"  width="20%">Username</td><td nowrap = "nowrap" class="tdborder" align="left"  width="15%">Type</td><td nowrap = "nowrap" class="tdborder" align="left"  width="15%">System IP</td><td nowrap = "nowrap" class="tdborder" align="left"  width="15%">Remarks</td><td nowrap = "nowrap" class="tdborder" align="left"  width="15%">Date</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result1))
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
			
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
				$grid .= '<tr bgcolor='.$color.'  align="left">';
				$grid .= "<td nowrap='nowrap' class='tdborder' align='left'  width='10%'>".$slnocount."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborder' align='left' width='20%'>".$fetch['eventtype']."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborder' align='left' width='20%'>".$name."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborder' align='left' width='15%'>".$fetch['type']."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborder' align='left' width='15%'>".$fetch['system']."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborder' align='left' width='15%'>".$remarks."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborder' align='left' width='15%'>".changedateformatwithtime($fetch['eventdatetime'])."</td>";
				$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
	
			echo("1|^|".$grid."|^|".$linkgrid."|^|".$fetchresultcount);
	}
	break;
	
	case 'griddata':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		
		
		$resultcount = "select  count(lms_logs_event.slno) as count
from lms_logs_event 
left join lms_users on lms_users.id =  lms_logs_event.userid 
left join lms_logs_eventtype on lms_logs_eventtype.slno =  lms_logs_event.eventtype
where left(lms_logs_event.eventdatetime,10) = '".date('Y-m-d')."' order by lms_logs_event.slno;";
		$fetch10 = runmysqlqueryfetch_log($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		if($showtype == 'all')
		$limit = 100000;
		else
		$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slnocount = 0;
		}
		else
		{
			$startlimit = $slnocount ;
			$slnocount = $slnocount;
		}
		
		
		$query = "select  lms_logs_eventtype.eventtype,lms_logs_event.eventdatetime,
lms_logs_event.remarks,lms_logs_event.system,lms_users.referenceid,lms_users.type
from lms_logs_event 
left join lms_users on lms_users.id =  lms_logs_event.userid 
left join lms_logs_eventtype on lms_logs_eventtype.slno =  lms_logs_event.eventtype  
where left(lms_logs_event.eventdatetime,10) = '".date('Y-m-d')."' order by lms_logs_event.slno";
			
		$result = runmysqlquery_log($query);
		$fetchresultcount = mysqli_num_rows($result);
		$addlimit = " LIMIT ".$startlimit.",".$limit.";";
		$query1 = $query.$addlimit;
		$result1 = runmysqlquery($query1);
	//	echo($query1);exit;
		$grid = '';
			
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0"  id="gridtable">';
		$grid .= '<tr class="gridheader"><td nowrap = "nowrap" class="tdborder" align="left"  width="10%" >Sl No</td><td nowrap = "nowrap" class="tdborder" align="left" width="20%">Event Type</td><td nowrap = "nowrap" class="tdborder" align="left"  width="20%">Username</td><td nowrap = "nowrap" class="tdborder" align="left"  width="15%">Type</td><td nowrap = "nowrap" class="tdborder" align="left"  width="15%">System IP</td><td nowrap = "nowrap" class="tdborder" align="left"  width="15%">Remarks</td><td nowrap = "nowrap" class="tdborder" align="left"  width="15%">Date</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result1))
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
			
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
				$grid .= '<tr bgcolor='.$color.'  align="left">';
				$grid .= "<td nowrap='nowrap' class='tdborder' align='left'  width='10%'>".$slnocount."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborder' align='left' width='20%'>".$fetch['eventtype']."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborder' align='left' width='20%'>".$name."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborder' align='left' width='15%'>".$fetch['type']."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborder' align='left' width='15%'>".$fetch['system']."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborder' align='left' width='15%'>".$remarks."</td>";
				$grid .= "<td nowrap='nowrap' class='tdborder' align='left' width='15%'>".changedateformatwithtime($fetch['eventdatetime'])."</td>";
				$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
	
			echo("1|^|".$grid."|^|".$linkgrid."|^|".$fetchresultcount);
	}
	break;
	
}
?>