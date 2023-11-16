<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include("../inc/checklogin.php");

//Permission check for the page
if($cookie_usertype <> "Dealer Member")
	header("Location:../home");

//Get the details of Logged in Dealer member
$query = "SELECT lms_dlrmembers.dlrmbrid AS dlrmbrid, lms_dlrmembers.dlrmbrname AS dlrmbrname, lms_dlrmembers.dlrmbrremarks AS dlrmbrremarks, lms_dlrmembers.dlrmbrcell AS dlrmbrcell, lms_dlrmembers.dlrmbremailid AS dlrmbremail, lms_dlrmembers.dealerid AS dealerid FROM lms_users join lms_dlrmembers on lms_users.referenceid = lms_dlrmembers.dlrmbrid WHERE username = '".$cookie_username."'";
$result = runmysqlqueryfetch($query);
$dlrmbrid = $result['dlrmbrid'];
$dlrmbrname = $result['dlrmbrname'];
$dlrmbrremarks = $result['dlrmbrremarks'];
$dlrmbrcell = $result['dlrmbrcell'];
$dlrmbremail = $result['dlrmbremail'];
$dlrid = $result['dealerid'];

//Get the details of Dealer to whom this dealer member belongs to.
$query = "SELECT dealers.id AS dlrid, dealers.dlrcompanyname AS dlrcompanyname, dealers.dlrname AS dlrname, dealers.dlraddress AS dlraddress, dealers.district AS dlrdistrict, dealers.state AS dlrstate, dealers.dlrcell AS dlrcell, dealers.dlrphone AS dlrphone, dealers.dlremail AS dlremail, dealers.dlrwebsite AS dlrwebsite FROM dealers WHERE dealers.id = '".$dlrid."'";
$result = runmysqlqueryfetch($query);
$dlrcompanyname = $result['dlrcompanyname'];
$dlrname = $result['dlrname'];
$dlraddress = $result['dlraddress'];
$dlrdistrict = $result['dlrdistrict'];
$dlrstate = $result['dlrstate'];
$dlrcell = $result['dlrcell'];
$dlrphone = $result['dlrphone'];
$dlremail = $result['dlremail'];
$dlrwebsite = $result['dlrwebsite'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Dealer Member Dashboard</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
</head>
<body>
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
            <td><strong><font style="font-size:16px" color="#0099CC">Welcome <?php echo($dlrmbrname); ?>!!</font></strong></td>
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
                                <td bgcolor="#0099CC"><strong><font color="#FFFFFF">Once more welcoming you...</font></strong></td>
                              </tr>
                              <tr>
                                <td height="20" valign="top" class="dashboard-box"><p>This area will allow you to get the leads assigned to you by  <font color="#0099CC"><strong><?php echo($dlrcompanyname); ?></strong></font>.</p>
                                  <p>New: Update the leads assigned to you from here &gt;&gt; <a href='../manageleads/simplelead.php'>UPDATE LEADS</a>. </p>
                                  <p>You can also change your password with the above option.</p></td>
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
                          <td valign="top" class="dashboard-box"><strong><font color="#0099CC"><?php echo($dlrmbrname); ?></font></strong><br />
                            Cell No: <?php echo($dlrmbrcell); ?><br />
                            Email: <?php echo($dlrmbremail); ?>
                            <hr width="100%" size="1" />
                            <strong>Dealer details:</strong><br />
                            <?php echo($dlrname); ?><br />
                            <font color="#0099CC"><strong><?php echo($dlrcompanyname); ?></strong></font><br />
                            <?php echo(ucwords(strtolower($dlraddress))); ?><br />
                            <font color="#0099CC"><strong><?php echo($dlrdistrict); ?>, <?php echo($dlrstate); ?></strong></font><br />
Cell: <?php echo($dlrcell); ?><br />
Phone: <?php echo($dlrphone); ?><br />
Email: <?php echo($dlremail); ?></td>
                        </tr>
                        <tr>
                          <td bgcolor="#0099CC"><strong><font color="#FFFFFF">Additional Links:</font></strong> </td>
                        </tr>
                        <tr>
                          <td><div>
                            <ul>
                              <li><a href="./dlrdownloads.php" onmouseover="ddrivetip('You can download Relyon setups, updates, templates and marketing materials from here.','cornsilk',300);" onmouseout="hideddrivetip();">Advanced Downloads</a></li>
                              <li><a href="http://imax.relyonsoft.com/dealer/" target="_blank" onmouseover="ddrivetip('Buy Relyon Scratch Cards - Online','cornsilk',300);" onmouseout="hideddrivetip();">Scratch Card Download</a> [<a href="scm-procedure.pdf" target="_blank">?</a>]</li>
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
            <td>&nbsp;</td>
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
