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
		
		$query = "SELECT distinct dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE ".$branchpiecejoin ." ORDER BY dealers.dlrcompanyname";
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
$dealerselect = '';
if(mysqli_num_rows($result) > 1)
$dealerselect .= '<option value="" selected="selected">- - - - All - - - -</option>';
while($fetch = mysqli_fetch_array($result))
{
	$dealerselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].'</option>';
}



// Select dealer list to display in lead contract card.
if(($cookie_usertype == 'Admin') || ($cookie_usertype == 'Sub Admin') || ($cookie_usertype == 'Reporting Authority'))
{
	switch($cookie_usertype)
	{
		case "Admin":
		case "Sub Admin":
			$query1 = "SELECT distinct dealers.id AS selectid, dealers.dlrcompanyname AS selectname 
	FROM dealers left join lms_users on lms_users.referenceid = dealers.id where lms_users.disablelogin <> 'yes' and lms_users.type = 'Dealer' ORDER BY dlrcompanyname;";
			break;
		case "Reporting Authority":
			$query1 = "select dealers.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users left join dealers on dealers.id=lms_users.referenceid where dealers.managerid  in (select dealers.managerid from dealers left join lms_users on dealers.managerid =lms_users.referenceid where lms_users.username = '".$cookie_username."'and lms_users.type = 'Reporting Authority')
	and  lms_users.type = 'Dealer' and lms_users.disablelogin <> 'yes' ORDER BY dealers.dlrcompanyname";
			if($cookie_username == "srinivasan")
			$query1 = "select dealers.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users  left join dealers on dealers.id =lms_users.referenceid where dealers.managerid  in (select dealers.managerid from dealers left join lms_users on dealers.managerid =lms_users.referenceid where lms_users.username = '".$cookie_username."'  or lms_users.username = 'nagaraj'and lms_users.type ='Reporting Authority') and lms_users.type = 'Dealer' and lms_users.disablelogin <> 'yes' ORDER BY dealers.dlrcompanyname";		
			break;
	}
	$result1 = runmysqlquery($query1); //echo('here');exit;
	$dealerselect1 = '';
	if(mysqli_num_rows($result) > 1)
	$dealerselect1 .= '<option value="" selected="selected">- - - Make Selection - - - </option>';
	while($fetch1 = mysqli_fetch_array($result1))
	{
		$dealerselect1 .= '<option value="'.$fetch1['selectid'].'">'.$fetch1['selectname'].'</option>';
	}
	$height = '385';
}
else
{
	$height = '360';
}


//Select the list of products for the drop-down
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
$productchangeselect .= '<option value="" selected="selected">- - - Select - - -</option>';
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
<title>LMS | Send Bulk SMS</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<link type="text/css" rel="stylesheet" href="../css/datepickercontrol.css?dummy=<?php echo (rand());?>">
<link media="screen" rel="stylesheet" href="../css/colorbox.css" />
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/bulksms.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/colorbox.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" language="javascript"></script>
</head>
<body>
<table width="950" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="pageheader"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="28"><?php include("../inc/header1.php"); ?></td>
        </tr>
        <tr>
          <td height="58" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="4"></td>
              </tr>
              <tr>
                <td height="54"><table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                      <td width="14%"><a href="http://lms.relyonsoft.net"><img src="../images/lms-logo.gif" alt="Lead Management Software" width="100" height="50" border="0" /></a></td>
                      <td width="86%"><?php include("../inc/navigation.php"); ?></td>
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
          <td>Bulk SMS Sending</td>
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
    <td valign="top" class="content"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="20"></td>
        </tr>
        <tr>
          <td height="20"><strong>&nbsp;1. Filter the Leads</strong></td>
        </tr
      >
        <tr>
          <td height="300" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:1px solid #CCCCCC;background-color:#ffffcc" >
                    <tr>
                      <td ><div id="displayfilter">
                          <form id="filterform" name="filterform" onsubmit="return false;" autocomplete = "off" method="post" action="filteredtoexcel.php">
                            <table width="100%" border="0" cellspacing="0" cellpadding="2" >
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
                                      <td width="37%"><input name="fromdate" type="text" class="formfields" id="DPC_fromdate" size="20" maxlength="10" value="<?php echo($date); ?>"  style="width:50%" readonly="readonly"/>
                                        <input type="hidden" name="hiddenfromdate" id="hiddenfromdate" /></td>
                                      <td width="13%">To Date : </td>
                                      <td width="39%"><input name="todate" type="text" class="formfields" id="DPC_todate" size="20" maxlength="10" value="<?php echo($defaulttodate); ?>" style="width:50%" readonly="readonly"/>
                                        <input type="hidden" name="hiddentodate" id="hiddentodate" /></td>
                                    </tr>
                                    <tr>
                                      <td>Product Name : </td>
                                      <td width="37%"><select name="productid" class="formfields" id="productid" style="width:50%">
                                          <?php 
						echo($productselect);
						?>
                                        </select>
                                        <input type="hidden" name="hiddenproductid" id="hiddenproductid" /></td>
                                      <td width="13%">Dealer Name: </td>
                                      <td width="39%"><select name="dealerid" class="formfields" id="dealerid" style="width:50%">
                                          <?php 
						echo($dealerselect);
						?>
                                        </select>
                                        <input type="hidden" name="hiddendealerid" id="hiddendealerid" /></td>
                                    </tr>
                                    <tr>
                                      <td>Given By : </td>
                                      <td width="37%"><select name="givenby" class="formfields" id="givenby" style="width:50%">
                                          <?php 
						echo($givenselect);
						?>
                                        </select>
                                        <input type="hidden" name="hiddengivenby" id="hiddengivenby" /></td>
                                      <td width="13%">Status of Lead :</td>
                                      <td width="39%"><select name="leadstatus" class="formfields" id="leadstatus" style="width:50%">
                                          <?php 
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
                                          <?php echo($referenceselect); ?>
                                        </select>
                                        <input type="hidden" name="hiddensource" id="hiddensource" /></td>
                                    </tr>
                                  </table></td>
                              </tr>
                              <tr>
                                <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                    <tr>
                                      <td width="19%">&nbsp;
                                        <input name="considerfollowup" type="checkbox" id="considerfollowup" onclick="filterfollowupdates();" />
                                        <label for="considerfollowup">Consider Follow Ups</label></td>
                                      <td width="29%"><label></label></td>
                                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="34%"><label>
                                              <input type="radio" name="followupradio" id="followuppending" value="followuppending" checked="checked" disabled="disabled"/>
                                              Follow Up Pending</label></td>
                                            <td width="66%"><label>
                                              <input type="radio" name="followupradio" id="followupmade" value="followupmade" disabled="disabled"/>
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
                                      <td width="37%"><input name="filter_followupdate1" type="text" class="formfields" id="DPC_filter_followupdate1" size="20" maxlength="10" value="<?php echo($defaulttodate); ?>" disabled="disabled" style="width:50%" readonly="readonly"/>
                                        <input name="filter_followupdate1hdn" type="hidden" class="formfields" id="filter_followupdate1hdn" value="" /></td>
                                      <td width="13%">To Date : </td>
                                      <td width="39%"><input name="filter_followupdate2" type="text" class="formfields" id="DPC_filter_followupdate2" size="20" maxlength="10" value="<?php echo($defaulttodate); ?>" disabled="disabled"  style="width:50%" readonly="readonly"/>
                                        <input name="filter_followupdate2hdn" type="hidden" class="formfields" id="filter_followupdate2hdn" value="" /></td>
                                    </tr>
                                    <tr>
                                      <td>Entered By :</td>
                                      <td><select name="followedby" id="followedby" style="width:50%" disabled="disabled">
                                          <?php 
						echo($givenselect1);
						?>
                                        </select>
                                        <input name="followedbyhidden" id = "followedbyhidden" type="hidden" /></td>
                                      <td>Remarks :</td>
                                      <td><input type="text" name="remarks" id="remarks" disabled="disabled" style="width:50%"/></td>
                                    </tr>
                                  </table></td>
                              </tr>
                              <tr>
                                <td colspan="2" id="msg_box2" align="left"></td>
                                <td colspan="2"><div align="center">
                                    <input name="view" type="button" class="formbutton" id="view" value="Show" onclick="filtering('view');" />
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input name="excel" type="button" class="formbutton" id="resetform" value="Reset" onclick="filtering('resetform');" />
                                  </div></td>
                              </tr>
                            </table>
                          </form>
                        </div></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td colspan="3"><input name="leadcount" id="leadcount" type="hidden" value="" />
                  &nbsp;</td>
              </tr>
              <tr height="20px">
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="40%"><strong>2. Select the Leads</strong></td>
                      <td  width="60%" id="totalleads" style="font-weight:bold">&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;">
                    <tr>
                      <td><div id="tabgroupgridc1" style="overflow:auto; height:250px; width:945px" align="center">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td><div id="tabgroupgridc1_1" align="center">
                                  <table width="100%" border="1" cellpadding="2" cellspacing="0" id="gridtablelead">
                                    <tr class="gridheader">
                                      <td width="2%" class="tdborderlead">&nbsp;</td>
                                      <td width="5%" class="tdborderlead">Sl No</td>
                                      <td width="6%" class="tdborderlead">Lead Id</td>
                                      <td width="9%" class="tdborderlead">Lead Date</td>
                                      <td width="9%" class="tdborderlead">Product</td>
                                      <td width="8%" class="tdborderlead">Company</td>
                                      <td width="7%" class="tdborderlead">Contact</td>
                                      <td width="6%" class="tdborderlead">Cell</td>
                                      <td width="12%" class="tdborderlead">Emailid</td>
                                      <td width="10%" class="tdborderlead">District</td>
                                      <td width="10%" class="tdborderlead">State</td>
                                      <td width="7%" class="tdborderlead">Dealer</td>
                                      <td width="20%" class="tdborderlead">Manager</td>
                                    </tr>
                                  </table>
                                </div></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                          </table>
                        </div></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td colspan="3"><label for="selectall">
                  <input type="checkbox" name="selectall" id="selectall" onclick="selectanddeselect('all')"/>
                  Select All</label>
                  <input name="hiddenid" type="hidden" id="hiddenid" /><input name="totalselected" type="hidden" id="totalselected" /></td>
              </tr>
              <tr>
                <td colspan="3">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3">&nbsp;<strong>3. Send SMS to Selected Leads</strong></td>
              </tr>
              <tr>
                <td colspan="3"></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><form id="smsform" name="smsform" method="post" action="">
              <table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:1px solid #CCCCCC;">
                <tr>
                  <td width="26%"><strong>Total number of leads Selected is</strong>&nbsp;<span id="selectedleadscount">0.</span></td>
                  <td width="27%" ><div align="right" id="sms-error"></div></td>
                  <td width="47%"><div align="center"><strong>Pick the fields</strong></div></td>
                </tr>
                <tr>
                  <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="9%" valign="top"><strong>SMS Text :</strong></td>
                        <td width="43%"><textarea name="smstext" id = "smstext" rows="4" class="formfields" style="padding:2px; width:400px; font-family:Arial, Helvetica, sans-serif; font-size:12px;" onkeyup="countsmslength()" onclick="countsmslength()"></textarea></td>
                        <td width="48%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="33%">&nbsp;</td>
                              <td width="67%"><div id="selectfield">
                                  <select name="pickfield" id="pickfield" size="3" style="width:45%;height:75px" class="formfield" ondblclick="javascript:insertAtCursor('smstext'); $('#smstext').focus()">
                                    <option value="#LeadID#">LeadID</option>
                                    <option value="#LeadDate#">LeadDate</option>
                                    <option value="#LeadProduct#">LeadProduct</option>
                                    <option value="#LeadCompany#">LeadCompany</option>
                                    <option value="#LeadContact#">LeadContact</option>
                                    <option value="#LeadDistrict#">LeadDistrict</option>
                                    <option value="#LeadState#">LeadState</option>
                                    <option value="#LeadCell#">LeadCell</option>
                                    <option value="#LeadPhone#">LeadPhone</option>
                                    <option value="#DealerCompany#">DealerCompany</option>
                                    <option value="#DealerName#">DealerName</option>
                                    <option value="#DealerCell#">DealerCell</option>
                                    <option value="#DealerEmail#">DealerEmail</option>
                                    <option value="#ManagerName#">ManagerName</option>
                                    <option value="#ManagerCell#">ManagerCell</option>
                                    <option value="#ManagerEmail#">ManagerEmail</option>
                                  </select>
                                </div></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                </tr>
                <tr>
                  <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td width="57%"  height="30px" ></td>
                        <td width="43%" align="center"  height="50px" ><input type="button" name="sendsmsbutton" id="sendsmsbutton" value="Preview SMS" class="formbutton" onclick="validate()" />
                          &nbsp;&nbsp;
                          <input type="button" name="reset" id="reset" value="Reset"  onclick="resetform()" class="formbutton"/>
                          <input type="hidden" name="hiddentotalmessages" id="hiddentotalmessages" /><input type="hidden" name="hiddenerrormessages" id="hiddenerrormessages" value=""/></td>
                      </tr>
                    </table></td>
                </tr>
                <tr>
                  <td height="30px" colspan="3"><div id="messagebox"></div></td>
                </tr>
              </table>
            </form></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><div style="display:none">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><div id='inline_example1' style='background:#fff; width:650px'>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td width="35%"><div align="right"><img src="../images/sms2.png" height="30px" /></div></td>
    <td width="46%"><div align="center" style=" font-size:20px; color:#669999; font-weight:bold;">
      <div align="left">&nbsp;&nbsp;Preview SMSes</div>
    </div></td>
    <td width="19%">&nbsp;</td>
   </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><div id="smscontent" style=" width:637px;height:350px ;overflow:auto; border:2px solid #CCCCCC"></div></td>
  </tr>
   <tr>
    <td height="40px" colspan="3" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="41%"><div id="progressbar" style="display:none; position:relative; top:+5px;" >
                            <div id="progressbar-out" style="width:200px; height:15px; border:1px solid #666666">
                              <div id="progressbar-in" style="width:0%; height:15px; background-color:#cdd9e7"></div>
                            </div>
                            <div style="width:200px; position:relative; top:-15px; left:0px; text-align:center" id="progressbar-data">&nbsp;</div>
                          </div></td>
        <td width="59%"><span id="abort" onclick = "abortsendingsms()" class="abort" style="display:none">(STOP)</span></td>
      </tr>
    </table></td>
                          
  </tr>
  
   <tr>
    <td colspan="3"><div align="center">
      <input type="button" value="Send SMS" id="sendsmsbutton" onclick="sendsms()" class="formbutton"/> 
      <input type="button" value="Close" id="closepreviewbutton"  onclick="$().colorbox.close();" class="formbutton"/>
    </div></td>
  </tr>
</table>
  <div align="right" style="padding-top:15px; padding-right:25px"><input type="button" value="Close" id="closecolorboxbutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/> </div>
</div></td>
          </tr>
        </table>
      </div></td>
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
