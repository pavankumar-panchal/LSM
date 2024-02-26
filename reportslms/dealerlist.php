<?php
include("../inc/checklogin.php");

//Permission check for the page
if ($cookie_usertype <> "Sub Admin" && $cookie_usertype <> "Reporting Authority" && $cookie_usertype <> "Admin")
  header("Location:../home");

$query7 = "select dealerlisttoexcel from lms_users where username = '" . $cookie_username . "'";
$result7 = runmysqlqueryfetch($query7);
$dealerlisttoexcel = $result7['dealerlisttoexcel'];
if ($dealerlisttoexcel == 'yes') {
  $disabled = "";
} else {
  $disabled = "disabled='disabled'";
}
?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>LMS | Dealer List</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand()); ?>">
  <script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand()); ?>" language="javascript"></script>
  <script src="../functions/jsfunctions.js?dummy=<?php echo (rand()); ?>" language="javascript"></script>
  <script src="../functions/reportdealers.js?dummy=<?php echo (rand()); ?>" language="javascript"></script>
  <!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
</head>

<body onload="griddata('');">
  <table width="950" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td class="pageheader">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="28">
              <?php include("../inc/header1.php"); ?>
            </td>
          </tr>
          <tr>
            <td height="58" valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="4"></td>
                </tr>
                <tr>
                  <td height="54">
                    <table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
                      <tr>
                        <td width="14%"><a href="http://lms.relyonsoft.net"><img src="../images/lms-logo.gif"
                              alt="Lead Management Software" width="100" height="50" border="0" /></a></td>
                        <td width="86%">
                          <?php include("../inc/navigation.php"); ?>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>

              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td height="1"></td>
    </tr>
    <tr>
      <td valign="middle" bgcolor="#D2D8FB" class="contentheader">
        <table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
          <tr>
            <td>Dealer List</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td height="2"></td>
    </tr>
    <tr>
      <td height="1" bgcolor="#006699"></td>
    </tr>
    <tr>
      <td valign="top" class="content">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="20"></td>
          </tr>
          <tr>
            <td height="300" valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td style="border:solid 1px #999999">
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><strong>Filter the data for Report:</strong></td>
                      </tr>
                      <tr>
                        <td>
                          <form id="filterform" name="filterform" onsubmit="return false;" autocomplete="off"
                            method="post" action="dealerlisttoexcel.php">
                            <table width="100%" border="0" cellspacing="0" cellpadding="2">
                              <tr>
                                <td width="9%">Search For:</td>
                                <td width="22%"><input name="searchcriteria" type="text" class="formfields"
                                    id="searchcriteria" size="30" maxlength="30" />
                                  <input type="hidden" name="hiddensearchcriteria" id="hiddensearchcriteria" />
                                </td>
                                <td width="2%">In:</td>
                                <td width="67%">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="4"
                                    style="border:1px solid #CCCCCC;">
                                    <tr>
                                      <td><span>
                                          <label>
                                            <input name="databasefield" type="radio" value="dlrid" checked="checked" />
                                            Dealer ID</label>
                                          <label>
                                            <input name="databasefield" type="radio" value="company" />
                                            Company</label>
                                          <label>
                                            <input type="radio" name="databasefield" value="name" />
                                            Name</label>
                                          <label>
                                            <input type="radio" name="databasefield" value="phone" />
                                            Phone </label>
                                          <label>
                                            <input type="radio" name="databasefield" value="cell" />
                                            Cell</label>
                                          <label>
                                            <input type="radio" name="databasefield" value="email" />
                                            Email </label>
                                          <label>
                                            <input type="radio" name="databasefield" value="district" />
                                            District </label>
                                          <label>
                                            <input type="radio" name="databasefield" value="state" />
                                            State </label>
                                          <label>
                                            <input type="radio" name="databasefield" value="manager" />
                                            Manager </label>
                                          <input type="hidden" name="hiddendatabasefield" id="hiddendatabasefield" />
                                        </span></td>
                                    </tr>
                                    <tr>
                                      <td>&nbsp;&nbsp;Disabled :
                                        <label>
                                          <select name="disabled" id="disabled">
                                            <option value="all" selected="selected">All</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                          </select>
                                        </label>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="4">
                                  <div align="right">
                                    <input name="view" type="button" class="formbutton" id="view" value="View"
                                      onclick="filtering('view');" />
                                    &nbsp;&nbsp;
                                    <input name="excel" type="button" class="formbutton" id="excel" value="To Excel"
                                      onclick="filtering('excel');" <?php echo $disabled; ?> /> &nbsp;&nbsp;
                                  </div>
                                </td>
                              </tr>
                            </table>

                          </form>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><strong>List of Dealers:<span id="gridprocess"></span></strong></td>
                      </tr>
                      <tr>
                        <td>
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td colspan="3" style="border:1px solid #333333;">
                                <div id="tabgroupgridc1" style="overflow:auto; height:250px; width:940px;  padding:0px;"
                                  align="center">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td>
                                        <div id="tabgroupgridc1_1" align="center"></div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div id="getmorelink" align="left" style="height:20px; padding:0px;"> </div>
                                      </td>
                                    </tr>
                                  </table>
                                </div>
                                <div id="resultgrid"
                                  style="overflow:auto; display:none; height:150px; width:704px; padding:0px;"
                                  align="center">&nbsp;</div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td height="20"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td height="1"></td>
    </tr>
    <tr>
      <td valign="top" class="pagefooter">
        <table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <td>
              <table width="100%" border="0" cellpadding="4" cellspacing="0" class="footer">
                <tr>
                  <td width="50%">Copyright © Relyon Softech Limited. All rights reserved. </td>
                  <td width="50%">
                    <div align="right"><a href="http://www.relyonsoft.com" target="_blank">www.relyonsoft.com</a> | <a
                        href="http://www.saraltaxoffice.com" target="_blank">www.saraltaxoffice.com</a> | <a
                        href="http://www.saralpaypack.com" target="_blank">www.saralpaypack.com</a> | <a
                        href="http://www.saraltds.com" target="_blank">www.saraltds.com</a></div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>


</body>

</html>