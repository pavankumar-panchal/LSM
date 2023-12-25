<?php

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
		$form_companyname = $_POST['form_companyname'];
		$form_name = $_POST['form_name'];
		$form_address = $_POST['form_address'];
		$form_region = $_POST['form_region'];
		$form_place = $_POST['form_place'];
		$form_stdcode = $_POST['form_stdcode'];
		$form_phone = $_POST['form_phone'];
		$form_cell = $_POST['form_cell'];
		$form_email = $_POST['form_email'];
		$form_product = $_POST['form_product'];
		$form_source = $_POST['form_source'];
		$form_dealer = $_POST['form_dealer'];
		$form_leadremarks = $_POST['form_leadremarks'];
	
		//Check who is making the entry
		$cookie_username = lmsgetcookie('lmsusername');
		$query = "select * from lms_users where lms_users.username = '".$cookie_username."'";
		$result = runmysqlqueryfetch($query);
		$enteredbyuserid = $result['id'];
		
		if($message == "")
		{
			$query = "SELECT * FROM leads WHERE emailid = '".$form_email."' AND productid = '".$form_product."' and  leaddatetime > DATE_ADD(CURDATE(),INTERVAL -365  DAY)";
			
			$result = runmysqlquery($query);
			$count = mysqli_num_rows($result);
			if($count > 0)
			{
			
				$result = runmysqlqueryfetch($query);
				$leadid = $result['id'];
				$message = "2^This email ID is already available as a lead to same product [Lead ID: ".$leadid."]. You can upload it for someother product.";
				//
			}
		}
		if($message == "")
		{
			if($form_dealer == 'mapping')
			{
					
				//If not in the lead table, check, is it present for some other product in lead table for any dealer [other than unmapped contact]
				$query1 = "SELECT *,leads.dealerid AS dealerid FROM leads left join lms_users on leads.dealerid = lms_users.referenceid WHERE leads.emailid = '".$form_email."' AND leads.dealerid <> '999999' AND lms_users.disablelogin <> 'yes';";
				$result = runmysqlquery($query1);
				$leadpresence2 = mysqli_num_rows($result);
				if($leadpresence2 > 0)
				{
					//if present, then assign the lead to the same dealer, who has got the first product. Take him as dealer ID
					$fetch = mysqli_fetch_array($result);
					$dealerid = $fetch['dealerid'];
				}
				else
				{
					//Get the category of the product
					$query = "SELECT * FROM products WHERE id = '".$form_product."'";
					$result = runmysqlqueryfetch($query);
					$prdcategory = $result['category'];
					
					//if not, then check the mapping table for respective product category/region and pick respective dealer ID.
					$query = "SELECT * FROM mapping left join lms_users on mapping.dealerid = lms_users.referenceid WHERE mapping.regionid = '".$form_region."' AND mapping.prdcategory = '".$prdcategory."' AND lms_users.disablelogin <>'yes';
";
					$result = runmysqlquery($query);
					$mappingcount = mysqli_num_rows($result);
					
					//If mapping exists for that region and product, pick respective dealer address
					if($mappingcount > 0)
					{
						
						$result = runmysqlqueryfetch($query);
						$query = "SELECT * FROM dealers WHERE id = '".$result['dealerid']."'";
						$result = runmysqlqueryfetch($query);
						$dealerid = $result['id'];
					}
					else
					{			
						//Get the Managed Area for this region
						$query = "SELECT managedarea FROM regions WHERE subdistcode = '".$form_region."'";
						$result = runmysqlqueryfetch($query);
						$managedarea = $result['managedarea'];
						
						//If mapping is not available for that product category/region, take unmapped contact as its dealer ID
						$query = "SELECT * FROM unmappedcontact WHERE managedarea = '".$managedarea."' and prdcategory = '".$prdcategory."'";
						$result = runmysqlqueryfetch($query);
						$dealerid = "999999";
					}
				}
			}
			else
			{
				$dealerid = $form_dealer;
			}
			$leaddate = datetimelocal("Y-m-d");
			$leadtime = datetimelocal("H:i:s");
			
			//$cookie_usertype = lmsgetcookie('lmsusersort');
			if(lmsgetcookie('lmsusersort') =='Dealer Member' )
			{
				//Fetch dealer memeber id
				$query154 = "select dlrmbrid as dlrmbrid from lms_users join lms_dlrmembers on lms_dlrmembers.dlrmbrid = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Dealer Member'";
				$result153 = runmysqlqueryfetch($query154);
				$dlrmbrid = $result153['dlrmbrid'];
			}
			
			$query2 = "insert into `leads` (dealerid, productid, source, company, name, address, place, regionid, phone, emailid, refer, leaduploadedby, leadstatus, leadremarks, dealernativeid, cell,stdcode,initialcontactname,initialaddress,initialstdcode,initialphone,initialcellnumber,initialemailid,leaddatetime,dlrmbrid)values('".$dealerid."', '".$form_product."','Manual Upload', '".$form_companyname."', '".$form_name."', '".$form_address."', '".$form_place."', '".$form_region."', '".$form_phone."', '".$form_email."', '".$form_source."', '".$enteredbyuserid."', 'Not Viewed', '".$form_leadremarks."', '".$dealerid."', '".$form_cell."', '".$form_stdcode."' ,'".$form_name."','".$form_address."','".$form_stdcode."','".$form_phone."','".$form_cell."','".$form_email."','".$leaddate.' '.$leadtime."','".$dlrmbrid."')";
			$result2 = runmysqlquery($query2); 
			
						
				 //Fetch new lead id to insert into updatelogs.
				$query3 = "select id,leadstatus from leads where source = 'Manual Upload' and company = '".$form_companyname."' and name = '".$form_name."' and leadremarks = '".$form_leadremarks."' and productid = '".$form_product."' and leaddatetime = '".$leaddate.' '.$leadtime."' ";
				$result3 = runmysqlqueryfetch($query3);
				
				//Insert Details to lms_updatelogs
				$query5 = "insert into lms_updatelogs set leadid = '".$result3['id']."',leadstatus = '".$result3['leadstatus']."',updatedate = '".$leaddate.' '.$leadtime. "',updatedby = '".$enteredbyuserid."'";
					$result5 = runmysqlquery($query5);
				
				
				// Insert logs on save of Lead 
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','23','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery($query);
				
				$message = "1^ Lead Id :".$result3['id']." Uploaded Successfully";

				//Send SMS to concerned dealer about the lead
				if($dealerid <> '999999')
				{
					$query = "SELECT * FROM dealers WHERE id = '".$dealerid."'";
					$result = runmysqlqueryfetch($query);
					$dlrcell = $result['dlrcell'];
					$query = "SELECT * FROM products WHERE id = '".$form_product."'";
					$result = runmysqlqueryfetch($query);
					$productname = $result['productname'];
			
					$servicename = 'LEAD Uploaded';
					$tonumber = $dlrcell;
					$smstext = "Relyon LMS: ".substr($form_name, 0, 29)." of ".substr($form_companyname, 0, 29)." requires ".substr($productname, 0, 29).". Call ".substr($form_phone, 0, 29).".";
					$senddate = $leaddate;
					$sendtime = $leadtime;
					sendsmsforleads($servicename, $tonumber, $smstext, $senddate, $sendtime,NULL,NULL);
				}
				
		}
		echo($message);
		break;

}
?>
