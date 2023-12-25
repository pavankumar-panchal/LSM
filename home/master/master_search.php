<?php
include("../inc/checklogin.php");

//Permission check for the page
if($cookie_usertype <> "Admin")
	header("Location:../home");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Master Search</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/mastersearch.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
</head>
<body id="bodysearch">
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
          <td> Master Search</td>
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
                <td style="border:#666666 1px solid"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td><strong>Search For Users:</strong></td>
                    </tr>
                    <tr>
                      <td><form id="mastersearchform" name="mastersearchform" method="post" action="">
                          <table width="100%" border="0" cellspacing="0" cellpadding="6">
                            <tr>
                              <td width="93">Name:
                                <input name="form_recid" type="hidden" class="formfields" id="form_recid" /></td>
                              <td width="330"><input name="form_search" type="text" class="formfields" id="form_search" size="50" maxlength="50" /></td>
                              <!--<td width="95">Email ID:</td>
                              <td width="374"><input name="form_email" type="text" class="formfields" id="form_email" size="50" maxlength="50" /></td>-->
                            </tr>
                            
                            <tr>
                            <td colspan="5"><div align="center"> <input name="new" type="button" class="formbutton" id="new" value="Reset" onclick="newentry();" />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <input name="save" type="button" class="formbutton" id="save" value="Search" onclick="griddata('search');" />
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td></tr><tr><!--<td colspan="3" id="msg_box">&nbsp;</td>-->
                            </tr>
                           
              <tr>
              <td colspan="2"><div id="dealerdisplay" style="font-size:16px; color:#FF9900; font-weight:bold;display:none;">Select a name below</div></td>
            </tr>
            <tr>
              <td colspan="3" id="msg_box">&nbsp;</td>
              <td><div id="submitlink" align="center" style="display:none;"><a href="#"><strong>Proceed &gt;&gt;</strong></a></div></td>
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
                <td><div id="editdata" style="display:none;"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td width="21%"><strong><span id="gridprocess"></span> </strong></td>
                      <td width="64%"><div align="center"><span id="totalcount"></span></div></td>
                      <td width="15%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="5" style="border:1px solid #333333;"><div id="tabgroupgridc1" style="overflow:auto; height:250px;   width:935px;padding:2px;" align="center" >
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td><div id="tabgroupgridc1_1" align="center"></div></td>
                            </tr>
                            <tr>
                              <td><div id="getmorelink"  align="left" style="height:20px; padding:2px;"> </div></td>
                            </tr>
                          </table>
                        </div>
                        <div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div></td>
                    </tr>
                  </table></div></td>
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
