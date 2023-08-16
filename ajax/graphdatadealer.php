<?

include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$submittype = $_POST['submittype'];
$cookie_username = lmsgetcookie('lmsusername');

switch($submittype)
{
	case "thisfinancialyear":
		$source = $_POST['source'];
		$area = $_POST['area'];
		$productgroup = $_POST['productgroup'];
		
		$sourcepiece = ($source == "")?"":"and leads.source = '".$source."'";
		$areapiece = ($area == "")?"":"and lms_managers.managedarea = '".$area."'";
		$productgrouppiece = ($productgroup == "")?"":"and products.category = '".$productgroup."'";
		
		$month = date('m');
		$fybegin = (date('m') >= '04')?(date('Y').'-04-01'):((date('Y')-1).'-04-01');
		$fyend = (date('m') >= '04')?(date('Y')+1 .'-03-31'):((date('Y')).'-03-31');
		
		$montharray1 = array(4,5,6,7,8,9,10,11,12,1,2,3);
		$query = "select count(*) as countdays,MONTH(leads.leaddatetime) as months from leads left join dealers on leads.dealerid = dealers.id left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' where leads.leaddatetime between '".$fybegin."' and '".$fyend."' and lms_users.username = '".$cookie_username."' ".$sourcepiece." ".$areapiece." ".$productgrouppiece." group by months order by months";
		
		$result = runmysqlquery($query);
		$monthsarray = array();
		$graphdata =  array();
		$dates = '';
		$onlydates = '';
		$finalgraphdata = '';
		while($fetch = mysqli_fetch_array($result))
		{
			$monthsarray[] = $fetch['months'];
			$graphdata[] = $fetch['countdays']; 
		}
		//print_r($data); exit;
		$count = 0; 
		for($i = 0;$i<count($montharray1);$i++)
		{
			if(in_array($monthsarray[$i],$montharray1))
			{
				if($finalgraphdata == '')
				{
					$finalgraphdata = $graphdata[$count];
				}
				else
				{
					$finalgraphdata =  $finalgraphdata .','. $graphdata[$count];
				}
				$count++;
			}
			else
			{
				if($finalgraphdata == '')
				{
					$finalgraphdata = "0";
				}
				else
				{
					$finalgraphdata = $finalgraphdata .', 0';
					//echo($finalgraphdata);exit;
				}
				
			}
		}
	    echo($finalgraphdata);
		
		break;
		
		
	case "lastfinancialyear" :
		$month = date('m');
		$fybegin = (date('m') <= '04')?(date('Y')-2 .'-04-01'):((date('Y'))-1 .'-04-01');
		$fyend = (date('m') <= '04')?(date('Y') - 1 .'-03-31'):((date('Y')).'-03-31');
		$source = $_POST['source'];
		$area = $_POST['area'];
		$productgroup = $_POST['productgroup'];
		
		$sourcepiece = ($source == "")?"":"and leads.source = '".$source."'";
		$areapiece = ($area == "")?"":"and lms_managers.managedarea = '".$area."'";
		$productgrouppiece = ($productgroup == "")?"":"and products.category = '".$productgroup."'";
		$montharray1 = array(4,5,6,7,8,9,10,11,12,1,2,3);
		$query = "select count(*) as countdays,MONTH(leads.leaddatetime) as months from leads 
left join dealers on leads.dealerid = dealers.id 
left join lms_managers on lms_managers.id = dealers.managerid 
left join products on products.id = leads.productid 
left join regions on leads.regionid = regions.subdistcode 
left join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' where leads.leaddatetime between '".$fybegin."' and '".$fyend."' and lms_users.username = '".$cookie_username."' ".$sourcepiece." ".$areapiece." ".$productgrouppiece." group by months order by leads.leaddatetime";
		
		$result = runmysqlquery($query);
		$monthsarray = array();
		$graphdata =  array();
		$dates = '';
		$onlydates = '';
		$finalgraphdata = '';
		while($fetch = mysqli_fetch_array($result))
		{
			$monthsarray[] = $fetch['months'];
			$graphdata[] = $fetch['countdays']; 
		}
		//print_r($data); exit;
		$count = 0; 
		for($i = 0;$i<count($montharray1);$i++)
		{
			if(in_array($monthsarray[$i],$montharray1))
			{
				if($finalgraphdata == '')
				{
					$finalgraphdata = $graphdata[$count];
				}
				else
				{
					$finalgraphdata =  $finalgraphdata .','. $graphdata[$count];
				}
				$count++;
			}
			else
			{
				if($finalgraphdata == '')
				{
					$finalgraphdata = "0";
				}
				else
				{
					$finalgraphdata = $finalgraphdata .', 0';
					//echo($finalgraphdata);exit;
				}
				
			}
		}
	    echo($finalgraphdata);
		break;
		
		
	case "thismonth":
		$source = $_POST['source'];
		$area = $_POST['area'];
		$productgroup = $_POST['productgroup'];
		
		$sourcepiece = ($source == "")?"":"and leads.source = '".$source."'";
		$areapiece = ($area == "")?"":"and lms_managers.managedarea = '".$area."'";
		$productgrouppiece = ($productgroup == "")?"":"and products.category = '".$productgroup."'";
		
		// Fetch total no of days.
		
		$totaldays = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); // 31
		
		$query = "select count(*) as counts,left(leads.leaddatetime,10) as dates from leads left join dealers on leads.dealerid = dealers.id left join lms_managers on lms_managers.id = dealers.managerid left join products on products.id = leads.productid  left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' where month(leads.leaddatetime) = (month(curdate())) and year(leads.leaddatetime) = (year(curdate()))  and lms_users.username = '".$cookie_username."' ".$sourcepiece." ".$areapiece." ".$productgrouppiece." group by left(leads.leaddatetime,10)";
		//echo($query);exit;
		$result = runmysqlquery($query);
		$count = mysqli_num_rows($result);
		//echo("[");
		$datearray =  array();
		$completedate = array();
		$graphdata =  array();
		$dates = '';
		$onlydates = '';
		$finalgraphdata = '';
		while($fetch = mysqli_fetch_array($result))
		{
			$splitdate = explode('-',$fetch['dates']);
			$datearray[] = $splitdate[2];
			$completedate[] = $fetch['dates'];
			$graphdata[] = $fetch['counts']; 
		}
		//print_r($data); exit;
		$count = 0;
		for($i = 1;$i<=$totaldays;$i++)
		{
			if(in_array($i,$datearray))
			{
				if($dates == '')
				{
					$onlydates = $datearray[$count];
					$dates = $completedate[$count];
					$finalgraphdata = $graphdata[$count];
				}
				else
				{
					$onlydates = $onlydates .','. $datearray[$count];
					$dates = $dates .','. $completedate[$count];
					$finalgraphdata =  $finalgraphdata .','. $graphdata[$count];
				}
				$count++;
			}
			else
			{
				if($dates == '')
				{
					if($i < 10)
						$i = '0'.$i;
					$onlydates = $i;
					$dates = date('Y').'-'.date('m').'-'.$i;
					$finalgraphdata = "0";
				}
				else
				{	
					if($i < 10)
						$i = '0'.$i;
					$onlydates = $onlydates .','.$i;
					$dates = $dates .','. date('Y').'-'.date('m').'-'.$i;
					$finalgraphdata = $finalgraphdata .', 0';
				}
			}
		}
		
		echo($onlydates .'###'. $finalgraphdata);
		break;
		
	case "lastmonth":
		$source = $_POST['source'];
		$area = $_POST['area'];
		$productgroup = $_POST['productgroup'];
		
		$sourcepiece = ($source == "")?"":"and leads.source = '".$source."'";
		$areapiece = ($area == "")?"":"and lms_managers.managedarea = '".$area."'";
		$productgrouppiece = ($productgroup == "")?"":"and products.category = '".$productgroup."'";
	
	
		
		if(date('m') == '1')
		{
			$month = '12';
			$year = date('Y') - 1;
			$monthpiece = '12';
			$yearpiece = 'year(curdate())-1';
		}
		else
		{
			$month = date('m') - 1;
			$year = date('Y');
			$monthpiece = '(month(curdate()) -1)'; 
			$yearpiece = 'year(curdate())';
		} 
		// Fetch total no of days.
		
		$totaldays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		
		$query = "select count(*) as counts,left(leads.leaddatetime,10) as dates from leads left join dealers on leads.dealerid = dealers.id  left join lms_managers on lms_managers.id = dealers.managerid  left join products on products.id = leads.productid   left join regions on leads.regionid = regions.subdistcode left join lms_users on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' where month(leads.leaddatetime) = ".$monthpiece." and year(leads.leaddatetime) = ".$yearpiece." and lms_users.username = '".$cookie_username."' ".$sourcepiece." ".$areapiece." ".$productgrouppiece." group by left(leads.leaddatetime,10)";
		$result = runmysqlquery($query);
		$datearray =  array();
		$completedate = array();
		$graphdata =  array();
		$dates = '';
		$finalgraphdata = '';
		$onlydates = '';
		$finalgraphdata = '';
		while($fetch = mysqli_fetch_array($result))
		{
			$splitdate = explode('-',$fetch['dates']);
			$datearray[] = $splitdate[2];
			$completedate[] = $fetch['dates'];
			$graphdata[] = $fetch['counts']; 
		}
		//print_r($data); exit;
		$count = 0;
		for($i = 1;$i<=$totaldays;$i++)
		{
			if(in_array($i,$datearray))
			{
				if($dates == '')
				{
					$onlydates = $datearray[$count];
					$dates = $completedate[$count];
					$finalgraphdata = $graphdata[$count];
				}
				else
				{
					$onlydates = $onlydates .','. $datearray[$count];
					$dates = $dates .','. $completedate[$count];
					$finalgraphdata =  $finalgraphdata .','. $graphdata[$count];
				}
				$count++;
			}
			else
			{
				if($dates == '')
				{
					if($i < 10)
						$i = '0'.$i;
					$onlydates = $i;
					$dates = date('Y').'-'.date('m').'-'.$i;
					$finalgraphdata = "0";
				}
				else
				{
					if($i < 10)
						$i = '0'.$i;
					$onlydates = $onlydates .','.$i;
					$dates = $dates .','. date('Y').'-'.date('m').'-'.$i;
					$finalgraphdata = $finalgraphdata .', 0';
				}
			}
		}
		
		echo($onlydates .'###'. $finalgraphdata);
		break;
}
?>