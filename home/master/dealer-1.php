<?php
include("../inc/checklogin.php");

//Permission check for the page
if($cookie_usertype <> "Admin")
	header("Location:../home");

	$form_recid = $_GET['dlrid'];

	$query = "SELECT * FROM dealers WHERE id = '".$form_recid."'";
	$result1 = runmysqlqueryfetch($query);
	$query = "SELECT * FROM lms_users WHERE referenceid = '".$form_recid."' and type = 'Dealer'";
	$result2 = runmysqlqueryfetch($query);
	$output = $result1['id']."^".$result1['stateid']."^".$result1['districtid']."^".$result1['dlrname']."^".$result1['dlrcompanyname']."^".$result1['dlraddress']."^".$result1['dlrcell']."^".$result1['dlrphone']."^".$result1['dlremail']."^".$result1['dlrwebsite']."^".$result1['managerid']."^".$result2['username']."^".$result2['password']."^".$result2['disablelogin']."^".$result2['relyonexecutive']."^".$result1['branch']."^".$result1['showmcacompanies'];
		#echo('1^'.$output);
	$id = $result1['id'];
	$stateid = $result1['stateid'];
	$disid = $result1['districtid'];
	$name = $result1['dlrname'];
	$company = $result1['dlrcompanyname'];
	$address = $result1['dlraddress'];
	$cell = $result1['dlrcell'];
	$phone = $result1['dlrphone'];
	$email = $result1['dlremail'];
	$website = $result1['dlrwebsite'];
	$managerid = $result1['managerid'];
	#echo "This is managerid ".$managerid;
	$user = $result2['username'];
	$pass = $result2['password'];
	$dislogin = $result2['disablelogin'];
	if ($dislogin=='yes'){$dis="checked";}else{$dis="";}
	$relyonexe = $result2['relyonexecutive'];
	if ($relyonexe=='yes'){$re="checked";}else{$re="";}
	$branch = $result1['branch'];
	$showca = $result1['showmcacompanies'];
	if ($showca=='yes'){$show="checked";}else{$show="";}

$query = "SELECT * FROM lms_managers where mgrname <> '' ORDER BY mgrname";
$result = runmysqlquery($query);
$managerselect = '<option value="" selected="selected">- - -Make a Selection- - -</option>';
while($fetch = mysqli_fetch_array($result))
{
	if ($managerid == $fetch['id'])
	{
		$managerselect .= '<option selected="selected" value="'.$fetch['id'].'">'.$fetch['mgrname'].'</option>'; 
		
	}
	else
	{
		$managerselect .= '<option value="'.$fetch['id'].'">'.$fetch['mgrname'].'</option>';
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Dealer Master</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/dealermaster.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
<script>
function dislogin()
{
	if($("#form_disablelogin:checked").val() == 'on')
	alert("Kindly Transfer the state mapping!!");
}
</script>
</head>
<body onload="griddata('');">
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
        <td>Dealer Master</td>
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
                <td><strong>Add / Update a Dealer:</strong></td>
              </tr>
              <tr>
                <td><form id="dealerform" name="dealerform" method="post" action="">
                  <table width="100%" border="0" cellspacing="0" cellpadding="6">
                    <tr>
                      <td width="147">Company Name:
                        <input name="form_recid" type="hidden" class="formfields" id="form_recid" value="<?php echo ($id); ?>" /></td>
                      <td width="302"><input name="form_companyname" type="text" class="formfields" id="form_companyname" value="<?php echo ($company); ?>" size="50" maxlength="50" /></td>
                      <td width="69">Contact person:</td>
                      <td width="374"><input name="form_name" type="text" class="formfields" id="form_name" value="<?php echo ($name); ?>" size="50" maxlength="50" /></td>
                    </tr>
                    <tr>
                      <td>Address:</td>
                      <td colspan="3"><input name="form_address" type="text" class="formfields" id="form_address" value="<?php echo ($address); ?>" size="126" maxlength="200" /></td>
                      </tr>
                    <tr>
                      <td>State:</td>
                      <td><select name="form_state" class="formfields" id="form_state" onchange="districtselect()" style="width:180px"><?php
                        $query = "select distinct statecode,statename from regions order by statename"; 
							$select = mysqli_query($query);  
							
							while($row = mysqli_fetch_array($select))
							{
								if ($stateid == $row['statecode'])
								{
									echo "<option selected='selected' value=". $row['statecode']. ">"; 
									echo $row['statename']; 
									echo "</option>"; 
								}
								
							}	
						 include('../inc/state.php')
						?>
                      </select></td>
                      <td>District:</td>
                      <td><div id="districtdiv">
                        <select name="form_district" class="formfields" id="district" onchange="districtselect()" style="width:180px"><?php
                        $query = "Select distinct distname, distcode from regions order by distname"; 
							$select = mysqli_query($query);  
							
							while($row = mysqli_fetch_array($select))
							{
								if ($disid == $row['distcode'])
								{
									echo "<option selected='selected' value=". $row['distcode']. ">"; 
									echo $row['distname']; 
									echo "</option>"; 
								}
								
							}	?>	
                          <option value = "">- - - -Select a State First - - - -</option>
                        </select>
                      </div></td>
                    </tr>
                    <tr>
                      <td>Phone:</td>
                      <td><input name="form_phone" type="text" class="formfields" id="form_phone" value="<?php echo ($phone); ?>" size="50" maxlength="50" /></td>
                      <td>Cell:</td>
                      <td><input name="form_cell" type="text" class="formfields" id="form_cell" value="<?php echo ($cell); ?>" size="50" maxlength="50" /></td>
                    </tr>
                    <tr>
                      <td>Email ID:</td>
                      <td><input name="form_email" type="text" class="formfields" id="form_email" value="<?php echo ($email); ?>" size="50" maxlength="50" /></td>
                      <td>Website:</td>
                      <td><input name="form_website" type="text" class="formfields" id="form_website" value="<?php echo ($website); ?>" size="50" maxlength="50" /></td>
                    </tr>
                    <tr>
                      <td>Reporting Authority:</td>
                      <td><select name="form_manager" class="formfields" id="form_manager" style="width:180px">
                      <?php echo($managerselect);?>
                      </select></td>
                      <td>Branch:</td>
                      <td><select name="form_branch" class="formfields" id="form_branch" style="width:180px;">
					   <?php    $query = "SELECT slno,branchname FROM lms_branch ORDER BY branchname"; 
							$select = mysqli_query($query);  
							
							while($row = mysqli_fetch_array($select))
							{
								if ($branch == $row['slno'])
								{
									echo "<option selected='selected' value=". $row['slno']. ">"; 
									echo $row['branchname']; 
									echo "</option>"; 
								}
							}					
					 ?>
                   <option value="">Select A Branch</option>
                    <?php include('../inc/branchlist.php');?>
                      </select></td>
                      </tr>
                    <tr>
                      <td>Username:</td>
                      <td><input name="form_username" type="text" class="formfields" id="form_username" value="<?php echo ($user); ?>" size="50" maxlength="50" /></td>
                      <td>Password:</td>
                      <td><input name="form_password" type="password" class="formfields" id="form_password" value="<?php echo ($pass); ?>" size="50" maxlength="50" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><label>
                        <input type="checkbox" name="form_disablelogin" id="form_disablelogin" <?php echo ($dis);?> onclick="dislogin();"/>
                      Disable ( login &amp; other provisions)</label></td>
                      <td>&nbsp;</td>
                      <td><label>
                        <input type="checkbox" name="form_relyonexecutive" id="form_relyonexecutive" <?php echo ($re);?> />
                        Relyon Executive</label></td>
                      </tr><tr>
                      <td>&nbsp;</td>
                      <td><label for="showmcacompanies">
                        <input type="checkbox" name="showmcacompanies" id="showmcacompanies" <?php echo ($show);?> />
                      Show MCA - Companies</label></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
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
                      </form>                </td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td style="border:solid 1px #999999"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td bgcolor="#DEE1FA"><strong>Filter: </strong>[<a style="cursor:pointer" onclick="newtog();">Show/Hide</a>]</td>
                </tr>
                <tr>
                  <td><form id="filterform" name="filterform" onsubmit="return false;" autocomplete = "off" style="display:none">
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td width="9%">&nbsp;&nbsp;Search For:</td>
                        <td width="21%"><input name="searchcriteria" type="text" class="formfields" id="searchcriteria" size="30" maxlength="30" /></td>
                        <td width="4%">&nbsp;&nbsp; In:</td>
                        <td width="66%"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:1px solid #CCCCCC">
                          <tr>
                            <td><span>
                          <label>
                          <input name="databasefield" type="radio" value="dlrid" checked="checked" />
                            Dealer ID</label>
                          <label>
                          <input name="databasefield" type="radio" value="company" />
                            Company</label>
                          <label>
                          <input type="radio" name="databasefield" value="name" />
                            Name</label>
                          <label>
                          <input type="radio" name="databasefield" value="phone" />
                            Phone </label>
                          <label>
                          <input type="radio" name="databasefield" value="cell" />
                            Cell</label>
                          <label>
                          <input type="radio" name="databasefield" value="email" />
                            Email </label>
                          <label>
                          <input type="radio" name="databasefield" value="district" />
                            District </label>
                          <label>
                          <input type="radio" name="databasefield" value="state" />
                            State </label>
                          <label>
                          <input type="radio" name="databasefield" value="manager" />
                            Manager </label>
                        </span></td>
                          </tr>
                          <tr>
                            <td>&nbsp;&nbsp;Disabled :
                              <label>
                        <select name="disabled" id="disabled">
                          <option value="all" selected="selected">All</option>
                          <option value="yes" >Yes</option>
                          <option value="no" >No</option>
                        </select>
                        </label> <input type="hidden" name="srchhiddenfield" id="srchhiddenfield" value=""/><input type="hidden" name="subselhiddenfield" id="subselhiddenfield" value=""/></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr height="20">
                       <td colspan="3" id="searchbox" width = "50%">&nbsp;</td>
    <td width="50%"><div align="right">
      <input name="search" type="button" class="formbutton" id="search" value="Search" onclick="filtering();" />
      &nbsp;&nbsp;
      <input type="button"  name="clear" id="clear" value="Clear" class="formbutton" onclick="clear1();" />
      &nbsp;&nbsp;</div></td>
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
                <td><strong>Dealer Register:<span id="gridprocess"></span></strong></td>
              </tr>
              <tr>
                <td colspan="3" style="border:1px solid #333333;"><div id="tabgroupgridc1" style="overflow:auto; height:250px; width:940px; padding:0px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="getmorelink"  align="left" style="height:20px; padding:0px;"> </div></td>
                                        </tr>
                                      </table></div><div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:0px;" align="center">&nbsp;</div></td>
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
