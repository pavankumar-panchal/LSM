<?php
// ini_set("memory_limit","-1");
include("../inc/ajax-referer-security.php");
include("../functions/phpfunctions.php");
include("../inc/getuserslno.php");

$switchtype = $_POST['switchtype'];
switch($switchtype)
{
	case 'getmcacount':
	{
		$responsearray3 = array();
		$query = "select count(*) as count from mca_companies ORDER BY company";
		$resultfetch = runmysqlqueryfetch($query);
		$count = $resultfetch['count'];
		$responsearray3['count'] = $count;
		echo(json_encode($responsearray3));
	}
	break;
	case 'generatemcalist':
	{
		$showmcalistvalues = getshowmcapermissionvalue();
		$showmcalistvaluessplit = explode('^',$showmcalistvalues);
		if($showmcalistvaluessplit[0] == 'yes' && $showmcalistvaluessplit[1] <> '')
			$showmcalisttype = 'where mca_stateidmapping.branchid = "'.$showmcalistvaluessplit[1].'"';
		else
			$showmcalisttype = '';
		$limit = '50';
		$mcalistarray = array();
		
		$query = "select id,company from mca_companies left join mca_stateidmapping on mca_companies.state = mca_stateidmapping.statename ".$showmcalisttype." ORDER BY company  LIMIT ".$limit.";";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$mcalistarray[$count] = $fetch['company'].'^'.$fetch['id'];
			$count++;
		}
		echo(json_encode($mcalistarray));
	}
	break;	
	case 'searchmcalist':
	{
		$showmcalistvalues = getshowmcapermissionvalue();
		$showmcalistvaluessplit = explode('^',$showmcalistvalues);
		if($showmcalistvaluessplit[0] == 'yes' && $showmcalistvaluessplit[1] <> '')
			$showmcalisttype = 'and mca_stateidmapping.branchid = "'.$showmcalistvaluessplit[1].'"';
		else
			$showmcalisttype = '';
		$typesearch = $_POST['percentgesearch'];
		$limit = '50';
		$searchtext = $_POST['searchtext'];
		$mcalistarray = array();
		if($typesearch == 'percentgesearch')
		{
			$query = "select id,company from mca_companies left join mca_stateidmapping on mca_companies.state = mca_stateidmapping.statename  where company like '%".$searchtext."%' ".$showmcalisttype." ORDER BY company  LIMIT ".$limit.";";
		}
		else
		{
			$query = "select id,company from mca_companies left join mca_stateidmapping on mca_companies.state = mca_stateidmapping.statename  where company like '".$searchtext."%' ".$showmcalisttype."  ORDER BY company  LIMIT ".$limit.";";
		}
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$mcalistarray[$count] = $fetch['company'].'^'.$fetch['id'];
			$count++;
		}
		echo(json_encode($mcalistarray));
	}
	break;	
	case 'detailstoform':
	{
		$detailstoformarray = array();
		$lastslno = $_POST['lastslno'];
		$query = "select mca_companies.company, mca_companies.emailid,mca_companies.pincode,mca_companies.city,mca_companies.class, mca_companies.address2,mca_companies.address1,mca_companies.roccode,mca_companies.state, mca_companies.registrationnumber,mca_companies.category,mca_companies.subcategory, mca_companies.cin, mca_companies.incorporateddate, mca_companies.agmdate, mca_companies.listingtype,mca_companies.balancesheetdate,mca_companies.authorisedcapital, mca_companies.paidupcapital,mca_additional_info.name,mca_additional_info.address as addaddress, mca_additional_info.place as addplace,mca_additional_info.phone as addphone,mca_additional_info.cell as addcell,mca_additional_info.emailid as addemailid, mca_additional_info.place as addplace, mca_additional_info.state as addstate, mca_additional_info.district as adddistrict,mca_additional_info.stdcode,mca_additional_info.slno as addinfoid  from mca_companies left join mca_additional_info on mca_additional_info.companyinfoid =mca_companies.id  where mca_companies.id = '".$lastslno."';";
		//echo($query);
		$resultfetch = runmysqlqueryfetch($query);
		
		$query1 = "select * from mca_director_info where companyinfoid = '".$lastslno."';";
		$result1 = runmysqlquery($query1);
		$rowcount = mysqli_num_rows($result1);
		$bottomborder = '';
		if($rowcount > 0)
		{
		  $slno =0;
		  while($fetch1 = mysqli_fetch_array($result1))
		  {
			  $slno++;
			  if($rowcount == $slno)
			  	$bottomborder = 'border-bottom: 1px solid #CCC;';
			  $grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="4" style=" '.$bottomborder.' border-left: 1px solid #CCC; border-right:1px solid #CCC"><tr><td width="8%" bgcolor="#F4FDFF"><strong>'.$slno.'</strong></td> <td width="9%" bgcolor="#F4FDFF"><strong>Name:</strong></td><td width="51%" bgcolor="#F4FDFF"><font color="#FF0000"><strong>'.$fetch1['dinname'].'</strong></font></td><td width="9%" bgcolor="#F4FDFF"><strong>DIN:</strong></td><td width="23%" bgcolor="#F4FDFF">'.$fetch1['din'].'</td></tr><tr><td bgcolor="#FBFEFF">&nbsp;</td><td bgcolor="#FBFEFF"><strong>Designation:</strong></td><td bgcolor="#FBFEFF">'.$fetch1['designation'].'</td><td bgcolor="#FBFEFF"><strong>From:</strong></td><td bgcolor="#FBFEFF">'.changedateformat($fetch1['appointmentdate']).'</td></tr><tr><td bgcolor="#F4FDFF">&nbsp;</td><td bgcolor="#F4FDFF"><strong >Address: </strong></td><td colspan="3" bgcolor="#F4FDFF" style="font-size:9px">'.$fetch1['address'].'</td></tr></table>';
  
		  }
		}
		else
		{
			$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" height="50px" style="border-bottom:#CCC 1px solid; border-left: 1px solid #CCC; border-right:1px solid #CCC"><tr><td><div align="center" style="font-size:18px"><font color="#FF0000"><strong>No Director information available</strong></font></div></td></tr></table>';
		}
		$listingtype = ($resultfetch['listingtype'] == 'U')?("Unlisted"):("Listed");
		$classtype = ($resultfetch['class'] == 'PRIV')?("Private"):("Public");
		$detailstoformarray['company'] = $resultfetch['company'];
		$detailstoformarray['emailid'] = $resultfetch['emailid'];
		$detailstoformarray['pincode'] = $resultfetch['pincode'];
		$detailstoformarray['city'] = $resultfetch['city'];
		$detailstoformarray['class'] = $classtype;
		$detailstoformarray['address2'] = $resultfetch['address2'];
		$detailstoformarray['address'] = $resultfetch['address1'];
		$detailstoformarray['roccode'] = $resultfetch['roccode'];
		$detailstoformarray['state'] = $resultfetch['state'];
		$detailstoformarray['registrationnumber'] = $resultfetch['registrationnumber'];
		$detailstoformarray['category'] = $resultfetch['category'];
		$detailstoformarray['subcategory'] = $resultfetch['subcategory'];
		$detailstoformarray['cin'] = $resultfetch['cin'];
		$detailstoformarray['incorporateddate'] = changedateformat($resultfetch['incorporateddate']);
		$detailstoformarray['agmdate'] = changedateformat($resultfetch['agmdate']);
		$detailstoformarray['listingtype'] = $listingtype;
		$detailstoformarray['balancesheetdate'] = changedateformat($resultfetch['balancesheetdate']);
		$detailstoformarray['authorisedcapital'] = formatnumber($resultfetch['authorisedcapital']);
		$detailstoformarray['paidupcapital'] = formatnumber($resultfetch['paidupcapital']);
		$detailstoformarray['name'] = $resultfetch['name'];
		$detailstoformarray['addaddress'] = $resultfetch['addaddress'];
		$detailstoformarray['addplace'] = $resultfetch['addplace'];
		$detailstoformarray['addphone'] = $resultfetch['addphone'];
		$detailstoformarray['addcell'] = $resultfetch['addcell'];
		$detailstoformarray['addemailid'] = $resultfetch['addemailid'];
		$detailstoformarray['adddistrict'] = $resultfetch['adddistrict'];
		$detailstoformarray['addstate'] = $resultfetch['addstate'];
		$detailstoformarray['addrowcount'] = $resultfetch['addemailid'];
		$detailstoformarray['stdcode'] = $resultfetch['stdcode'];
		$detailstoformarray['addinfoid'] = $resultfetch['addinfoid'];
		
		$detailstoformarray['grid'] = $grid;
		echo(json_encode($detailstoformarray));
	}
	break;
	case 'advancesearch':
	{
		$advancesearcharray = array();
		$typesearch = $_POST['typesearch'];
		$databasefield = $_POST['databasefield'];
		$textfield = $_POST['textfield'];
		$state = $_POST['state'];
		$subselection = $_POST['subselection'];
		$class = $_POST['class'];
		$roccode = $_POST['roccode'];
		$paidupcapital = $_POST['paidupcapital'];
		$branch = $_POST['branch'];
		$searchtextfield = $_POST['searchtextfield'];
		$searchtype = $_POST['searchtype'];
		
		if($typesearch == '')
			$percentagesearch = '';
		else
			$percentagesearch = '%';
		$state_typepiece = ($state == "")?(""):(" AND mca_stateidmapping.slno = '".$state."' ");
		$class_typepiece = ($class == "")?(""):(" AND mca_companies.class = '".$class."' ");
		$roccode_typepiece = ($roccode == "")?(""):(" AND mca_companies.roccode = '".$roccode."' ");
		$branch_typepiece = ($branch == "")?(""):(" AND mca_stateidmapping.branchid = '".$branch."' ");
		//$paidupcapital_typepiece = ($paidupcapital == "")?(""):(" AND mca_companies.paidupcapital = '".$paidupcapital."' ");
		if($paidupcapital == 'below5crore')
		{
			$paidupcapital_typepiece = " AND mca_companies.paidupcapital < '50000000'";
		}
		else if($paidupcapital == 'above5crore')
		{
			$paidupcapital_typepiece = " AND mca_companies.paidupcapital > '50000000'";
		}
		else
		{
			$paidupcapital_typepiece = '';
		}
		if($searchtype == 'advancesearch')
			$textfieldtype = "'%".$searchtextfield."%'";
		else
			//$textfieldtype = $percentagesearch.$textfield.'%';
			//$textfieldtype = "'".$percentagesearch.$searchtextfield."%' and company like '".$textfield."%'";
			$textfieldtype = "'".$percentagesearch.$textfield.' '.$searchtextfield."%'";
	//	echo($textfieldtype);
		switch($databasefield)
		{
			case 'company':
			{
				$query = "select mca_companies.id,mca_companies.company from mca_companies left join  mca_director_info on mca_director_info.cin = mca_companies.cin left join mca_stateidmapping on mca_companies.state = mca_stateidmapping.statename where company like  ".$textfieldtype." ".$state_typepiece.$class_typepiece.$roccode_typepiece.$paidupcapital_typepiece.$branch_typepiece." order by company limit 50; ";
			}
			break;
			case 'address':
			{
				$query = "select mca_companies.id,mca_companies.company from mca_companies left join  mca_director_info on mca_director_info.cin = mca_companies.cin left join mca_stateidmapping on mca_companies.state = mca_stateidmapping.statename where (address1 like ".$textfieldtype." or address2 like ".$textfieldtype.")  ".$state_typepiece.$class_typepiece.$roccode_typepiece.$paidupcapital_typepiece.$branch_typepiece."  order by company limit 50;";
			}
			break;
			case 'city':
			{
				$query = "select mca_companies.id,mca_companies.company from mca_companies left join  mca_director_info on mca_director_info.cin = mca_companies.cin left join mca_stateidmapping on mca_companies.state = mca_stateidmapping.statename where city like ".$textfieldtype." ".$state_typepiece.$class_typepiece.$roccode_typepiece.$paidupcapital_typepiece.$branch_typepiece."  order by company limit 50;";
			}
			break;
			case 'pincode':
			{
				$query = "select mca_companies.id,mca_companies.company from mca_companies left join  mca_director_info on mca_director_info.cin = mca_companies.cin left join mca_stateidmapping on mca_companies.state = mca_stateidmapping.statename where pincode like ".$textfieldtype." ".$state_typepiece.$class_typepiece.$roccode_typepiece.$paidupcapital_typepiece.$branch_typepiece." order by company limit 50;";
			}
			break;
			case 'emailid':
			{
				$query = "select mca_companies.id,mca_companies.company from mca_companies left join  mca_director_info on mca_director_info.cin = mca_companies.cin left join mca_stateidmapping on mca_companies.state = mca_stateidmapping.statename where emailid like ".$textfieldtype." ".$state_typepiece.$class_typepiece.$roccode_typepiece.$paidupcapital_typepiece.$branch_typepiece."  order by company limit 50;";
			}
			break;
			case 'cin':
			{
				$query = "select mca_companies.id,mca_companies.company from mca_companies left join  mca_director_info on mca_director_info.cin = mca_companies.cin left join mca_stateidmapping on mca_companies.state = mca_stateidmapping.statename where mca_companies.cin like ".$textfieldtype." ".$state_typepiece.$class_typepiece.$roccode_typepiece.$paidupcapital_typepiece.$branch_typepiece."  order by company limit 50;";
			}
			break;
			case 'din':
			{
				$query = "select mca_companies.id,mca_companies.company from mca_companies left join  mca_director_info on mca_director_info.cin = mca_companies.cin left join mca_stateidmapping on mca_companies.state = mca_stateidmapping.statename where mca_director_info.din like ".$textfieldtype." ".$state_typepiece.$class_typepiece.$roccode_typepiece.$paidupcapital_typepiece.$branch_typepiece."  order by company limit 50;";
			}
			break;
			case 'directorname':
			{
				$query = "select mca_companies.id,mca_companies.company from mca_companies left join  mca_director_info on mca_director_info.cin = mca_companies.cin left join mca_stateidmapping on mca_companies.state = mca_stateidmapping.statename where mca_director_info.dinname like ".$textfieldtype." ".$state_typepiece.$class_typepiece.$roccode_typepiece.$paidupcapital_typepiece.$branch_typepiece."  order by company limit 50;";
			}
			break;
			default:
				$query = "select mca_companies.id,mca_companies.company from mca_companies left join  mca_director_info on mca_director_info.cin = mca_companies.cin left join mca_stateidmapping on mca_companies.state = mca_stateidmapping.statename where company like ".$textfieldtype." ".$state_typepiece.$class_typepiece.$roccode_typepiece.$paidupcapital_typepiece.$branch_typepiece."  order by company limit 50;";
				break;
		}
	//	echo($query);
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$advancesearcharray[$count] = $fetch['company'].'^'.$fetch['id'];
			$count++;
		}
		echo(json_encode($advancesearcharray));
	}
	break;
	case "form_state":
	$code = $_POST['code'];
	$query = "Select distinct distname, distcode from regions where statecode = '".$code."'";
	$result = runmysqlquery($query);
	$count = mysqli_num_rows($result);
	if($count > 0)
	{
		echo('<select name="form_district" id="form_district"  style="width:325px" class="formfields">');
		echo('<option value="" selected="selected"> - Make Selection - </option>');
		while($array = mysqli_fetch_array($result))
		{
			echo('<option value="'.$array['distcode'].'" >'.$array['distname'].'</option>');
		}
		echo('</select>');
	}
	else
	{
		echo('<select name="form_district" id="form_district"  style="width:325px" class="formfields">');
		echo('<option value="" selected="selected">- - - -Select a State First - - - -</option>');
		echo('</select>');
	}
	break;
	case 'saveadditionaldetails':
	{
		$lastslno = $_POST['lastslno'];
		$addlastslno = $_POST['addlastslno'];
		$name = $_POST['contactperson'];
		$address = $_POST['address'];
		$emailid = $_POST['emailid'];
		$state = $_POST['state'];
		$district = $_POST['district'];
		$phone = $_POST['phone'];
		$place = $_POST['place'];
		$cell = $_POST['cell'];
		$stdcode = $_POST['stdcode'];
		if($addlastslno == '')
		{
		  $query = "Insert into mca_additional_info (companyinfoid,	name, address, emailid, phone, cell, state, district, place,stdcode)
	  values ('".$lastslno."', '".$name."', '".$address."', '".$emailid."', '".$phone."', '".$cell."', '".$state."', '".$district."','".$place."','".$stdcode."')";
		  $result = runmysqlquery($query);
		}
		else
		{
			$query = "update mca_additional_info set name = '".$name."', address = '".$address."',emailid = '".$emailid."',phone = '".$phone."', cell = '".$cell."',state =  '".$state."', district = '".$district."', place = '".$place."',stdcode =  '".$stdcode."' where slno = '".$addlastslno."';";
			$result = runmysqlquery($query);
		}
		//echo($query);
		$responsearray = array();
		$responsearray['errorcode'] = '1';
		$responsearray['errormessage'] = 'Record saved successfully';
		echo(json_encode($responsearray));

	}
	break;
	case "state":
	{
	  $statecode = $_POST['statecode'];
	  $query = "Select distinct distname, distcode from regions where statecode = '".$statecode."'";
	  $result = runmysqlquery($query);
	  $count = mysqli_num_rows($result);
	  if($count > 0)
	  {
		  echo('<select name="form_district" id="form_district" class="formfields" style="width:325px" class="formfields">');
		  echo('<option value="" selected="selected"> - Make Selection - </option>');
		  while($array = mysqli_fetch_array($result))
		  {
			  echo('<option value="'.$array['distcode'].'" >'.$array['distname'].'</option>');
		  }
		  echo('</select>');
	  }
	  else
	  {
		  echo('<select name="form_district" id="form_district" class="formfields" style="width:325px">');
		  echo('<option value="" selected="selected">- - - -Select a State First - - - -</option>');
		  echo('</select>');
	  }
	}
	break;
	case 'uploadlead':
	{
		$form_companyname = $_POST['form_companyname'];
		$form_name = $_POST['form_name'];
		$form_address = $_POST['form_address'];
	//	$form_region = $_POST['form_region'];
		$form_place = $_POST['form_place'];
		$form_stdcode = $_POST['form_stdcode'];
		$form_phone = $_POST['form_phone'];
		$form_cell = $_POST['form_cell'];
		$form_email = $_POST['form_email'];
		$form_product = $_POST['form_product'];
		$form_source = $_POST['form_source'];
		$form_dealer = $_POST['form_dealer'];
		$form_leadremarks = $_POST['form_leadremarks'];
		$lastslno = $_POST['lastslno'];
		$district = $_POST['district'];
		$state = $_POST['state'];
		$query222 = "select subdistcode as region from mca_stateidmapping left join regions on regions.statecode = mca_stateidmapping.stateid where regions.statecode = '".$state."' and regions.distcode = '".$district."'; ";
		$resultfetch22 = runmysqlqueryfetch($query222);
		$form_region = $resultfetch22['region'];
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
			
			$query2 = "insert into `leads` (dealerid, productid, source, company, name, address, place, regionid, phone, emailid, refer, leaduploadedby, leadstatus, leadremarks, dealernativeid, cell,stdcode,initialcontactname,initialaddress,initialstdcode,initialphone,initialcellnumber,initialemailid,leaddatetime,dlrmbrid,mcaid)values('".$dealerid."', '".$form_product."','Manual Upload', '".$form_companyname."', '".$form_name."', '".$form_address."', '".$form_place."', '".$form_region."', '".$form_phone."', '".$form_email."', '".$form_source."', '".$enteredbyuserid."', 'Not Viewed', '".$form_leadremarks."', '".$dealerid."', '".$form_cell."', '".$form_stdcode."' ,'".$form_name."','".$form_address."','".$form_stdcode."','".$form_phone."','".$form_cell."','".$form_email."','".$leaddate.' '.$leadtime."','".$dlrmbrid."','".$lastslno."')";
			$result2 = runmysqlquery($query2); 
			
						
				 //Fetch new lead id to insert into updatelogs.
				$query3 = "select id,leadstatus from leads where source = 'Manual Upload' and company = '".$form_companyname."' and name = '".$form_name."' and leadremarks = '".$form_leadremarks."' and productid = '".$form_product."' and leaddatetime = '".$leaddate.' '.$leadtime."' ";
				$result3 = runmysqlqueryfetch($query3);
				
				//Insert Details to lms_updatelogs
				$query5 = "insert into lms_updatelogs set leadid = '".$result3['id']."',leadstatus = '".$result3['leadstatus']."',updatedate = '".$leaddate.' '.$leadtime. "',updatedby = '".$enteredbyuserid."'";
					$result5 = runmysqlquery($query5);
				
				
				// Insert logs on save of Lead 
				$query = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','23','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."')";
				$result = runmysqlquery_log($query);
				
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
					//sendsmsforleads($servicename, $tonumber, $smstext, $senddate, $sendtime,NULL,NULL);
				}
				
		}
		echo($message);
	}
	break;
}





?>