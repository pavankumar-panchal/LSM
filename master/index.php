<?php
include("../inc/checklogin.php");
//Permission check for the page
if($cookie_usertype <> "Admin")
	header("Location:../home");
?>
<?php
include("../reminder.php");
?>
<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Region Master</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/regionmaster.js?dummy=<?php echo (rand());?>" language="javascript"></script>
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
          <td>Region Master</td>
        </tr>
 <tr>
        <!-- <td class="bannerbg" height="50" style="background:red;color:#fff;text-align:center;font-size:21px">TEST LINK</td> -->
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
                      <td><strong>Add / Update a Region:</strong></td>
                    </tr>
                    <tr>
                      <td><form id="regionform" name="regionform" method="post" action="">
                          <table width="100%" border="0" cellspacing="0" cellpadding="6">
                            <tr>
                              <td width="93">State:
                                <input name="form_recid" type="hidden" class="formfields" id="form_recid" />
                                <input name="form_fixed_added" type="hidden" class="formfields" id="form_fixed_added" value="added" /></td>
                              <td width="330"><select name="form_state" class="formfields" id="form_state" onchange="districtselect();">
                                  <option value="" selected="selected">- - -Make a Selection- - -</option>
                                  <option value="1">ANDHRA PRADESH</option>
                                  <option value="2">ASSAM</option>
                                  <option value="3">ARUNACHAL PRADESH</option>
                                  <option value="4">BIHAR</option>
                                  <option value="5">GUJRAT</option>
                                  <option value="6">HARYANA</option>
                                  <option value="7">HIMACHAL PRADESH</option>
                                  <option value="8">JAMMU &amp; KASHMIR</option>
                                  <option value="9">KARNATAKA</option>
                                  <option value="10">KERALA</option>
                                  <option value="11">MADHYA PRADESH</option>
                                  <option value="12">MAHARASHTRA</option>
                                  <option value="13">MANIPUR</option>
                                  <option value="14">MEGHALAYA</option>
                                  <option value="15">MIZORAM</option>
                                  <option value="16">NAGALAND</option>
                                  <option value="17">ORISSA</option>
                                  <option value="18">PUNJAB</option>
                                  <option value="19">RAJASTHAN</option>
                                  <option value="20">SIKKIM</option>
                                  <option value="21">TAMIL NADU</option>
                                  <option value="22">TRIPURA</option>
                                  <option value="23">UTTAR PRADESH</option>
                                  <option value="24">WEST BENGAL</option>
                                  <option value="25">DELHI</option>
                                  <option value="26">GOA</option>
                                  <option value="27">PONDICHERY</option>
                                  <option value="28">LAKSHDWEEP</option>
                                  <option value="29">DAMAN &amp; DIU</option>
                                  <option value="30">DADRA &amp; NAGAR</option>
                                  <option value="31">CHANDIGARH</option>
                                  <option value="32">ANDAMAN &amp; NICOBAR</option>
                                  <option value="33">UTTARANCHAL</option>
                                  <option value="34">JHARKHAND</option>
                                  <option value="35">CHATTISGARH</option>
                                  <option value="36">OUTSIDE INDIA</option>
                                  <option value="37">TELANGANA</option>
                                </select></td>
                              <td width="95">District:</td>
                              <td width="374"><div id="districtdiv">
                                  <select name="form_district" class="formfields" id="district">
                                    <option value = "">- - - -Select a State First - - - -</option>
                                  </select>
                                </div></td>
                            </tr>
                            <tr>
                              <td>Region:</td>
                              <td><input name="form_region" type="text" class="formfields" id="form_region" size="50" maxlength="50" /></td>
                              <td>Managed Area::</td>
                              <td><select name="form_managedarea" class="formfields" id="form_managedarea">
                                  <option value = "">- - - -Select - - - -</option>
                                  <option value="Bangalore">Bangalore</option>
                                  <option value="CSD">CSD</option>
                                  <option value="KKG">KKG</option>
                                </select></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
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
                      <td width="18%"><strong><span id="gridprocess"></span></strong></td>
                      <td width="64%"><div align="center"><span id="totalcount"></span></div></td>
                      <td width="18%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="3" style="border:1px solid #333333;"><div id="tabgroupgridc1" style="overflow:auto; height:250px; width:940px; padding:0px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="getmorelink"  align="left" style="height:20px; padding:0px;"> </div></td>
                                        </tr>
                                      </table></div><div id="resultgrid" style="overflow:auto; display:none; height:150px; width:700px; padding:0px;" align="center">&nbsp;</div></td>
                    </tr>
                    <tr>
                      <td colspan="3">&nbsp;</td>
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
        <!-- <td class="bannerbg" height="50" style="background:red;color:#fff;text-align:center;font-size:21px">TEST LINK</td> -->
      </tr>
</table>
</body>
</html>
