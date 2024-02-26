<?php
include("../inc/checklogin.php");

if( $cookie_usertype == "Admin" || $cookie_usertype  == "Sub Admin")
{
	$query7 = "select mappinginformationtoexcel from lms_users where username = '".$cookie_username."'";
	$result7 = runmysqlqueryfetch($query7);
	$mappinginformationtoexcel = $result7['mappinginformationtoexcel'];
	if($mappinginformationtoexcel == 'yes')
	{$disabled = "";}
	else
	{$disabled = "disabled='disabled'";}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Mapping Information</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/reportmappingingformation.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
</head>
<body onload="showdefault();">
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
          <td>Mapping Information</td>
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
          <td height="20" colspan="3"></td>
        </tr>
        <tr>
          <td colspan="3" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:1px solid #CCCCCC;">
              <tr>
                <td><form id="mappinginformationform" name="mappinginformationform" method="post" onsubmit="return false;" action = "">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                            <tr>
                              <td colspan="2"><strong>Filter data for report:</strong></td>
                            </tr>
                            <tr>
                              <td width="24%">Search Text:</td>
                              <td width="76%"><input type="text" name="companyname" id="companyname" size="30" maxlength="30" autocomplete = 'off' style="border:1px solid #CCCCCC"/></td>
                            </tr>
                            <tr>
                              <td colspan="2"><fieldset style="width:65%; border:1px solid #CCCCCC; ">
                                <legend>Look In</legend>
 
                                <label>
                                <input name="dealercompany" id = "dealercompany" type="radio" value="dealercompany" checked="checked"/>
                                &nbsp;
                                Dealer Company</label>
                                <label>&nbsp;
                                <input name="dealercompany" id="dealername" type="radio" value="dealername" />
                                Dealer Name</label>
                                </fieldset></td>
                            </tr>
                            <tr>
                              <td colspan="2"><fieldset style="width:65%;border:1px solid #CCCCCC;">
                                <legend>Order By</legend>
                                <label>
                                <input name="orderby" type="radio" value="region" checked="checked" />
                                Region </label>
                                <label>
                                <input type="radio" name="orderby" id="product" value="product" />
                                Product</label>
                                <label>
                                <input type="radio" name="orderby" id="dealer" value="dealer" />
                                Dealer</label>
                                </fieldset></td>
                            </tr>
                            <tr>
                              <td colspan="2"><fieldset style="width:65%;border:1px solid #CCCCCC;">
                                <legend>Generate</legend>
                                <label>
                                  <input name="generate" type="radio" value="all" checked="checked" />
                                  All </label>
                                <label>
                                  <input type="radio" name="generate" id="having" value="having" />
                                  Having records</label>
                                <label>
                                  <input type="radio" name="generate" id="missing" value="missing" />
                                  Missing records</label>
                              </fieldset></td>
                            </tr>
                            <tr>
                              <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                              <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                              <td colspan="2">&nbsp;</td>
                            </tr>
                          </table></td>
                        <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                            <tr>
                              <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                              <td width="31%"> Product Category: </td>
                              <td width="69%"><label>
                                <select name="category" id="category" style="width:50%;border:1px solid #CCCCCC">
                                  <option value="" selected="selected">- - - All  - - - </option>
                                  <option value="STO" >STO</option>
                                  <option value="SPP" >SPP</option>
                                  <option value="SAC" >SAC</option>
                                  <option value="OTHERS" >OTHERS</option>
                                </select>
                                </label></td>
                            </tr>
                            <tr>
                              <td width="31%">State :</td>
                              <td width="69%"><select name="form_state" id="form_state" class="formfields" onchange="districtselect()" style="width:50%;border:1px solid #CCCCCC">
                                  <option value=""> - - - ALL - - - </option>
                                  <?php include('../inc/state.php'); ?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td width="31%">District: </td>
                              <td width="69%"><div id="districtdiv">
                                  <select name="form_district" class="formfields" id="form_district" onchange="regionselect()" style="width:50%;border:1px solid #CCCCCC">
                                    <option value = "">- - - ALL - - -</option>
                                  </select>
                                </div></td>
                            </tr>
                            <tr>
                              <td>Region:</td>
                              <td><div id="regiondiv">
                                  <select name="form_region" class="formfields" id="form_region" style="width:50%;border:1px solid #CCCCCC">
                                    <option value = "">- - - ALL - - -</option>
                                  </select>
                                </div></td>
                            </tr>
                            <tr>
                              <td>Dealer Disabled:</td>
                              <td><select name="disabled" id="disabled" style="width:50%; border:1px solid #CCCCCC">
                                  <option value="" selected="selected">- - - ALL - - -</option>
                                  <option value="yes" >Yes</option>
                                  <option value="no" >No</option>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td align="right" colspan="2"><input type="button" name="view" id="view" value="View" onclick="filter('view');" class="formbutton" />
                                &nbsp;&nbsp;&nbsp;
                                <input type="button" name="toexcel" id="toexcel" value="To Excel" onclick="filter('toexcel');" class="formbutton" <?php echo $disabled;?>/>
                                &nbsp;&nbsp;&nbsp; </td>
                            </tr>
                          </table></td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="20" colspan="3"></td>
        </tr>
        <tr>
          <td width="21%"><strong>Mapping Infomation:<span id = "totalcount"></span></strong> </td>
          <td width="16%" align="left"><span id = "gridprocess"></span></td>
          <td width="63%"></td>
        </tr>
        <tr>
          <td height="20" colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #999999;">
              <tr>
                <td colspan="3" ><div id="tabgroupgridc1" style="overflow:auto; height:260px; width:945px" align="center">
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
else
{
	header("Location:../home");
}
?>