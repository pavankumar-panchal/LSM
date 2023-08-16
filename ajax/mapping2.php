<?

include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];

switch($submittype)
{
	case "form_state":
		$code = $_POST['code'];
		$query = "Select distinct distname, distcode from regions where statecode = '".$code."'";
		$result = runmysqlquery($query);
		$count = mysqli_num_rows($result);
		if($count > 0)
		{
			echo('<select name="form_district" id="form_district" onchange="regionselect()">');
			echo('<option value="" selected="selected"> - Make Selection - </option>');
			while($array = mysqli_fetch_array($result))
			{
				echo('<option value="'.$array['distcode'].'" >'.$array['distname'].'</option>');
			}
			echo('</select>');
		}
		else
		{
			echo('<select name="form_district" id="form_district" onchange="regionselect()">');
			echo('<option value="" selected="selected">- - - -Select a State First - - - -</option>');
			echo('</select>');
		}
		break;


	case "form_district":
		$code = $_POST['code'];
		$query = "Select subdistname, subdistcode from regions where distcode = '".$code."'";
		$result = runmysqlquery($query);
		$count = mysqli_num_rows($result);
		if($count > 0)
		{
			echo('<select name="form_region" id="form_region">');
			echo('<option value="" selected="selected"> - Make Selection - </option>');
			while($array = mysqli_fetch_array($result))
			{
				echo('<option value="'.$array['subdistcode'].'" >'.$array['subdistname'].'</option>');
			}
			echo('</select>');
		}
		else
		{
			echo('<select name="form_region" id="form_region">');
			echo('<option value="" selected="selected">- - - -Select a District First - - - -</option>');
			echo('</select>');
		}
		break;

	case "save":
		$form_recid = $_POST['form_recid'];
		$form_region = $_POST['form_region'];
		$form_prdcategory = $_POST['form_prdcategory'];
		$form_dealerid = $_POST['form_dealerid'];
		if($form_recid == "")
		{
			if($message == "")
			{
				$query1 = "SELECT * FROM mapping WHERE regionid = '".$form_region."' AND prdcategory = '".$form_prdcategory."'";
				$result = runmysqlquery($query1);
				$count = mysqli_num_rows($result);
				if($count > 0)
				{
					$fetch = mysqli_fetch_array($result);
					$message = "2^A dealer has already been assigned to this Region and Category. [".$fetch['dealerid']."]";
				}
			}
			if($message == "")
			{
				$query = "insert into `mapping` (dealerid, regionid, productid, prdcategory)values('".$form_dealerid."', '".$form_region."', '', '".$form_prdcategory."')";
				$result = runmysqlquery($query); 
				// Insert logs on save of Lead Mapping
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','21','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery_log($query);
				$message = "1^Data Saved Successfully.";
			}
		}
		else
		{
			if($message == "")
			{
				$query2 = "SELECT * FROM mapping WHERE regionid = '".$form_region."' AND prdcategory = '".$form_prdcategory."'";
				$result = runmysqlquery($query2);
				$count = mysqli_num_rows($result);
				if($count > 0)
				while($fetch = mysqli_fetch_array($result))
				{
					if($fetch['id'] <> $form_recid)
					$message = "2^Some other dealer has already been assigned to this Region and Category. [".$fetch['dealerid']."]";
				}
			}
			if($message == "")
			{
				$query = "UPDATE mapping SET dealerid = '".$form_dealerid."', regionid = '".$form_region."', prdcategory = '".$form_prdcategory."' WHERE id = '".$form_recid."'";
				$result = runmysqlquery($query); 
				// Insert logs on update of Lead Mapping
				$query1 = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','44','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery_log($query);
				$message = "1^Data Updated Successfully.";
			}
		}
		echo($message);
		break;


	case "delete":
		$form_recid = $_POST['form_recid'];
		$query = "DELETE FROM mapping WHERE id = '".$form_recid."'";
		$result = runmysqlquery($query); 
		// Insert logs on save of Lead Mapping
		$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','45','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
		$result = runmysqlquery_log($query);
		$message = "1^Data Deleted Successfully.";
		echo($message);
		break;

	case "griddata":
		//$mapdlrid = lmsgetcookie('mapdlrid');  
		$mapdlrid = $_COOKIE['mapdlrid'];
 		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$query1 = "select mapping.id, dealers.dlrcompanyname, regions.statename, regions.distname, regions.subdistname, mapping.prdcategory from mapping JOIN regions on regions.subdistcode = mapping.regionid JOIN dealers on dealers.id = mapping.dealerid WHERE mapping.dealerid = '".$mapdlrid."' ORDER BY mapping.id";
		$result1 = runmysqlquery($query1);
		$fetchresultcount = mysqli_num_rows($result1);
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
		if($slnocount == '0')
		{
			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
			//Write the header Row of the table
			$grid .= '<tr class="gridheader"><td nowrap="nowrap" class="tdborder">ID</td><td nowrap="nowrap"  class="tdborder">Dealer</td><td nowrap="nowrap"  class="tdborder">State</td><td nowrap="nowrap"  class="tdborder">District</td><td nowrap="nowrap"  class="tdborder">Region</td><td nowrap="nowrap"  class="tdborder">Category</td></tr>';
		}
		$query = "select mapping.id, dealers.dlrcompanyname, regions.statename, regions.distname, regions.subdistname, mapping.prdcategory from mapping JOIN regions on regions.subdistcode = mapping.regionid JOIN dealers on dealers.id = mapping.dealerid WHERE mapping.dealerid = '".$mapdlrid."' ORDER BY mapping.id LIMIT ".$startlimit.",".$limit.";";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_row($result))
		{
			$slnocount++;
			//Begin a row
			$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
			
			//Write the cell data
			for($i = 0; $i < count($fetch); $i++)
			{
				$grid .= "<td nowrap='nowrap' class='tdborder'>&nbsp;".gridtrim30($fetch[$i])."</td>";
			}
		
			//End the Row
			$grid .= '</tr>';
		}
		//End of Table
		$grid .= '</tbody></table>';
		if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';		
		echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
		
		break;

	case "gridtoform":
			$form_recid = $_POST['form_recid'];
			$query = "select mapping.id, regions.statecode, regions.distcode, mapping.regionid, mapping.prdcategory from mapping JOIN regions on regions.subdistcode = mapping.regionid WHERE mapping.id = '".$form_recid."'";
			$result1 = runmysqlqueryfetch($query);
			$output = $result1['id']."^".$result1['statecode']."^".$result1['distcode']."^".$result1['regionid']."^".$result1['prdcategory'];
		echo('1^'.$output);
		break;

}
?>