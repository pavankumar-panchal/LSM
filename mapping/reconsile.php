<?php
include("../inc/checklogin.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Reconsile Mapping</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/reconsile.js?dummy=<?php echo (rand());?>" language="javascript"></script>
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
          <td> Reconsile Mapping </td>
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
                <td><strong>Reconsile Mapping</strong> - Map unmapped leads, if mapping is available now</td>
              </tr>
              <tr>
                <td><form action="" method="post" name="reconsileform" id="reconsileform">
                    <table width="100%" border="0" cellspacing="0" cellpadding="6">
                      <tr>
                        <td colspan="3"><label for="field0">
                            <input name="unmappedleads" type="radio" id="field0" value="unmappedleads" checked="checked"/>
                            Reconsile unmapped leads</label></td>
                      </tr>
                      <tr>
                        <td colspan="3"><label for="field1">
                            <input type="radio" name="unmappedleads" id="field1" value="disableddealers"/>
                            Reconsile  leads of disabled dealers</label></td>
                      </tr>
                      <tr>
                        <td width="24%" height="35"><div id="progressbar" style="display:none"><div id="progressbar-out" style="width:200px; height:15px; border:1px solid #666666">
                          <div id="progressbar-in" style="width:0%; height:15px; background-color:#CCCCCC"></div>
                           </div><div style="width:200px; position:relative; top:-15px; left:0px; text-align:center" id="progressbar-data">&nbsp;</div></div></td>
                        <td width="17%" valign="top"><span id="abort" onclick = "abortreconsileajaxprocess()" class="abort" style="display:none">(STOP)</span></td>
                        <td width="59%" rowspan="2"><div align="right">
                            <!--<input type="text" name="currentlooprun" value="" readonly="readonly" id="currentlooprun" />
                            <input type="text" name="totallooprun" value="" readonly="readonly" id="totallooprun" />
                            <input type="text" name="totalleadcount" value="" readonly="readonly"  id="totalleadcount"/>-->
                            <input name="reconsile" type="button" class="formbutton" id="reconsile" value="Reconsile Now" onclick="reconsilenow();" />
                          </div></td>
                      </tr>
                      <tr> </tr>
                      <tr>
                        <td colspan="3"><div id="reconsileresult"></div></td>
                      </tr>
                    </table>
                  </form></td>
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
