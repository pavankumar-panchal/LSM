<?php
include("../inc/checklogin.php");

//Permission check for the page
if($cookie_usertype <> "Sub Admin")
{
	header("Location:../home"); 
}
if($cookie_lastlogindate == "First Time")
{
	header('Location:../profile/first_time_login.php');
}

//Get the details of Logged in Sub Admin
$query = "SELECT lms_subadmins.id AS sadid, lms_subadmins.sadname AS sadname, lms_subadmins.sademailid AS sademail FROM lms_users join lms_subadmins on lms_users.referenceid = lms_subadmins.id WHERE username = '".$cookie_username."'";
$result = runmysqlqueryfetch($query);
$sadid = $result['sadid'];
$sadname = $result['sadname'];
$sademail = $result['sademail'];

//Get the lead stats for today
$today = datetimelocal("Y-m-d");
$query = "select * from leads WHERE substring(leads.leaddatetime,1,10) = '".$today."'";
$result = runmysqlquery($query);
$todayleadcount_total = mysqli_num_rows($result);
$query = "select * from leads JOIN regions on regions.subdistcode = leads.regionid WHERE substring(leads.leaddatetime,1,10) = '".$today."' AND regions.managedarea = 'CSD'";
$result = runmysqlquery($query);
$todayleadcount_csd = mysqli_num_rows($result);
$query = "select * from leads JOIN regions on regions.subdistcode = leads.regionid WHERE substring(leads.leaddatetime,1,10) = '".$today."' AND regions.managedarea = 'KKG'";
$result = runmysqlquery($query);
$todayleadcount_kkg = mysqli_num_rows($result);
$query = "select * from leads JOIN regions on regions.subdistcode = leads.regionid WHERE substring(leads.leaddatetime,1,10) = '".$today."' AND regions.managedarea = 'Bangalore'";
$result = runmysqlquery($query);
$todayleadcount_blr = mysqli_num_rows($result);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | SubAdmin Dashboard</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script type="text/javascript" src="../functions/jquery.js?dummy=<?php echo (rand());?>"></script>
<script type="text/javascript" src="../functions/highcharts-new.js?dummy=<?php echo (rand());?>"></script>
<script type="text/javascript" src="../functions/excanvas.compiled.js"></script>
</head>
<body onload="retrievedata()">
<div style="left: -1000px; top: 597px; visibility: hidden;" id="dhtmltooltip">dummy</div>
<script src="../functions/tooltip.js?dummy=<?php echo (rand());?>" language="javascript"></script>
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
        <td>Dashboard</td>
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
        <td height="300" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr>
            <td><strong><font style="font-size:16px" color="#0099CC">Welcome <?php echo($sadname); ?>!!</font></strong></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td width="75%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="100%" valign="top" style="border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                              <tr>
                                <td bgcolor="#0099CC"><strong><font color="#FFFFFF">Total Number of leads generated today!!</font></strong>                                                               </td>
                              </tr>
                              <tr>
                                <td height="20" valign="top" class="dashboard-box">Total Number of Leads: <?php echo($todayleadcount_total); ?><br />
                                  <font color="#FF0000">CSD Region: <?php echo($todayleadcount_csd); ?><br />
                                  </font><font color="#006600">KKG Region: <?php echo($todayleadcount_kkg); ?></font><br />
                                  <font color="#0000FF">Bangalore: <?php echo($todayleadcount_blr); ?></font><br />
                                  <br />
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td title="<?php echo(number_format(($todayleadcount_csd*100)/$todayleadcount_total)); ?>%" width="<?php echo(($todayleadcount_csd*100)/$todayleadcount_total); ?>%" height="20" bgcolor="#FF0000"></td>
                                      <td title="<?php echo(number_format(($todayleadcount_kkg*100)/$todayleadcount_total)); ?>%" width="<?php echo(($todayleadcount_kkg*100)/$todayleadcount_total); ?>%" bgcolor="#006600"></td>
                                      <td title="<?php echo(number_format(($todayleadcount_blr*100)/$todayleadcount_total)); ?>%" width="<?php echo(($todayleadcount_blr*100)/$todayleadcount_total); ?>%" bgcolor="#0000FF"></td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td colspan="3" height="22px">&nbsp;</td>
                                    </tr>
                                  </table></td>
                              </tr>
                             
                              <tr>
                                <td></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table></td>
                  </tr>
              
                </table></td>
                <td width="25%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="100%" valign="top" style="border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                          <td bgcolor="#0099CC"><strong><font color="#FFFFFF">User Profile</font></strong></td>
                        </tr>
                        <tr>
                          <td valign="top" class="dashboard-box"><strong><font color="#0099CC"><?php echo($sadname); ?></font></strong><br />
							Email: <?php echo($sademail); ?><br />                            </td>
                        </tr>
                        <tr>
                          <td bgcolor="#0099CC"><strong><font color="#FFFFFF">Additional Links:</font></strong> </td>
                        </tr>
                        <tr>
                          <td><div>
                            <ul>
                              <li><a href="./dlrdownloads.php" onmouseover="ddrivetip('You can download Relyon setups, updates, templates and marketing materials from here.','cornsilk',300);" onmouseout="hideddrivetip();">Advanced Downloads</a></li>
                              <li><a href="http://imax.relyonsoft.net/dealer/" target="_blank" onmouseover="ddrivetip('Buy Relyon Scratch Cards - Online','cornsilk',300);" onmouseout="hideddrivetip();">Scratch Card Download</a> [<a href="scm-procedure.pdf" target="_blank">?</a>]</li>
                              <li><a title="Relyon Product Registration" href="#" onclick="window.open('http://imax.relyonsoft.com/register/','','width=765,height=500,top=100,left=100')" onmouseover="ddrivetip('Product can be registered to customers from here.','cornsilk',300);" onmouseout="hideddrivetip();">Product Registration</a></li>
                              <li><a href="http://billing.relyonsoft.com" target="_blank" onmouseover="ddrivetip('You can rise Relyon Bill directly to end user.','cornsilk',300);" onmouseout="hideddrivetip();">Online Billing Application</a></li>
                              <li><a href="http://www.relyonsoft.com/webmail" target="_blank" onmouseover="ddrivetip('Login to Relyon Webmail account directly from here. [If you have not configured to Email Client].','cornsilk',300);" onmouseout="hideddrivetip();">Relyon Emails</a></li>
                              <li><a href="http://forum.relyonsoft.com" target="_blank" onmouseover="ddrivetip('Open discussion forum of Relyon, where anyone can come and put their views/discuss about Relyon Products.','cornsilk',300);" onmouseout="hideddrivetip();">Relyon Discussion Forum</a></li>
                              <li><a href="http://www.payroll-india.com/download/RelyonContacts.htm" target="_blank" onmouseover="ddrivetip('Contact details of Relyon members for specific subjects.','cornsilk',300);" onmouseout="hideddrivetip();">Relyon Member Contacts</a></li>
                              <li><a href="http://dealers.relyonsoft.com/home/Price-List-2012.pdf" target="_blank" onmouseover="ddrivetip('List of Product Prices.','cornsilk',300);" onmouseout="hideddrivetip();">Relyon Product Price list</a></li>
                                  <li><a href="http://relyonsoft.info/csd_prj/" target="_blank" onmouseover="ddrivetip('Manage Your Tasks.','cornsilk',300);" onmouseout="hideddrivetip();">Task Management</a></li>
                            </ul>
                          </div></td>
                        </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td valign="top" align="center"><table width="99%" border="0" cellspacing="0" cellpadding="4" style="border:solid 1px #999999">
            <tr><td colspan="9"><div align="center" style=" color:#0099CC; font-size:16px"><strong>Lead Volume Analysis</strong></div></td></tr>
            <tr><td colspan="9">&nbsp;</td></tr>
              <tr>
                <td width="6%"><div align="right">Period : </div></td>
                <td width="13%"><select name="period" id="period" class ="formfields" style="width:90%">
                    <option value="thismonth" selected="selected">This Month</option>
                    <option value="lastmonth">Last Month</option>
                    <option value="thisfinancialyear">This Year</option>
                    <option value="lastfinancialyear">Last Year</option>
                  </select>
                </td>
                <td width="7%"><div align="right">Source :</div></td>
                <td width="18%">
                  <select name="source" id="source" class ="formfields" style="width:90%">
                    <option value="" selected="selected">- - - All - - -</option>
                    <option value="Product Download">Web Downloads</option>
                    <option value="Manual Upload">Manual Uploads</option>
                  </select>
             </td>
                <td width="5%"><div align="right">Area :</div></td>
                <td width="12%"><select name="area" id="area" class ="formfields" style="width:90%">
                    <option value="" selected="selected">- - All - - </option>
                    <option value="CSD" >CSD</option>
                    <option value="BKG" >BKG</option>
                    <option value="BKM">BKM</option>
                </select></td>
                <td width="12%"><div align="right">Product Group :</div></td>
                <td width="15%"><label>
                  <select name="productgroup" id="productgroup" class ="formfields" style="width:90%">
                    <option value="" selected="selected"> - - All - - </option>
                    <option value="SPP" >SPP</option>
                    <option value="STO" >STO</option>
                    <option value="TDS" >TDS</option>
                    <option value="OTHERS" >OTHERS</option>
                  </select>
                </label></td>
                <td width="12%" ><input name="gobutton" type="button" class= "formbutton" id="gobutton" value="Go &raquo;" onclick="retrievedata()"/>
                        <input name="hiddenvalue" type="hidden" value="" id="hiddenvalue"/>
                  </strong>
                        <input type="hidden" name="alldates" id="alldates" />
                        <input type="hidden" name="yaxisscale" id="yaxisscale" />
                        <input type="hidden" name="xaxisscale" id="xaxisscale" />
               </td>
              </tr>
            
              
              <tr>
                <td  colspan="9"><div id="container" style="width: 800px; height: 300px; margin: 0 auto">
            <script type="text/javascript" src="../functions/highchartsubadmin.js?dummy=<?php echo (rand());?>"></script>
            </div></td>
              </tr>
            </table></td>
          </tr>
        </table>
          </td>
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
