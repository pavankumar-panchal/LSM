<?php
include("../inc/checklogin.php");
//Permission check for the page
if($cookie_usertype <> "Sub Admin" && $cookie_usertype <> "Reporting Authority" && $cookie_usertype <> "Admin" && $cookie_usertype <> "Dealer")
	header("Location:../home");

//Select the list of dealers/managers for whom report can be generated.
switch($cookie_usertype)
{
	case "Admin":
	case "Sub Admin":
		$dealerselect = '<option value="" selected="selected">--- All ---</option>';
		$query = "SELECT id AS selectid, mgrname AS selectname FROM lms_managers ORDER BY mgrname";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$dealerselect .= '<option value="m'.$fetch['selectid'].'">'.$fetch['selectname'].' [M]</option>';
		}
		$query = "SELECT id AS selectid, dlrcompanyname AS selectname FROM dealers ORDER BY dlrcompanyname";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$dealerselect .= '<option value="d'.$fetch['selectid'].'">'.$fetch['selectname'].' [D]</option>';
		}
		break;
	case "Reporting Authority":
		$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '".$cookie_username."' ORDER BY dealers.dlrcompanyname";
		$result = runmysqlquery($query);
		$dealerselect = '';
		if(mysqli_num_rows($result) > 1)
		$dealerselect .= '<option value="" selected="selected">--- All ---</option>';
		while($fetch = mysqli_fetch_array($result))
		{
			$dealerselect .= '<option value="d'.$fetch['selectid'].'">'.$fetch['selectname'].' [D]</option>';
		}
		break;
	case "Dealer":
		$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.id WHERE lms_users.username = '".$cookie_username."' ORDER BY dealers.dlrcompanyname";
		$result = runmysqlquery($query);
		$dealerselect = '';
		if(mysqli_num_rows($result) > 1)
		$dealerselect .= '<option value="" selected="selected">--- All ---</option>';
		while($fetch = mysqli_fetch_array($result))
		{
			$dealerselect .= '<option value="d'.$fetch['selectid'].'">'.$fetch['selectname'].' [D]</option>';
		}
		break;
}

//Select the list of products for the drop-down
$query = "SELECT id,productname FROM products ORDER BY productname";
$result = runmysqlquery($query);
$productselect = '<option value="" selected="selected">--- All ---</option>';
while($fetch = mysqli_fetch_array($result))
{
	$productselect .= '<option value="'.$fetch['id'].'">'.$fetch['productname'].'</option>';
}


//Get current date for TO DATE field
$defaulttodate = datetimelocal("d-m-Y");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Lead Source Tracker</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/reportlst.js?dummy=<?php echo (rand());?>" language="javascript"></script>
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
        <td>Lead Source Tracker</td>
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
                  <td><strong>Select the data for Report:</strong></td>
                </tr>
                <tr>
                  <td><form id="filterform" name="filterform" onsubmit="return false;" autocomplete = "off" method="post" action="leadstoexcel.php">
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td width="11%">From Date :                            </td>
                          <td width="38%"><input name="fromdate" type="text" class="formfields" id="fromdate" size="25" maxlength="10" value="01-04-2008" /></td>
                          <td width="12%">To Date :                            </td>
                          <td width="39%"><input name="todate" type="text" class="formfields" id="todate" size="25" maxlength="10" value="<?php echo($defaulttodate); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Product Name :                                    </td>
                          <td width="38%"><select name="productid" class="formfields" id="productid">
                            <?php 
						echo($productselect);
						?>
                          </select></td>
                          <td width="12%">Dealer/Manager : </td>
                          <td width="39%"><select name="dealerid" class="formfields" id="dealerid">
                            <?php 
						echo($dealerselect);
						?>
                          </select></td>
                        </tr>
                          <tr>
                          <td colspan="2" id="msg_box">&nbsp;</td>
                          <td colspan="2"><div align="center">
                            <input name="view" type="button" class="formbutton" id="view" value="View" onclick="getdata();" />
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
                  <td><strong>Report:<span id="gridprocess"></span></strong></td>
                </tr>
                <tr>
                  <td><table width="100%" border="0" cellpadding="2" cellspacing="0">
                    <tr>
                      <td nowrap="nowrap" bgcolor="#C2D2E1" class="fourborder33">&nbsp;</td>
                      <td valign="top" bgcolor="#C2D2E1" class="fourborder33"><div align="center"><font color="#000000">Web Downloads</font></div></td>
                      <td valign="top" bgcolor="#C2D2E1" class="fourborder33"><div align="center"><font color="#000000">Manual Uploaded by Self</font></div></td>
                      <td valign="top" bgcolor="#C2D2E1" class="fourborder33"><div align="center"><font color="#000000">Manual Uploaded by Others</font></div></td>
                      </tr>
                    <tr>
                      <td nowrap="nowrap" class="fourborder33">Saral PayPack</td>
                      <td nowrap="nowrap" class="fourborder33"><span id="spp1"></span></td>
                      <td nowrap="nowrap" class="fourborder33"><span id="spp2"></span></td>
                      <td nowrap="nowrap" class="fourborder33"><span id="spp3"></span></td>
                      </tr>
                    <tr>
                      <td nowrap="nowrap" class="fourborder33">Saral TaxOffice</td>
                      <td nowrap="nowrap" class="fourborder33"><span id="sto1"></span></td>
                      <td nowrap="nowrap" class="fourborder33"><span id="sto2"></span></td>
                      <td nowrap="nowrap" class="fourborder33"><span id="sto3"></span></td>
                      </tr>
                    <tr>
                      <td nowrap="nowrap" class="fourborder33">SaralTDS/ Others</td>
                      <td nowrap="nowrap" class="fourborder33"><span id="others1"></span></td>
                      <td nowrap="nowrap" class="fourborder33"><span id="others2"></span></td>
                      <td nowrap="nowrap" class="fourborder33"><span id="others3"></span></td>
                      </tr>
                    <tr>
                      <td nowrap="nowrap" bgcolor="#E6E6E6" class="fourborder33">Total</td>
                      <td nowrap="nowrap" bgcolor="#E6E6E6" class="fourborder33"><span id="total1"></span></td>
                      <td nowrap="nowrap" bgcolor="#E6E6E6" class="fourborder33"><span id="total2"></span></td>
                      <td nowrap="nowrap" bgcolor="#E6E6E6" class="fourborder33"><span id="total3"></span></td>
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
