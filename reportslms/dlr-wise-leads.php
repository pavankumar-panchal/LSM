<?php
include("../inc/checklogin.php");
//Permission check for the page
if($cookie_usertype <> "Sub Admin" && $cookie_usertype <> "Reporting Authority" && $cookie_usertype <> "Admin" && $cookie_usertype <> "Dealer")
	header("Location:../home");

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

//Select the list of LEAD STATUS for the drop-down
$query = "SELECT distinct leadstatus FROM leads ORDER BY leadstatus";
$result = runmysqlquery($query);
$leadstatusselect = '<option value="" selected="selected">--- All ---</option>';
while($fetch = mysqli_fetch_array($result))
{
	$leadstatusselect .= '<option value="'.$fetch['leadstatus'].'">'.$fetch['leadstatus'].'</option>';
}

//Get all the user names with respective displaynames, where they are allowed to upload a lead.
switch($cookie_usertype)
{
	case "Admin":
	case "Sub Admin":
		$givenselect = '<option value="" selected="selected">All</option><option value="web">Web Downloads</option>';
		//Add all Sub Admins
		$query = "select lms_users.id AS selectid, lms_subadmins.sadname AS selectname from lms_users join lms_subadmins on lms_users.referenceid = lms_subadmins.id where lms_users.type = 'Sub Admin' ORDER BY selectname";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [S]</option>';
		}
		//Add all Managers
		$query = "select lms_users.id AS selectid, lms_managers.mgrname AS selectname from lms_users join lms_managers on lms_users.referenceid = lms_managers.id where lms_managers.mgrname <> '' and lms_users.type = 'Reporting Authority' ORDER BY selectname";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [M]</option>';
		}
		//Add all Dealers
		$query = "select lms_users.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users join dealers on lms_users.referenceid = dealers.id where lms_users.type = 'Dealer' ORDER BY selectname";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [D]</option>';
		}
		break;

	case "Reporting Authority":
		$givenselect = '<option value="" selected="selected">All</option><option value="web">Web Downloads</option>';
		//Add respective manager name
		$query = "select lms_users.id AS selectid, lms_managers.mgrname AS selectname from lms_users join lms_managers on lms_users.referenceid = lms_managers.id where  lms_users.username = '".$cookie_username."'";
		if($cookie_username == "srinivasan")
			$query = "select lms_users.id AS selectid, lms_managers.mgrname AS selectname from lms_users join lms_managers on lms_users.referenceid = lms_managers.id where lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj'";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [M]</option>';
		}
		//Add all the Dealers, who are under the manager logged in
		$query = "select lms_users.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users join (SELECT dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '".$cookie_username."') AS dealers on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' ORDER BY selectname";
		if($cookie_username == "srinivasan")
			$query = "select lms_users.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users join (SELECT dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj') AS dealers on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' ORDER BY selectname";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [D]</option>';
		}
		break;

	case "Dealer":
		$givenselect = '<option value="" selected="selected">All</option><option value="web">Web Downloads</option>';
		//Add respective Dealer name
		$query = "select lms_users.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users join dealers on lms_users.referenceid = dealers.id where lms_users.username = '".$cookie_username."'";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [D]</option>';
		}
		break;
}

$query7 = "select dlrwiseleadsxl from lms_users where username = '".$cookie_username."'";
$result7 = runmysqlqueryfetch($query7);
$dlrwiseleadsxl = $result7['dlrwiseleadsxl'];
if($dlrwiseleadsxl == 'yes')
{$disabled = "";}
else
{$disabled = "disabled='disabled'";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Dealer wise leads</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<link type="text/css" rel="stylesheet" href="../css/datepickercontrol.css?dummy=<?php echo(rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/reportsdlrwiseleads.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
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
        <td>Dealer wise leads</td>
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
        <td height="300" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">

          <tr>
            <td style="border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td><strong>Filter the data for Report:</strong></td>
                </tr>
                <tr>
                  <td><form id="filterform" name="filterform" onsubmit="return false;" autocomplete = "off" method="post" action="dlr-wise-leads-xl.php">
                        <table width="100%" border="0" cellspacing="0" cellpadding="6">
                          <tr>
                            <td width="12%">From Date : </td>
                            <td width="37%"><input name="fromdate" type="text" class="formfields" id="DPC_fromdate" size="25" maxlength="10" value="<?php echo($date); ?>" readonly="readonly"/></td>
                            <td width="11%">To Date : </td>
                            <td width="40%"><input name="todate" type="text" class="formfields" id="DPC_todate" size="25" maxlength="10" value="<?php echo($defaulttodate); ?>" readonly="readonly"/></td>
                          </tr>
                          <tr>
                            <td>Given By : </td>
                            <td width="37%"><select name="givenby" class="formfields" id="givenby" style=" width:52%">
                                <?php 
						echo($givenselect);
						?>
                            </select></td>
                            <td width="11%">Status of Lead : </td>
                            <td width="40%"><select name="leadstatus" class="formfields" id="leadstatus" style=" width:48%">
                            <?php 
						echo($leadstatusselect);
						?>
                            </select></td>
                          </tr>
                          <tr>
                            <td colspan="2" style="border-top:solid 1px #999999;"><strong>Last Follow up date: </strong>
                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                               <input name="considerfollowup" type="checkbox" id="considerfollowup" onclick="filterfollowupdates();" />
                              <label for="considerfollowup">Consider Follow Up dates</label>                            </td>
                            <td colspan="2" style="border-bottom:solid 1px #999999; border-left:solid 1px #999999;">&nbsp;
                            <input name="dropterminatedstatus" type="checkbox" id="dropterminatedstatus" value="true" checked="checked" />
                            <label for="dropterminatedstatus">Do not consider Order Closed / Fake / Exsting Users / Not Interested</label>                            </td>
                          </tr>
                          <tr>
                            <td>From Date : </td>
                            <td><input name="filter_followupdate1" type="text" class="formfields"  id="DPC_filter_followupdate1" size="25" maxlength="10" value="<?php echo($defaulttodate); ?>" disabled="disabled" readonly="readonly"/><input name="filter_followupdate1hdn" type="hidden" class="formfields" id="filter_followupdate1hdn" value="" /></td>
                            <td>To Date : </td>
                            <td><input name="filter_followupdate2" type="text" class="formfields" id="DPC_filter_followupdate2" size="25" maxlength="10" value="<?php echo($defaulttodate); ?>" disabled="disabled" readonly="readonly"/><input name="filter_followupdate2hdn" type="hidden" class="formfields" id="filter_followupdate2hdn" value="" /></td>
                          </tr>
                          <tr>
                            <td colspan="2" id="msg_box">&nbsp;</td>
                            <td colspan="2"><div align="center">
                                <input name="excel" type="button" class="formbutton" id="excel" value="To Excel" onclick="filtering();" <?php echo $disabled;?> />
                            </div></td>
                          </tr>
                        </table>
                      </form></td>
                </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="20"></td>
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
