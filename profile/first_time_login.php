<?php
include("../inc/checklogin.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Change Password</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/changepassword-first.js?dummy=<?php echo (rand());?>" language="javascript"></script>
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
                <td width="86%">&nbsp;<?php /*include("../inc/navigation.php");*/ ?></td>
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
        <td>Change Password</td>
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
                  <td><strong>Provide the details to change the Password:</strong></td>
                </tr>
                <tr>
                  <td><form id="passwordform" name="passwordform" onsubmit="return false;" autocomplete = "off" method="post" action="">
                  <table width="40%" border="0" align="center" cellpadding="6" cellspacing="0">
                        <tr>
                          <td colspan="2" height="30px"><font color="#FF0000">
                            <div align="center" id="msg_box"> </div>
                          </font></td>
                          </tr></table>
                <div id="disfields" style="display:block;"><table width="40%" border="0" align="center" cellpadding="6" cellspacing="0">
                                      <tr>
                          <td>Old Password : </td>
                          <td><input name="oldpassword" type="password" class="formfields" id="oldpassword" size="30" maxlength="20" value="" /></td>
                        </tr>
                        <tr>
                          <td width="44%">New Password : </td>
                          <td width="56%"><input name="newpassword" type="password" class="formfields" id="newpassword" size="30" maxlength="20" value="" /></td>
                        </tr>
                        <tr>
                          <td>Confirm Password : </td>
                          <td><input name="cnewpassword" type="password" class="formfields" id="cnewpassword" size="30" maxlength="20" value="" /></td>
                        </tr>

                        <tr>
                          <td>&nbsp;</td>
                          <td><div align="center">
                            <input name="change" type="button" class="formbutton" id="change" value="Change" onclick="changepwd();" />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input name="reset" type="reset" class="formbutton" id="reset" value="Reset Form" onclick="resetform()"/>
                          </div></td>
                        </tr>
                      </table></div><!--END Disfields -->
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
