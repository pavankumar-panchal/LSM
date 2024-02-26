<?php

include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];
$linkgrid="";
$grid="";
$class="";
$disableuser="";
$message="";
switch($submittype)
{
	case "search":
		$form_recid = $_POST['form_recid'];
		$form_search = $_POST['form_search'];
		/*$form_email = $_POST['form_email'];*/
	
			if (isset($_POST['startlimit']))
			{
				$startlimit = $_POST['startlimit'];
			}
			else
			{
				$startlimit='';
			}
			if (isset($_POST['slnocount']))
			{
				$slnocount = $_POST['slnocount'];
			}
			else
			{
				$slnocount='';
			}
			if (isset($_POST['showtype']))
			{
				$showtype = $_POST['showtype'];
			}
			else
			{
				$showtype='';
			}
		$query1 = "SELECT count(*) as totalcount FROM lms_users WHERE username LIKE'%".$form_search."%'";
		$result1 = runmysqlqueryfetch($query1);
		$fetchresultcount = $result1['totalcount'];
		if($showtype == 'all')
		$limit = 10;
		else
		$limit = 5;
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
		if ($form_search=="")
		{
			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
			$grid .= '<tr class="gridheader">
			<td nowrap="nowrap" class = "tdborder" width = "10px">Kindly Enter Keywords, NAME, Cell no, OR E-mail ID </td></tr>';
			
		}
		else
		{
		if($slnocount == '0')
		{
			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
			//Write the header Row of the table
			$grid .= '<tr class="gridheader">
			<td nowrap="nowrap" class = "tdborder" width = "10px">Sl No</td>
			<td nowrap="nowrap" class = "tdborder">ID</td>
			<td nowrap="nowrap" class = "tdborder">Name</td>
			<td nowrap="nowrap" class = "tdborder">Username</td>
			<td nowrap="nowrap" class = "tdborder">Cell</td>
			<td nowrap="nowrap" class = "tdborder">Email ID</td>
			<td nowrap="nowrap" class = "tdborder">User Type</td>
			</tr>';
		}
		/*$query = "SELECT id, sadname,cell,sademailid FROM lms_subadmins ORDER BY sadname";*/
		$query1 = "SELECT lms_subadmins.id, lms_subadmins.sadname, lms_users.username,lms_subadmins.cell, lms_subadmins.sademailid, lms_users.type, lms_users.disablelogin From lms_users INNER JOIN lms_subadmins ON lms_users.referenceid=lms_subadmins.id WHERE (lms_subadmins.sademailid LIKE '%".$form_search."%' OR lms_users.username LIKE '%".$form_search."%' OR lms_subadmins.cell LIKE '%".$form_search."%' OR lms_subadmins.sadname LIKE '%".$form_search."%') AND (lms_users.type='Sub Admin')ORDER BY lms_subadmins.sadname";
		
		$result1 = runmysqlquery($query1);
		while($fetch1 = mysqli_fetch_row($result1))
		{
			if($fetch1[6]=='yes')
			{
				$class='disabledull';
				$disableid='disableid';
			}
			else
			{
				$class='gridrow';
				$disableid='tdborder';
			}

			$slnocount++;
			/*$class='gridrow';*/
			//Begin a row
			$grid .= '<tr class="'.$class.'" onclick="javascript:gridtoformsubadmin(\''.$fetch1[0].'\');">';
				
			$grid .= "<td nowrap='nowrap' class = 'tdborder'>".$slnocount."</td>";
			//Write the cell data
			for($i = 0; $i < (count($fetch1)-1); $i++)
			{
				$grid .= "<td nowrap='nowrap' class = 'tdborder'>".gridtrim30($fetch1[$i])."</td>";
			}
		
			//End the Row
			$grid .= '</tr>';
		
		}

		$query2 = "SELECT lms_managers.id, lms_managers.mgrname, lms_users.username,lms_managers.mgrcell, lms_managers.mgremailid, lms_users.type, lms_users.disablelogin From lms_users INNER JOIN lms_managers ON lms_users.referenceid=lms_managers.id WHERE (lms_managers.mgremailid LIKE '%".$form_search."%' OR lms_users.username LIKE '%".$form_search."%' OR lms_managers.mgrcell LIKE '%".$form_search."%' OR lms_managers.mgrname LIKE '%".$form_search."%') AND (lms_users.type='Reporting Authority') ORDER BY lms_managers.mgrname";
		
		$result2 = runmysqlquery($query2);
		while($fetch2 = mysqli_fetch_row($result2))
		{
			if($fetch2[6]=='yes')
			{
				$class='disabledull';
				$disableid='disableid';
			}
			else
			{
				$class='gridrow';
				$disableid='tdborder';
			}

			$slnocount++;
			/*$class='gridrow';*/
			//Begin a row
			$grid .= '<tr class="'.$class.'" onclick="javascript:gridtoformmgr(\''.$fetch2[0].'\');">';
			
			$grid .= "<td nowrap='nowrap' class = 'tdborder'>".$slnocount."</td>";
			//Write the cell data
			for($i = 0; $i < (count($fetch2)-1); $i++)
			{
				$grid .= "<td nowrap='nowrap' class = 'tdborder'>".gridtrim30($fetch2[$i])."</td>";
			}
		
			//End the Row
			$grid .= '</tr>';
		
		}

		$query3 = "SELECT dealers.id, dealers.dlrname, lms_users.username, dealers.dlrcell, dealers.dlremail, lms_users.type, lms_users.disablelogin From lms_users INNER JOIN dealers ON lms_users.referenceid=dealers.id WHERE (dealers.dlremail LIKE '%".$form_search."%' OR lms_users.username LIKE '%".$form_search."%' OR dealers.dlrcell LIKE '%".$form_search."%' OR dealers.dlrname LIKE '%".$form_search."%') AND (lms_users.type='Dealer') ORDER BY dealers.dlrname";

		$result3 = runmysqlquery($query3);
		while($fetch3 = mysqli_fetch_row($result3))
		{
			if($fetch3[6]=='yes')
			{
				$class='disabledull';
				$disableid='disableid';
			}
			else
			{
				$class='gridrow';
				$disableid='tdborder';
			}
			$slnocount++;
			/*$class='gridrow';*/
			//Begin a row
			$grid .= '<tr class="'.$class.'" onclick="javascript:gridtoformdlr(\''.$fetch3[0].'\');">';
			
			$grid .= "<td nowrap='nowrap' class = 'tdborder'>".$slnocount."</td>";
			//Write the cell data
			for($i = 0; $i < (count($fetch3)-1); $i++)
			{
				$grid .= "<td nowrap='nowrap' class = 'tdborder'>".gridtrim30($fetch3[$i])."</td>";
			}
		
			//End the Row
			$grid .= '</tr>';
		
		}
		}
		//End of Table Count of Pagination
		$grid .= '</tbody></table>';
		/*if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';*/		
		
		echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
		break;


	case "gridtoformsubadmin":
	
			$form_recid = $_POST['form_recid'];
			$query = "SELECT * FROM lms_users WHERE referenceid = '".$form_recid."' and type = 'Sub Admin'";
			$result1 = runmysqlqueryfetch($query);
			$query = "SELECT * FROM lms_subadmins WHERE id = '".$form_recid."'";
			$result2 = runmysqlqueryfetch($query);
			$output = $result2['id']."^".$result2['sadname']."^".$result2['sademailid']."^".$result1['username']."^".$result1['password']."^".$result2['transferuploadedleads']."^".$result1['disablelogin']."^".$result2['cell']."^".$result2['showmcacompanies'];
		echo('1^'.$output);
		break;
		
	case "gridtoformmgr":
			$form_recid = $_POST['form_recid'];
			$query = "SELECT * FROM lms_users  WHERE referenceid = '".$form_recid."' and type = 'Reporting Authority'";
			$result1 = runmysqlqueryfetch($query);
			$query = "SELECT * FROM lms_managers WHERE id = '".$form_recid."'";
			$result2 = runmysqlqueryfetch($query);
			$output = $result2['id']."^".$result2['mgrname']."^".$result2['mgrlocation']."^".$result2['mgremailid']."^".$result2['mgrcell']."^".$result1['username']."^".$result1['password']."^".$result2['transferuploadedleads']."^".$result1['disablelogin']."^".$result2['managedarea']."^".$result2['branch']."^".$result2['branchhead']."^".$result2['showmcacompanies'];
		echo('1^'.$output);
		break;
		
	case "gridtoformdlr":
			$form_recid = $_POST['form_recid'];
			$query = "SELECT * FROM dealers WHERE id = '".$form_recid."'";
			$result1 = runmysqlqueryfetch($query);
			$query = "SELECT * FROM lms_users WHERE referenceid = '".$form_recid."' and type = 'Dealer'";
			$result2 = runmysqlqueryfetch($query);
			$output = $result1['id']."^".$result1['stateid']."^".$result1['districtid']."^".$result1['dlrname']."^".$result1['dlrcompanyname']."^".$result1['dlraddress']."^".$result1['dlrcell']."^".$result1['dlrphone']."^".$result1['dlremail']."^".$result1['dlrwebsite']."^".$result1['managerid']."^".$result2['username']."^".$result2['password']."^".$result2['disablelogin']."^".$result2['relyonexecutive']."^".$result1['branch']."^".$result1['showmcacompanies'];
		echo('1^'.$output);
		break;
}
?>