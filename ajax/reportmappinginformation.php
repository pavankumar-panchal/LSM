<?php
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];

switch($submittype)
{
	case 'getdistrict':
		$code = $_POST['code'];
		$query = "Select distinct distname, distcode from regions where statecode = '".$code."'";
		$result = runmysqlquery($query);
		$count = mysqli_num_rows($result);
		if($count > 0)
		{
			echo('<select name="form_district" id="form_district" onchange="regionselect()">');
			echo('<option value="" selected="selected"> - - - - - ALL - - - - -  </option>');
			while($array = mysqli_fetch_array($result))
			{
				echo('<option value="'.$array['distcode'].'" >'.$array['distname'].'</option>');
			}
			echo('</select>');
		}
		else
		{
			echo('<select name="form_district" id="form_district" onchange="regionselect()">');
			echo('<option value="" selected="selected"> - - - ALL - - - </option>');
			echo('</select>');
		}
		break;
		
	case "getsubdistrict":
		$code = $_POST['code'];
		$query = "Select subdistname, subdistcode from regions where distcode = '".$code."'";
		$result = runmysqlquery($query);
		$count = mysqli_num_rows($result);
		if($count > 0)
		{
			echo('<select name="form_region" id="form_region">');
			echo('<option value="" selected="selected"> - - - ALL - - - </option>');
			while($array = mysqli_fetch_array($result))
			{
				echo('<option value="'.$array['subdistcode'].'" >'.$array['subdistname'].'</option>');
			}
			echo('</select>');
		}
		else
		{
			echo('<select name="form_region" id="form_region">');
			echo('<option value="" selected="selected">- - - - - ALL - - - - -</option>');
			echo('</select>');
		}
		break;

	case "griddata":
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		if($showtype == 'all')
			$limit = 1000;
		else
			$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slnocount = 0;
		}
		else
		{
			$startlimit = $slnocount;
			$slnocount = $slnocount;
		}	
		
		$query = "select regions.statename, regions.distname, regions.subdistname, productcategory.prdcategory, dealers.dlrcompanyname,dealers.dlrname,dealers.district,lms_users.disablelogin from regions  join (select distinct prdcategory from mapping) as productcategory left join mapping on regions.subdistcode = mapping.regionid and productcategory.prdcategory = mapping.prdcategory left join dealers on mapping.dealerid = dealers.id left join lms_users on lms_users.referenceid = dealers.id and lms_users.type = 'Dealer'";		
		if($slnocount == '0')
		{
			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
			//Write the header Row of the table
			$grid .= '<tr class="gridheader"><td nowrap="nowrap"  class="tdborderlead">Sl No</td><td nowrap="nowrap"  class="tdborderlead">Mapping State</td><td nowrap="nowrap"  class="tdborderlead">Mapping District</td><td nowrap="nowrap"  class="tdborderlead">Mapping Region</td><td nowrap="nowrap"  class="tdborderlead">Product Category</td><td nowrap="nowrap"  class="tdborderlead">Dealer Company</td><td nowrap="nowrap"  class="tdborderlead">Dealer Person</td><td nowrap="nowrap"  class="tdborderlead">Dealer Place</td><td nowrap="nowrap"  class="tdborderlead">Dealer Disabled</td></tr><tbody>';
		}
		$result = runmysqlquery($query);
		$fetchresultcount = mysqli_num_rows($result);
		$addlimit = " LIMIT ".$startlimit.",".$limit."; ";
		$query1 = $query.$addlimit; 
		$result1 = runmysqlquery($query1);
		while($fetch = mysqli_fetch_row($result1))
		{
			$slnocount++;
			//Begin a row
			$grid .= '<tr>';
			$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".$slnocount."</td>";
			//Write the cell data
			for($i = 0; $i < count($fetch); $i++)
			{
				$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch[$i])."</td>";
			}
		
			//End the Row
			$grid .= '</tr>';
		}	
		$grid .= "</tbody></table>";
		if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecords(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';		
		echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
		break;	
		
	case "filter":
		$searchtext = $_POST['searchtext'];
		$category = $_POST['category'];
		$state = $_POST['state'];
		$district = $_POST['district'];
		$region = $_POST['region'];
		$disabled = $_POST['disabled'];
		$radiovalue = $_POST['radiovalue'];
		$orderby = $_POST['orderby'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$generate = $_POST['generate'];
		if($showtype == 'all')
			$limit = 1000;
		else
			$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slnocount = 0;
		}
		else
		{
			$startlimit = $slnocount;
			$slnocount = $slnocount;
		}	
		
		$categorypiece = ($category == '')?"":("AND mapping.prdcategory = '".$category."'");
		$statepiece = ($state == '')?"":("AND regions.statecode = '".$state."'");
		$districtpiece = ($district == '')?"":("AND regions.distcode = '".$district."'");
		$regionpiece = ($region == '')?"":("AND mapping.regionid = '".$region."'");
		$disabledpiece = ($disabled == '')?"":("AND lms_users.disablelogin = '".$disabled."'");
		if($radiovalue == 'dealercompany')
		{
			$searchpiece = ($searchtext == '')?"":("AND dealers.dlrcompanyname like '%".$searchtext."%'");
		}
		else if($radiovalue == "dealername")
		{
			$searchpiece = ($searchtext == '')?"":("AND dealers.dlrname like '%".$searchtext."%'");
		}
		// conditons to orderby 
		if($orderby == 'region')
		{
			$orderbypiece = ($orderby == '')?"":("ORDER BY regions.statename,regions.distname,regions.subdistname,productcategory.prdcategory");
		}
		else if($orderby == 'product')
		{
			$orderbypiece = ($orderby == '')?"":("ORDER BY productcategory.prdcategory,regions.statename,regions.distname,regions.subdistname");
		}
		else if($orderby == 'dealer')
		{
			$orderbypiece = ($orderby == '')?"":("ORDER BY dealers.dlrcompanyname,regions.statename,regions.distname,regions.subdistname");
		}
				// condition to generate
		if($generate == '')
		{
			$generatepiece = '';
		}
		else if($generate == 'having')
		{
			$generatepiece = ' and mapping.id is not NULL';;
		}
		else if($generate == 'missing')
		{
			$generatepiece =  ' and mapping.id is  NULL';
		}
		$query = "select regions.statename, regions.distname, regions.subdistname, productcategory.prdcategory, dealers.dlrcompanyname,dealers.dlrname,dealers.district,lms_users.disablelogin from regions  join (select distinct prdcategory from mapping) as productcategory left join mapping on regions.subdistcode = mapping.regionid and productcategory.prdcategory = mapping.prdcategory left join dealers on mapping.dealerid = dealers.id left join lms_users on lms_users.referenceid = dealers.id and lms_users.type = 'Dealer' where   regions.distname <> '' ".$categorypiece ."  ".$statepiece."  ".$districtpiece."  ".$regionpiece." ".$searchpiece." ".$disabledpiece.$generatepiece." ".$orderbypiece."";	
		//echo($query);exit;
		if($slnocount == '0')
		{
			$grid = '<table width="100%" border="0" bordercolor="#ffffff" cellspacing="0" cellpadding="2" id="gridtablelead">';
			//Write the header Row of the table
			$grid .= '<tr class="gridheader"><td nowrap="nowrap"  class="tdborderlead">Sl No</td><td nowrap="nowrap"  class="tdborderlead">Mapping State</td><td nowrap="nowrap"  class="tdborderlead">Mapping District</td><td nowrap="nowrap"  class="tdborderlead">Mapping Region</td><td nowrap="nowrap"  class="tdborderlead">Product Category</td><td nowrap="nowrap"  class="tdborderlead">Dealer Company</td><td nowrap="nowrap"  class="tdborderlead">Dealer Person</td><td nowrap="nowrap"  class="tdborderlead">Dealer Place</td><td nowrap="nowrap"  class="tdborderlead">Dealer Disabled</td></tr><tbody>';
		}
		$result = runmysqlquery($query);
		$fetchresultcount = mysqli_num_rows($result);
		$addlimit = " LIMIT ".$startlimit.",".$limit."; ";
		$query1 = $query.$addlimit; 
		$result1 = runmysqlquery($query1);
		while($fetch = mysqli_fetch_row($result1))
		{
			$slnocount++;
			//Begin a row
			$grid .= '<tr>';
			$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".$slnocount."</td>";
			//Write the cell data
			for($i = 0; $i < count($fetch); $i++)
			{
				$grid .= "<td nowrap='nowrap' class='tdborderlead'>&nbsp;".gridtrim30($fetch[$i])."</td>";
			}
		
			//End the Row
			$grid .= '</tr>';
		}	
		$grid .= "</tbody></table>";
		if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorerecordsofsearch(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorerecordsofsearch(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';		
		echo('1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount);
		
	
		
		break;		

}


?>
