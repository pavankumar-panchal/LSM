<?
include("../inc/checklogin.php");

//Permission check for the page
if($cookie_usertype <> "Admin")
	header("Location:../home");

	$form_recid = $_GET['mgrid'];
	$query = "SELECT * FROM lms_users  WHERE referenceid = '".$form_recid."' and type = 'Reporting Authority'";
	$result1 = runmysqlqueryfetch($query);
	$query = "SELECT * FROM lms_managers WHERE id = '".$form_recid."'";
	$result2 = runmysqlqueryfetch($query);
	$output = $result2['id']."^".$result2['mgrname']."^".$result2['mgrlocation']."^".$result2['mgremailid']."^".$result2['mgrcell']."^".$result1['username']."^".$result1['password']."^".$result2['transferuploadedleads']."^".$result1['disablelogin']."^".$result2['managedarea']."^".$result2['branch']."^".$result2['branchhead']."^".$result2['showmcacompanies'];
		#echo('1^'.$output);
	$id = $result2['id'];
	$name=$result2['mgrname'];
	$location=$result2['mgrlocation'];
	$email=$result2['mgremailid'];
	$cell=$result2['mgrcell'];
	$user=$result1['username'];
	$pass=$result1['password'];
	$uploaded=$result2['transferuploadedleads'];
	if ($uploaded==1){$upload="checked";}else{$upload="";}
	$dislogin=$result1['disablelogin'];
	if ($dislogin=='yes'){$dis="checked";}else{$dis="";}
	$area=$result2['managedarea'];
	$branch=$result2['branch'];
	$branchhead=$result2['branchhead'];
	if ($branchhead=='yes'){$bh="checked";}else{$bh="";}
	$showca=$result2['showmcacompanies'];
	if ($showca=='yes'){$show="checked";}else{$show="";}
	#echo $output;
		setcookie('mapmgrid',$form_recid,time()+3600000, "/");
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Manager Master</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<? echo (rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<? echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<? echo (rand());?>" language="javascript"></script>
<script src="../functions/managermaster.js?dummy=<? echo (rand());?>" language="javascript"></script>
<script>
function dislogin()
{
	if($("#form_disablelogin:checked").val() == 'on')
	alert("Kindly Transfer the region mapping!!");
}
</script>
<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
</head>
<body onload="griddata('');">
<table width="950" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="pageheader"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="28"><? include("../inc/header1.php"); ?></td>
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
                <td width="86%"><? include("../inc/navigation.php"); ?></td>
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
        <td>Reporting Authority Master</td>
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
                  <td><strong>Add / Update a Reporting Authority:</strong></td>
                </tr>
                <tr>
                  <td><form id="managerform" name="managerform" method="post" action="">
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td width="93">Name:<input name="form_recid" type="hidden" class="formfields" id="form_recid" value="<? echo ($id); ?>" /></td>
                          <td width="330"><input name="form_name" type="text" class="formfields" id="form_name" value="<? echo ($name); ?>" size="50" maxlength="50" /></td>
                          <td width="100">Location:</td>
                          <td width="369"><input name="form_location" type="text" class="formfields" id="form_location" value="<? echo ($location); ?>" size="50" maxlength="50" /></td>
                        </tr>

                        <tr>
                          <td>Email ID:</td>
                          <td><input name="form_email" type="text" class="formfields" id="form_email" value="<? echo ($email); ?>" size="50" maxlength="50" /></td>
                          <td>Cell:</td>
                          <td><input name="form_cell" type="text" class="formfields" id="form_cell" value="<? echo ($cell); ?>" size="50" maxlength="50" /></td>
                        </tr>

                        <tr>
                          <td>Username:</td>
                          <td><input name="form_username" type="text" class="formfields" id="form_username" value="<? echo ($user); ?>" size="50" maxlength="50" /></td>
                          <td>Password :</td>
                          <td><input name="form_password" type="password" class="formfields" id="form_password" value="<? echo ($pass); ?>" size="50" maxlength="50" /></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td><input type="checkbox" name="branchhead" id="branchhead" <? echo ($bh); ?> />
                            <label for="branchhead">Branch Head</label></td>
                          <td>Branch:</td>
                          <td><select name="form_branch" class="formfields" id="form_branch" style="width:200px;">
                                                                          
					   <?    $query = "SELECT slno,branchname FROM lms_branch ORDER BY branchname"; 
							$select = mysqli_query($query);  
							
							while($row = mysqli_fetch_array($select))
							{
								if ($branch == $row['slno'])
								{
									echo "<option selected='selected' value=". $row['slno']. ">"; 
									echo $row['branchname']; 
									echo "</option>"; 
								}
								/*else 
								{
									echo "<option value=". $row['slno']. ">"; 
									echo $row['branchname']; 
									echo "</option>"; 
								}*/
							}					
					 ?>
                                           <option value="">Select A Branch</option>
											<? include('../inc/branchlist.php');?>
                                                                        </select></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td><input type="checkbox" name="transferuploadedleads" id="transferuploadedleads" <? echo ($upload);?> />
                            <label for="transferuploadedleads">Permission to Transfer Leads uploaded by dealer.</label></td>
                          <td>Managed Area :</td>
                          <td><select name="form_managedarea" id="form_managedarea" class="formfields" >
                          <? echo ('<option value="'.$area.'">'.$area.'</option>'); ?>
                            <option value="">- - Select - -</option>
                            <option value="BKG">BKG</option>
                            <option value="BKM">BKM</option>
                            <option value="CSD">CSD</option>
                          </select></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td><label>
                            <input type="checkbox" name="form_disablelogin" id="form_disablelogin" <? echo ($dis);?> onclick="dislogin();" />
                            Disable Login
                            </label></td>
                          <td>&nbsp;</td>
                          <td><font color="#FBFCFF"><span id="hiddenpwd"></span></font></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td><label for="showmcacompanies">
                            <input type="checkbox" name="showmcacompanies" id="showmcacompanies" <? echo ($show);?>/>
                            Show MCA - Companies</label></td>
                          <td>&nbsp;</td>
                          <td><font color="#FBFCFF"><span id="hiddenpwd"></span></font></td>
                        </tr>
                        <tr>
                          <td colspan="3" id="msg_box">&nbsp;</td>
                          <td><div align="center">
                              <input name="new" type="button" class="formbutton" id="new" value="New" onclick="newentry();" />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="save" type="button" class="formbutton" id="save" value="Save" onclick="formsubmit('save');" />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="delete" type="button" class="formbutton" id="delete" value="Delete" onclick="formsubmit('delete');" />
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
                  <td width="26%"><strong>Reporting Authority Register:<span id="gridprocess"></span></strong></td>
                  <td width="48%"><div align="center"><span id="totalcount"></span></div></td>
                  <td width="26%">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="3" style="border:1px solid #333333;"><div id="tabgroupgridc1" style="overflow:auto; width:935px; height:250px;  padding:2px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="getmorelink"  align="left" style="height:20px; padding:2px;"> </div></td>
                                        </tr>
                                      </table></div><div id="resultgrid" style="overflow:auto; display:none; height:150px; width:690px; padding:2px;" align="center">&nbsp;</div></td>
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
