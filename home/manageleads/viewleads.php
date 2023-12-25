<?php
include("../inc/checklogin.php");

//Permission check for the page
if($cookie_usertype <> "Dealer" && $cookie_usertype <> "Sub Admin" && $cookie_usertype <> "Reporting Authority" && $cookie_usertype <> "Admin" && $cookie_usertype <> "Dealer Member")
	header("Location:../home");

//Select the list of dealers for whom data can be filtered.
switch($cookie_usertype)
{
	case "Admin":
		$query = "SELECT id AS selectid, dlrcompanyname AS selectname FROM dealers ORDER BY dlrcompanyname";
		break;
	case "Reporting Authority":
	//Check wheteher the manager is branch head or not
		$query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."' AND lms_users.type = 'Reporting Authority';";
		$result1 = runmysqlqueryfetch($query1);
		if($result1['branchhead'] == 'yes')
			$branchpiecejoin = "(dealers.branch = '".$result1['branch']."' OR dealers.managerid  = '".$result1['managerid']."')";
		else
			$branchpiecejoin = "lms_users.username = '".$cookie_username."' ";
			
		$query = "SELECT distinct dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE ".$branchpiecejoin." ORDER BY dealers.dlrcompanyname";
		if($cookie_username == "srinivasan")
		$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj' ORDER BY dealers.dlrcompanyname";
		
		break;
	case "Dealer":
		$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.id WHERE lms_users.username = '".$cookie_username."' ORDER BY dealers.dlrcompanyname";
		break;
	case "Dealer Member":
		$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN lms_dlrmembers on lms_dlrmembers.dlrmbrid = lms_users.referenceid JOIN dealers ON lms_dlrmembers.dealerid = dealers.id WHERE lms_users.username = '".$cookie_username."' ORDER BY dealers.dlrcompanyname";
		break;
	case "Sub Admin":
		$query = "SELECT id AS selectid, dlrcompanyname AS selectname FROM dealers ORDER BY dlrcompanyname";
		break;
}

$result = runmysqlquery($query);
$dealerselect = '';
if(mysqli_num_rows($result) > 1)
$dealerselect .= '<option value="" selected="selected">--- All ---</option>';
while($fetch = mysqli_fetch_array($result))
{
	$dealerselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].'</option>';
}

//Select the list of products for the drop-down
$query = "SELECT id,productname FROM products ORDER BY productname";
$result = runmysqlquery($query);
$productselect = '<option value="" selected="selected">--- All ---</option>';
while($fetch = mysqli_fetch_array($result))
{
	$productselect .= '<option value="'.$fetch['id'].'">'.$fetch['productname'].'</option>';
}

//Select the list of LEAD STATUS for the drop-down
$query = "SELECT distinct leadstatus FROM leads ORDER BY leadstatus";
$result = runmysqlquery($query);
$leadstatusselect = '<option value="" selected="selected">--- All ---</option>';
while($fetch = mysqli_fetch_array($result))
{
	$leadstatusselect .= '<option value="'.$fetch['leadstatus'].'">'.$fetch['leadstatus'].'</option>';
}

// Get date for From date field.

$month = date('m'); 
if($month >= '04')
   $date = '01-04-'.date('Y'); 
else 
{
	$year = date('Y') - '1';
	$date = '01-04-'.$year; //echo($date);
}

//Get current date for TO DATE field
$defaulttodate = datetimelocal("d-m-Y");

//Get all the user names with respective displaynames, where they are allowed to upload a lead.
switch($cookie_usertype)
{
	case "Admin":
		$query = "select lms_users.id as selectid,lms_users.username as selectname from lms_users where lms_users.username = '".$cookie_username."'";
		$fetch = runmysqlqueryfetch($query);
		$givenselect = '<option value="'.$fetch['selectid'].'">Admin</option>';
		break;
	case "Sub Admin":
		$query = "select lms_users.id as selectid,lms_subadmins.sadname as selectname from lms_users left join lms_subadmins on lms_subadmins.id = lms_users.referenceid where lms_users.username = '".$cookie_username."'";
		$fetch = runmysqlqueryfetch($query);
		$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [S]</option>';
		break;

	case "Reporting Authority":
		$query = "select lms_users.id as selectid,lms_managers.mgrname as selectname from lms_users left join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."'";
		$fetch = runmysqlqueryfetch($query);
		$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [M]</option>';
		break;

	case "Dealer":
		$query = "select lms_users.id as selectid,dealers.dlrcompanyname as selectname from lms_users left join dealers on dealers.id = lms_users.referenceid where lms_users.username = '".$cookie_username."'";
		$fetch = runmysqlqueryfetch($query);
		$givenselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].' [D]</option>';
		break;
}
$query7 = "select viewleadstoexcel from lms_users where username = '".$cookie_username."'";
$result7 = runmysqlqueryfetch($query7);
$viewleadstoexcel = $result7['viewleadstoexcel'];
if($viewleadstoexcel == 'yes')
{ $disabled = "";}
else{$disabled = "disabled='disabled'";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | View Given Leads</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<link type="text/css" rel="stylesheet" href="../css/datepickercontrol.css?dummy=<?php echo(rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/viewleads.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
</head>
<body onload="leadgridtab2('1','tabgroupleadgrid','default');newtog();">
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
          <td>View Uploaded Leads</td>
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
          <td height="20px" colspan="4"><a name="leadview" id="leadview"></a></td>
        </tr>
        <tr>
          <td colspan="4"  valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="4" style="border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="6">
                          <tr>
                            <td ><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                <tr>
                                  <td width="40%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                      <tr>
                                        <td valign="top" style="border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                            <tr>
                                              <td bgcolor="#0099CC"><strong><font color="#FFFFFF">Lead Contact Card</font></strong></td>
                                            </tr>
                                            <tr>
                                              <td  valign="top"><span id="leadisplay">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                                  <tr height="20px">
                                                    <td width="121"><strong>Company [id]</strong>: </td>
                                                    <td width="232"><font color="#FF6600"><span id="id">-Select a lead- </span></font>
                                                      <input type="hidden" name="hiddenid" id="hiddenid" value = ""/>
                                                      <input type="hidden" name="hiddencompany" id="hiddencompany" /></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Contact person</strong>:<font color="#FF6600">
                                                      <input name="hiddencontact" id="hiddencontact" type="hidden" value="" />
                                                      </font></td>
                                                    <td><font color="#FF6600"><span id="contactperson">-Select a lead-</span></font></td>
                                                  </tr>
                                                  <tr height="30px">
                                                    <td valign="top"><strong>Address:<font color="#FF6600">
                                                      <input name="hiddenaddress" id = "hiddenaddress" type="hidden" value="" />
                                                      </font></strong></td>
                                                    <td valign="top"><font color="#FF6600"><span id="address">-Select a lead-</span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>District / State</strong>:
                                                      <input type="hidden" name="hiddendistrictstate" id="hiddendistrictstate" /></td>
                                                    <td><font color="#FF6600"><span id="district">-Select a lead-</span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>STD Code</strong>:
                                                      <input type="hidden" name="hiddenstdcode" id="hiddenstdcode" /></td>
                                                    <td><font color="#FF6600"><span id="stdcode">-Select a lead-</span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Landline</strong>:
                                                      <input type="hidden" name="hiddenphone" id="hiddenphone" /></td>
                                                    <td><font color="#FF6600"><span id="phone">-Select a lead-</span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Cell</strong>:
                                                      <input type="hidden" name="hiddencell" id="hiddencell" /></td>
                                                    <td><font color="#FF6600"><span id="cell">-Select a lead-</span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Email ID</strong>:
                                                      <input type="hidden" name="hiddenemailid" id="hiddenemailid" /></td>
                                                    <td><font color="#FF6600"><span id="emailid">-Select a lead-</span></font></td>
                                                  </tr>
                                                  <tr height="40px">
                                                    <td valign="top"><strong>Reference [Type] :
                                                      <input type="hidden" name="hiddenreference" id="hiddenreference" />
                                                      </strong></td>
                                                    <td valign="top"><font color="#FF6600"><span id="referencetype">-Select a lead-</span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Given By :
                                                      <input type="hidden" name="hiddengivenby" id="hiddengivenby" />
                                                      </strong></td>
                                                    <td><font color="#FF6600"><span id="givenby1">-Select a lead-</span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Date of lead:
                                                      <input type="hidden" name="hiddendateoflead" id="hiddendateoflead" />
                                                      </strong></td>
                                                    <td><font color="#FF6600"><span id="dateoflead">-Select a lead-</span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Dealer viewed date:
                                                      <input type="hidden" name="hiddendealerviewdate" id="hiddendealerviewdate" />
                                                      </strong></td>
                                                    <td><font color="#FF6600"><span id="dealerviewdate">-Select a lead-</span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Product:
                                                      <input type="hidden" name="hiddenproduct" id="hiddenproduct" />
                                                      </strong></td>
                                                    <td><font color="#FF6600"><span id="product1">-Select a lead-</span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Dealer:
                                                      <input type="hidden" name="hiddendealer" id="hiddendealer" />
                                                      </strong></td>
                                                    <td><font color="#FF6600"><span id="dealer1">-Select a lead-</span></font></td>
                                                  </tr>
                                                  <tr height="20px">
                                                    <td><strong>Manager:
                                                      <input type="hidden" name="hiddenmanager" id="hiddenmanager" />
                                                      </strong></td>
                                                    <td><font color="#FF6600"><span id="manager">-Select a lead-</span></font></td>
                                                  </tr>
                                                  
                                                  <tr>
                                                    <td height="5px" colspan="2"></td>
                                                  </tr>
                                                </table>
                                                </span></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                    </table></td>
                                  <td width="60%"  valign="top"><table width="100%"  cellspacing="0" cellpadding="1" style="border:solid 1px #999999">
                                      <tr>
                                        <td colspan="4" bgcolor="#0099CC"  height="20px"><strong><font color="#FFFFFF">Lead Tracker</font></strong></td>
                                      </tr>
                                      <tr height="20px">
                                        <td width="25%" align="center" id="tabgroupgridh1" onclick="gridtab3('1','tabgroupgrid','followup'); " style="cursor:pointer" class="grid-active-tabclass">Follow Up</td>
                                        <td width="25%" align="center" id="tabgroupgridh2" onclick="gridtab3('2','tabgroupgrid','updatelogs');" style="cursor:pointer" class="grid-tabclass">Update Logs</td>
                                        <td width="25%" align="center" id="tabgroupgridh3" onclick="gridtab3('3','tabgroupgrid','transferlogs');" style="cursor:pointer" class="grid-tabclass">Transfer Logs</td>
                                        <td width="25%" align="center" class="grid-tabclass1">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td  colspan="4" valign="top" ><table width="100%"  cellspacing="0" cellpadding="0" >
                                            <tr>
                                              <td valign="top" height="310px"><div id="tabgroupgridc1" style="display:block;">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                      <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                                          <tr>
                                                            <td width="32%" valign="top">Remarks :</td>
                                                            <td colspan="2" valign="top"><textarea name="form_leadremarks" rows="2" class="formfields" style="padding:2px; width:400px; font-family:Arial, Helvetica, sans-serif; font-size:12px" id="form_leadremarks"></textarea>
                                                              <input type="hidden" name="hiddenactivetype" id="hiddenactivetype" value = "followup"/></td>
                                                          </tr>
                                                          <tr>
                                                            <td valign="top">Next Followup Date :</td>
                                                            <td width="34%" valign="top"><input name="followupdate" onfocus="cleantext('followupdate','dd-mm-yyyy');" onblur="puttext('followupdate','dd-mm-yyyy')"  type="text" class="formfields" id="followupdate" size="20" maxlength="10" readonly="readonly" /></td>
                                                            <td width="34%"><div align="left">
                                                              <input style="height:20px" name="newfollowup" type="button" class="formbutton" id="newfollowup" value="Clear" onclick="newfollowup();" />
                                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
                                                          </tr>
                                                      </table></td>
                                                    </tr>
                                                    <tr>
                                                      <td height="20px" valign="top"><span id="followupmessage"></span></td>
                                                    </tr>
                                                    <tr>
                                                      <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                                          <tr>
                                                            <td><div id="smallgrid" class="grid-div-small2">
                                                                <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999">
                                                                  <tr class="gridheader">
                                                                    <td width="9%">Sl No</td>
                                                                    <td width="14%">Date</td>
                                                                    <td width="37%">Remarks</td>
                                                                    <td width="20%">Next Follow-up</td>
                                                                    <td width="20%">Entered by</td>
                                                                  </tr>
                                                                </table>
                                                              </div></td>
                                                          </tr>
                                                        </table></td>
                                                    </tr>
                                                  </table>
                                                </div>
                                                <div id="tabgroupgridc2" style="display:none;">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <input type="hidden" name="hiddenactivetype" id="hiddenactivetype"/>
                                                    <tr>
                                                      <td colspan="3" ><div id="tabgroupgridc11" style="overflow:auto; height:310px; width:550px" align="center">
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                              <td><div id="tabgroupgridc1_2" align="center">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0" id="gridtable1">
                                                                    <tr class="gridheader" height="20px">
                                                                      <td class="tdborder1">&nbsp;Sl No</td>
                                                                      <td class="tdborder1">&nbsp;Lead Status</td>
                                                                      <td class="tdborder1">&nbsp;Updated Date</td>
                                                                      <td class="tdborder1">&nbsp;Updated By</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                            <tr>
                                                              <td><div id="getmorelink2"  align="left" style="height:20px; "> </div></td>
                                                            </tr>
                                                          </table>
                                                        </div>
                                                        <div id="resultgrid2" style="overflow:auto; display:none; height:300px; width:550px;" align="center">&nbsp;</div></td>
                                                    </tr>
                                                  </table>
                                                </div>
                                                <div id="tabgroupgridc3" style="display:none;">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <input type="hidden" name="hiddenactivetype" id="hiddenactivetype"/>
                                                    <tr>
                                                      <td colspan="3" ><div id="tabgroupgridc12" style="overflow:auto; height:310px; width:550px" align="center">
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                              <td><div id="tabgroupgridc1_3" align="center">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0" id="gridtable1">
                                                                    <tr class="gridheader" height="20px">
                                                                      <td class="tdborder1">&nbsp;Sl No</td>
                                                                      <td class="tdborder1">&nbsp;From Dealer</td>
                                                                      <td class="tdborder1">&nbsp;To Dealer</td>
                                                                      <td class="tdborder1">&nbsp;Transfer Date</td>
                                                                      <td class="tdborder1">&nbsp;Transfered By</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                            <tr>
                                                              <td><div id="getmorelink3"  align="left" style="height:20px; "></div></td>
                                                            </tr>
                                                          </table>
                                                        </div>
                                                        <div id="resultgrid3" style="overflow:auto; display:none; height:300px; width:550px;" align="center">&nbsp;</div></td>
                                                    </tr>
                                                  </table>
                                                </div></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                    </table></td>
                                </tr>
                                <tr>
                                  <td valign="top"><span align="center">
                                    <input name="form_recid" type="hidden" class="formfields" id="form_recid" />
                                    </span></td>
                                  <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td  valign="top" style="border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                            <tr>
                                              <td  bgcolor="#0099CC"><strong><font color="#FFFFFF">Lead Status</font></strong></td>
                                            </tr>
                                            <tr>
                                              <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                  <tr>
                                                    <td width="13%" valign="top"><strong>Initial Remarks : </strong></td>
                                                    <td width="37%" valign="top"><span id="leadremarks"><font color="#FF6600">-Select a lead-</font></span></td>
                                                    <td valign="top"><strong>Current Status  :</strong> </td>
                                                    <td valign="top"><select name="form_leadstatus" id="form_leadstatus" class="formfields" disabled="disabled">
                                                        <option value="" selected="selected">--- Select ---</option>
                                                        <option value="Not Viewed">Not Viewed</option>
                                                        <option value="UnAttended">UnAttended</option>
                                                        <option value="Not Interested">Not Interested</option>
                                                        <option value="Fake Enquiry">Fake Enquiry</option>
                                                        <option value="Registered User">Registered User</option>
                                                        <option value="Attended">Attended</option>
                                                        <option value="Perusing to Purchase">Perusing to Purchase</option>
                                                        <option value="Demo Given">Demo Given</option>
                                                        <option value="Quote Sent">Quote Sent</option>
                                                        <option value="Order Closed">Order Closed</option>
                                                      </select>
                                                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                  </tr>
                                                  <tr> </tr>
                                                </table></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                      <tr><td><table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td id="msg_box" width="50%">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</td></tr>
                                    </table></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4" style="border:solid 1px #999999; background-color:#ffffcc; color:#000000"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td bgcolor="#DEE1FA"><strong>Filter: </strong>[<a style="cursor:pointer" onclick="newtog();">Show/Hide</a>]</td>
                    </tr>
                    <tr>
                      <td><form id="filterform" name="filterform" onsubmit="return false;" autocomplete = "off" method="post" action="viewleadstoexcel.php " >
                          <table width="100%" border="0" cellspacing="0" cellpadding="6">
                            <tr>
                              <td width="12%">From Date : </td>
                              <td width="37%"><input name="fromdate" type="text" class="formfields" id="DPC_fromdate" size="20" maxlength="10" value="<?php echo($date); ?>" readonly="readonly"/>
                                <input type="hidden" name="hiddenfromdate" id="hiddenfromdate" /></td>
                              <td width="11%">To Date : </td>
                              <td width="40%"><input name="todate" type="text" class="formfields" id="DPC_todate" size="20" maxlength="10" value="<?php echo($defaulttodate); ?>" readonly="readonly" />
                                <input type="hidden" name="hiddentodate" id="hiddentodate" /></td>
                            </tr>
                            <tr>
                              <td>Product Name : </td>
                              <td width="37%"><select name="productid" class="formfields" id="productid">
                                  <?php 
						echo($productselect);
						?>
                                </select>
                                <input type="hidden" name="hiddenproductid" id="hiddenproductid" /></td>
                              <td width="11%">Dealer Name: </td>
                              <td width="40%"><select name="dealerid" class="formfields" id="dealerid">
                                  <?php 
						echo($dealerselect);
						?>
                                </select>
                                <input type="hidden" name="hiddendealerid" id="hiddendealerid" /></td>
                            </tr>
                            <tr>
                              <td>Given By : </td>
                              <td width="37%"><?php echo($givenselect);?>
                                <input type="hidden" name="hiddengivenby" id="hiddengivenby" /></td>
                              <td width="11%">Status of Lead : </td>
                              <td width="40%"><select name="leadstatus" class="formfields" id="leadstatus">
                                  <?php 
						echo($leadstatusselect);
						?>
                                </select>
                                <input type="hidden" name="hiddenleadstatus" id="hiddenleadstatus" /></td>
                            </tr>
                            <tr>
                              <td colspan="2" style="border-top:solid 1px #999999;"><strong>Last Follow up date: </strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input name="considerfollowup" type="checkbox" id="considerfollowup" onclick="filterfollowupdates();" />
                                <label for="considerfollowup">Consider Follow Up dates</label>                              </td>
                              <td colspan="2" style="border-bottom:solid 1px #999999; border-left:solid 1px #999999;">&nbsp;
                                <input name="dropterminatedstatus" type="checkbox" id="dropterminatedstatus" value="true" checked="checked" />
                                <label for="dropterminatedstatus">Do not consider Order Closed / Fake / Exsting Users / Not Interested</label>                              </td>
                            </tr>
                            <tr>
                              <td>From Date : </td>
                              <td><input name="filter_followupdate1" type="text" class="formfields" id="DPC_filter_followupdate1" size="20" maxlength="10" value="<?php echo($defaulttodate); ?>" disabled="disabled" readonly="readonly"/>
                                <input name="filter_followupdate1hdn" type="hidden" class="formfields" id="filter_followupdate1hdn" value="" /></td>
                              <td>To Date : </td>
                              <td><input name="filter_followupdate2" type="text" class="formfields" id="DPC_filter_followupdate2" size="20" maxlength="10" value="<?php echo($defaulttodate); ?>" disabled="disabled" readonly="readonly"/>
                                <input name="filter_followupdate2hdn" type="hidden" class="formfields" id="filter_followupdate2hdn" value="" /></td>
                            </tr>
                            <tr>
                              <td colspan="2" id="msg_box2"></td>
                              <td colspan="2"><div align="center">
                                  <input name="view" type="button" class="formbutton" id="view" value="Show" onclick="filtering('view');" />
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <input name="excel" type="button" class="formbutton" id="excel" value="To Excel" onclick="filtering('excel');" <?php echo $disabled;?> />
                                </div></td>
                            </tr>
                          </table>
                        </form></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>
              <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="100" align="center" id="tabgroupleadgridh1" onclick="leadgridtab2('1','tabgroupleadgrid','default'); " class="grid-active-leadtabclass"> Leads</td>
                  <td width="1px"></td>
                   <td width="100" align="center" id="tabgroupleadgridh2" onclick="leadgridtab2('2','tabgroupleadgrid','searchresult'); " class="grid-leadtabclass" >Search Result</td>
                   <td width="100"></td>
                   <td width="100"></td>
                   <td width="100"></td>
                   <td width="100"></td>
                   <td width="65"></td>
                </tr>
              </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="20px" colspan="4"><div id="tabgroupleadgridc1" style="display:block;">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                <tr class="headerline">
                  <td width="50%"><span id="gridprocess"></span></td>
                  <td width="50%"><span id="gridprocess1"></span></td>
                </tr>
                <tr>
                  <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td colspan="3" ><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:945px" align="center">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td><div id="tabgroupgridc1_1" align="center"></div></td>
                              </tr>
                              <tr>
                                <td><div id="getmorelink"  align="left" style="height:20px; "></div></td>
                              </tr>
                            </table>
                          </div>
                          <div id="resultgrid" style="overflow:auto; display:none; height:150px; width:695px;" align="center">&nbsp;</div></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
            </div>
            
      
            <div id="tabgroupleadgridc2" style="display:none;">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                <tr class="headerline">
                  <td width="50%" ><strong>&nbsp;List of leads:<span id="gridprocessf"></span></strong></td>
                  <td align="left"><span id="gridprocessf1"></span></td>
                </tr>
                <tr>
                  <td colspan="3" ><div id="tabgroupgridf1" style="overflow:auto; height:200px; width:945px" align="center">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><div id="tabgroupgridf1_1" align="center">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr class="gridheader" height="20px">
                                  <td class="tdborderlead">&nbsp;Sl No</td>
                                  <td class="tdborderlead">&nbsp;Lead Id</td>
                                  <td class="tdborderlead">&nbsp;Lead Date</td>
                                  <td class="tdborderlead">&nbsp;Product</td>
                                  <td class="tdborderlead">&nbsp;Company</td>
                                  <td class="tdborderlead">&nbsp;Contact</td>
                                  <td class="tdborderlead">&nbsp;Landline</td>
                                  <td class="tdborderlead">&nbsp;Cell</td>
                                  <td class="tdborderlead">&nbsp;Email Id</td>
                                  <td class="tdborderlead">&nbsp;District</td>
                                  <td class="tdborderlead">&nbsp;State</td>
                                  <td class="tdborderlead">&nbsp;Dealer</td>
                                  <td class="tdborderlead">&nbsp;Manager</td>
                                </tr>
                              </table>
                            </div></td>
                        </tr>
                        <tr>
                          <td><div id="getmorelinkf1"  align="left" style="height:20px; "> </div></td>
                        </tr>
                      </table>
                    </div>
                    <div id="resultgridf1" style="overflow:auto; display:none; height:150px; width:700px;" align="center">&nbsp;</div></td>
                </tr>
              </table>
            </div>            </td>
        </tr>
        <tr>
          <td height="20px" colspan="4"></td>
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
