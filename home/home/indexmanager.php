<?php
include("../inc/checklogin.php");

//Permission check for the page
if($cookie_usertype <> "Reporting Authority" )
{
	header("Location:../home");
}
if($cookie_lastlogindate == "First Time")
{
	header('Location:../profile/first_time_login.php');
}

//Get the details of Logged in Manager
$query = "SELECT lms_managers.id AS mgrid, lms_managers.mgrname AS mgrname, lms_managers.mgrlocation AS mgrlocation, lms_managers.mgrcell AS mgrcell, lms_managers.mgremailid AS mgremail FROM lms_users join lms_managers on lms_users.referenceid = lms_managers.id WHERE username = '".$cookie_username."'";
$result = runmysqlqueryfetch($query);
$mgrid = $result['mgrid'];
$mgrname = $result['mgrname'];
$mgrlocation = $result['mgrlocation'];
$mgrcell = $result['mgrcell'];
$mgremail = $result['mgremail'];

//Get the mapping data
$query = "select dealers.dlrcompanyname AS dlrcompanyname, regions.statename AS state, regions.distname AS district, regions.subdistname AS region, mapping.prdcategory AS prdcategory from mapping JOIN regions on regions.subdistcode = mapping.regionid join dealers on dealers.id = mapping.dealerid WHERE dealers.managerid = '".$mgrid."' ORDER BY regions.statename, regions.distname, regions.subdistname";
$result = runmysqlquery($query);
$mappinglist = "";
$regioncount = 0;
$mappingresultcount = mysqli_num_rows($result);
if($mappingresultcount == 0)
	$mappinglist = "<div align='center'>There is no Mapping available for you.</div>";
else
{
	$mappinglist = 'There are total of '.$mappingresultcount.' regions/products mapped under you.[<a style="cursor:pointer" onclick="tog(\'managermappingtable\');">Show/Hide</a>] <br /><div id="managermappingtable" style="display:NONE;"><table width="100%" border="1" cellspacing="0" cellpadding="2"><tbody>';
	//Write the header Row of the table
	$mappinglist .= '<tr class="gridheader"><td nowrap="nowrap">Sl No</td><td nowrap="nowrap">State</td><td nowrap="nowrap">District</td><td nowrap="nowrap">Region</td><td nowrap="nowrap">Product Category</td><td nowrap="nowrap">Dealer Name</td></tr>';
	while($fetch = mysqli_fetch_array($result))
	{
		$regioncount++;
		//Begin a row
		$mappinglist .= '<tr>';
		//Write the cell data
		$mappinglist .= "<td nowrap='nowrap'>".$regioncount."</td>"."<td nowrap='nowrap'>".$fetch['state']."</td>"."<td nowrap='nowrap'>".$fetch['district']."</td>"."<td nowrap='nowrap'>".$fetch['region']."</td>"."<td nowrap='nowrap'>".$fetch['prdcategory']."</td>"."<td nowrap='nowrap'>".$fetch['dlrcompanyname']."</td>";
		//End the Row
		$mappinglist .= '</tr>';
	}
	//End of Table
	$mappinglist .= '</tbody></table></div><br /> *Product Category: STO = Saral TaxOffice, SPP = Saral PayPack, OTHERS = Other Products of Relyon.';
}

//List of attentions for the dealer
$attentionlist = "";
$query = "select * from leads join dealers on dealers.id = leads.dealerid where managerid = '".$mgrid."' AND dealerviewdate IS null";
$result = runmysqlquery($query);
$count = mysqli_num_rows($result);
$bullet = '<img src="../images/bullet.gif" width="10" height="10" /> ';
if($count > 0)
$attentionlist = $bullet."There are ".$count." leads not viewed by Dealers (assigend to you). Visit <a href='../manageleads/simplelead.php'>UPDATE LEADS</a> and verify the leads accordingly.";

if($attentionlist == "")
$attentionlist = "There are no attentions available at this point of time.";

//Get the Assigned dealers data
$query = "select * FROM dealers WHERE dealers.managerid = '".$mgrid."' ORDER BY dlrcompanyname";
$result = runmysqlquery($query);
$dealercount = 0;
$assigneddealerlist = "";
$dealerresultcount = mysqli_num_rows($result);
if($dealerresultcount == 0)
	$assigneddealerlist = "<div align='center'>There are no dealers assigned to you.</div>";
else
{
	$assigneddealerlist = 'There are total of '.$dealerresultcount.' dealers assigned to you. [<a style="cursor:pointer" onclick="tog(\'assigneddealertable\');">Show/Hide</a>] <br /><div id="assigneddealertable" style="display:NONE;"><ol>';
	while($fetch = mysqli_fetch_array($result))
	{
		$dealercount++;

		$assigneddealerlist .= "<li>";
		if($dealercount % 2 <> 0)
		$assigneddealerlist .= "<font color='#FF6600'>";
		else
		$assigneddealerlist .= "<font color='#0099CC'>";
		$assigneddealerlist .= $fetch['dlrname'].", ".$fetch['dlrcompanyname'].", ".$fetch['district'].", ".$fetch['state'].", Cell: ".$fetch['dlrcell'].", Phone: ".$fetch['dlrphone'].", Email ID: ".$fetch['dlremail'];
		$assigneddealerlist .= "</font>";
		$assigneddealerlist .= "</li>";
	}
	$assigneddealerlist .= '</ol></div>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Manager Dashboard</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script type="text/javascript" src="../functions/jquery.js?dummy=<?php echo (rand());?>"></script>
<script type="text/javascript" src="../functions/highcharts-new.js?dummy=<?php echo (rand());?>"></script>
<script type="text/javascript" src="../functions/excanvas.compiled.js"></script>
<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
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
            <td><strong><font style="font-size:16px" color="#0099CC">Welcome <?php echo($mgrname); ?>!!</font></strong></td>
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
                              <td bgcolor="#0099CC"><strong><font color="#FFFFFF">Attention!!</font></strong></td>
                            </tr>
                            <tr>
                              <td valign="top" class="dashboard-box"><?php echo($attentionlist); ?> <br />                              </td>
                            </tr>
                        </table></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="100%" valign="top" style="border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                              <tr>
                                <td bgcolor="#0099CC"><strong><font color="#FFFFFF">Dealers assigned to <strong><?php echo($mgrname); ?></strong></font></strong></td>
                              </tr>
                              <tr>
                                <td height="20" valign="top" class="dashboard-box"><?php echo($assigneddealerlist); ?><br />
                                </td>
                              </tr>
                          </table></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="100%" valign="top" style="border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                            <tr>
                              <td bgcolor="#0099CC"><strong><font color="#FFFFFF">Regions Mapped under <strong><?php echo($mgrname); ?></strong></font></strong></td>
                            </tr>
                            <tr>
                              <td height="20" valign="top" class="dashboard-box"><?php echo($mappinglist); ?><br />                              </td>
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
                          <td valign="top" class="dashboard-box"><strong><font color="#0099CC"><?php echo($mgrname); ?></font></strong><br />
							<?php echo($mgrlocation); ?><br />
							Cell No: <?php echo($mgrcell); ?><br />
							Email: <?php echo($mgremail); ?><br />                            </td>
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
            <td><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:2px solid #CCCCCC">
      <tr>
          <td height="20" colspan="9"><div align="center" style=" color:#0099CC; font-size:16px"><strong>Lead Volume Analysis </strong>(under your account)</div></td>
        </tr>
         <tr>
          <td height="20" colspan="9"></td></tr>
        <tr>
                <td width="6%"><div align="right">Period : </div></td>
                <td width="13%"><select name="period" id="period" class ="formfields" >
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
          <td colspan="9"><div id="container" style="width: 800px; height: 300px; margin: 0 auto">
              <script type="text/javascript" src="../functions/highchartmanager.js?dummy=<?php echo (rand());?>"></script>
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
