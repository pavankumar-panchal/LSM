<?php

include("../inc/checklogin.php");

//Permission check for the page
if($cookie_usertype <> "Sub Admin" && $cookie_usertype <> "Reporting Authority" && $cookie_usertype <> "Admin" && $cookie_usertype <> "Dealer" && $cookie_usertype <> "Implementer" && $cookie_usertype <> "Dealer Member")
	header("Location:../home");

//Select the list of products for the drop-down
$query = "SELECT id,productname FROM products ORDER BY productname";
$result = runmysqlquery($query);
$productselect = '<option value="" selected="selected">- - -Make a Selection- - -</option>';
while($fetch = mysqli_fetch_array($result))
{
	$productselect .= '<option value="'.$fetch['id'].'">'.$fetch['productname'].'</option>';
}

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

// For reference List

$query6 = "select distinct refer from leads order by refer";
$result6 = runmysqlquery($query6);
$referenceselect .= '<option value="" selected="selected">- - - Make a Selection - - - </option>';
while($fetch6 = mysqli_fetch_array($result6))
{
	$referenceselect .= '<option value="'.$fetch6['refer'].'">'.$fetch6['refer'].'</option>';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Upload Leads</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/leadupload.js?dummy=<?php echo (rand());?>" language="javascript"></script>
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
    <td valign="middle" bgcolor="#D2D8FB" class="contentheader"><table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
      <tr>
        <td>Upload Leads</td>
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
                  <td><strong>Provide the new Lead detail:</strong></td>
                </tr>
                <tr>
                  <td><form id="leaduploadform" name="leaduploadform" method="post" action="">
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td width="142">Company Name:
                            <input name="form_recid" type="hidden" id="form_recid" /><input name="cookie_usertype" type="hidden" id="cookie_usertype" value="<?php echo($cookie_usertype); ?>" /></td>
                          <td width="307"><input name="form_companyname" type="text" class="formfields" id="form_companyname" size="50" maxlength="50" /></td>
                          <td width="97">Contact person:</td>
                          <td width="346"><input name="form_name" type="text" class="formfields" id="form_name" size="50" maxlength="50" /></td>
                        </tr>
                        <tr>
                          <td>Address:</td>
                          <td colspan="3"><input name="form_address" type="text" class="formfields" id="form_address" size="126" maxlength="200" /></td>
                        </tr>
                        <tr>
                          <td>State:</td>
                          <td><select name="form_state" class="formfields" id="form_state" onchange="districtselect()">
                              <?php include('../inc/state.php')?>
                          </select></td>
                          <td>District:</td>
                          <td><div id="districtdiv">
                            <select name="form_district" class="formfields" id="form_district" onchange="regionselect()">
                              <option value = "">- - - -Select a State First - - - -</option>
                            </select>
                          </div></td>
                        </tr>
                        <tr>
                          <td>Region:</td>
                          <td><div id="regiondiv">
                            <select name="form_region" class="formfields" id="form_region">
                              <option value = "">- - - -Select a District First - - - -</option>
                            </select>
                          </div></td>
                          <td>Place:</td>
                          <td><input name="form_place" type="text" class="formfields" id="form_place" size="50" maxlength="50" /></td>
                        </tr>
                        <tr>
                          <td>STD Code:</td>
                          <td><input name="form_stdcode" type="text" class="formfields" id="form_stdcode" size="50" maxlength="50" /></td>
                          <td>Landline:</td>
                          <td><input name="form_phone" type="text" class="formfields" id="form_phone" size="50" maxlength="50" /></td>
                        </tr>
                        <tr>
                          <td>Cell:</td>
                          <td><input name="form_cell" type="text" class="formfields" id="form_cell" size="50" maxlength="50" /></td>
                          <td>Email ID:</td>
                          <td><input name="form_email" type="text" class="formfields" id="form_email" size="50" maxlength="50" /></td>
                        </tr>
                        <tr>
                          <td valign="top">Product Name:</td>
                          <td valign="top"><select name="form_product" class="formfields" id="form_product">
                            <?php
						echo($productselect);
						?>
                          </select></td>
                          <td>Reference:</td>
                          <td><select name="form_source" class="formfields" id="form_source">
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
                          <td valign="top">To Dealer:</td>
                          <td valign="top"><div>
                          
                            <input type="checkbox" checked="checked" onclick="checkaspermapping();" name="aspermapping" id="aspermapping"  />
                            <label for="aspermapping">As per Mapping</label>
                            
                            <input style="display:none" type="checkbox" name="aspermapping" id="aspermapping" disabled="disabled" />
                         
                          
                          <p>
                            
                            <select name="form_dealer" class="formfields" id="form_dealer" style="display:none;width:56%"  >
                              <?php
						echo($dealerselect);
						?>
                              </select>
                            
                          </p>
                          </div>                            </td>
                          <td valign="top">Remarks:</td>
                          <td><textarea name="form_leadremarks" cols="45" rows="5" class="formfields" style="padding:2px; font-family:Arial, Helvetica, sans-serif; font-size:12px" id="form_leadremarks"></textarea></td>
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
<input name="save" type="button" class="formbutton" id="save" value="Add Lead >>" onclick="formsubmit('save');" />
                          </div></td>
                        </tr>
                      </table>
                  </form></td>
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
