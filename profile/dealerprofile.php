<?php
include("../inc/checklogin.php");
 
//Permission check for the page
if($cookie_usertype <> "Dealer")
	header("Location:../home");


else
{
//Get the details of dealer who has logged in
$query = "SELECT * from lms_users join dealers on lms_users.referenceid = dealers.id WHERE lms_users.username = '".$cookie_username."'";
$result = runmysqlqueryfetch($query);
$dlrcompanyname = $result['dlrcompanyname'];
$dlrname = $result['dlrname'];
$dlraddress = $result['dlraddress'];
$dlrcell = $result['dlrcell'];
$dlrphone = $result['dlrphone'];
$dlremail = $result['dlremail'];
$dlrwebsite = $result['dlrwebsite'];
$state = $result['state'];
$district = $result['district'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Edit Profile</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/dealerprofile.js?dummy=<?php echo (rand());?>" language="javascript"></script>
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
        <td>Edit Profile</td>
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
                  <td><strong>Following details are allowed to update:</strong></td>
                </tr>
                <tr>
                  <td><form id="profileform" name="profileform" method="post" action="" >
                      <table width="100%" border="0" align="center" cellpadding="6" cellspacing="0">
                        <tr>
                          <td colspan="3" height="26">
                            <div align="center"><?php echo($message); ?> </div>                          </td>
                        </tr>
                        <tr>
                          <td>Company Name : </td>
                          <td colspan="2"><?php echo($dlrcompanyname); ?></td>
                        </tr>
                        <tr>
                          <td width="12%">Contact person : <font color="#FF0000">*</font></td>
                          <td colspan="2"><input name="form_name" type="text" class="formfields" id="form_name" size="130" maxlength="60" value="<?php echo($dlrname); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Address : <font color="#FF0000">*</font></td>
                          <td colspan="2"><input name="form_address" type="text" class="formfields" id="form_address" size="130" maxlength="200" value="<?php echo($dlraddress); ?>" /></td>
                        </tr>
                        <tr>
                          <td>District : </td>
                          <td colspan="2"><?php echo($district); ?></td>
                        </tr>
                        <tr>
                          <td>State : </td>
                          <td colspan="2"><?php echo($state); ?></td>
                        </tr>
                        <tr>
                          <td>Phone : <font color="#FF0000">*</font></td>
                          <td colspan="2"><input name="form_phone" type="text" class="formfields" id="form_phone" size="60" maxlength="30" value="<?php echo($dlrphone); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Cell : <font color="#FF0000">*</font> </td>
                          <td colspan="2"><input name="form_mobile" type="text" class="formfields" id="form_mobile" size="60" maxlength="10" value="<?php echo($dlrcell); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Email : </td>
                          <td colspan="2"><?php echo($dlremail); ?></td>
                        </tr>
                        <tr>
                          <td>Website : </td>
                          <td colspan="2"><input name="form_website" type="text" class="formfields" id="form_website" size="60" maxlength="50" value="<?php echo($dlrwebsite); ?>" /></td>
                        </tr>
                        <tr>
                         <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="62%" id="msg_box">&nbsp;</td>
    <td width="38%"><div align="center">
                             <input name="update" type="button" class="formbutton" id="update" value="Update" onclick="validate();" />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="reset" type="reset" class="formbutton" id="reset" value="Reset Form" />
                          </div></td>
  </tr>
</table>
</td>
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
<?php
}
?>