<?php
include("../inc/checklogin.php");

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
		$query = "select lms_users.id AS selectid, lms_managers.mgrname AS selectname from lms_users join lms_managers on lms_users.referenceid = lms_managers.id where lms_managers.mgrname <> '' and lms_users.type = 'Reporting Authority' ORDER BY selectname";
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

$query7 = "select leadstatuscharttoexcel from lms_users where username = '".$cookie_username."'";
$result7 = runmysqlqueryfetch($query7);
$leadstatuscharttoexcel = $result7['leadstatuscharttoexcel'];
if($leadstatuscharttoexcel == 'yes')
{$disabled = "";}
else
{$disabled = "disabled='disabled'";}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Lead Status Chart</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<link type="text/css" rel="stylesheet" href="../css/datepickercontrol.css?dummy=<?php echo(rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/leadstatuschart.js?dummy=<?php echo (rand());?>" language="javascript"></script>
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
        <td>Lead Status Chart</td>
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
        <td valign="top"><form action="leadstatuscharttoexcel.php" method="post" name="leadstatuschartform" id="leadstatuschartform"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:1px solid #CCCCCC">
          <tr>
            <td colspan="4"><strong>Lead Date </strong></td>
          </tr>
          <tr>
            <td width="15%"> From :</td>
            <td width="37%"><input name="fromdate" type="text" class="formfields" id="DPC_fromdate" size="20" maxlength="10" value="<?php echo($date); ?>"  style="width:50%" autocomplete = "off" readonly="readonly"/></td>
            <td width="16%"> To :</td>
            <td width="32%"><input name="todate" type="text" class="formfields" id="DPC_todate" size="20" maxlength="10" value="<?php echo($defaulttodate); ?>" style="width:50%" autocomplete = "off" readonly="readonly"/></td>
          </tr>
          <tr>
            <td>Lead Source :</td>
            <td><select name="leadsource" id="leadsource" style="width:50%" class="formfields">
             <option value="" selected="selected">- - - All - - -</option>
            <option value="Manual Upload">Manual Upload</option>
             <option value="Product Download">Product Download</option>
            </select></td>
            <td>Reference :</td>
            <td><select name="leadreference" class="formfields" id="leadreference" style="width:50%">
                          <?php echo($referenceselect); ?>  
                          </select></td>
          </tr>
          <tr>
            <td>Given By :</td>
            <td><select name="givenby" class="formfields" id="givenby" style="width:50%">
                                          <?php 
						echo($givenselect);
						?>
                                        </select></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4"><fieldset style="width:35%; border:1px solid #CCCCCC; ">
                                <legend>Report Type</legend>
 
                                <label>
                                <input name="reporttype" id = "typegivenby" type="radio" value="givenby" checked="checked"/>
                                &nbsp;Given By</label>
                                <label>&nbsp;
                                <input name="reporttype" id="typereference" type="radio" value="reference" />
                                Lead Reference</label>
            </fieldset></td>	
          </tr>
         <tr>
            <td colspan="3" id="errormessage" height="25px">&nbsp;</td>
            <td height="25px"><div align="center">
                                  
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <input name="excel" type="button" class="formbutton" id="excel" value="To Excel" onclick="filtering('excel');" <?php echo $disabled;?> />
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
          </tr>
          <tr>
            <td colspan="4">&nbsp;</td>
          </tr>
        </table></form></td>
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
