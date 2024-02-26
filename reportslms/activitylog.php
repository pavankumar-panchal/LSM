<?php
include("../inc/checklogin.php");

//Permission check for the page
if($cookie_usertype <> "Admin")
	header("Location:../home");
	

//Get all the user names with respective displaynames, where they are allowed to upload a lead.
switch($cookie_usertype)
{
	case "Admin":
		$givenselect1 = '<option value="" selected="selected">ALL</option>';
		//Add all Sub Admins
		$query = "select lms_users.id AS selectid, lms_subadmins.sadname AS selectname from lms_users join lms_subadmins on lms_users.referenceid = lms_subadmins.id where lms_users.type = 'Sub Admin' ORDER BY selectname";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect1 .= '<option value="'.$fetch['selectid'].'^'.'[S]'.'">'.$fetch['selectname'].' [S]</option>';
		}
		//Add all Managers
		$query = "select lms_users.id AS selectid, lms_managers.mgrname AS selectname from lms_users join lms_managers on lms_users.referenceid = lms_managers.id where lms_users.type = 'Reporting Authority' ORDER BY selectname";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect1 .= '<option value="'.$fetch['selectid'].'^'.'[M]'.'">'.$fetch['selectname'].' [M]</option>';
		}
		//Add all Dealers
		$query = "select lms_users.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users join dealers on lms_users.referenceid = dealers.id where lms_users.type = 'Dealer' ORDER BY selectname";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect1 .= '<option value="'.$fetch['selectid'].'^'.'[D]'.'">'.$fetch['selectname'].' [D]</option>';
		}
		//Add all Dealers Members
		$query = "select  lms_users.id AS selectid, dlrmbrname as selectname from lms_users join lms_dlrmembers on lms_dlrmembers.dlrmbrid = lms_users.referenceid where lms_users.type = 'Dealer Member' ORDER BY selectname";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$givenselect1 .= '<option value="'.$fetch['selectid'].'^'.'[DM]'.'">'.$fetch['selectname'].' [DM]</option>';
		}
		break;
  
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Activity Log</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
<link type="text/css" rel="stylesheet" href="../css/datepickercontrol.css?dummy=<?php echo(rand());?>">
<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/jsfunctions.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/activitylog.js?dummy=<?php echo (rand());?>" language="javascript"></script>
<script src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" language="javascript"></script>

<!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
</head>
<body onload="griddata('')">
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
        <td>Activity Log</td>
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
                  <td><strong>Filter the data for Report:</strong></td>
                </tr>
                <tr>
                  <td><form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2" >
                                    <tr>
                                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#FBF3DB" >
                                          <tr>
                                            
                                            <td width="37%"  valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                <tr >
                                                  <td width="22%" align="left" valign="top">From Date: </td>
                                                  <td width="78%" align="left" valign="top"><input name="fromdate" type="text" class="formfields" id="DPC_fromdate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>" readonly="readonly" />
                                                    <input type="hidden" name="flag" id="flag" value="true" />
                                                    <input type="hidden" name="category" id="category" value="<?php echo($pagelink) ?>" /></td>
                                                </tr>
                                              </table></td>
                                            <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr >
                                                  <td colspan="2" valign="top" style="padding:0"></td>
                                                </tr>
                                                <tr >
                                                  <td width="22%" align="left" valign="top" >To Date:</td>
                                                  <td width="78%" align="left" valign="top" ><label for="sto"></label>
                                                    <label for="spp">
                                                      <input name="todate" type="text" class="formfields" id="DPC_todate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly"/>
                                                    </label></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr>
                                            <td colspan="3"><div align="left" style="display:block;height:20px; padding-top:5px; " id="detailsdiv" >
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td width="87%" ><div style="border-top:dashed 1px #000000;" align="center"></div></td>
                                                    <td width="13%" ><div align="right"><strong style=" padding-right:10 px">Advanced Options</strong></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="2">&nbsp;</td>
                                                  </tr>
                                                </table>
                                              </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="3"><div id="filterdiv" style="display:none1; text-align: left;">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                  <tr>
                                                    <td width="100%" valign="top" ><table width="100%" border="0" cellpadding="3" cellspacing="0" >
                                                        <tr>
                                                          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                              <tr>
                                                                <td width="43%"><table width="99%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td width="9%" align="left" valign="top" >Text: </td>
                                                                      <td width="91%" colspan="3" align="left" valign="top" ><input name="searchcriteria" type="text" id="searchcriteria" size="33" maxlength="150" class="formfields"  autocomplete="off" value=""/>
                                                                        <span style="font-size:8px; color:#939393;">(Leave Empty for all)</span></td>
                                                                      <td>&nbsp;</td>
                                                                    </tr>
                                                                  </table></td>
                                                                <td width="12%">&nbsp;</td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2" >
                                                            
                                                            
                                                            <tr valign="top" >
                                                              <td width="17%" height="2" ><table width="100%" border="0" cellspacing="0" cellpadding="6" style="border:solid 1px #000" >
                                                                <tr>
                                                                  <td align="left"><strong>Look In</strong></td>
                                                                </tr>
                                                                <tr>
                                                                  <td align="left"><label>
                                                                    <input name="databasefield" type="radio" id="databasefield0" value="userid" checked="checked"/>
                                                                    User ID</label></td>
                                                                </tr>
                                                                <tr>
                                                                  <td align="left"><label>
                                                                    <input type="radio" name="databasefield" id="databasefield2" value="systemip" />
                                                                    System IP </label></td>
                                                                </tr>
                                                              </table></td>
                                                              <td width="83%" ><table width="100%" border="0" cellspacing="0" cellpadding="4"  style="border:solid 1px #CCC" >
                                                                <tr>
                                                                  <td colspan="2" align="left"><strong>Selections</strong>:</td>
                                                                </tr>
                                                                <tr>
                                                                  <td height="10" align="left" valign="top">Username:</td>
                                                                  <td height="10" align="left" valign="top"><select name="username" class="swiftselect" id="username" style="width:180px;">
                                                                  <?php echo($givenselect1) ?>
                                                                  </select></td>
                                                                </tr>
                                                                <tr>
                                                                  <td width="21%" height="10" align="left" valign="top">Event Type</td>
                                                                  <td width="79%" height="10" align="left" valign="top"><select name="eventtype" class="swiftselect" id="eventtype" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <?php 
											include('../inc/eventtype.php');
											?>
                                                                  </select></td>
                                                                </tr>
                                                              </table></td>
                                                            </tr>
                                                           
                                                            
                                                          </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td width="60%" align="right" valign="middle" style="padding-right:3px;"></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                </table>
                                            </div></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:5px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="67%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="33%" align="right" valign="middle"><input name="view" type="button" class="swiftchoicebutton" id="view" value="View" onclick="searchfilter('');" />
                                              &nbsp;
                                              <input name="toexcel" type="submit" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="filtertoexcel('toexcel');"/>
                                              &nbsp;
                                              <input type="button" name="reset_form" value="Reset" class="swiftchoicebutton" onclick="resetDefaultValues(this.form);" /></td>
                                          </tr>
                                        </table></td>
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
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                <td width="140px" align="center" id="tabgroupgridh1" onclick="gridtab2('1','tabgroupgrid');griddata('')" style="cursor:pointer" class="grid-active-leadtabclass">Today's Activity</td>
                                                <td width="2">&nbsp;</td>
                                                <td width="140px" align="center" id="tabgroupgridh2" onclick="gridtab2('2','tabgroupgrid'); " style="cursor:pointer" class="grid-leadtabclass">Search Results</td>
                                                <td width="2">&nbsp;</td>
                                                <td><div id="gridprocessing"> </div></td>
                                              </tr>
                                            </table></td>
                                        </tr>
                                        <tr>
                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                              <tr class="header-line" >
                                                <td width="220px"><div id="tabdescription">&nbsp;</div></td>
                                                <td width="216px" style="text-align:center;"><span id="tabgroupgridwb1" ></span><span id="tabgroupgridwb2" ></span></td>
                                                <td width="296px" style="padding:0">&nbsp;</td>
                                              </tr>
                                              <tr>
                                                <td colspan="3" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:260px; width:945px; padding:2px;" align="center">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td><div id="tabgroupgridc1_1" align="center" ></div></td>
                                                      </tr>
                                                      <tr>
                                                        <td><div id="tabgroupgridc1link" align="left" > </div></td>
                                                      </tr>
                                                    </table>
                                                    <div id="resultgrid" style="overflow:auto; display:none; height:260px; width:945px; padding:2px;" align="center">&nbsp;</div>
                                                  </div>
                                                  <div id="tabgroupgridc2" style="overflow:auto;height:260px; width:945px; padding:2px; display:none;" align="center">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td colspan="2"><div id="tabgroupgridc2_1" align="center" ></div></td>
                                                      </tr>
                                                      <tr>
                                                        <td><div id="tabgroupgridc2link" align="left"> </div></td>
                                                        <td>&nbsp;</td>
                                                      </tr>
                                                    </table>
                                                    <div id="searchresultgrid" style="display:none;" align="center">&nbsp;</div>
                                                  </div>
                                                  </td>
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
