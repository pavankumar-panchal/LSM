<?php
include("../inc/checklogin.php");

//Permission check for the page
$showmcalistvalues = getshowmcapermissionvalue();
$showmcalistvaluessplit = explode('^',$showmcalistvalues);
if($showmcalistvaluessplit[0] <> 'yes')
	header("Location:../home");
	

//Select the list of dealers for whom lead can be uploaded.
switch($cookie_usertype)
{
	case "Admin":
		//$query = "SELECT id AS selectid, dlrcompanyname AS selectname FROM dealers ORDER BY dlrcompanyname";
		$query = "SELECT distinct dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM dealers left join lms_users on lms_users.referenceid = dealers.id where lms_users.disablelogin <> 'yes' and lms_users.type = 'Dealer' ORDER BY dlrcompanyname;";
		$result = runmysqlquery($query);
		$dealerselect = '<option value="" selected="selected">- - -Make a Selection- - -</option>';
		while($fetch = mysqli_fetch_array($result))
		{
			$dealerselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].'</option>';
		}
		break;
	case "Reporting Authority":
		//$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '".$cookie_username."' ORDER BY dealers.dlrcompanyname";
		$query = "select dealers.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users left join dealers on dealers.id=lms_users.referenceid where dealers.managerid  in (select dealers.managerid from dealers left join lms_users on dealers.managerid =lms_users.referenceid where lms_users.username = '".$cookie_username."'and lms_users.type = 'Reporting Authority')
and  lms_users.type = 'Dealer' and lms_users.disablelogin <> 'yes' ORDER BY dealers.dlrcompanyname";
		
		if($cookie_username == "srinivasan")
			//$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '".$cookie_username."' or  lms_users.username = 'nagaraj' ORDER BY dealers.dlrcompanyname";
			$query = "select dealers.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users  left join dealers on dealers.id =lms_users.referenceid where dealers.managerid  in (select dealers.managerid from dealers left join lms_users on dealers.managerid =lms_users.referenceid where lms_users.username = '".$cookie_username."'  or lms_users.username = 'nagaraj'and lms_users.type ='Reporting Authority') and lms_users.type = 'Dealer' and lms_users.disablelogin <> 'yes' ORDER BY dealers.dlrcompanyname";
		$result = runmysqlquery($query);
		$dealerselect = '<option value="" selected="selected">- - -Make a Selection- - -</option>';
		while($fetch = mysqli_fetch_array($result))
		{
			$dealerselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].'</option>';
		}
		break;
	case "Sub Admin":
		//$query = "SELECT id AS selectid, dlrcompanyname AS selectname FROM dealers ORDER BY dlrcompanyname";
		$query = "SELECT distinct dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM dealers left join lms_users on lms_users.referenceid = dealers.id where lms_users.disablelogin <> 'yes' and lms_users.type = 'Dealer' ORDER BY dlrcompanyname;";
		$result = runmysqlquery($query);
		$dealerselect = '<option value="" selected="selected">- - -Make a Selection- - -</option>';
		while($fetch = mysqli_fetch_array($result))
		{
			$dealerselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].'</option>';
		}
		break;
	case "Dealer":
		//$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.id WHERE lms_users.username = '".$cookie_username."' ORDER BY dealers.dlrcompanyname";
		
		$query = "select dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM dealers LEFT JOIN lms_users ON lms_users.referenceid = dealers.id WHERE lms_users.username = '".$cookie_username."' AND lms_users.type = 'Dealer' AND disablelogin <> 'yes' ORDER BY dealers.dlrcompanyname;";
		$result = runmysqlquery($query);
		$dealerselect = '<option value="" selected="selected">- - -Make a Selection- - -</option>';
		while($fetch = mysqli_fetch_array($result))
		{
			$dealerselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].'</option>';
		}
		break;
	case "Dealer Member":
		$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN lms_dlrmembers on lms_dlrmembers.dlrmbrid = lms_users.referenceid JOIN dealers ON lms_dlrmembers.dealerid = dealers.id WHERE lms_users.username = '".$cookie_username."' ORDER BY dealers.dlrcompanyname";
		$result = runmysqlquery($query);
		$dealerselect = '<option value="" selected="selected">- - -Make a Selection- - -</option>';
		while($fetch = mysqli_fetch_array($result))
		{
			$dealerselect .= '<option value="'.$fetch['selectid'].'">'.$fetch['selectname'].'</option>';
		}
		break;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | MCA Companies</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<link media="screen" rel="stylesheet" href="../css/colorbox.css?dummy=<?php echo (rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/mcacompanies.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/colorbox.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script language="javascript" src="../functions/clipboardcopy.js?dummy=<?php echo (rand());?>"></script>


<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
</head>
<body onload="document.getElementById('detailsearchtext').focus()">
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
    <td valign="middle" bgcolor="#D2D8FB" class="contentheader"><table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
      <tr>
        <td>MCA Companies</td>
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
        <td height="20" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="23%" valign="top"><form id="filterform" name="filterform" method="post" action="" onsubmit="return false;">
              <table width="80%" border="0" cellspacing="0" cellpadding="3">
                <tr>
                  <td width="71%" height="34" id="customerselectionprocess" align="left" style="padding:0"></td>
                  <td width="29%" style="padding:0"><div align="right"><a onclick="refreshmcaarray();" style="cursor:pointer; padding-right:10px;"><img src="../images/refresh.jpg"   alt="Refresh customer" border="0" align="middle" title="Refresh customer Data"  /></a></div></td>
                </tr>
                <tr>
                  <td colspan="2" align="left" valign="top"><input name="detailsearchtext" type="text" class="formfields"  id="detailsearchtext" onkeyup="mcasearch(event);"  autocomplete="off"  style="width:206px"/>
                    <input type="hidden" name="flag" id="flag" />
                    <span style="display:none">
                      <input name="searchtextid" type="hidden" id="searchtextid"  disabled="disabled"/>
                      </span>
                    <div id="detailloadcustomerlist">
                      <select name="mcalist" size="5" class="formfields" id="mcalist" style="width:208px; height:500px" onclick ="selectfromlist();" onchange="selectfromlist();"  >
                      </select>
                    </div></td>
                </tr>
              </table>
            </form></td>
            <td width="77%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr> <tr>
                <td width="81%">&nbsp;</td>
                <td width="19%"><div align="left">
                  <input name="search" type="submit" class="formbutton" id="search" value="Advanced Search"  onclick="displayDiv('1','filterdiv')"   style="cursor:pointer"/>
                </div></td>
              </tr>
               <tr>
                <td colspan="2">&nbsp;</td></tr>
              <tr>
                <td colspan="2"><div id="filterdiv" style="display:none;">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                          <td valign="top"><div>
                            <form action="" method="post" name="searchfilterform" id="searchfilterform" onsubmit="return false;">
                              <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr bgcolor="#0099CC">
                                  <td width="100%" align="left"  style="padding:0" height="25px"><font color="#FFFFFF">&nbsp;&nbsp;<strong>Search Option</strong></font></td>
                                  </tr>
                                <tr>
                                  <td valign="top" ><table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#FFFFCC" style="border:dashed 1px #545429">
                                    <tr>
                                      <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4" >
                                        <tr>
                                          <td colspan="4" align="left" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td width="9%" align="left" valign="middle" >Text: </td>
                                              <td width="91%" colspan="3" align="left" valign="top" ><input name="searchcriteria" type="text" id="searchcriteria" size="35" maxlength="60" class="formfields"  autocomplete="off" value=""/>
                                                <span style="font-size:9px; color:#999999; padding:1px">(Leave Empty for all)
                                                <input type="hidden" name="searchhidden" id="searchhidden" value="" />
                                                </span></td>
                                              <td>&nbsp;</td>
                                              </tr>
                                            </table></td>
                                          </tr>
                                                                              <tr>
                                          <td colspan="2" align="left" style="padding:3px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr>
                                                  <td colspan="8" align="left"><strong>Look in:</strong></td>
                                                </tr>
                                                <tr>
                                                  <td width="14%" align="left"><label>
                                                     <label for"databasefield1"><input type="radio" name="databasefield" id="databasefield1" value="company" checked="checked"/>
                                                   Company </label></td>
                                                  <td width="13%" align="left"><label for"databasefield2"><input type="radio" name="databasefield" value="address" id="databasefield2" /> 
                                                    Address</label>
</td>
                                                  <td width="10%" align="left"><label for"databasefield4"><input type="radio" name="databasefield" value="city" id="databasefield4" /> 
                                                    City</label>
</td>
                                                  <td width="11%" align="left"><label for"databasefield5"><input type="radio" name="databasefield" value="pincode" id="databasefield5" /> 
                                                    Pincode</label>
</td>
                                                  <td width="13%" align="left"><label for"databasefield6"><input type="radio" name="databasefield" value="emailid" id="databasefield6" /> 
                                                    Email ID</label>
</td>
                                                  <td width="11%" align="left"><label for="databasefield7">
                                                    <input type="radio" name="databasefield" value="cin" id="databasefield7" />
                                                    CIN</label></td>
                                                  <td width="11%" align="left"><label for="databasefield8">
                                                    <input type="radio" name="databasefield" value="din" id="databasefield8" />
                                                    DIN</label></td>
                                                  <td width="17%" align="left"><label for="databasefield9">
                                                    <input type="radio" name="databasefield" value="directorname" id="databasefield9" />
                                                    Director Name</label></td>
                                                </tr>
                                                
                                                <tr>
                                                  <td colspan="8" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                      <td width="11%">State:</td>
                                                      <td width="37%"><select name="mca_state" class="formfields" id="mca_state" style="width:200px;">
                                                        <option value = "">All</option>
                                                        <?php include('../inc/mcastate.php'); ?>
                                                      </select></td>
                                                      <td width="16%">Class:</td>
                                                      <td width="36%"><select name="mca_class" class="formfields" id="mca_class"style="width:120px;">
                                                        <option value = "" selected="selected">All</option>
                                                        <?php include('../inc/mcaclass.php'); ?>
                                                      </select></td>
                                                    </tr>
                                                    <tr>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                      <td>Roc Code:</td>
                                                      <td><select name="mca_roccode" class="formfields" id="mca_roccode" style="width:200px;">
                                                        <option value = "">All</option><?php include('../inc/mcaroc-code.php'); ?>
                                                      </select></td>
                                                      <td>Paid up capital:</td>
                                                      <td><select name="mca_puc" class="formfields" id="mca_puc" style="width:120px;">
                                                                                                                <option value = "" selected="selected">All</option>
                                                       <option value = "below5crore">Below 5 Crore</option>
                                                       <option value = "above5crore">Above 5 Crore</option>
                                                      </select></td>
                                                    </tr>
                                                    <tr>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                      <td>Branch:</td>
                                                      <td><select name="mca_branch" class="formfields" id="mca_branch" style="width:200px;">
                                                        <option value = "">All</option>
                                                        <?php include('../inc/mcabranch.php'); ?>
                                                      </select></td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                    </tr>
                                                  </table></td>
                                                </tr>
                                                
                                              </table></td>
                                              </tr>
                                            </table></td>
                                          </tr>
                                        <tr>
                                          <td width="62%" height="35" align="left" valign="middle" ><div id="filter-form-error"></div></td>
                                          <td width="38%" align="left" valign="middle" ><div align="center"><input name="mcasearch" type="submit" class="formbutton" id="mcasearch" value="Search"  onclick="advancesearch();" style="cursor:pointer"  />&nbsp;&nbsp; <input name="clear" type="submit" class="formbutton" id="clear" value="Clear"  onclick="clearfilter()"  style="cursor:pointer"/>&nbsp;&nbsp; <input name="close" type="submit" class="formbutton" id="close" value="Close"  onclick="closefilter()"  style="cursor:pointer"/></div></td>
                                          </tr>
                                        </table></td>
                                      </tr>
                                    </table></td>
                                  </tr>
                                <tr>
                                  <td align="right" valign="middle" style="padding-right:15px; "></td>
                                  </tr>
                                </table>
                              </form>
                            </div></td>
                          </tr>
                        </table>
                      </div></td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="2" style="border-bottom:#CCC 1px solid; border-left: 1px solid #CCC; border-right:1px solid #CCC" >
                  <tr>
                    <td width="22%" height="25px" bgcolor="#0099CC"><font color="#FFFFFF"><strong>Company Information</strong></font></td>
                    <td width="78%" bgcolor="#0099CC"><div id="customerdetailselection"></div></td>
                  </tr>
                  <tr>
                    <td colspan="2" height="2px;"></td>
                  </tr>
                  <tr>
                    <td colspan="2"><form id="leaduploadform" name="leaduploadform" method="post" action="">
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td width="115" bgcolor="#FBFEFF"><strong>Company Name:
                            </strong>
                            <input name="lastslno" type="hidden" id="lastslno" />
                            <input name="cookie_usertype" type="hidden" id="cookie_usertype" value="<?php echo($cookie_usertype); ?>" /></td>
                          <td colspan="3" bgcolor="#FBFEFF"><font color="#FF0000"><strong><div id="form_companyname"></div></strong></font></td>
                        </tr>
                        <tr bgcolor="#F4FDFF">
                          <td  valign="top" bgcolor="#F4FDFF"><strong>Address1:</strong></td>
                          <td width="275" valign="top"><div id="form_address" style="font-size:9px"></div></td>
                          <td width="134" valign="top"><strong>Incorporated Date:</strong></td>
                          <td width="154"><div id="form_incorporateddate"></div></td>
                        </tr>
                        <tr bgcolor="#FBFEFF">
                          <td valign="top"><strong>Address2</strong></td>
                          <td valign="top"><div id="form_address2" style="font-size:9px"></div></td>
                          <td valign="top"><strong>Roc Code: </strong></td>
                          <td><div id="form_roccode"></div></td>
                        </tr>
                        <tr bgcolor="#F4FDFF">
                          <td bgcolor="#F4FDFF"><strong>City:</strong></td>
                          <td><div id="form_city"></div></td>
                          <td bgcolor="#F4FDFF"><strong>Registration No:</strong></td>
                          <td bgcolor="#F4FDFF"><div id="form_registrationnumber"></div></td>
                          </tr>
                        <tr bgcolor="#FBFEFF">
                          <td valign="top"><strong>State:</strong></td>
                          <td valign="top"><div id="form_state"></div></td>
                          <td><strong>CIN:</strong></td>
                          <td><div id="form_cin"></div></td>
                          </tr>
                        <tr bgcolor="#F4FDFF">
                          <td><strong>Pincode:</strong></td>
                          <td><div id="form_pincode"></div></td>
                          <td><strong>Last AGM Date:</strong></td>
                          <td><div id="form_agmdate"></div></td>
                        </tr>
                        <tr bgcolor="#FBFEFF">
                          <td><strong>Email ID:</strong></td>
                          <td bgcolor="#FBFEFF"><div id="form_emailid"></div></td>
                          <td bgcolor="#FBFEFF"><strong>Last BS Date:</strong></td>
                          <td bgcolor="#FBFEFF"><div id="form_balancesheetdate"></div></td>
                          </tr>
                        <tr bgcolor="#F4FDFF">
                          <td bgcolor="#F4FDFF"><strong>Class:</strong></td>
                          <td><div id="form_class"></div></td>
                          <td><strong>Authorized Capital:</strong></td>
                          <td><div id="form_authorisedcapital"></div></td>
                        </tr>
                        <tr bgcolor="#FBFEFF">
                          <td bgcolor="#FBFEFF"><strong>Listing Type:</strong></td>
                          <td><div  id="form_listingtype"></div></td>
                          <td><strong>Paid up capital:</strong></td>
                          <td><div id="form_paidupcapital"></div></td>
                        </tr>
                      </table>
                    </form></td>
                  </tr>
                  <tr></tr>
                </table></td>
              </tr>
              <tr><td colspan="2">&nbsp;</td></tr>
               <tr>
                 <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                   <tr>
                     <td bgcolor="#0099CC" height="25px;" style="padding:4px 4px 4px 4px"><font color="#FFFFFF"><strong >Director Information</strong></font></td>
                     <td bgcolor="#0099CC" style="padding:4px 4px 4px 4px"><div align="right"><img src="../images/minus.jpg" border="0" id="toggleimg2" name="toggleimg1"  align="absmiddle" onclick="hideshowdirectorinfodiv();" style="cursor:pointer"/></div></td>
                   </tr>
                   <tr>
                     <td colspan="2"><div id="directorinfo" style="display:block"><table width="100%" border="0" cellspacing="0" cellpadding="0" height="50px"  style="border-bottom:#CCC 1px solid; border-left: 1px solid #CCC; border-right:1px solid #CCC">
  <tr>
    <td><div align="center" style="font-size:18px"><font color="#FF0000"><strong>No Director information available</strong></font></div></td>
  </tr>
</table>
</div></td>
                     </tr>
                 </table></td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                 <tr></tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="2"  style="border-bottom:#CCC 1px solid; border-left: 1px solid #CCC; border-right:1px solid #CCC">
                  <tr>
                    <td bgcolor="#0099CC" height="25px"><font color="#FFFFFF"><strong>Verified Information</strong></font></td>
                  </tr>
                  <tr>
                    <td><form id="saveform" name="saveform" action="" method="post" onsubmit="return false"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="16%">Contact Person:</td>
                        <td width="46%"><input name="contactperson" type="text" class="formfields" id="contactperson"  size="50"  autocomplete ="off" /> <input name="addlastslno" type="hidden" id="addlastslno" value="" /></td>
                        <td width="11%">Place:</td>
                        <td width="27%"><input name="place" type="text" class="formfields" id="place"  size="30" maxlength="30" autocomplete ="off"  /></td>
                      </tr>
                      <tr>
                        <td>Address:</td>
                        <td><input name="address" type="text" class="formfields" id="address"  size="50" autocomplete ="off" /></td>
                        <td>Cell:</td>
                        <td><input name="cell" type="text" class="formfields" id="cell"  size="30" maxlength="100" autocomplete ="off" /></td>
                      </tr>
                      <tr>
                        <td>Email ID:</td>
                        <td><input name="emailid" type="text" class="formfields" id="emailid"  size="50" autocomplete ="off"  /></td>
                        <td>Phone:</td>
                        <td><input name="phone" type="text" class="formfields" id="phone"  size="30" maxlength="10" autocomplete ="off"  /></td>
                      </tr>
                      <tr>
                        <td>State:</td>
                        <td><select name="state" class="formfields" id="state" onchange="districtselect()" style="width:325px">
                              <?php include('../inc/state.php')?>
                          </select></td>
                        <td>STD code:</td>
                        <td><input name="stdcode" type="text" class="formfields" id="stdcode"  size="30" maxlength="6" autocomplete ="off"  /></td>
                      </tr>
                      <tr>
                        <td>District:</td>
                        <td><div id="districtdiv">
                          <select name="form_district" class="formfields" id="form_district"  style="width:325px">
                            <option value = "">- - - -Select a State First - - - -</option>
                          </select>
                        </div></td>
                        <td>&nbsp;</td>
                        <td><span  style="display:none" id="short_url" ></span></td>
                      </tr>
                      <tr><td colspan="4" height="5px;"></td></tr>
                      <tr>
                        <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="53%"><div id="errormessage"></div></td>
    <td width="26%"><div align="center">
      <input name="save" type="submit" class="formbutton" id="save" value="Save"    style="cursor:pointer"  onclick="saveadditionaldetails();"/>
  &nbsp;
  <input name="converttoleadbtn" type="submit" class="formbutton" id="converttoleadbtn" value="Convert to lead"   style="cursor:pointer" onclick="converttolead()"/>
    </div></td>
    <td width="21%"><div id="info_copy_button"></div></td>
  </tr>
</table>
</td></tr>
                    </table></form></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="2"><div style="display:none">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><div id='inline_example1' style='background:#fff; width:650px; height:300px;'><form id="submitform" name="submitform" method="post" onsubmit="return false"><table width="100%" border="0" cellspacing="0" cellpadding="6">
              <tr>
                <td valign="top"><strong>Contact Person:</strong></td>
                <td colspan="2" valign="top"><div id="displayname">&nbsp;</div></td>
                <td><strong>Place:</strong></td>
                <td><div id="displayplace">&nbsp;</div></td>
              </tr>
              <tr>
                <td valign="top"><strong>Address:</strong></td>
                <td colspan="2" valign="top"><div id="displayaddress">&nbsp;</div></td>
                <td><strong>Cell:</strong></td>
                <td><div id="displaycell">&nbsp;</div></td>
              </tr>
            <tr>
                          <td width="114" valign="top"><strong>Email ID:</strong></td>
                          <td colspan="2" valign="top"><div id="displayemailid">&nbsp;</div></td>
                          <td width="88"><strong>Phone:</strong></td>
                          <td width="182"><div id="displayphone">&nbsp;</div></td>
                        </tr>
            <tr>
              <td valign="top"> <strong>State:</strong></td>
              <td colspan="2" valign="top"><div id="displaystate">&nbsp;</div></td>
              <td><strong>STD Code:</strong></td>
              <td><div id="displaystdcode">&nbsp;</div></td>
            </tr>
            <tr>
              <td valign="top"> <strong>District:</strong></td>
              <td colspan="2" valign="top"><div id="displaydistrict">&nbsp;</div></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
                        <tr>
                          <td width="114" valign="top"><strong>Product Name:</strong></td>
                          <td colspan="2" valign="top"><select name="form_product" class="formfields" id="form_product"  style="width:170px">
                           <option value="" selected="selected">- - -Make a Selection- - -</option>
                            <?php
							
						include('../inc/product.php');
						?>
                            </select></td>
                          <td width="88"><strong>Reference:</strong></td>
                          <td width="182"><select name="form_source" class="formfields" id="form_source"  style="width:170px">
                            <option value="" selected="selected">- - -Make a Selection- - -</option>
                            <option value="Advertisement">Advertisement</option>
                            <option value="Bulkmail">Bulkmail</option>
                            <option value="Email">Email</option>
                            <option value="Existing Customer">Existing Customer</option>
                            <option value="Incoming Call">Incoming Call</option>
                            <option value="Incoming Email with clear reqt">Incoming Email with clear reqt</option>
                            <option value="Mailer - Letter">Mailer - Letter</option>
                            <option value="NSDL/Income Tax website">NSDL/Income Tax website</option>
                            <option value="Others">Others</option>
                            <option value="Reference from customer">Reference from customer</option>
                            <option value="Relyon Representative">Relyon Representative</option>
                            <option value="Telecalling">Telecalling</option>
                            <option value="Web Search/Search Engine">Web Search/Search Engine</option>
                            
                            </select></td>
                        </tr>
                        <tr>
                          <td valign="top"><strong>To Dealer:</strong></td>
                          <td width="164" valign="top"><div>
                          
                            <input type="checkbox" checked="checked" onclick="checkaspermapping();" name="aspermapping" id="aspermapping"  />
                            <label for="aspermapping">As per Mapping</label>
                            
                            <br />
                            <br />
                            <input style="display:none" type="checkbox" name="aspermapping" id="aspermapping" disabled="disabled" />
                         
                          
                            
                          <span>  <select name="form_dealer" class="formfields" id="form_dealer" style="display:none;width:170px"   >
                              <?php
						echo($dealerselect);
						?>
                              </select> </span></div>                            </td>
                          <td width="42" valign="middle"><div id="help" style=" display:none; padding-bottom:10px;" align="left"><img class="imageclass" onmouseout="hidetooltip()" onmouseover="generatedealertooltip()" src="../images/help-image.gif" /></div></td>
                          <td valign="top"><strong>Remarks:</strong></td>
                          <td><textarea name="form_leadremarks" cols="25" rows="3" class="formfields" style="padding:2px; font-family:Arial, Helvetica, sans-serif; font-size:12px;resize:none; width:170px;" id="form_leadremarks"></textarea></td>
                        </tr>
                        
                        <tr>
                          <td colspan="4" id="msg_box">&nbsp;</td>
                          <td><div align="center">
                              
                            <input name="uploadlead" type="button" class="formbutton" id="uploadlead" value="Upload Lead" onclick="uploadleadvalues();"  style="cursor:pointer"/>&nbsp;&nbsp;<input name="closeform" type="button" class="formbutton" id="closeform" value="Close" onclick="$().colorbox.close();" style="cursor:pointer" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
                        </tr>
                      </table></form>
</div></td>
          </tr>
        </table>
      </div></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
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
<script>
refreshmcaarray();
addInfoCopyButton();
</script>