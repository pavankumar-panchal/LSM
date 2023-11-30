<?php
##include Files. . ##
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$linkgrid="";
$grid="";
$message="";
$submittype = $_POST['submittype'];
	
switch($submittype)
{
	case "griddata":
	
			$form_dlrlist = $_POST['form_dlrlist'];
			#echo "This is The dealer name ".$form_dlrlist;
			$serial=1;
			
			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
			
			//Write the header Row of the table
			$grid .= '<tr class="gridheader">
			<td nowrap="nowrap" class="tdborder">Sl no.</td>
			<td nowrap="nowrap" class="tdborder">ID</td>
			<td nowrap="nowrap"  class="tdborder">Dealer</td>
			<td nowrap="nowrap"  class="tdborder">State</td>
			<td nowrap="nowrap"  class="tdborder">District</td>
			<td nowrap="nowrap"  class="tdborder">Region</td>
			<td nowrap="nowrap"  class="tdborder">Category</td>
			</tr>';
			
			$query = "select mapping.id, dealers.dlrcompanyname, regions.statename as state, regions.distname, regions.subdistname, mapping.prdcategory from mapping JOIN regions on regions.subdistcode = mapping.regionid JOIN dealers on dealers.id = mapping.dealerid WHERE mapping.dealerid = '".$form_dlrlist."' ORDER BY mapping.id ";
			$result = runmysqlquery($query);
			$fetchresultcount = mysqli_num_rows($result);
			while($fetch = mysqli_fetch_row($result))
			{
				//Begin a row
				$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
				$grid .= "<td nowrap='nowrap' class='tdborder'>&nbsp;".$serial++."</td>";
				
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
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';

			echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
	break;
		

	case "gridlist":
		
			$form_dlrlist1 = $_POST['form_dlrlist1'];
			#echo "This is The dealer name ".$form_dlrlist;
			$serial=1;

			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
			//Write the header Row of the table
			$grid .= '<tr class="gridheader">
			<td nowrap="nowrap" class="tdborder">Sl no.</td>
			<td nowrap="nowrap" class="tdborder">ID</td>
			<td nowrap="nowrap"  class="tdborder">Dealer</td>
			<td nowrap="nowrap"  class="tdborder">State</td>
			<td nowrap="nowrap"  class="tdborder">District</td>
			<td nowrap="nowrap"  class="tdborder">Region</td>
			<td nowrap="nowrap"  class="tdborder">Category</td>
			</tr>';
		
			$query = "select mapping.id, dealers.dlrcompanyname, regions.statename, regions.distname, regions.subdistname, mapping.prdcategory from mapping JOIN regions on regions.subdistcode = mapping.regionid JOIN dealers on dealers.id = mapping.dealerid WHERE mapping.dealerid = '".$form_dlrlist1."' ORDER BY mapping.id";
			
			$result = runmysqlquery($query);
			$fetchresultcount = mysqli_num_rows($result);
			while($fetch = mysqli_fetch_row($result))
			{
				//Begin a row
				$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
				$grid .= "<td nowrap='nowrap' class='tdborder'>&nbsp;".$serial++."</td>";
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
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
	break;
	
	
	case "transferdata":
	
			$form_dlrlist = $_POST['form_dlrlist'];
			$form_dlrlist1 = $_POST['form_dlrlist1'];
			$form_state = $_POST['form_state'];
			$form_category = $_POST['form_category'];
			if($form_state=="all" || $form_category =="all")
			{
				$query = "UPDATE mapping set dealerid = '".$form_dlrlist1."' WHERE dealerid = '".$form_dlrlist."'";
			}
			else
			{
				$query = "UPDATE mapping INNER JOIN regions ON regions.subdistcode = mapping.regionid SET mapping.dealerid = '".$form_dlrlist1."' WHERE regions.statename = '".$form_state."' AND mapping.dealerid = '".$form_dlrlist."' AND mapping.prdcategory = '".$form_category."'";
			}
			$result = runmysqlquery($query); 
		
		// Inser logs on Transfer Mapping Data
			$query = "insert into lms_logs_event(userid,system,eventtype,remarks,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','83','Data Mapped to Dealerid ".$form_dlrlist1." From Dealerid ".$form_dlrlist."','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
			$result = runmysqlquery($query);
		
			$message = "1^Data Has Transfer Successfully!!.";
			
		echo ($message);
	break;


	case "state":

			$form_dlrlist = $_POST['form_dlrlist'];
			
			$query3= "SELECT DISTINCT regions.statename as state FROM mapping JOIN regions on regions.subdistcode=mapping.regionid WHERE mapping.dealerid=".$form_dlrlist;
			#$query3 = "select * from mapping where dealerid ='".$form_dlrlist."' ";
			echo('<option value="" selected="selected"> Select a State Name </option>');
			$result_data = mysqli_query($query3) or die('MySql Error' . mysqli_error());
			if(mysqli_num_rows($result_data) > 1)
			{
				echo('<option value="all"> -- ALL -- </option>');
			}
			$msg = "";
			while($fetch = mysqli_fetch_array($result_data))
			{
			
				$msg = $fetch['state'];
				echo('<option value="'.$msg.'">'.$msg.'</option>');
			}
	break;
	
		case "category":

			$form_dlrlist = $_POST['form_dlrlist'];
			$form_state = $_POST['form_state'];
			
			$query3= "SELECT DISTINCT mapping.prdcategory as category FROM mapping JOIN regions on regions.subdistcode=mapping.regionid WHERE mapping.dealerid='".$form_dlrlist."' and regions.statename = '".$form_state."'";
			/*$query3= "select DISTINCT mapping.prdcategory as category from mapping JOIN regions on 
			regions.subdistcode = mapping.regionid JOIN dealers on dealers.id = mapping.dealerid 
			WHERE mapping.dealerid = '".$form_dlrlist."' ORDER BY mapping.id";*/
			
			#$query3= "SELECT DISTINCT mapping.prdcategory as category FROM mapping JOIN regions on regions.subdistcode=mapping.regionid WHERE mapping.dealerid='".$form_dlrlist."' and mapping.regionid = ".$form_state;
			echo('<option value="" selected="selected"> Select a category </option>');
			$result_data = mysqli_query($query3) or die('MySql Error' . mysqli_error());
			if(mysqli_num_rows($result_data) > 1)
			{
				echo('<option value="all"> -- ALL -- </option>');
			}
			$msg = "";
			while($fetch = mysqli_fetch_array($result_data))
			{
			
				$msg= $fetch['category'];
				echo('<option value="'.$msg.'">'.$msg.'</option>');
			}
	break;

		
	case "gridstate":
			
			$form_dlrlist = $_POST['form_dlrlist'];
			$form_state = $_POST['form_state'];
			$serial=1;
			
			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
			
			//Write the header Row of the table
			$grid .= '<tr class="gridheader">
			<td nowrap="nowrap" class="tdborder">Sl no.</td>
			<td nowrap="nowrap" class="tdborder">ID</td>
			<td nowrap="nowrap"  class="tdborder">Dealer</td>
			<td nowrap="nowrap"  class="tdborder">State</td>
			<td nowrap="nowrap"  class="tdborder">District</td>
			<td nowrap="nowrap"  class="tdborder">Region</td>
			<td nowrap="nowrap"  class="tdborder">Category</td>
			</tr>';
			
			if($form_state=='all')
			{
				$query = "select mapping.id, dealers.dlrcompanyname, regions.statename, regions.distname, regions.subdistname, mapping.prdcategory from mapping JOIN regions on regions.subdistcode = mapping.regionid JOIN dealers on dealers.id = mapping.dealerid WHERE mapping.dealerid = '".$form_dlrlist."' ORDER BY mapping.id";
			}
			else
			{
				$query = "select mapping.id, dealers.dlrcompanyname, regions.statename, regions.distname, regions.subdistname, mapping.prdcategory from mapping JOIN regions on regions.subdistcode = mapping.regionid JOIN dealers on dealers.id = mapping.dealerid WHERE mapping.dealerid = '".$form_dlrlist."' AND regions.statename = '".$form_state."' ORDER BY mapping.id ";
			}
			$result = runmysqlquery($query);
			$fetchresultcount = mysqli_num_rows($result);
			while($fetch = mysqli_fetch_row($result))
			{
				//Begin a row
				$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
				$grid .= "<td nowrap='nowrap' class='tdborder'>&nbsp;".$serial++."</td>";
				
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
			
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			
			echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
	break;
	
		case "gridcategory":
			
			$form_dlrlist = $_POST['form_dlrlist'];
			$form_state = $_POST['form_state'];
			$form_category = $_POST['form_category'];
			
			$serial=1;
			
			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtable"><tbody>';
			
			//Write the header Row of the table
			$grid .= '<tr class="gridheader">
			<td nowrap="nowrap" class="tdborder">Sl no.</td>
			<td nowrap="nowrap" class="tdborder">ID</td>
			<td nowrap="nowrap"  class="tdborder">Dealer</td>
			<td nowrap="nowrap"  class="tdborder">State</td>
			<td nowrap="nowrap"  class="tdborder">District</td>
			<td nowrap="nowrap"  class="tdborder">Region</td>
			<td nowrap="nowrap"  class="tdborder">Category</td>
			</tr>';
			
			if($form_category == 'all')
			{
				$query = "select mapping.id, dealers.dlrcompanyname, regions.statename, regions.distname, regions.subdistname, mapping.prdcategory from mapping JOIN regions on regions.subdistcode = mapping.regionid JOIN dealers on dealers.id = mapping.dealerid WHERE mapping.dealerid = '".$form_dlrlist."' AND regions.statename = '".$form_state."' ORDER BY mapping.id";
			}
			else
			{
				$query = "select mapping.id, dealers.dlrcompanyname, regions.statename, regions.distname, regions.subdistname, mapping.prdcategory from mapping JOIN regions on regions.subdistcode = mapping.regionid JOIN dealers on dealers.id = mapping.dealerid WHERE mapping.dealerid = '".$form_dlrlist."' AND regions.statename = '".$form_state."' AND  mapping.prdcategory = '".$form_category."'  ORDER BY mapping.id ";
			}
			$result = runmysqlquery($query);
			$fetchresultcount = mysqli_num_rows($result);
			while($fetch = mysqli_fetch_row($result))
			{
				//Begin a row
				$grid .= '<tr class="gridrow" onclick="javascript:gridtoform(\''.$fetch[0].'\');">';
				$grid .= "<td nowrap='nowrap' class='tdborder'>&nbsp;".$serial++."</td>";
				
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
			
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			
			echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
	break;

}
?>