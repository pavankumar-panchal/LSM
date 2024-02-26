<?php
include("../inc/checklogin.php");

// Get date for From date field.

$month = date('m'); 
$date = '01-'.$month.'-'.date('Y'); 



if($month >= '04')
   $fyearbegin = '01-04-'.date('Y'); 
else 
{
	$year = date('Y') - '1';
	$fyearbegin = '01-04-'.$year; //echo($date);
}

//Get current date for TO DATE field
$defaulttodate = datetimelocal("d-m-Y");

$query7 = "select demogivenstatstoexcel from lms_users where username = '".$cookie_username."'";
$result7 = runmysqlqueryfetch($query7);
$demogivenstatstoexcel = $result7['demogivenstatstoexcel'];
if($demogivenstatstoexcel == 'yes')
{$disabled = "";}
else
{$disabled = "disabled='disabled'";}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Demo Given Stats</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<link type="text/css" rel="stylesheet" href="../css/datepickercontrol.css?dummy=<?php echo(rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/demogivenstats.js?dummy=<?php echo (rand());?>" language="javascript"></script>
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
        <td>Demo Given Statistics</td>
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
        <td valign="top"><form method="post" name="demostatsform" id="demostatsform" action="demogivenstatstoexcel.php"><table width="100%" border="0" cellspacing="0" cellpadding="2" style="border:1px solid #CCCCCC">
          <tr>
        <td colspan="4">This report generates a report of "Lead uploaded People" with their respective statistics of leads passed Demo (for the given date range).</td>
      </tr>
          <tr>
            <td colspan="4"><strong>Lead Date </strong></td>
          </tr>
          <tr>
            <td width="12%"> From Date :</td>
            <td width="40%"><input name="fromdate" type="text" class="formfields" id="DPC_fromdate" size="20" maxlength="10" value="<?php echo($fyearbegin); ?>"  style="width:50%" autocomplete = "off" readonly="readonly"/></td>
            <td width="10%">To Date :</td>
            <td width="38%"><input name="todate" type="text" class="formfields" id="DPC_todate" size="20" maxlength="10" value="<?php echo($defaulttodate); ?>" style="width:50%" autocomplete = "off" readonly="readonly"/></td>
          </tr>
          <tr>
            <td colspan="4"><strong>Demo Date </strong></td>
          </tr> <tr>
            <td colspan="4">This is the first date, where lead is updated to either of &quot;Demo Given&quot;, &quot;Persuing to Purchase&quot;, &quot;Quote Sent&quot; or &quot;Order Closed&quot;.</td>
          </tr>
          <tr>
            <td>From Date :</td>
            <td><input name="demofromdate" type="text" class="formfields" id="DPC_fromdatedemo" size="20" maxlength="10" value="<?php echo($date); ?>"  style="width:50%" autocomplete = "off" readonly="readonly"/></td>
            <td>To Date :</td>
            <td><input name="demotodate" type="text" class="formfields" id="DPC_todatedemo" size="20" maxlength="10" value="<?php echo($defaulttodate); ?>" style="width:50%" autocomplete = "off" readonly="readonly"/></td>
          </tr>
          <tr>
            <td colspan="4">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="3" id="errormessage" height="25px">&nbsp;</td>
            <td height="25px"><div align="center">
                                  
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <input name="excel" type="button" class="formbutton" id="excel" value="To Excel" onclick="filtering('excel');" <?php echo $disabled;?> />
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
          </tr>
        </table></form></td>
      </tr>
      <tr>
        <td height="20"></td>
      </tr>
      <tr>
        <td height="20">&nbsp;</td>
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
