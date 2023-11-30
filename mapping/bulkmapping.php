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
<title>LMS | Bulk Mapping</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/bulkmapping.js?dummy=<?php echo (rand());?>" language="javascript"></script>
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
    <td valign="middle" bgcolor="#D2D8FB" class="contentheader"><table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
      <tr>
        <td>Bulk Mapping</td>
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
          <td height="20" colspan="2"></td>
        </tr>
        <tr>
          <td width="18%" height="300" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="active-leftnav"><strong>Dealer Selection</strong></td>
            </tr>
            <tr>
              <td><form id="filterform" name="filterform" method="post" action="" onsubmit="return false;">
                <table width="100%" border="0" cellspacing="0" cellpadding="3">
                  <tr>
                    <td width="100%"><input type="hidden" id="lastslno" name="lastslno" value="" /></td>
                  </tr>
                  <tr>
                    <td align="left"><input name="detailsearchtext" type="text" class="formfields" id="detailsearchtext"  onkeyup="dealersearch(event);" autocomplete="off"  style="width:208px;"/>
                      <div id="detailloaddealerlist">
                        <select name="dealerlist" size="5" class="formfields" id="dealerlist" style="width:210px; height:505px;" onchange="selectfromlist();" onclick="selectfromlist()">
                          <option></option>
                        </select>
                      </div></td>
                  </tr>
                </table>
              </form></td>
            </tr>
            <tr>
              <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
                <tr>
                  <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong></td>
                  <td width="55%" id="totalcount" align="left">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
          </table></td>
          <td width="82%" valign="top"><form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
            <table width="100%" border="0" cellspacing="0" cellpadding="2" style="border:1px solid #d1dceb;">
            <tr><td height="25px;" colspan="2" bgcolor="#0099CC"><font color="#FFFFFF"><strong>Bulk Mapping</strong></font></td></tr>
            
              <tr>
                <td colspan="2" valign="top" ><table width="100%" border="0" align="left" cellpadding="2" cellspacing="0">
                <tr>
                    <td valign="top" width="19%" height="20px;"><div align="left"><strong>Dealer:</strong></div></td>
                    <td  height="20px;" colspan="2" valign="top"><font color="#FF0000"><strong>
                      <div id="displaydealername" align="left" ></div>
                      </strong></font> </td>
                    </tr>
                  <tr>
                    <td valign="top" width="19%"><div align="left"><strong>Category:</strong></div></td>
                    <td valign="top" width="16%"><select name="prdcategory" class="formfields" id="prdcategory" style="width:100px;" disabled="disabled">
                                                        <option value = "">Select</option>
                                                        <?php include('../inc/prdcategory.php'); ?>
                                                      </select></td>
                    <td valign="top" width="65%"><div align="left"><input name="go" type="button" class= "swiftchoicebutton" id="go" value="Go" onclick="getregionlist();" /></div></td>
                  </tr>
                  <tr>
                    <td valign="top"><div align="left"><strong>Selected Category:</strong></div></td>
                    <td valign="top"><strong><font color="#FF0000"><div id="displaycategory" align="left"></div></font></strong></td>
                    <td valign="top">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="4" >
                  <tr>
                    <td width="39%" rowspan="15" ><table width="100%" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td valign="top" class="swiftselectfont" style="padding-left:9px" ><strong>Unassigned</strong></td>
                        <td valign="top" class="swiftselectfont" style="padding-left:9px" ><div id="list1div"></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" valign="top"><div id="unassignedlistdiv">
                          <select name="list1" size="5" class="formfields" id="list1" style="width:210px; height:400px;">
                           
                          </select></div></td>
                      </tr>
                      <tr>
                        <td width="32%" valign="top"><strong>Total Count:</strong></td>
                        <td width="68%" valign="top"><div id="list1count" align="left"></div></td>
                      </tr>
                    </table></td>
                    <td width="22%" >&nbsp;</td>
                    <td width="39%" rowspan="15" ><table width="100%" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td  class="swiftselectfont" style="padding-left:25px" ><strong>Assigned</strong></td>
                        <td  class="swiftselectfont" style="padding-left:25px" ><div id="list2div"></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" ><div id="assignedlistdiv"><select name="list2" size="5" class="formfields" id="list2" style="width:210px; height:400px" >
                        </select></div></td>
                      </tr>
                      <tr>
                        <td width="32%" ><strong>Total Count:</strong></td>
                        <td width="68%" ><div id="list2count" align="left"></div></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><div align="left">
                      <input name="add" type="button" class= "swiftchoicebutton" id="add" value="Add &gt;&gt;" onclick="addentry()"  />
                    </div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><div align="left">
                      <input name="remove" type="button" class= "swiftchoicebutton" id="remove" value="&lt;&lt; Remove" onclick="deleteentry()" />
                    </div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><div align="left">
                      <input name="removeall" type="button" class= "swiftchoicebutton" id="removeall" value="Remove All" onclick="deleteallentry()" />
                    </div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="3" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="56%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                    <td width="44%" align="right" valign="middle"><div align="center"><input name="save" type="button" class= "swiftchoicebutton" id="save" value="Save" onclick="formsubmit();" />
                      &nbsp;
                      <input name="resetvalues" type="button" class="swiftchoicebutton" id="resetvalues" value="Reset" onclick="resetlistvalues();" />
                      </div></td>
                  </tr>
                </table></td>
              </tr>
            </table>
          </form></td>
        </tr>
        <tr>
          <td height="20" colspan="2"></td>
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
<script>refreshdealerarray();
</script>