<?php
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];

switch($submittype)
{

	case 'generatedealerlist':
	{
		$dealerarray = array();
		
		$query = "select distinct dealers.id, dealers.dlrcompanyname from dealers left join lms_users on lms_users.referenceid=dealers.id where disablelogin = 'no' order by dlrcompanyname;";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$dealerarray[$count] = $fetch['dlrcompanyname'].'^'.$fetch['id'];
			$count++;
		}
		echo(json_encode($dealerarray));
	}
	break;	
	case 'assignedlist':
	{
		$assignedlistarray = array();
		$lastslno = $_POST['lastslno'];
		$category = $_POST['category'];
		$query = "select regions.slno,concat(statename,' - ',distname,' - ',subdistname) as region,subdistcode from mapping left join regions on  regions.subdistcode = mapping.regionid where prdcategory ='".$category."' and dealerid = '".$lastslno."';";
		$result = runmysqlquery($query);
		$rowcount = mysqli_num_rows($result);
		$grid .= '<select name="list2" size="5" class="formfieldsselect" id="list2" style="width:210px; height:400px" >';
		if($rowcount > 0)
		{
			while($fetch = mysqli_fetch_array($result))
			{
				$grid .= '<option value="'.$fetch['subdistcode'].'" ondblclick="deleteentry(\''.$fetch['subdistcode'].'\');">'.$fetch['region'].'</option>';
			}
		}
		$grid .= '</select>';

		$assignedlistarray['grid'] = $grid;
		$assignedlistarray['rowcount'] = $rowcount;
		echo(json_encode($assignedlistarray));
	}
	break;
	case 'unassignedlist':
	{
		$unassignedlistarray = array();
		$lastslno = $_POST['lastslno'];
		$category = $_POST['category'];
		$query = "select regions.slno,concat(statename,' - ',distname,' - ',subdistname) as region,subdistcode from regions  join (select distinct prdcategory from mapping) as productcategory left join mapping on regions.subdistcode = mapping.regionid and productcategory.prdcategory = mapping.prdcategory where mapping.id is null and productcategory.prdcategory = '".$category."' order by statename ;";
		$result = runmysqlquery($query);
		$rowcount = mysqli_num_rows($result);
		$grid .= '<select name="list1" size="5" class="formfieldsselect" id="list1" style="width:210px; height:400px" >';
		if($rowcount > 0)
		{
			
			while($fetch = mysqli_fetch_array($result))
			{
				$grid .= '<option value="'.$fetch['subdistcode'].'" ondblclick="addentry(\''.$fetch['subdistcode'].'\');">'.$fetch['region'].'</option>';
			}
		}
		$grid .= '</select>';
		$unassignedlistarray['grid'] = $grid;
		$unassignedlistarray['rowcount'] = $rowcount;
		echo(json_encode($unassignedlistarray));
	}
	break;
	case 'save':
	{
		$errormessagearray = array();
		$lastslno = $_POST['lastslno'];
		$listarray = $_POST['listarray'];
		$category = $_POST['category'];
		$listarraysplit = explode(',',$listarray);
		
		$query = "Delete from mapping where dealerid = '".$lastslno."'";
		$result = runmysqlquery($query);
		
		for($i=0; $i<count($listarraysplit); $i++)
		{
			$query1 = "Insert into mapping (dealerid,regionid, productid, prdcategory) values('".$lastslno."', 
	'".$listarraysplit[$i]."', '0', '".$category."')";
			$result1 = runmysqlquery($query1);
		}
		$errormessagearray['errorcode'] = '1';
		$errormessagearray['errormeg'] = 'Record saved successfully';
		echo(json_encode($errormessagearray));
		
	}
	break;
		
}
?>