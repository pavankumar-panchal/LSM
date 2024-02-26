<?php
include("../inc/checklogin.php");
include('../ajax/dlrlist.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Transfer Mapping</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/statetransfermapping.js?dummy=<?php echo (rand());?>" language="javascript"></script>
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
                      <td width="14%"><a href="http://useradmin.relyonsoft.com"><img src="../images/lms-logo.gif" alt="Lead Management Software" width="100" height="50" border="0" /></a></td>
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
          <td>Transfer state mapping</td>
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
          <td height="300" valign="top" style="border:#666666 0px solid"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td><div id="dealerdisplay" style="font-size:16px; color:#FF9900; font-weight:bold">Select a Dealer Name below :</div></td>
                    </tr>
                    <tr>
                      <td style="border:#666666 0px solid"><table width="100%" border="0" cellspacing="0" cellpadding="6">
                          <tr>
                            <td colspan="2"><select name="form_dlrlist" class="formfields" id="form_dlrlist" style="width:265px;" onchange="griddata();load_state();document.getElementById('msg_box').innerHTML='';document.getElementById('rd_box').innerHTML='';">
                                <?php dlrname();?>
                              </select></td>
                            <td><select name="form_state" class="formfields" id="form_state" style="width:265px;" onchange="state();">
                                <option name="sel_dlr" value="all"> Select Dealer Name First </option>
                              </select></td>
                          </tr>
                          <tr>
                            <td width="65%" id="rd_box" style="color:#F8051E">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><div id="data1" style="display:none;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="24%"><strong>Mapping Data:</strong><span id="gridprocess"></span></td>
                        <td width="55%"><div align="center"><span id="totalcount"></span></div></td>
                        <td width="21%">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td colspan="3" style="border:1px solid #333333;"><div id="tabgroupgridc1" style="overflow:auto; height:250px; width:940px;  padding:2px;" align="center">
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
                          </table></td>
                      </tr>
                    </table>
                  </div></td>
              </tr>
              <tr>
                <td><div id="dlrtransfer" style="display:none;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><div id="dealerdisplay" style="font-size:16px; color:#FF9900; font-weight:bold">Data Transfer To :</div></td>
                      </tr>
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="6">
                            <tr>
                              <td colspan="2"><select name="form_dlrlist1" class="formfields" id="form_dlrlist1" style="width:265px;" onchange="gridlist();document.getElementById('msg_box').innerHTML='';">
                                  <?php dlrnameactive();?>
                                </select></td>
                            </tr>
                            <tr>
                              <td width="65%" id="msg_box">&nbsp;</td>
                            </tr>
                          </table></td>
                      </tr>
                    </table>
                  </div></td>
              </tr>
              <tr>
                <td><div id="data2" style="display:none;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="24%"><strong>Mapping Data:</strong><span id="gridprocess1"></span></td>
                        <td width="55%"><div align="center"><span id="totalcount1"></span></div></td>
                        <td width="21%">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td colspan="3" style="border:1px solid #333333;"><div id="tabgroupgridc2" style="overflow:auto; height:250px; width:940px;  padding:2px;" align="center">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><div id="tabgroupgridc2_1" align="center"></div></td>
                                    </tr>
                                    <tr>
                                      <td><div id="getmorelink1"  align="left" style="height:20px; padding:2px;"> </div></td>
                                    </tr>
                                  </table>
                                </div>
                                <div id="resultgrid1" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div></td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td width="50%" align="center"  height="50px" ><input name="transferdata" type="button" id="transferdata" value="Transfer" onclick="transferdata();" disabled/></td>
                        <td><input name="new" type="reset" id="new" value="Reset" onclick="newentry();" /></td>
                      </tr>
                    </table>
                  </div></td>
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