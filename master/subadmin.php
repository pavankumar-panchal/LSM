<?
include("../inc/checklogin.php");

//Permission check for the page
if($cookie_usertype <> "Admin")
	header("Location:../home");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Sub-Admin Master</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<? echo (rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<? echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<? echo (rand());?>" language="javascript"></script>
<script src="../functions/subadminmaster.js?dummy=<? echo (rand());?>" language="javascript"></script>
<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
<script>
function dislogin()
{
	if($("#form_disablelogin:checked").val() == 'on')
	alert("Kindly Transfer the state mapping!!");
}
</script>
</head>
<body onload="griddata('');">
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
                <td width="14%"><a href="http://lms.relyonsoft.net"><img src="../images/lms-logo.gif" alt="Lead Management Software" width="100" height="50" border="0" /></a></td>
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
        <td>Sub-Admin Master</td>
      </tr>
    </table></td>
  </tr>
 <tr>
        <td class="bannerbg" height="50" style="background:red;color:#fff;text-align:center;font-size:21px">TEST LINK</td>
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
            <td style="border:#666666 1px solid"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td><strong>Add / Update a Sub-Admin:</strong></td>
                </tr>
                <tr>
                  <td><form id="subadminform" name="subadminform" method="post" action="">
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td width="93">Name:
                            <input name="form_recid" type="hidden" class="formfields" id="form_recid" /></td>
                          <td width="330"><input name="form_name" type="text" class="formfields" id="form_name" size="50" maxlength="50" /></td>
                          <td width="95">Email ID:</td>
                          <td width="374"><input name="form_email" type="text" class="formfields" id="form_email" size="50" maxlength="50" /></td>
                        </tr>


                        <tr>
                          <td>Username:</td>
                          <td><input name="form_username" type="text" class="formfields" id="form_username" size="50" maxlength="50" /></td>
                          <td>Password</td>
                          <td><input name="form_password" type="password" class="formfields" id="form_password" size="50" maxlength="50" /></td>
                        </tr>
                        <tr>
                          <td>Cell :</td>
                          <td><input name="form_cell" type="text" id="form_cell" size="50"  class="formfields" /></td>
                          <td>&nbsp;</td>
                          <td><input type="checkbox" name="transferuploadedleads" id="transferuploadedleads" />
                            <label for="transferuploadedleads">Permission to Transfer Leads uploaded by dealer.</label></td>
                        </tr>
                         <tr>
                        <td>&nbsp;</td>
                       <td><font color="#FBFCFF"><span id="hiddenpwd"></span></font></td>
                        <td>&nbsp;</td>
                        <td><label>
                          <input type="checkbox" name="form_disablelogin" id="form_disablelogin" onclick="dislogin();"/>
                        Disable Login</label></td>
                        </tr><tr>
                        <td>&nbsp;</td>
                       <td><font color="#FBFCFF"><span id="hiddenpwd"></span></font></td>
                        <td>&nbsp;</td>
                        <td><label for="showmcacompanies">
                          <input type="checkbox" name="showmcacompanies" id="showmcacompanies" />
                        Show MCA - Companies</label></td>
                        </tr>
                        <tr>
                          <td colspan="3" id="msg_box">&nbsp;</td>
                          <td><div align="center">
                            <input name="new" type="button" class="formbutton" id="new" value="New" onclick="newentry();" />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input name="save" type="button" class="formbutton" id="save" value="Save" onclick="formsubmit('save');" />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input name="delete" type="button" class="formbutton" id="delete" value="Delete" onclick="formsubmit('delete');" />
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
                  <td width="21%"><strong><span id="gridprocess"></span> </strong></td>
                  <td width="64%"><div align="center"><span id="totalcount"></span></div></td>
                  <td width="15%">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="3" style="border:1px solid #333333;"><div id="tabgroupgridc1" style="overflow:auto; height:250px;   width:935px;padding:2px;" align="center" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
 <tr>
        <td class="bannerbg" height="50" style="background:red;color:#fff;text-align:center;font-size:21px">TEST LINK</td>
      </tr>
</table>

    
</body>
</html>
