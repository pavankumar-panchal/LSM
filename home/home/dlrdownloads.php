<?php
include("../inc/checklogin.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Relyon Advanced Download Section</title>
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
        <td>Relyon Advanced Download Section</td>
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
            <td style="border:#666666 1px solid"><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#666666">
              <tr>
                <td valign="top" bgcolor="#006699"><font color="#FFFFFF">&nbsp;</font></td>
                <td valign="top" bgcolor="#006699"><div align="center"><font color="#FFFFFF"><strong>Setups</strong></font></div></td>
                <td valign="top" bgcolor="#006699"><div align="center"><font color="#FFFFFF"><strong>Utilities</strong></font></div></td>
                <td valign="top" bgcolor="#006699"><div align="center"><font color="#FFFFFF"><strong>Documents</strong></font></div></td>
                <td valign="top" bgcolor="#006699"><div align="center"><font color="#FFFFFF"><strong>Marketing Contents</strong></font></div></td>
              </tr>
              <tr>
                <td width="20%" height="39" valign="top" bgcolor="#F7D0CE"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                  <tr>
                    <td bgcolor="#FFFFFF"><img src="../images/spp.gif" alt="Saral PayPack" width="175" height="42" /></td>
                  </tr>
                </table>                  </td>
                <td width="20%" valign="top" bgcolor="#F7D0CE"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Saral PayPack' AND categoryh = 'Setups'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
                <td width="20%" valign="top" bgcolor="#F7D0CE"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Saral PayPack' AND categoryh = 'Utilities'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
                <td width="20%" valign="top" bgcolor="#F7D0CE"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Saral PayPack' AND categoryh = 'Documents'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
                <td width="20%" valign="top" bgcolor="#F7D0CE"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Saral PayPack' AND categoryh = 'Others'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
              </tr>
              <tr>
                <td height="55" valign="top" bgcolor="#B5C7F0"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                    <tr>
                      <td bgcolor="#FFFFFF"><img src="../images/stologo.gif" alt="Saral TaxOffice" width="175" height="62" /></td>
                    </tr>
                  </table></td>
                <td valign="top" bgcolor="#B5C7F0">
                <?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Saral TaxOffice' AND categoryh = 'Setups'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?>
                </td>
                <td valign="top" bgcolor="#B5C7F0"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Saral TaxOffice' AND categoryh = 'Utilities'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
                <td valign="top" bgcolor="#B5C7F0"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Saral TaxOffice' AND categoryh = 'Documents'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
                <td valign="top" bgcolor="#B5C7F0"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Saral TaxOffice' AND categoryh = 'Others'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
              </tr>
              <tr>
                <td height="45" valign="top" bgcolor="#B9FFCF"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                    <tr>
                      <td bgcolor="#FFFFFF"><img src="../images/tds-logo.png" alt="Saral TDS" width="175" height="48" /></td>
                    </tr>
                  </table></td>
                <td valign="top" bgcolor="#B9FFCF"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Saral TDS' AND categoryh = 'Setups'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
                <td valign="top" bgcolor="#B9FFCF"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Saral TDS' AND categoryh = 'Utilities'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
                <td valign="top" bgcolor="#B9FFCF"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Saral TDS' AND categoryh = 'Documents'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
                <td valign="top" bgcolor="#B9FFCF"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Saral TDS' AND categoryh = 'Others'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
              </tr>
              <tr>
                <td height="55" valign="top" bgcolor="#FFFF97"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                    <tr>
                      <td><strong>Upcoming Releases</strong></td>
                    </tr>
                  </table></td>
                <td valign="top" bgcolor="#FFFF97"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Upcoming Releases' AND categoryh = 'Setups'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
                <td valign="top" bgcolor="#FFFF97"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Upcoming Releases' AND categoryh = 'Utilities'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
                <td valign="top" bgcolor="#FFFF97"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Upcoming Releases' AND categoryh = 'Documents'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
                <td valign="top" bgcolor="#FFFF97"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Upcoming Releases' AND categoryh = 'Others'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
              </tr>
              <tr>
                <td height="61" valign="top" bgcolor="#CCCCCC"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                    <tr>
                      <td><strong>Useful Links</strong></td>
                    </tr>
                  </table></td>
                <td valign="top" bgcolor="#CCCCCC"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Usefull Links' AND categoryh = 'Setups'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
                <td valign="top" bgcolor="#CCCCCC"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Usefull Links' AND categoryh = 'Utilities'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
                <td valign="top" bgcolor="#CCCCCC"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Usefull Links' AND categoryh = 'Documents'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
                <td valign="top" bgcolor="#CCCCCC"><?php
				$query = "";
				$query = "SELECT id, name, description, fullurl, categoryv, categoryh FROM lms_dlrdownloads WHERE categoryv = 'Usefull Links' AND categoryh = 'Others'";
				$result = runmysqlquery($query);
				$resultcount = mysqli_num_rows($result);
				if($resultcount == 0)
				$output = "&nbsp;";
				else
				{
					$output = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$count = 0;
					while($fetch = mysqli_fetch_array($result))
					{
						$count++;
						$output .= "<tr>";
						$output .= '<td width="10%" valign="top">'.$count.'</td><td width="90%" valign="top"><a onMouseOver="ddrivetip(\''.$fetch['description'].'\',\'cornsilk\',300);" onMouseOut="hideddrivetip();" target="_blank" href="'.$fetch['fullurl'].'">'.$fetch['name'].'</a></td>';
						$output .= "</tr>";
					}
					$output .= "</table>";
				}
				echo($output);
				?></td>
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
