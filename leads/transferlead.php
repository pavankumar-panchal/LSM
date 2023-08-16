<?
include("../inc/checklogin.php");

//Permission check for the page
if($cookie_usertype <> "Sub Admin" && $cookie_usertype <> "Reporting Authority" && $cookie_usertype <> "Admin")
	header("Location:../home");

//Select the list of dealers for whom lead can be transferred.
switch($cookie_usertype)
{
	case "Admin":
		$query = "SELECT distinct dealers.id AS selectid, dealers.dlrcompanyname AS selectname 
FROM dealers left join lms_users on lms_users.referenceid = dealers.id where lms_users.disablelogin <> 'yes' and lms_users.type = 'Dealer' ORDER BY dlrcompanyname;";
		break;
	case "Reporting Authority":
		$query = "select dealers.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users left join dealers on dealers.id=lms_users.referenceid where dealers.managerid  in (select dealers.managerid from dealers left join lms_users on dealers.managerid =lms_users.referenceid where lms_users.username = '".$cookie_username."'and lms_users.type = 'Reporting Authority')
and  lms_users.type = 'Dealer' and lms_users.disablelogin <> 'yes' ORDER BY dealers.dlrcompanyname";
		if($cookie_username == "srinivasan")
			$query = "select dealers.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users  left join dealers on dealers.id =lms_users.referenceid where dealers.managerid  in (select dealers.managerid from dealers left join lms_users on dealers.managerid =lms_users.referenceid where lms_users.username = '".$cookie_username."'  or lms_users.username = 'nagaraj'and lms_users.type ='Reporting Authority') and lms_users.type = 'Dealer' and lms_users.disablelogin <> 'yes' ORDER BY dealers.dlrcompanyname";
		break;
	case "Sub Admin":
		$query = "SELECT distinct dealers.id AS selectid, dealers.dlrcompanyname AS selectname 
FROM dealers left join lms_users on lms_users.referenceid = dealers.id where lms_users.disablelogin <> 'yes' and lms_users.type = 'Dealer' ORDER BY dlrcompanyname;";
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
                <td style="border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="2">
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
                                  <td height="80" valign="top"><font color="#FF6600"><span id="leadisplay">-Select a lead-</span></font></td>
                                  <td valign="top"><font color="#FF6600"><span id="productdisplay">-Select a lead-</span></font></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td colspan="2"><strong>Transfer lead:</strong></td>
                          </tr>
                          <tr>
                            <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td width="50%">From Dealer: <font color="#FF6600"><span id="fromdealerdisplay">-Select a lead-</span></font></td>
                                  <td width="50%">To Dealer:
                                    <select name="form_dealer" class="formfields" id="form_dealer">
                                      <? 
						echo($dealerselect);
						?>
                                    </select></td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td><font color="#FF0000">*Changing the Dealer will remove the Dealer member assigned to that lead, if any.</font></td>
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
                      <td><form id="filter" name="filter" onsubmit="return false;">
                          <table width="100%" border="0" cellspacing="0" cellpadding="6">
                            <tr>
                              <td width="38%"><strong>Search</strong>:
                              <input name="searchcriteria" type="text" class="formfields" id="searchcriteria"  size="30" maxlength="30" /></td>
                              <td width="62%" colspan="2" rowspan="2" style="border:solid 1px #999999; background-color:#FFFFFF"><strong>Search For</strong>:
                                <label> <br />
                                <input name="databasefield" type="radio"  value="leadid" checked="checked"/>
                                Lead ID</label>
                                <label>
                                <input name="databasefield" type="radio"  value="company"/>
                                Company</label>
                                <label>
                                <input type="radio" name="databasefield" value="name" />
                                Contact Person</label>
                                <label>
                                <input type="radio" name="databasefield" value="phone" />
                                Phone </label>
                                <label>
                                <input type="radio" name="databasefield" value="cell" />
                                Cell </label>
                                <label>
                                <input type="radio" name="databasefield" value="email" />
                                Email </label>
                                <label>
                                <input type="radio" name="databasefield" value="district" />
                                District </label>
                                <label>
                                <input type="radio" name="databasefield" value="state" />
                                State </label>
                                <br />
                                <label>
                                <input type="radio" name="databasefield" value="product" />
                                Product Name</label>
                                <label>
                                <input type="radio" name="databasefield" value="dealer" />
                                Dealer Name</label>
                                <label>
                                <input type="radio" name="databasefield" value="manager" />
                              Manager Name</label></td>
                            </tr>
                            <tr>
                              <td><strong>From</strong>:
                                <label>
                                <input name="datatype" type="radio"  value="download" checked="checked"/>
                                Web Downloads</label>
                                <label>
                                <input type="radio" name="datatype" value="upload"/>
                                Manual uploads</label>
                                <label>
                                <input name="datatype" type="radio" value="both"/>
                                Both</label>                                </td><input type="hidden" name="srchhiddenfield" value=""/><input type="hidden" name="subselhiddenfield" value=""/><input type="hidden" name="datatypehiddenfield" value=""/>
                            </tr>
                            <tr>
                              <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="60%" id="searchbox">&nbsp;</td>
    <td><div align="right">
      <input name="search" type="button"  id="search" value="Search" class="formbutton" onclick="filtering();" />
      &nbsp;&nbsp;
      <input type="button"  name="clear" id="clear" value="Clear" class="formbutton" onclick="clear1();" />
      &nbsp;&nbsp;</div></td>
  </tr>
</table>
</td>
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
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td colspan="3" style="border:1px solid #333333;"><div id="tabgroupgridc1" style="overflow:auto; height:250px; width:940px" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="getmorelink"  align="left" style="height:20px; "> </div></td>
                                        </tr>
                                      </table></div><div id="resultgrid" style="overflow:auto; display:none; height:150px; width:695px;" align="center">&nbsp;</div></td>
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
