<?
include("../inc/checklogin.php");

//Permission check for the page
if($cookie_usertype <> "Sub Admin" && $cookie_usertype <> "Reporting Authority" && $cookie_usertype <> "Admin" && $cookie_usertype <> "Dealer")
	header("Location:../home/unauthorised.php");

//Select the list of dealers for whom lead can be transferred.
switch($cookie_usertype)
{
	case "Admin":
		$query = "SELECT id AS selectid, dlrcompanyname AS selectname FROM dealers ORDER BY dlrcompanyname";
		break;
	case "Reporting Authority":
		$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '".$cookie_username."' ORDER BY dealers.dlrcompanyname";
		break;
	case "Sub Admin":
		$query = "SELECT id AS selectid, dlrcompanyname AS selectname FROM dealers ORDER BY dlrcompanyname";
		break;
	case "Dealer":
		$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.id WHERE lms_users.username = '".$cookie_username."' ORDER BY dealers.dlrcompanyname";
		break;
}

$result = runmysqlquery($query);
$dealerselect = '<option value="" selected="selected">- - -Make a Selection- - -</option>';
while($fetch = mysqli_fetch_array($result))
{
	$dealerselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].'</option>';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Lead Transfer</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<? echo (rand());?>">
<script src="../functions/jsfunctions.js?dummy=<? echo (rand());?>" language="javascript"></script>
<script src="../functions/leadtransfer1.js?dummy=<? echo (rand());?>" language="javascript"></script>
<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
</head>
<body onload="griddata();">
<table width="950" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="pageheader"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="28">&nbsp;</td>
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
                      <td width="86%"><? include("../inc/header1.php"); ?>
                        <? include("../inc/navigation.php"); ?></td>
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
          <td>Transfer Lead</td>
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
                <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td><strong>Change the dealer for a lead</strong></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="6">
                          <tr>
                            <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td width="50%"><strong>Lead Information:</strong></td>
                                  <td width="50%"><strong>Product:</strong></td>
                                </tr>
                                <tr>
                                  <td><span id="leadisplay"><font color="#FF6600">-Select a lead-</font></span></td>
                                  <td><span id="productdisplay"><font color="#FF6600">-Select a lead-</font></span></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td colspan="2"><strong>Transfer lead:</strong></td>
                          </tr>
                          <tr>
                            <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td width="50%">From Dealer: <span id="productdisplay2"><font color="#FF6600">-Select a lead-</font></span></td>
                                  <td width="50%">To Dealer:
                                    <select name="form_dealer" class="formfields" id="form_dealer">
                                      <?
						echo($dealerselect);
						?>
                                    </select></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td width="65%" id="msg_box">&nbsp;</td>
                            <td width="35%"><div align="center">
                                <input name="form_recid" type="hidden" class="formfields" id="form_recid" />
                                <input name="save" type="button" class="formbutton" id="save" value="Update" onclick="formsubmit('save');" />
                              </div></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td style="border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td><strong>Filter:</strong></td>
                    </tr>
                    <tr>
                      <td><form id="filter" name="filter" method="post" action="">
                          <table width="100%" border="0" cellspacing="0" cellpadding="6">
                            <tr>
                              <td width="34%"><strong>Search</strong>:
                              <input name="searchcriteria" type="text" class="formfields" id="searchcriteria" onchange="filtering();" onkeyup="filtering();" size="30" maxlength="30" /></td>
                              <td width="66%" rowspan="2"><strong>Search For</strong>:
                                <label>
                                <br />
                                <input name="databasefield" type="radio" onclick="filtering();" value="leadid" checked="checked"/>
Lead ID
<input name="databasefield" type="radio" onclick="filtering();" value="company"/>
                                Company</label>
                                <label>
                                <input type="radio" name="databasefield" value="name" onclick="filtering();"/>
                                Contact Person</label>
                                <label>
                                <input type="radio" name="databasefield" value="cell" onclick="filtering();" />
                                Cell </label>
                                <label>
                                <input type="radio" name="databasefield" value="phone" onclick="filtering();"/>
                                Phone </label>
                                <label>
                                <input type="radio" name="databasefield" value="email" onclick="filtering();"/>
                                Email </label>
                                <label>
                                <input type="radio" name="databasefield" value="district" onclick="filtering();"/>
                                District </label>
                                <label>
                                <input type="radio" name="databasefield" value="state" onclick="filtering();"/>
                                State </label>
                                
                                <br />
                                <label>
                                <input type="radio" name="databasefield" value="dealer" onclick="filtering();"/>
Dealer Name</label>
                                <label><input type="radio" name="databasefield" value="manager" onclick="filtering();"/>
                                Manager Name</label></td>
                            </tr>
                            <tr>
                              <td><strong>From</strong>:
                                <label>
                                <input name="datatype" type="radio" onclick="filtering();" value="download" checked="checked"/>
Web Downloads</label>
                                <label>
                                <input type="radio" name="datatype" value="upload" onclick="filtering();"/>
Manual uploads</label></td>
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
                      <td><strong>List of leads:<span id="gridprocess"></span></strong></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="6">
                          <tr>
                            <td><div id="callgrid" class="grid-div"></div></td>
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
