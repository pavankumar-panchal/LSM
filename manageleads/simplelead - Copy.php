<?php
include("../inc/checklogin.php");

//Permission check for the page
if($cookie_usertype <> "Dealer" && $cookie_usertype <> "Sub Admin" && $cookie_usertype <> "Reporting Authority" && $cookie_usertype <> "Admin" && $cookie_usertype <> "Dealer Member")
	header("Location:../home");


//Select the list of dealers for whom data can be filtered.
switch($cookie_usertype)
{
	case "Admin":
		$query = "SELECT id AS selectid, dlrcompanyname AS selectname FROM dealers ORDER BY dlrcompanyname";
		break;
	case "Reporting Authority":
	//Check wheteher the manager is branch head or not
		$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
		$result1 = runmysqlqueryfetch($query1);
					
		if($result1['branchhead'] == 'yes')
			$branchpiecejoin = "(dealers.branch = '".$result1['branch']."' OR dealers.managerid  = '".$result1['managerid']."')";
		else
			$branchpiecejoin = "lms_users.username = '".$cookie_username."' ";
			
		$query = "SELECT distinct dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE ".$branchpiecejoin." ORDER BY dealers.dlrcompanyname";
		if($cookie_username == "srinivasan")
		$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj' ORDER BY dealers.dlrcompanyname";
		
		break;
	case "Dealer":
		$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.id WHERE lms_users.username = '".$cookie_username."' ORDER BY dealers.dlrcompanyname";
		break;
	case "Dealer Member":
		$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN lms_dlrmembers on lms_dlrmembers.dlrmbrid = lms_users.referenceid JOIN dealers ON lms_dlrmembers.dealerid = dealers.id WHERE lms_users.username = '".$cookie_username."' ORDER BY dealers.dlrcompanyname";
		break;
	case "Sub Admin":
		$query = "SELECT id AS selectid, dlrcompanyname AS selectname FROM dealers ORDER BY dlrcompanyname";
		break;
}

$result = runmysqlquery($query);
echo ($query);
$dealerselect = '';
if(mysqli_num_rows($result) > 1)
$dealerselect .= '<option value="" selected="selected">- - - - All - - - -</option>';
while($fetch = mysqli_fetch_array($result))
{
	$dealerselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].'</option>';
}


echo ($dealerselect);
exit();
// Select dealer list to display in lead contract card.
//if(($cookie_usertype == 'Admin') || ($cookie_usertype == 'Sub Admin') || ($cookie_usertype == 'Reporting Authority'))
//{
	switch($cookie_usertype)
	{
		case "Admin":
		case "Sub Admin":
			$query1 = "SELECT distinct dealers.id AS selectid, dealers.dlrcompanyname AS selectname 
	FROM dealers left join lms_users on lms_users.referenceid = dealers.id where lms_users.disablelogin <> 'yes' and lms_users.type = 'Dealer' ORDER BY dlrcompanyname;";
			break;
		case "Reporting Authority":
		 //Check wheteher the manager is branch head or not
			$query123 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
			$result1 = runmysqlqueryfetch($query123);
			if($result1['branchhead'] == 'yes')
			{
				$branchpiecejoin = "AND (dealers.branch = '".$result1['branch']."' OR dealers.managerid  = '".$result1['managerid']."')";
				$joinpiece = "";
			}
			else
			{
				$branchpiecejoin = "";
				$joinpiece = "lms_users.username = '".$cookie_username."' AND ";
			}
				
			$query1 = "select dealers.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users left join dealers on dealers.id=lms_users.referenceid where dealers.managerid  in (select dealers.managerid from dealers left join lms_users on dealers.managerid =lms_users.referenceid where  ".$joinpiece." lms_users.type = 'Reporting Authority')
	and  lms_users.type = 'Dealer' and lms_users.disablelogin <> 'yes' ".$branchpiecejoin." ORDER BY dealers.dlrcompanyname";
			if($cookie_username == "srinivasan")
			$query1 = "select dealers.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users  left join dealers on dealers.id =lms_users.referenceid where dealers.managerid  in (select dealers.managerid from dealers left join lms_users on dealers.managerid =lms_users.referenceid where lms_users.username = '".$cookie_username."'  or lms_users.username = 'nagaraj'and lms_users.type ='Reporting Authority') and lms_users.type = 'Dealer' and lms_users.disablelogin <> 'yes' ORDER BY dealers.dlrcompanyname";
			break;	
		case "Dealer":
		$query1 = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.id WHERE lms_users.username = '".$cookie_username."' ORDER BY dealers.dlrcompanyname";
		break;
	case "Dealer Member":
		$query1 = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN lms_dlrmembers on lms_dlrmembers.dlrmbrid = lms_users.referenceid JOIN dealers ON lms_dlrmembers.dealerid = dealers.id WHERE lms_users.username = '".$cookie_username."' ORDER BY dealers.dlrcompanyname";
		break;	
			
	}
	$result1 = runmysqlquery($query1); //echo($query1);exit;
	$dealerselect1 = '';
	if(mysqli_num_rows($result) > 0)
	$dealerselect1 .= '<option value="" selected="selected">- - - - Make A Selection - - - - </option>';
	while($fetch1 = mysqli_fetch_array($result1))
	{
		$dealerselect1 .= '<option value="'.$fetch1['selectid'].'">'.$fetch1['selectname'].'</option>';
	}
	
	if($cookie_usertype == 'Dealer Member')
		$height = '369px';
	else 
		$height = '369px';


//Select the list of products and its groups for the drop-down
$query3 = "SELECT id,productname FROM products ORDER BY productname";
$result3 = runmysqlquery($query3);
$productselect = '<option value="" selected="selected">- - - - All - - - -</option>';
$productselect .='<optgroup label="Products" style = "font-family:"Times New Roman", Times, serif;">';
while($fetch = mysqli_fetch_array($result3))
{
	$productselect .= '<option value="'.$fetch['id'].'">'.$fetch['productname'].'</option>';
}
$productselect .='</optgroup>';
$query4 = "SELECT distinct category  FROM products ORDER BY category";
$result4 = runmysqlquery($query4);
$productselect .='<optgroup label="Groups" style = "font-family:"Times New Roman", Times, serif;">';
while($fetch = mysqli_fetch_array($result4))
{
	$productselect .= '<option value="'.$fetch['category'].'">'.$fetch['category'].'</option>';
}
$productselect .='</optgroup>';


// Select the list of products for product change list
$query10 = "SELECT id,productname FROM products ORDER BY productname";
$result10 = runmysqlquery($query10);
$productchangeselect .= '<option value="" selected="selected">- - - - Make a Selection - - - -</option>';
while($fetch = mysqli_fetch_array($result10))
{
	$productchangeselect .= '<option value="'.$fetch['id'].'">'.$fetch['productname'].'</option>';
}
$productchangeselect .= '</option>';

//Select the list of LEAD STATUS for the drop-down
$query2 = "SELECT distinct leadstatus FROM leads ORDER BY leadstatus";
$result2 = runmysqlquery($query2);
$leadstatusselect = '<option value="" selected="selected">- - - - All - - - -</option>';
while($fetch = mysqli_fetch_array($result2))
{
	$leadstatusselect .= '<option value="'.$fetch['leadstatus'].'">'.$fetch['leadstatus'].'</option>';
}

// Select List to transfer Leads to dealer Members



$query5 = "select lms_dlrmembers.dlrmbrid AS selectid, lms_dlrmembers.dlrmbrname AS selectname from lms_dlrmembers  left join lms_users on lms_users.referenceid =lms_dlrmembers.dlrmbrid where dealerid in (select dealers.id from dealers left join lms_users on lms_users.referenceid = dealers.id where lms_users.username = '".$cookie_username."' and lms_users.type = 'Dealer') and lms_users.disablelogin <> 'yes' and lms_users.type = 'Dealer Member'";

$result5 = runmysqlquery($query5);
$count = mysqli_num_rows($result5);
if($count > 0)
{
	$dlrmbrselect = '<option value = "" selected="selected"> - - - - Make A Selection - - - - </option>';
	while($fetch5 = mysqli_fetch_array($result5))
	{
		$dlrmbrselect .= '<option value="'.$fetch5['selectid'].'">'.$fetch5['selectname'].'</option>';
	}
}	
else
	$dlrmbrselect = '<option value = "" selected="selected"> - - - - Make A Selection - - - - </option>';


// Get date for From date field.

$month = date('m'); 
if($month >= '04')
   $date = '01-04-'.date('Y'); 
else 
{
	$year = date('Y') - '1';
	$date = '01-04-'.$year; //echo($date);
}

//Get current date for TO DATE field
$defaulttodate = datetimelocal("d-m-Y");

//Get all the user names with respective displaynames, where they are allowed to upload a lead.
switch($cookie_usertype)
{
	case "Admin":
	case "Sub Admin":
		$givenselect = '<option value="" selected="selected">- - - - All - - - -</option><option value="web">Web Downloads</option>';
		// For Last Updated By(Removed Web Downlaods
		$givenselect1 = '<option value="" selected="selected">- - - - All - - - -</option>';
		//Add all Sub Admins
		$query = "select lms_users.id AS selectid, lms_subadmins.sadname AS selectname from lms_users join lms_subadmins on lms_users.referenceid = lms_subadmins.id where lms_users.type = 'Sub Admin' ORDER BY selectname";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [S]</option>';
			$givenselect1 .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [S]</option>';
		}
		//Add all Managers
		$query = "select lms_users.id AS selectid, lms_managers.mgrname AS selectname from lms_users join lms_managers on lms_users.referenceid = lms_managers.id where lms_users.type = 'Reporting Authority' ORDER BY selectname";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [M]</option>';
			$givenselect1 .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [M]</option>';
		}
		//Add all Dealers
		$query = "select lms_users.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users join dealers on lms_users.referenceid = dealers.id where lms_users.type = 'Dealer' ORDER BY selectname";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [D]</option>';
			$givenselect1 .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [D]</option>';
		}
		break;

	case "Reporting Authority":
		$givenselect = '<option value="" selected="selected">- - - - All - - - - </option><option value="web">Web Downloads</option>';
		$givenselect1 = '<option value="" selected="selected">- - - - All - - - - </option>';
		//Add respective manager name
		$query = "select lms_users.id AS selectid, lms_managers.mgrname AS selectname from lms_users join lms_managers on lms_users.referenceid = lms_managers.id where lms_users.username = '".$cookie_username."'";
		if($cookie_username == "srinivasan")
			$query = "select lms_users.id AS selectid, lms_managers.mgrname AS selectname from lms_users join lms_managers on lms_users.referenceid = lms_managers.id where lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj'";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [M]</option>';
			$givenselect1 .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [M]</option>';
		}
		//Add all the Dealers, who are under the manager logged in
		$query = "select lms_users.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users join (SELECT dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '".$cookie_username."') AS dealers on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' ORDER BY selectname";
		if($cookie_username == "srinivasan")
		$query = "select lms_users.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users join (SELECT dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj') AS dealers on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' ORDER BY selectname";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [D]</option>';
			$givenselect1 .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [D]</option>';
		}
		break;

	case "Dealer":
		$givenselect = '<option value="" selected="selected">- - - - All - - - - </option><option value="web">Web Downloads</option>';
		$givenselect1 = '<option value="" selected="selected">- - - - All - - - -</option>';
		//Add respective Dealer name
		$query = "select lms_users.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users join dealers on lms_users.referenceid = dealers.id where lms_users.username = '".$cookie_username."'";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [D]</option>';
			$givenselect1 .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [D]</option>';
		}
		break;
		
	case "Dealer Member":
		$givenselect = '<option value="" selected="selected">- - - - All - - - -</option><option value="web">Web Downloads</option>';
		$givenselect1 = '<option value="" selected="selected">- - - - All - - - -</option><option value="web">Web Downloads</option>';
		break;
}

// For reference List

$query6 = "select distinct refer from leads where refer <> '' order by refer";
$result6 = runmysqlquery($query6);
$referenceselect .= '<option value="" selected="selected">- - - All - - - </option>';
while($fetch6 = mysqli_fetch_array($result6))
{
	$referenceselect .= '<option value="'.$fetch6['refer'].'">'.$fetch6['refer'].'</option>';
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Update</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<? echo (rand());?>">
<link type="text/css" rel="stylesheet" href="../css/datepickercontrol.css?dummy=<? echo(rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<? echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<? echo (rand());?>" language="javascript"></script>
<script src="../functions/simplelead.js?dummy=<? echo (rand());?>" language="javascript"></script>
<script src="../functions/datepickercontrol.js?dummy=<? echo (rand());?>" language="javascript"></script>
<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
</head>
<body onload="leadgridtab5('1','tabgroupleadgrid','default'),newtog(),updatestatusstrip();">
<div style="left: -1000px; top: 597px; visibility: hidden;" id="dhtmltooltip1">dummy</div>
<script src="../functions/tooltip1.js?dummy=<? echo (rand());?>" language="javascript"></script>
<table width="950" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="pageheader"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="28"><? include("../inc/header1.php"); ?></td>
        </tr>
        <tr>
          <td height="58" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="4"></td>
              </tr>
              <tr>
                <td height="54"><table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                      <td width="14%"><a href="http://useradmin.relyonsoft.com"><img src="../images/lms-logo.gif" alt="Lead Management Software" width="100" height="50" border="0" /></a></td>
                      <td width="86%"><? include("../inc/navigation.php"); ?></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="1"></td>
  </tr>
  <tr>
    <td valign="middle" bgcolor="#D2D8FB" class="contentheader"><table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
        <tr>
          <td>Update Lead</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="2"></td>
  </tr>
  <tr>
    <td height="1" bgcolor="#006699"></td>
  </tr>
  <tr>
    <td  height="40px" style="background:#FFFFCE"><table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" >
        <tr>
          <td width="17%" rowspan="2"><font color="#999999" style="font-size:20px">Total:&nbsp;<span id = "leadstotal" style="color:#000000" ></span></font><br />
            <span  onclick="updatestatusstrip();" class="statusstripclass">&nbsp;&nbsp;&nbsp;&nbsp;(Refresh Count)</span></td>
          <td width="83%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="12%" height="20"><div align="right" class="statusstriptextclass"><strong> Not Viewed :</strong></div></td>
                <td width="5%"><div align="right"><span id="noviewed"></span></div></td>
                <td width="12%"><div align="right" class="statusstriptextclass"><strong>UnAttended :</strong></div></td>
                <td width="6%"><div align="right"><span id="unattended"></span></div></td>
                <td width="14%"><div align="right" class="statusstriptextclass"><strong>Fake Enquiry :</strong></div></td>
                <td width="6%"><div align="right"><span id="notinterested"></span></div></td>
                <td width="19%"><div align="right" class="statusstriptextclass"><strong>Not Interested :</strong></div></td>
                <td width="6%"><div align="right"><span id="fakeenquiry"></span></div></td>
                <td width="15%"><div align="right" class="statusstriptextclass"><strong>Registered User :</strong></div></td>
                <td width="5%"><div align="right"><span id="registereduser"></span></div></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="12%"><div align="right" class="statusstriptextclass"><strong>Attended :</strong></div></td>
                <td width="5%"><div align="right"><span id="attended"></span></div></td>
                <td width="12%"><div align="right" class="statusstriptextclass"><strong>Demo Given :</strong> </div></td>
                <td width="6%"><div align="right"><span id="persuingtopurchase"></span></div></td>
                <td width="14%"><div align="right" class="statusstriptextclass"><strong>Quote Sent :</strong></div></td>
                <td width="6%"><div align="right"><span id="demogiven"></span></div></td>
                <td width="19%"><div align="right" class="statusstriptextclass"><strong>Persuing to Purchase :</strong></div></td>
                <td width="6%"><div align="right"><span id="quotesent"></span></div></td>
                <td width="15%"><div align="right" class="statusstriptextclass"><strong>Order Closed :</strong></div></td>
                <td width="5%"><div align="right"><span id="orderclosed"></span></div></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td valign="top" class="content"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td  colspan="4"><a name="leadview" id="leadview"></a></td>
        </tr>
        <tr>
          <td colspan="4"  valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="4" style="border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="6">
                          <tr>
                            <td ><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                <tr>
                                  <td width="40%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td valign="top" style="border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td bgcolor="#0099CC" height="20px"><strong><font color="#FFFFFF">Lead Contact Card</font></strong></td>
                                            </tr>
                                            <tr>
                                              <td  valign="top" height="50"><span id="leadisplay">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                                  <tr height="30px">
                                                    <td width="125"><strong>Company [id]</strong>: </td>
                                                    <td colspan="2"><font color="#FF6600"><span id="id"> </span></font>
                                                      <input type="hidden" name="hiddenid" id="hiddenid" value = ""/>
                                                      <input type="hidden" name="hiddencompany" id="hiddencompany" /></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Contact person</strong>:<font color="#FF6600">
                                                      <input name="hiddencontact" id="hiddencontact" type="hidden" value="" />
                                                      </font></td>
                                                    <td colspan="2"><font color="#FF6600">
                                                      <input name="contactperson" id ="contactperson" type="text" autocomplete = "off" style="width:230px; color:#FF6600;border:1px solid #E1E1E1;"/>
                                                      </font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Address:<font color="#FF6600">
                                                      <input name="hiddenaddress" id = "hiddenaddress" type="hidden" value="" />
                                                      </font></strong></td>
                                                    <td colspan="2"><font color="#FF6600">
                                                      <input name="address" id ="address" type="text" autocomplete = "off" style="width:230px; color:#FF6600;border:1px solid #E1E1E1;"/>
                                                      </font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>District / State</strong>:
                                                      <input type="hidden" name="hiddendistrictstate" id="hiddendistrictstate" /></td>
                                                    <td colspan="2"><font color="#FF6600"><span id="district"></span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>STD Code</strong>:
                                                      <input type="hidden" name="hiddenstdcode" id="hiddenstdcode" /></td>
                                                    <td colspan="2"><font color="#FF6600">
                                                      <input name="stdcode" id ="stdcode" type="text" autocomplete = "off" style="width:230px; color:#FF6600;border:1px solid #E1E1E1;"/>
                                                      </font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Landline</strong>:
                                                      <input type="hidden" name="hiddenphone" id="hiddenphone" /></td>
                                                    <td colspan="2"><font color="#FF6600">
                                                      <input name="phone" id ="phone" type="text" autocomplete = "off" style="width:230px; color:#FF6600;border:1px solid #E1E1E1;"/>
                                                      </font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Cell</strong>:
                                                      <input type="hidden" name="hiddencell" id="hiddencell" /></td>
                                                    <td colspan="2"><font color="#FF6600">
                                                      <input name="cell" id ="cell" type="text" autocomplete = "off" style="width:230px;color:#FF6600;border:1px solid #E1E1E1;"/>
                                                      </font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Email ID</strong>:
                                                      <input type="hidden" name="hiddenemailid" id="hiddenemailid" /></td>
                                                    <td colspan="2"><font color="#FF6600">
                                                      <input name="emailid" id ="emailid" type="text" autocomplete = "off" style="width:230px;color:#FF6600; border:1px solid #E1E1E1;"/>
                                                      </font></td>
                                                  </tr>
                                                  <tr height="35px">
                                                    <td valign="top"><strong>Reference [Type] :
                                                      <input type="hidden" name="hiddenreference" id="hiddenreference" />
                                                      </strong></td>
                                                    <td colspan="2" valign="top"><font color="#FF6600"><span id="referencetype"></span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Given By :
                                                      <input type="hidden" name="hiddengivenby" id="hiddengivenby" />
                                                      <input type="hidden" name="hiddengivenbytext" id="hiddengivenbytext" />
                                                      </strong></td>
                                                    <td width="212"><font color="#FF6600"><span id="givenby1"></span></font></td>
                                                    <td width="20"><span id="help" style="display:none;"><img src="../images/help-image.gif"  onmouseover="tooltip('All')" onMouseout="hidetooltip()" class="imageclass"/></span></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Date of lead:
                                                      <input type="hidden" name="hiddendateoflead" id="hiddendateoflead" />
                                                      </strong></td>
                                                    <td colspan="2"><font color="#FF6600"><span id="dateoflead"></span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Dealer viewed date:
                                                      <input type="hidden" name="hiddendealerviewdate" id="hiddendealerviewdate" />
                                                      </strong></td>
                                                    <td colspan="2"><font color="#FF6600"><span id="dealerviewdate"></span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Product:
                                                      <input type="hidden" name="hiddenproduct" id="hiddenproduct" />
                                                      </strong></td>
                                                    <td colspan="2"><font color="#FF6600"><span id="product1"></span></font></td>
                                                  </tr>
                                                   
                                                  
                                                  <?  if(($cookie_usertype == 'Admin') || ($cookie_usertype == 'Sub Admin') || ($cookie_usertype == 'Reporting Authority') || ($cookie_usertype == 'Dealer') || ($cookie_usertype == 'Dealer Member')) { ?>
                                                  <tr height="20px">
                                                    <td><strong>Dealer:
                                                      <input type="hidden" name="hiddendealer" id="hiddendealer" />
                                                      <input type="hidden" name="hiddentype" id="hiddentype" value = "<? echo($cookie_usertype); ?>" />
                                                      <input type="hidden" name="hiddendealertext" id="hiddendealertext" />
                                                      </strong></td>
                                                    <td><font color="#FF6600"><span id="dealer1" ></span></font></td>
                                                     <td><span id="help1" style="display:none;"><img src="../images/help-image.gif"  onMouseover="tooltip('Dealer')" onMouseout="hidetooltip()"  class="imageclass"/></span></td>
                                                   </tr>
                                                  <tr >
                                                    <td colspan="2" valign="top" height="25px"><div  id="link" align="center" style="display:none;" ><span  onclick="divopenclosefunction()" class="transferleadclass">Transfer this Lead >></span></div>
                                                      <div id = "selectlist" style="display:none;" align="left"><strong>Transfer To:</strong>&nbsp;&nbsp;&nbsp;
                                                        <select name="dealerlist1" id = "dealerlist1" style="width:180px">
                                                          <? echo($dealerselect1);?>
                                                        </select>
                                                        
                                                        &nbsp;&nbsp;<img src="../images/lmsreset-button.jpeg" onclick="resetlink('open')" class="imageclass"/>&nbsp;&nbsp;<img src="../images/lmsclose-button.jpeg" onclick="resetlink('close')" class="imageclass"/></div></td>
                                                  </tr>
                                                  
                                                  <tr height="20px">
                                                    <td><strong>Dealer Member:
                                                      <input type="hidden" name="hiddendealer" id="hiddendealer" />
                                                      <input type="hidden" name="hiddentype" id="hiddentype" value = "<? echo($cookie_usertype); ?>" />
                                                      <input type="hidden" name="hiddendealertext" id="hiddendealertext" />
                                                      </strong></td>
                                                    <td><font color="#FF6600"><span id="dealer2" ></span></font></td>
                                                    <td><span id="help1" style="display:none;"><img src="../images/help-image.gif"  onMouseover="tooltip('Dealer')" onMouseout="hidetooltip()"  class="imageclass"/></span></td>
                                                  </tr>
                                                  <? }if(($cookie_usertype == 'Dealer'))  { ?>
                                                  <tr >
                                                    <td colspan="2" valign="top" height="25px"><div  id="link1" align="center" style="display:none;" ><span  onclick="divopenclosefunction()" class="transferleadclass">Assign this Lead >></span></div>
                                                      <div id = "selectlist1" style="display:none;" align="left"><strong>Transfer To:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <select name="dealermemberlist" id = "dealermemberlist" style=" width:180px">
                                                          <? echo($dlrmbrselect);?>
                                                        </select>
                                                        &nbsp;&nbsp;<img src="../images/lmsreset-button.jpeg" onclick="resetlink('open')" class="imageclass"/>&nbsp;&nbsp;<img src="../images/lmsclose-button.jpeg" onclick="resetlink('close')" class="imageclass"/></div></td>
                                                  </tr>
                                                  <? } ?>
                                                  <tr height="20px">
                                                    <td><strong>Manager:
                                                      <input type="hidden" name="hiddenmanager" id="hiddenmanager" />
                                                      <input type="hidden" name="hiddenmanagertext" id="hiddenmanagertext" />
                                                      </strong></td>
                                                    <td><font color="#FF6600"><span id="manager"></span></font></td>
                                                    <td><span id="help2" style="display:none;"><img src="../images/help-image.gif"  onMouseover="tooltip('Manager')"; onMouseout="hidetooltip()"  class="imageclass"/></span></td>
                                                  </tr>
                                                  <tr>
                                                    <td td colspan="3"><label>&nbsp;&nbsp;&nbsp;
                                                      <input name="saveconfirm" id = "saveconfirm" type="checkbox"/>
                                                      &nbsp;&nbsp;Yes, I am Ready to Update.</label></td>
                                                  </tr>
                                                  <tr>
                                                    <td height="5px" colspan="3"></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td  valign="center" id="messagebox1" width="65%"></td>
                                                          <td valign="top"><div align="right">
                                                              <input type="button" name="update" id="update" value="Save" class="formbutton" onclick="updatedata();"/>
                                                              &nbsp;&nbsp;
                                                              <input name="reset" type="button" value="Reset" class="formbutton" onclick="resetform();" />
                                                            </div></td>
                                                        </tr>
                                                        <? if($cookie_usertype == 'Dealer Member') { ?>
                                                        <tr >
                                                          <td colspan="2" valign="top" height="25px">&nbsp;</td>
                                                        </tr>
                                                        <? } ?>
                                                      </table></td>
                                                  </tr>
                                                </table>
                                                </span></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                    </table></td>
                                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td width="1%"  height="20px"></td>
                                        <td width="14%" height="20px" valign="top"><div id="tarckerdiv" class="leadtrackerdivclass" onclick="leadtrack('tracker')">Lead Tracker</div></td>
                                        <td width="7%"  height="20px" valign="top"><div align="center">|</div></td>
                                        <td width="17%"  height="20px" valign="top"><div id="productchange" class="leadtrackerdivclass" onclick="leadtrack('productchange')">Change Product</div></td>
                                        <td width="6%"  height="20px" valign="top"><div align="center" >|</div></td>
                                        <td width="14%" valign="top"><div id="sendsmsdiv" class="leadtrackerdivclass" onclick="leadtrack('sendsms')">Send SMS</div></td>
                                        <td width="41%">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td  colspan="7"  valign="top"><div id="tracker" style="display:block;">
                                            <table width="100%"  cellspacing="0" cellpadding="1" style="border:solid 1px #999999">
                                              <tr>
                                                <td colspan="5" bgcolor="#0099CC"  height="20px"><strong><font color="#FFFFFF">Lead Tracker</font></strong></td>
                                              </tr>
                                              <tr height="20px">
                                                <td width="17%" align="center" id="tabgroupgridh1" onclick="gridtab5('1','tabgroupgrid','followup'); " style="cursor:pointer" class="grid-active-tabclass">Follow Up</td>
                                                <td width="20%" align="center" id="tabgroupgridh2" onclick="gridtab5('2','tabgroupgrid','updatelogs');" style="cursor:pointer" class="grid-tabclass">Update Logs</td>
                                                <td width="20%" align="center" id="tabgroupgridh3" onclick="gridtab5('3','tabgroupgrid','transferlogs');" style="cursor:pointer" class="grid-tabclass">Transfer Logs</td>
                                                <td width="21%" align="center" id="tabgroupgridh4" onclick="gridtab5('4','tabgroupgrid','viewotherleads');" style="cursor:pointer" class="grid-tabclass">Matching Leads</td>
                                                <td width="22%" align="center" id="tabgroupgridh5" onclick="gridtab5('5','tabgroupgrid','downloadlogs');" style="cursor:pointer" class="grid-tabclass">Download Logs</td>
                                              </tr>
                                              <tr>
                                                <td  colspan="5" valign="top" ><table width="100%"  cellspacing="0" cellpadding="0" >
                                                    <tr>
                                                      <td valign="top" height="364px"><div id="tabgroupgridc1" style="display:block;">
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                              <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                  <tr>
                                                                    <td width="32%" valign="top">Remarks :</td>
                                                                    <td colspan="2" valign="top"><textarea name="form_leadremarks" rows="2" class="formfields" style="padding:2px; width:400px; font-family:Arial, Helvetica, sans-serif; font-size:12px" id="form_leadremarks"></textarea>
                                                                      <input type="hidden" name="hiddenactivetype" id="hiddenactivetype" value = "followup"/></td>
                                                                  </tr>
                                                                  <tr>
                                                                    <td valign="top">Next Followup Date :</td>
                                                                    <td width="34%" valign="top"><input name="followupdate" type="text" class="formfields" id="DPC_followupdate" size="20" maxlength="10" value=""  readonly="readonly"/></td>
                                                                    <td width="34%" valign="top"><div align="center">
                                                                        <input style="height:20px" name="newfollowup" type="button" class="formbutton" id="newfollowup" value="New" onclick="newfollowup();" />
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                        <input style="height:20px" name="addfollowup" type="button" class="formbutton" id="addfollowup" value="Add &gt;&gt;" onclick="addfollowup();" />
                                                                      </div></td>
                                                                  </tr>
                                                                </table></td>
                                                            </tr>
                                                            <tr>
                                                              <td height="20px" valign="top"><span id="followupmessage"></span></td>
                                                            </tr>
                                                            <tr>
                                                              <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                                                  <tr>
                                                                    <td><div id="smallgrid" class="grid-div-small1">
                                                                        <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999">
                                                                          <tr class="gridheader">
                                                                            <td width="9%">Sl No</td>
                                                                            <td width="14%">Date</td>
                                                                            <td width="37%">Remarks</td>
                                                                            <td width="20%">Next Follow-up</td>
                                                                            <td width="20%">Entered by</td>
                                                                          </tr>
                                                                        </table>
                                                                      </div></td>
                                                                  </tr>
                                                                </table></td>
                                                            </tr>
                                                          </table>
                                                        </div>
                                                        <div id="tabgroupgridc2" style="display:none;">
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <input type="hidden" name="hiddenactivetype" id="hiddenactivetype"/>
                                                            <tr>
                                                              <td colspan="3" ><div id="tabgroupgridc11" style="overflow:auto; height:320px; width:550px" align="center">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td><div id="tabgroupgridc1_2" align="center">
                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="0" id="gridtable1">
                                                                            <tr class="gridheader" height="20px">
                                                                              <td class="tdborder1">&nbsp;Sl No</td>
                                                                              <td class="tdborder1">&nbsp;Lead Status</td>
                                                                              <td class="tdborder1">&nbsp;Updated Date</td>
                                                                              <td class="tdborder1">&nbsp;Updated By</td>
                                                                            </tr>
                                                                          </table>
                                                                        </div></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><div id="getmorelink2"  align="left" style="height:20px; "> </div></td>
                                                                    </tr>
                                                                  </table>
                                                                </div>
                                                                <div id="resultgrid2" style="overflow:auto; display:none; height:300px; width:550px;" align="center">&nbsp;</div></td>
                                                            </tr>
                                                          </table>
                                                        </div>
                                                        <div id="tabgroupgridc3" style="display:none;">
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <input type="hidden" name="hiddenactivetype" id="hiddenactivetype"/>
                                                            <tr>
                                                              <td colspan="3" ><div id="tabgroupgridc12" style="overflow:auto; height:310px; width:550px" align="center">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td><div id="tabgroupgridc1_3" align="center">
                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="0" id="gridtable1">
                                                                            <tr class="gridheader" height="20px">
                                                                              <td class="tdborder1">&nbsp;Sl No</td>
                                                                              <td class="tdborder1">&nbsp;From Dealer</td>
                                                                              <td class="tdborder1">&nbsp;To Dealer</td>
                                                                              <td class="tdborder1">&nbsp;Transfer Date</td>
                                                                              <td class="tdborder1">&nbsp;Transfered By</td>
                                                                            </tr>
                                                                          </table>
                                                                        </div></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><div id="getmorelink3"  align="left" style="height:20px; "></div></td>
                                                                    </tr>
                                                                  </table>
                                                                </div>
                                                                <div id="resultgrid3" style="overflow:auto; display:none; height:300px; width:550px;" align="center">&nbsp;</div></td>
                                                            </tr>
                                                          </table>
                                                        </div>
                                                        <div id="tabgroupgridc4" style="display:none;">
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <input type="hidden" name="hiddenactivetype" id="hiddenactivetype"/>
                                                            <tr>
                                                              <td colspan="3" ><div id="tabgroupgridc14" style="overflow:auto; height:310px; width:550px" align="center">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td><div id="tabgroupgridc1_4" align="center">
                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="0" id="gridtable1">
                                                                            <tr class="gridheader" height="20px">
                                                                              <td class="tdborder1">&nbsp;Sl No</td>
                                                                              <td class="tdborder1">&nbsp;Lead Id</td>
                                                                              <td class="tdborder1">&nbsp;Lead Date</td>
                                                                              <td class="tdborder1">&nbsp;Product</td>
                                                                              <td class="tdborder1">&nbsp;Company</td>
                                                                              <td class="tdborder1">&nbsp;Dealer</td>
                                                                            </tr>
                                                                          </table>
                                                                        </div></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><div id="getmorelink4" align="left" style="height:20px; "></div></td>
                                                                    </tr>
                                                                  </table>
                                                                </div>
                                                                <div id="resultgrid4" style="overflow:auto; display:none; height:300px; width:550px;" align="center">&nbsp;</div></td>
                                                            </tr>
                                                          </table>
                                                        </div>
                                                        <div id="tabgroupgridc5" style="display:none;">
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <input type="hidden" name="hiddenactivetype" id="hiddenactivetype"/>
                                                            <tr>
                                                              <td colspan="3" ><div id="tabgroupgridc15" style="overflow:auto; height:310px; width:550px" align="center">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td><div id="tabgroupgridc1_5" align="center">
                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="0" id="gridtable1">
                                                                            <tr class="gridheader" height="20px">
                                                                              <td class="tdborder1">&nbsp;Sl No</td>
                                                                              <td class="tdborder1">&nbsp;Company</td>
                                                                              <td class="tdborder1">&nbsp;Product</td>
                                                                              <td class="tdborder1">&nbsp;Date(Time)</td>
                                                                            </tr>
                                                                          </table>
                                                                        </div></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><div id="getmorelink5"  align="left" style="height:20px; "></div></td>
                                                                    </tr>
                                                                  </table>
                                                                </div>
                                                                <div id="resultgrid5" style="overflow:auto; display:none; height:300px; width:550px;" align="center">&nbsp;</div></td>
                                                            </tr>
                                                          </table>
                                                        </div></td>
                                                    </tr>
                                                  </table></td>
                                              </tr>
                                            </table>
                                          </div>
                                          <div id="changetheproduct" style="display:none">
                                            <form id="productchangeform" name="productchangeform" method="post" action="">
                                              <table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:363px">
                                                <tr>
                                                  <td><table width="100%" border="0" cellspacing="0" cellpadding="2" style="border:solid 1px #999999">
                                                      <tr>
                                                        <td bgcolor="#0099CC" height="17px"><strong><font color="#FFFFFF">Change Product by adding as a new Lead</font></strong></td>
                                                      </tr>
                                                      <tr>
                                                        <td ><table width="100%" border="0" cellspacing="0" >
                                                            <tr>
                                                              <td colspan="2"><div align="justify"><font color="#FF6666"><strong>Note :</strong> </font>This provision will create/upload a &quot;New Lead&quot; under you name for a different product selected. Please note, existing lead remains as it is, without any changes. If existing lead is not interested, please update the same. You can access such new lead created, by separately locating it.</div></td>
                                                            </tr>
                                                            <tr>
                                                              <td width="27%" height="30px"><strong>Company :</strong>
                                                                <input type="hidden" name="hiddennewleadcompany" id="hiddennewleadcompany" /></td>
                                                              <td width="73%" height="30px"><span id="newleadcompany"></span></td>
                                                            </tr>
                                                            <tr>
                                                              <td><strong>Contact person :</strong></td>
                                                              <td><input type="text" name="newleadcontactperson" id="newleadcontactperson" class="formfields1"/>
                                                                <input type="hidden" name="hiddennewleadcontact" id="hiddennewleadcontact" />
                                                              </td>
                                                            </tr>
                                                            <tr>
                                                              <td><strong>Cell :</strong></td>
                                                              <td><input type="text" name="newleadcell" id="newleadcell" class="formfields1" />
                                                                <input type="hidden" name="hiddennewleadcell" id="hiddennewleadcell" />
                                                              </td>
                                                            </tr>
                                                            <tr>
                                                              <td><strong>Email Id :</strong></td>
                                                              <td><input type="text" name="newleademailid" id="newleademailid" class="formfields1"/>
                                                                <input name="hiddennewleademailid" id="hiddennewleademailid" type="hidden"  />
                                                              </td>
                                                            </tr>
                                                            <tr>
                                                              <td width="31%"><font color="#FF6666"><strong>Product :</strong></font></td>
                                                              <td width="69%"><label>
                                                                <select name="productchangeselect" id="productchangeselect" class="formfields1" style="border:1px solid #FF6666">
                                                                  <?
													  echo($productchangeselect);
													  ?>
                                                                </select>
                                                                </label></td>
                                                            </tr>
                                                            <tr>
                                                              <td><strong>Reference :</strong></td>
                                                              <td><select name="newleadsource" class="formfields1" id="newleadsource">
                                                                  <option value="" selected="selected">- - - - Make a Selection - - - -</option>
                                                                  <option value="Advertisement">Advertisement</option>
                                                                  <option value="Email">Email</option>
                                                                  <option value="Bulkmail">Bulkmail</option>
                                                                  <option value="Existing Customer">Existing Customer</option>
                                                                  <option value="Mailer - Letter">Mailer - Letter</option>
                                                                  <option value="NSDL/Income Tax website">NSDL/Income Tax website</option>
                                                                  <option value="Reference from customer">Reference from customer</option>
                                                                  <option value="Relyon Representative">Relyon Representative</option>
                                                                  <option value="Web Search/Search Engine">Web Search/Search Engine</option>
                                                                  <option value="Incoming Email with clear reqt">Incoming Email with clear reqt</option>
                                                                  <option value="Incoming Call">Incoming Call</option>
                                                                  <option value="Others">Others</option>
                                                                </select>
                                                                <input type="hidden" name="hiddennewleadsource" id="hiddennewleadsource" /></td>
                                                            </tr>
                                                            <tr>
                                                              <td valign="top"><strong>Remarks :</strong></td>
                                                              <td><textarea name="leadremarks1" rows="4" class="formfields" style="padding:2px; width:400px; font-family:Arial, Helvetica, sans-serif; font-size:12px;border:1px thin #000000;" id="leadremarks1"></textarea></td>
                                                            </tr>
                                                            <tr>
                                                              <td><strong>Dealer :</strong></td>
                                                              <td><label>
                                                                <input type="radio" name="dealerselection" id="samedealer" value="samedealer" checked="checked" onclick="togdealerselect();"/>
                                                                Same Dealer</label>
                                                                <label>
                                                                <input type="radio" name="dealerselection" id="aspermapping" value="aspermapping" onclick="togdealerselect();" />
                                                                As per Mapping</label>
                                                                <label>
                                                                <input type="radio" name="dealerselection" id="manualselection" value="manualselection"  onclick="togdealerselect();"/>
                                                                Manual Selection</label></td>
                                                            </tr>
                                                            <tr>
                                                              <td>&nbsp;</td>
                                                              <td height="25px"><div id="dealerlistdiv" style="display:none;">
                                                                  <select name="changeproductdealerlist" id="changeproductdealerlist" style="width:50%">
                                                                    <? 
						echo($dealerselect1);
						?>
                                                                  </select>
                                                                </div></td>
                                                            </tr>
                                                            <tr>
                                                              <td colspan="2" height="25px">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                              <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                  <tr>
                                                                    <td width="68%" height="25" id = "errordisplay">&nbsp;</td>
                                                                    <td width="32%" align="right"><input type="button" name="create" id="create" value="Create Lead" class="formbutton" onclick="createlead();"/>
                                                                      &nbsp;&nbsp;
                                                                      <input type="button" name="resetbutton" id="resetbutton" value="Reset" class="formbutton" onclick="resetproductchange();"/>
                                                                    </td>
                                                                  </tr>
                                                                </table></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                              </table>
                                            </form>
                                          </div>
                                          <div id = "sendsms" style="display:none">
                                            <form id="smsform" name="smsform" method="post" action="">
                                              <table width="100%" border="0" cellspacing="0" cellpadding="2" style="border:solid 1px #999999;height:412px">
                                                <tr>
                                                  <td  height="20px" colspan="2" bgcolor="#0099CC"><strong><font color="#FFFFFF">Send SMS</font></strong></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2" height="25px" id="prior-sms-error">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td width="22%"><strong>Cell :</strong></td>
                                                  <td width="78%"><span id="smscell"></span>
                                                    <input type="hidden" name="hiddensmscell" id="hiddensmscell" />
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td valign="top"><strong>SMS Text:</strong></td>
                                                  <td><textarea name="smstext" id = "smstext" rows="3" class="formfields" style="padding:2px; width:400px; font-family:Arial, Helvetica, sans-serif; font-size:12px; size:160;" onkeyup="countsmslength()" onclick="countsmslength()"></textarea></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2"><label>
                                                    <input type="checkbox" name="mynumber" id="mynumber"  onclick="javascript:  insertAtCursor(document.smsform.smstext); document.smsform.smstext.focus();countsmslength() "/>
                                                    Add My Number </label></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                      <tr>
                                                        <td width="70%" id="sms-error" height="20px">&nbsp;</td>
                                                        <td width="30%" height="20px"><input name="sendsmsbutton" id="sendsmsbutton" type="button" value="Send SMS" class="formbutton" onclick="sendsms('sms');"/>
                                                          &nbsp;&nbsp;&nbsp;
                                                          <input name="smsclear" type="button" value="Reset" class="formbutton" onclick="sendsms('reset');"/></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td colspan="3"  valign="top"><div id="sendsmsc1" style="overflow:auto;  width:545px; height:200px;border:1px solid #999999;" >
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0"   >
                                                              <tr>
                                                                <td valign="top"><div id="sendsmsc1_1">
                                                                    <table width="100%" border="1" cellspacing="0" cellpadding="0" id="gridtable1">
                                                                      <tr class="gridheader" height="20px">
                                                                        <td>Sl No</td>
                                                                        <td>SMS Date</td>
                                                                        <td>SMS Text</td>
                                                                        <td>To Number</td>
                                                                        <td>Sent By</td>
                                                                      </tr>
                                                                    </table>
                                                                  </div></td>
                                                              </tr>
                                                              <tr>
                                                                <td><div id="getmoresmslink"  align="left" style="height:20px; "></div></td>
                                                              </tr>
                                                            </table>
                                                            <div id="resultgridsms" style="overflow:auto; display:none; width:545px;" align="center">&nbsp;</div>
                                                          </div></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                              </table>
                                            </form>
                                          </div></td>
                                      </tr>
                                    </table></td>
                                </tr>
                                <tr>
                                  <td valign="top"><span align="center">
                                    <input name="form_recid" type="hidden" class="formfields" id="form_recid" />
                                    </span></td>
                                  <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td  valign="top" style="border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                            <tr>
                                              <td  bgcolor="#0099CC"><strong><font color="#FFFFFF">Lead Status</font></strong></td>
                                            </tr>
                                            <tr>
                                              <td valign="top" height="65px"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                  <tr>
                                                    <td valign="top" colspan="2"><strong>Initial Remarks :
                                                      <div id="showeditimage" style="display:none;" align="left"><img src="../images/edit1.png"  height="16" onclick="opentoedit();" class="imageclass"></div>
                                                      <!-- <div id="showeditimage" style="display:none;" align="left"><img src="../images/LMS-edit.jpg"  onmouseover="showtheeditimage('show')"
 onmouseout="showtheeditimage('hide')">
</div>
<div style="display:none;position:absolute; opacity:0.9;" id="hoverimage" onclick="opentoedit();"><img src="../images/LMS-edithover.jpg"  /></div>-->
                                                      <input type="hidden" name="hiddenleadremarks" id="hiddenleadremarks" />
                                                      </strong></td>
                                                    <td width="33%" valign="top"><div id="leadremarks"><font color="#FF6600">-Select a lead-</font></div>
                                                      <div id="leadremarksbox" style="display:none;">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                                          <tr>
                                                            <td width="84%"><textarea name="editleadremarks" id="editleadremarks" rows="2" class="formfields" style="padding:2px; width:260px; font-family:Arial, Helvetica, sans-serif; font-size:12px" ></textarea></td>
                                                            <td width="16%"><div><img src="../images/lmsreset-button.jpeg" onclick="closeopentxtbox('open')" class="imageclass"/></div>
 
                                                              <div><img src="../images/lmsclose-button.jpeg" onclick="closeopentxtbox('close')" class="imageclass"/></div></td>
                                                          </tr>
                                                        </table>
                                                      </div></td>
                                                    <td width="10%" valign="top"></td>
                                                    <td width="14%" valign="top"><strong>Current Status  :</strong> </td>
                                                    <td width="31%" valign="top"><select name="form_leadstatus" id="form_leadstatus" class="formfields">
                                                        <option value="" selected="selected">--- Select ---</option>
                                                        <option value="Not Viewed">Not Viewed</option>
                                                        <option value="UnAttended">UnAttended</option>
                                                        <option value="Not Interested">Not Interested</option>
                                                        <option value="Fake Enquiry">Fake Enquiry</option>
                                                        <option value="Registered User">Registered User</option>
                                                        <option value="Attended">Attended</option>
                                                        <option value="Perusing to Purchase">Perusing to Purchase</option>
                                                        <option value="Demo Given">Demo Given</option>
                                                        <option value="Quote Sent">Quote Sent</option>
                                                        <option value="Order Closed">Order Closed</option>
                                                      </select>
                                                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                      <input name="save" type="button" class="formbutton" id="save" value="Update" onclick="formsubmit('save');" /></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                      <tr>
                                        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                            <tr>
                                              <td id="msg_box" width="50%" height="25px">&nbsp;</td>
                                              <td>&nbsp;</td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                    </table></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4" style="color:#000000;border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td bgcolor="#DEE1FA"><strong>Filter: </strong>[<a style="cursor:pointer" onclick="newtog();">Show/Hide</a>]</td>
                    </tr>
                    <tr>
                      <td><form id="filterform" name="filterform" onsubmit="return false;" autocomplete = "off" method="post" action="filteredtoexcel.php">
                          <div id="divform">
                            <table width="100%" border="0" cellspacing="0" cellpadding="2" style="background-color:#ffffcc" >
                              <tr>
                                <td>&nbsp;<strong>Search</strong>:
                                  <input name="searchcriteria" type="text" class="formfields" id="searchcriteria"  size="30" maxlength="30" />
                                  <font style="font-size:10px;" color="#666666">(Leave Empty for all)</font>
                                  <input type="hidden" name="srchhiddenfield" id="srchhiddenfield" /></td>
                                <td><strong>From :</strong></td>
                                <td colspan="2">&nbsp;
                                  <label>
                                  <input name="datatype" type="radio"  value="download" />
                                  Web Downloads</label>
                                  <label>
                                  <input type="radio" name="datatype" value="upload"/>
                                  Manual Uploads</label>
                                  <label>
                                  <input name="datatype" type="radio" value="both" checked="checked"/>
                                  Both</label>
                                  <input type="hidden" name="subselhiddenfield" id="subselhiddenfield" />
                                </td>
                              </tr>
                              <tr>
                                <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border-bottom:1px solid #CCCCCC">
                                    <tr>
                                      <td><strong>Search For:</strong>
                                        <label>
                                        <input name="databasefield" type="radio"  value="leadid" />
                                        Lead ID</label>
                                        <label>
                                        <input name="databasefield" type="radio"  value="company" checked="checked"/>
                                        Company</label>
                                        <label>
                                        <input type="radio" name="databasefield" value="name" />
                                        Contact Person</label>
                                        <label>
                                        <input type="radio" name="databasefield" value="phone" />
                                        Phone </label>
                                        <label>
                                        <input type="radio" name="databasefield" value="cell" />
                                        Cell </label>
                                        <label>
                                        <input type="radio" name="databasefield" value="email" />
                                        Email </label>
                                        <label>
                                        <input type="radio" name="databasefield" value="district" />
                                        District </label>
                                        <label>
                                        <input type="radio" name="databasefield" value="state" />
                                        State </label>
                                        &nbsp;
                                        <label>
                                        <input type="radio" name="databasefield" value="manager" />
                                        Manager Name</label>
                                        <input name="datatypehiddenfield" id="datatypehiddenfield" type="hidden" /></td>
                                    </tr>
                                  </table></td>
                              </tr>
                              <tr>
                                <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border-bottom:1px solid #CCCCCC">
                                    <tr>
                                      <td width="11%">From Date : </td>
                                      <td width="37%"><input name="fromdate" type="text" class="formfields" id="DPC_fromdate" size="20" maxlength="10" value="<? echo($date); ?>"  style="width:50%" />
                                        <input type="hidden" name="hiddenfromdate" id="hiddenfromdate" /></td>
                                      <td width="13%">To Date : </td>
                                      <td width="39%"><input name="todate" type="text" class="formfields" id="DPC_todate" size="20" maxlength="10" value="<? echo($defaulttodate); ?>" style="width:50%" />
                                        <input type="hidden" name="hiddentodate" id="hiddentodate" /></td>
                                    </tr>
                                    <tr>
                                      <td>Product Name : </td>
                                      <td width="37%"><select name="productid" class="formfields" id="productid" style="width:50%">
                                          <? 
						echo($productselect);
						?>
                                        </select>
                                        <input type="hidden" name="hiddenproductid" id="hiddenproductid" />
                                        <input type="hidden" name="hiddengrouplabel" id="hiddengrouplabel" /></td>
                                      <td width="13%">Dealer Name: </td>
                                      <td width="39%"><select name="dealerid" class="formfields" id="dealerid" style="width:50%">
                                          <? 
						echo($dealerselect);
						?>
                                        </select>
                                        <input type="hidden" name="hiddendealerid" id="hiddendealerid" /></td>
                                    </tr>
                                    <tr>
                                      <td>Given By : </td>
                                      <td width="37%"><select name="givenby" class="formfields" id="givenby" style="width:50%">
                                          <? 
						echo($givenselect);
						?>
                                        </select>
                                        <input type="hidden" name="hiddengivenby1" id="hiddengivenby1" /></td>
                                      <td width="13%">Status of Lead :</td>
                                      <td width="39%"><select name="leadstatus" class="formfields" id="leadstatus" style="width:50%">
                                          <? 
						echo($leadstatusselect);
						?>
                                        </select>
                                        <input type="hidden" name="hiddenleadstatus" id="hiddenleadstatus" /></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2"><input name="dropterminatedstatus" type="checkbox" id="dropterminatedstatus" value="true" checked="checked" />
                                        <label for="dropterminatedstatus">Do not consider Order Closed / Fake / Exsting Users / Not Interested</label></td>
                                      <td>Lead Source:</td>
                                      <td><select name="form_source" class="formfields" id="form_source" style="width:50%">
                                          <? echo($referenceselect);?>
                                        </select>
                                        <input type="hidden" name="hiddensource" id="hiddensource" /></td>
                                    </tr>
                                  </table></td>
                              </tr>
                              <tr>
                                <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                    <tr>
                                      <td width="48%">&nbsp;
                                        <input name="considerfollowup" type="checkbox" id="considerfollowup" onclick="filterfollowupdates();" />
                                        <label for="considerfollowup">Consider Follow Ups</label></td>
                                      <td width="52%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="34%"><label>
                                              <input type="radio" name="followup" id="followuppending" value="followuppending" checked="checked" disabled="disabled"/>
                                              Follow Up Pending</label></td>
                                            <td width="66%"><label>
                                              <input type="radio" name="followup" id="followupmade" value="followupmade" disabled="disabled"/>
                                              Follow Up Made </label></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table></td>
                              </tr>
                              <tr>
                                <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                    <tr>
                                      <td width="11%">From Date : </td>
                                      <td width="37%"><input name="filter_followupdate1" type="text" class="formfields" id="DPC_filter_followupdate1" size="20" maxlength="10" value="<? echo($defaulttodate); ?>" disabled="disabled" style="width:50%" />
                                        <input name="filter_followupdate1hdn" type="hidden" class="formfields" id="filter_followupdate1hdn" value="" /></td>
                                      <td width="13%">To Date : </td>
                                      <td width="39%"><input name="filter_followupdate2" type="text" class="formfields" id="DPC_filter_followupdate2" size="20" maxlength="10" value="<? echo($defaulttodate); ?>" disabled="disabled"  style="width:50%" />
                                        <input name="filter_followupdate2hdn" type="hidden" class="formfields" id="filter_followupdate2hdn" value="" /></td>
                                    </tr>
                                    <tr>
                                      <td>Entered By :</td>
                                      <td><select name="followedby" id="followedby" style="width:50%" disabled="disabled">
                                          <? 
						echo($givenselect1);
						?>
                                        </select>
                                        <input name="followedbyhidden" id = "followedbyhidden" type="hidden" /></td>
                                      <td>Remarks :</td>
                                      <td><input type="text" name="remarks" id="remarks" disabled="disabled" style="width:50%"/>
                                      </td>
                                    </tr>
                                  </table></td>
                              </tr>
                              <tr>
                                <td colspan="2" id="msg_box2"></td>
                                <td colspan="2"><div align="center">
                                    <input name="view" type="button" class="formbutton" id="view" value="Show" onclick="filtering('view');" />
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input name="excel" type="button" class="formbutton" id="excel" value="To Excel" onclick="filtering('excel');" />
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input name="excel" type="button" class="formbutton" id="resetform" value="Reset" onclick="filtering('resetform');" />
                                  </div></td>
                              </tr>
                            </table>
                          </div>
                        </form></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="141" id="tabgroupleadgridh1" onclick="leadgridtab5('1','tabgroupleadgrid','default'); " class="grid-active-leadtabclass">Recent Leads</td>
                      <td width="3"></td>
                      <td width="141"  id="tabgroupleadgridh2" onclick="leadgridtab5('2','tabgroupleadgrid','notviewed'); " class="grid-leadtabclass">Leads Not Viewed!</td>
                      <td width="3"></td>
                      <td width="141"  id="tabgroupleadgridh3" onclick="leadgridtab5('3','tabgroupleadgrid','todayfollowup'); " class="grid-leadtabclass">Follow Ups Due</td>
                      <td width="3"></td>
                      <td width="141" id="tabgroupleadgridh4" onclick="leadgridtab5('4','tabgroupleadgrid','nofollowup'); " class="grid-leadtabclass">Zero Follow Ups !</td>
                      <td width="3"></td>
                      <td width="141"  id="tabgroupleadgridh5" onclick="leadgridtab5('5','tabgroupleadgrid','searchresult'); " class="grid-leadtabclass">Search Result</td>
                      <td width="141"></td>
                      <td width="94"></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="20px" colspan="4"><div id="tabgroupleadgridc1" style="display:block;">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                <tr class="headerline">
                  <td width="50%"><span id="gridprocess"></span></td>
                  <td width="50%"  align="right" id="gridprocess1"></td>
                </tr>
                <tr>
                  <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td colspan="3" ><div id="tabgroupgridc1" style="overflow:auto; height:260px; width:945px" align="center">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td><div id="tabgroupgridc1_1" align="center"></div></td>
                              </tr>
                              <tr>
                                <td><div id="getmorelink"  align="left" style="height:20px; "></div></td>
                              </tr>
                            </table>
                          </div>
                          <div id="resultgrid" style="overflow:auto; display:none; height:150px; width:695px;" align="center">&nbsp;</div></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
            </div>
            <div id = 'tabgroupleadgridc2' style="display:none;">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                <tr class="headerline">
                  <td width="50%" ><strong>&nbsp;<span id="gridprocessnv"></span></strong></td>
                  <td align="left">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="3" ><div id="tabgroupgridnv1" style="overflow:auto; height:260px; width:945px" align="center">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><div id="tabgroupgridnv1_1" align="center">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr class="gridheader" height="20px">
                                  <td class="tdborderlead">&nbsp;Sl No</td>
                                  <td class="tdborderlead">&nbsp;Lead Id</td>
                                  <td class="tdborderlead">&nbsp;Lead Date</td>
                                  <td class="tdborderlead">&nbsp;Product</td>
                                  <td class="tdborderlead">&nbsp;Product</td>
                                  <td class="tdborderlead">&nbsp;Company</td>
                                  <td class="tdborderlead">&nbsp;Contact</td>
                                  <td class="tdborderlead">&nbsp;Landline</td>
                                  <td class="tdborderlead">&nbsp;Cell</td>
                                  <td class="tdborderlead">&nbsp;Email Id</td>
                                  <td class="tdborderlead">&nbsp;District</td>
                                  <td class="tdborderlead">&nbsp;State</td>
                                  <td class="tdborderlead">&nbsp;Dealer</td>
                                  <td class="tdborderlead">&nbsp;Manager</td>
                                </tr>
                              </table>
                            </div></td>
                        </tr>
                        <tr>
                          <td><div id="getmorelinknv1"  align="left" style="height:20px; "> </div></td>
                        </tr>
                      </table>
                    </div>
                    <div id="resultgridnv1" style="overflow:auto; display:none; height:150px; width:700px;" align="center">&nbsp;</div></td>
                </tr>
              </table>
            </div>
            <div id="tabgroupleadgridc3" style="display:none;">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                <tr class="headerline">
                  <td width="15%"><div id = "followuptotal" >&nbsp;</div></td>
                  <td align="left"><div id="showprocessingimage"></div></td>
                </tr>
                <tr>
                  <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td colspan="3"><div id="tabgroupgridfup1" style="overflow:auto; height:260px; width:947px" align="center">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td><div id="tabgroupgridfup1_1" align="center">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr class="gridheader">
                                        <td class="tdborderlead">&nbsp;Sl No</td>
                                        <td class="tdborderlead">&nbsp;Lead Id</td>
                                        <td class="tdborderlead">&nbsp;Lead Date</td>
                                        <td class="tdborderlead">&nbsp;Product</td>
                                        <td class="tdborderlead">&nbsp;Company</td>
                                        <td class="tdborderlead">&nbsp;Contact</td>
                                        <td class="tdborderlead">&nbsp;Landline</td>
                                        <td class="tdborderlead">&nbsp;Cell</td>
                                        <td class="tdborderlead">&nbsp;Email Id</td>
                                        <td class="tdborderlead">&nbsp;District</td>
                                        <td class="tdborderlead">&nbsp;State</td>
                                        <td class="tdborderlead">&nbsp;Dealer</td>
                                        <td class="tdborderlead">&nbsp;Manager</td>
                                      </tr>
                                    </table>
                                  </div></td>
                              </tr>
                              <tr>
                                <td><div id="getmorelinkfup"  align="left" style="height:20px; "> </div></td>
                              </tr>
                            </table>
                          </div>
                          <div id="resultgridfup" style="overflow:auto; display:none; height:150px; width:695px;" align="center">&nbsp;</div></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
            </div>
            <div id="tabgroupleadgridc4" style="display:none;">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                <tr class="headerline">
                  <td width="15%"><div id = "followuptotaln" >&nbsp;</div></td>
                  <td align="left"><span id="gridprocessn1"></span></td>
                </tr>
                <tr>
                  <td colspan="3" ><div id="tabgroupgridn1" style="overflow:auto; height:260px; width:945px" align="center">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><div id="tabgroupgridn1_1" align="center">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr class="gridheader" height="20px">
                                  <td class="tdborderlead">&nbsp;Sl No</td>
                                  <td class="tdborderlead">&nbsp;Lead Id</td>
                                  <td class="tdborderlead">&nbsp;Lead Date</td>
                                  <td class="tdborderlead">&nbsp;Product</td>
                                  <td class="tdborderlead">&nbsp;Company</td>
                                  <td class="tdborderlead">&nbsp;Contact</td>
                                  <td class="tdborderlead">&nbsp;Landline</td>
                                  <td class="tdborderlead">&nbsp;Cell</td>
                                  <td class="tdborderlead">&nbsp;Email Id</td>
                                  <td class="tdborderlead">&nbsp;District</td>
                                  <td class="tdborderlead">&nbsp;State</td>
                                  <td class="tdborderlead">&nbsp;Dealer</td>
                                  <td class="tdborderlead">&nbsp;Manager</td>
                                </tr>
                              </table>
                            </div></td>
                        </tr>
                        <tr>
                          <td><div id="getmorelinkn1"  align="left" style="height:20px; "> </div></td>
                        </tr>
                      </table>
                    </div>
                    <div id="resultgridn1" style="overflow:auto; display:none; height:150px; width:700px;" align="center">&nbsp;</div></td>
                </tr>
              </table>
            </div>
            <div id="tabgroupleadgridc5" style="display:none;">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                <tr class="headerline">
                  <td width="50%" ><strong>&nbsp;List of leads:<span id="gridprocessf"></span></strong></td>
                  <td align="left"><span id="gridprocessf1"></span></td>
                </tr>
                <tr>
                  <td colspan="3" ><div id="tabgroupgridf1" style="overflow:auto; height:260px; width:945px" align="center">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><div id="tabgroupgridf1_1" align="center">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr class="gridheader" height="20px">
                                  <td class="tdborderlead">&nbsp;Sl No</td>
                                  <td class="tdborderlead">&nbsp;Lead Id</td>
                                  <td class="tdborderlead">&nbsp;Lead Date</td>
                                  <td class="tdborderlead">&nbsp;Product</td>
                                  <td class="tdborderlead">&nbsp;Company</td>
                                  <td class="tdborderlead">&nbsp;Contact</td>
                                  <td class="tdborderlead">&nbsp;Landline</td>
                                  <td class="tdborderlead">&nbsp;Cell</td>
                                  <td class="tdborderlead">&nbsp;Email Id</td>
                                  <td class="tdborderlead">&nbsp;District</td>
                                  <td class="tdborderlead">&nbsp;State</td>
                                  <td class="tdborderlead">&nbsp;Dealer</td>
                                  <td class="tdborderlead">&nbsp;Manager</td>
                                </tr>
                              </table>
                            </div></td>
                        </tr>
                        <tr>
                          <td><div id="getmorelinkf1"  align="left" style="height:20px; "> </div></td>
                        </tr>
                      </table>
                    </div>
                    <div id="resultgridf1" style="overflow:auto; display:none; height:150px; width:700px;" align="center">&nbsp;</div></td>
                </tr>
              </table>
            </div></td>
        </tr>
        <tr>
          <td height="20px" colspan="4"></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="1"></td>
  </tr>
  <tr>
    <td valign="top" class="pagefooter"><table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="8"></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellpadding="4" cellspacing="0" class="footer">
              <tr>
                <td width="50%">Copyright © Relyon Softech Limited. All rights reserved. </td>
                <td width="50%"><div align="right"><a href="http://www.relyonsoft.com" target="_blank">www.relyonsoft.com</a> | <a href="http://www.saraltaxoffice.com" target="_blank">www.saraltaxoffice.com</a> | <a href="http://www.saralpaypack.com" target="_blank">www.saralpaypack.com</a> | <a href="http://www.saraltds.com" target="_blank">www.saraltds.com</a></div></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
