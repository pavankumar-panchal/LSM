<?php
include("../inc/checklogin.php");

//Permission check for the page
if($cookie_usertype <> "Sub Admin" && $cookie_usertype <> "Admin")
	header("Location:../home");
	
$query7 = "select managerlisttoexcel from lms_users where username = '".$cookie_username."'";
$result7 = runmysqlqueryfetch($query7);
$managerlisttoexcel = $result7['managerlisttoexcel'];
if($managerlisttoexcel == 'yes')
{
  $disabled = "";
}else{$disabled = "disabled='disabled'";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Manager List</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/reportmanagers.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
</head>
<body onload="griddata('');">
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
        <td>Manager List</td>
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
                  <td><form id="filterform" name="filterform" onsubmit="return false;" autocomplete = "off" method="post" action="managerlisttoexcel.php">
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td width="9%" valign="top">Search for : </td>
                          <td width="22%" valign="top"><input name="searchcriteria" type="text" class="formfields" id="searchcriteria" size="30" maxlength="30" />
                            <input type="hidden" name="hiddensearchcriteria" id="hiddensearchcriteria" /></td>
                          <td width="3%" valign="top">In : </td>
                          <td width="66%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4" style=" border:1px solid #CCCCCC;">
  <tr>
    <td><span>
                            <label>
                            <input name="databasefield" type="radio" value="mgrid" checked="checked"/>
Manager ID</label>
                            <label>
                            <input name="databasefield" type="radio" value="name"/> 
                            Name</label>
                            <label>
                            <input type="radio" name="databasefield" value="location"/>
                             Location</label>
                            <label>
                            <input type="radio" name="databasefield" value="email"/>
Email </label>
                            <label>
                            <input type="radio" name="databasefield" value="cell"/>
Cell</label>
                            <input type="hidden" name="hiddendatabasefield" id="hiddendatabasefield" />
    </span></td>
  </tr>
  <tr>
    <td>&nbsp;&nbsp;&nbsp;Managed Area : 
      <select name="managedarea" id="managedarea">
      	<option value="" selected="selected">ALL</option>
        <option value="BKG">BKG</option>
        <option value="BKM">BKM</option>
        <option value="CSD">CSD</option>
      </select> 
      &nbsp;&nbsp;Disable Login : 
      <label>
      <select name="disablelogin" id="disablelogin">
      <option value="" selected="selected">ALL</option>
      <option value="yes">Yes</option>
      <option value="no">No</option>
      </select>
      </label></td>
  </tr>
</table>
</td>
                        </tr>

                        <tr>
                          <td colspan="2">&nbsp;</td>
                          <td colspan="2"><div align="center">
                              <input name="view" type="button" class="formbutton" id="view" value="View" onclick="filtering('view');" />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="excel" type="button" class="formbutton" id="excel" value="To Excel" onclick="filtering('excel');" <?php echo $disabled;?> />
                          </div></td>
                        </tr>
                      </table>
                  </form></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td><strong>List of Managers:<span id="gridprocess"></span></strong></td>
                </tr>
                <tr>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td colspan="3" style="border:1px solid #333333;"><div id="tabgroupgridc1" style="overflow:auto; height:250px; width:940px;  padding:0px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="getmorelink"  align="left" style="height:20px; padding:2px;"> </div></td>
                                        </tr>
                                      </table></div><div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div></td>
                      </tr>
                  </table></td>
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
