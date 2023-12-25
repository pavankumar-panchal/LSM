<?php
include("../inc/checklogin.php");

$query = "SELECT * FROM unmappedcontact WHERE managedarea = 'Bangalore' AND prdcategory = 'SPP'";
$result = runmysqlqueryfetch($query);
$form_companyname_bspp = $result['dlrcompanyname'];
$form_name_bspp = $result['dlrname'];
$form_address_bspp = $result['dlraddress'];
$form_phone_bspp = $result['dlrphone'];
$form_cell_bspp = $result['dlrcell'];
$form_email_bspp = $result['dlremail'];

$query = "SELECT * FROM unmappedcontact WHERE managedarea = 'Bangalore' AND prdcategory = 'STO'";
$result = runmysqlqueryfetch($query);
$form_companyname_bsto = $result['dlrcompanyname'];
$form_name_bsto = $result['dlrname'];
$form_address_bsto = $result['dlraddress'];
$form_phone_bsto = $result['dlrphone'];
$form_cell_bsto = $result['dlrcell'];
$form_email_bsto = $result['dlremail'];

$query = "SELECT * FROM unmappedcontact WHERE managedarea = 'Bangalore' AND prdcategory = 'OTHERS'";
$result = runmysqlqueryfetch($query);
$form_companyname_bothers = $result['dlrcompanyname'];
$form_name_bothers = $result['dlrname'];
$form_address_bothers = $result['dlraddress'];
$form_phone_bothers = $result['dlrphone'];
$form_cell_bothers = $result['dlrcell'];
$form_email_bothers = $result['dlremail'];

$query = "SELECT * FROM unmappedcontact WHERE managedarea = 'CSD' AND prdcategory = 'SPP'";
$result = runmysqlqueryfetch($query);
$form_companyname_cspp = $result['dlrcompanyname'];
$form_name_cspp = $result['dlrname'];
$form_address_cspp = $result['dlraddress'];
$form_phone_cspp = $result['dlrphone'];
$form_cell_cspp = $result['dlrcell'];
$form_email_cspp = $result['dlremail'];

$query = "SELECT * FROM unmappedcontact WHERE managedarea = 'CSD' AND prdcategory = 'STO'";
$result = runmysqlqueryfetch($query);
$form_companyname_csto = $result['dlrcompanyname'];
$form_name_csto = $result['dlrname'];
$form_address_csto = $result['dlraddress'];
$form_phone_csto = $result['dlrphone'];
$form_cell_csto = $result['dlrcell'];
$form_email_csto = $result['dlremail'];

$query = "SELECT * FROM unmappedcontact WHERE managedarea = 'CSD' AND prdcategory = 'OTHERS'";
$result = runmysqlqueryfetch($query);
$form_companyname_cothers = $result['dlrcompanyname'];
$form_name_cothers = $result['dlrname'];
$form_address_cothers = $result['dlraddress'];
$form_phone_cothers = $result['dlrphone'];
$form_cell_cothers = $result['dlrcell'];
$form_email_cothers = $result['dlremail'];

$query = "SELECT * FROM unmappedcontact WHERE managedarea = 'KKG' AND prdcategory = 'SPP'";
$result = runmysqlqueryfetch($query);
$form_companyname_kspp = $result['dlrcompanyname'];
$form_name_kspp = $result['dlrname'];
$form_address_kspp = $result['dlraddress'];
$form_phone_kspp = $result['dlrphone'];
$form_cell_kspp = $result['dlrcell'];
$form_email_kspp = $result['dlremail'];

$query = "SELECT * FROM unmappedcontact WHERE managedarea = 'KKG' AND prdcategory = 'STO'";
$result = runmysqlqueryfetch($query);
$form_companyname_ksto = $result['dlrcompanyname'];
$form_name_ksto = $result['dlrname'];
$form_address_ksto = $result['dlraddress'];
$form_phone_ksto = $result['dlrphone'];
$form_cell_ksto = $result['dlrcell'];
$form_email_ksto = $result['dlremail'];

$query = "SELECT * FROM unmappedcontact WHERE managedarea = 'KKG' AND prdcategory = 'OTHERS'";
$result = runmysqlqueryfetch($query);
$form_companyname_kothers = $result['dlrcompanyname'];
$form_name_kothers = $result['dlrname'];
$form_address_kothers = $result['dlraddress'];
$form_phone_kothers = $result['dlrphone'];
$form_cell_kothers = $result['dlrcell'];
$form_email_kothers = $result['dlremail'];

//Check if submitted
if($_POST['submit'])
{
	$form_companyname_bspp = $_POST['form_companyname_bspp'];
	$form_name_bspp = $_POST['form_name_bspp'];
	$form_address_bspp = $_POST['form_address_bspp'];
	$form_phone_bspp = $_POST['form_phone_bspp'];
	$form_cell_bspp = $_POST['form_cell_bspp'];
	$form_email_bspp = $_POST['form_email_bspp'];

	$form_companyname_bsto = $_POST['form_companyname_bsto'];
	$form_name_bsto = $_POST['form_name_bsto'];
	$form_address_bsto = $_POST['form_address_bsto'];
	$form_phone_bsto = $_POST['form_phone_bsto'];
	$form_cell_bsto = $_POST['form_cell_bsto'];
	$form_email_bsto = $_POST['form_email_bsto'];
	
	$form_companyname_bothers = $_POST['form_companyname_bothers'];
	$form_name_bothers = $_POST['form_name_bothers'];
	$form_address_bothers = $_POST['form_address_bothers'];
	$form_phone_bothers = $_POST['form_phone_bothers'];
	$form_cell_bothers = $_POST['form_cell_bothers'];
	$form_email_bothers = $_POST['form_email_bothers'];
	
	$form_companyname_cspp = $_POST['form_companyname_cspp'];
	$form_name_cspp = $_POST['form_name_cspp'];
	$form_address_cspp = $_POST['form_address_cspp'];
	$form_phone_cspp = $_POST['form_phone_cspp'];
	$form_cell_cspp = $_POST['form_cell_cspp'];
	$form_email_cspp = $_POST['form_email_cspp'];
	
	$form_companyname_csto = $_POST['form_companyname_csto'];
	$form_name_csto = $_POST['form_name_csto'];
	$form_address_csto = $_POST['form_address_csto'];
	$form_phone_csto = $_POST['form_phone_csto'];
	$form_cell_csto = $_POST['form_cell_csto'];
	$form_email_csto = $_POST['form_email_csto'];
	
	$form_companyname_cothers = $_POST['form_companyname_cothers'];
	$form_name_cothers = $_POST['form_name_cothers'];
	$form_address_cothers = $_POST['form_address_cothers'];
	$form_phone_cothers = $_POST['form_phone_cothers'];
	$form_cell_cothers = $_POST['form_cell_cothers'];
	$form_email_cothers = $_POST['form_email_cothers'];
	
	$form_companyname_kspp = $_POST['form_companyname_kspp'];
	$form_name_kspp = $_POST['form_name_kspp'];
	$form_address_kspp = $_POST['form_address_kspp'];
	$form_phone_kspp = $_POST['form_phone_kspp'];
	$form_cell_kspp = $_POST['form_cell_kspp'];
	$form_email_kspp = $_POST['form_email_kspp'];
	
	$form_companyname_ksto = $_POST['form_companyname_ksto'];
	$form_name_ksto = $_POST['form_name_ksto'];
	$form_address_ksto = $_POST['form_address_ksto'];
	$form_phone_ksto = $_POST['form_phone_ksto'];
	$form_cell_ksto = $_POST['form_cell_ksto'];
	$form_email_ksto = $_POST['form_email_ksto'];
	
	$form_companyname_kothers = $_POST['form_companyname_kothers'];
	$form_name_kothers = $_POST['form_name_kothers'];
	$form_address_kothers = $_POST['form_address_kothers'];
	$form_phone_kothers = $_POST['form_phone_kothers'];
	$form_cell_kothers = $_POST['form_cell_kothers'];
	$form_email_kothers = $_POST['form_email_kothers'];
	

	$query = "update unmappedcontact set dlrcompanyname = '".$form_companyname_bspp."', dlrname = '".$form_name_bspp."', dlraddress = '".$form_address_bspp."', dlrphone = '".$form_phone_bspp."', dlrcell = '".$form_cell_bspp."', dlremail = '".$form_email_bspp."' WHERE managedarea = '".Bangalore."' AND prdcategory = '".SPP."'";
	$result = runmysqlquery($query);
	$query = "update unmappedcontact set dlrcompanyname = '".$form_companyname_bsto."', dlrname = '".$form_name_bsto."', dlraddress = '".$form_address_bsto."', dlrphone = '".$form_phone_bsto."', dlrcell = '".$form_cell_bsto."', dlremail = '".$form_email_bsto."' WHERE managedarea = '".Bangalore."' AND prdcategory = '".STO."'";
	$result = runmysqlquery($query);
	$query = "update unmappedcontact set dlrcompanyname = '".$form_companyname_bothers."', dlrname = '".$form_name_bothers."', dlraddress = '".$form_address_bothers."', dlrphone = '".$form_phone_bothers."', dlrcell = '".$form_cell_bothers."', dlremail = '".$form_email_bothers."' WHERE managedarea = '".Bangalore."' AND prdcategory = '".OTHERS."'";
	$result = runmysqlquery($query);
	$query = "update unmappedcontact set dlrcompanyname = '".$form_companyname_cspp."', dlrname = '".$form_name_cspp."', dlraddress = '".$form_address_cspp."', dlrphone = '".$form_phone_cspp."', dlrcell = '".$form_cell_cspp."', dlremail = '".$form_email_cspp."' WHERE managedarea = '".CSD."' AND prdcategory = '".SPP."'";
	$result = runmysqlquery($query);
	$query = "update unmappedcontact set dlrcompanyname = '".$form_companyname_csto."', dlrname = '".$form_name_csto."', dlraddress = '".$form_address_csto."', dlrphone = '".$form_phone_csto."', dlrcell = '".$form_cell_csto."', dlremail = '".$form_email_csto."' WHERE managedarea = '".CSD."' AND prdcategory = '".STO."'";
	$result = runmysqlquery($query);
	$query = "update unmappedcontact set dlrcompanyname = '".$form_companyname_cothers."', dlrname = '".$form_name_cothers."', dlraddress = '".$form_address_cothers."', dlrphone = '".$form_phone_cothers."', dlrcell = '".$form_cell_cothers."', dlremail = '".$form_email_cothers."' WHERE managedarea = '".CSD."' AND prdcategory = '".OTHERS."'";
	$result = runmysqlquery($query);
	$query = "update unmappedcontact set dlrcompanyname = '".$form_companyname_kspp."', dlrname = '".$form_name_kspp."', dlraddress = '".$form_address_kspp."', dlrphone = '".$form_phone_kspp."', dlrcell = '".$form_cell_kspp."', dlremail = '".$form_email_kspp."' WHERE managedarea = '".KKG."' AND prdcategory = '".SPP."'";
	$result = runmysqlquery($query);
	$query = "update unmappedcontact set dlrcompanyname = '".$form_companyname_ksto."', dlrname = '".$form_name_ksto."', dlraddress = '".$form_address_ksto."', dlrphone = '".$form_phone_ksto."', dlrcell = '".$form_cell_ksto."', dlremail = '".$form_email_ksto."' WHERE managedarea = '".KKG."' AND prdcategory = '".STO."'";
	$result = runmysqlquery($query);
	$query = "update unmappedcontact set dlrcompanyname = '".$form_companyname_kothers."', dlrname = '".$form_name_kothers."', dlraddress = '".$form_address_kothers."', dlrphone = '".$form_phone_kothers."', dlrcell = '".$form_cell_kothers."', dlremail = '".$form_email_kothers."' WHERE managedarea = '".KKG."' AND prdcategory = '".OTHERS."'";
	$result = runmysqlquery($query);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LMS | Contacts for Unmapped</title>
<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand());?>">
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
        <td>Contact details for unmapped web leads</td>
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
                  <td><form id="ucform" name="ucform" method="post" action="">
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td><strong>Bangalore - SPP:</strong></td>
                          <td><p>&nbsp;</p>
                            </td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="147">Company Name:                            </td>
                          <td width="302"><input name="form_companyname_bspp" type="text" class="formfields" id="form_companyname_bspp" value="<?php echo($form_companyname_bspp); ?>" size="50" maxlength="50" /></td>
                          <td width="69">Contact person:</td>
                          <td width="374"><input name="form_name_bspp" type="text" class="formfields" id="form_name_bspp" size="50" maxlength="50" value="<?php echo($form_name_bspp); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Address:</td>
                          <td colspan="3"><input name="form_address_bspp" type="text" class="formfields" id="form_address_bspp" size="126" maxlength="200" value="<?php echo($form_address_bspp); ?>" /></td>
                        </tr>

                        <tr>
                          <td>Phone:</td>
                          <td><input name="form_phone_bspp" type="text" class="formfields" id="form_phone_bspp" size="50" maxlength="50" value="<?php echo($form_phone_bspp); ?>" /></td>
                          <td>Cell:</td>
                          <td><input name="form_cell_bspp" type="text" class="formfields" id="form_cell_bspp" size="50" maxlength="50" value="<?php echo($form_cell_bspp); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Email ID:</td>
                          <td><input name="form_email_bspp" type="text" class="formfields" id="form_email_bspp" size="50" maxlength="50" value="<?php echo($form_email_bspp); ?>" /></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>

                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                      <hr align="center" width="98%" size="1" />
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td><strong>Bangalore - STO:</strong></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="147">Company Name: </td>
                          <td width="302"><input name="form_companyname_bsto" type="text" class="formfields" id="form_companyname_bsto" size="50" maxlength="50" value="<?php echo($form_companyname_bsto); ?>" /></td>
                          <td width="69">Contact person:</td>
                          <td width="374"><input name="form_name_bsto" type="text" class="formfields" id="form_name_bsto" size="50" maxlength="50" value="<?php echo($form_name_bsto); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Address:</td>
                          <td colspan="3"><input name="form_address_bsto" type="text" class="formfields" id="form_address_bsto" size="126" maxlength="200" value="<?php echo($form_address_bsto); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Phone:</td>
                          <td><input name="form_phone_bsto" type="text" class="formfields" id="form_phone_bsto" size="50" maxlength="50" value="<?php echo($form_phone_bsto); ?>" /></td>
                          <td>Cell:</td>
                          <td><input name="form_cell_bsto" type="text" class="formfields" id="form_cell_bsto" size="50" maxlength="50" value="<?php echo($form_cell_bsto); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Email ID:</td>
                          <td><input name="form_email_bsto" type="text" class="formfields" id="form_email_bsto" size="50" maxlength="50" value="<?php echo($form_email_bsto); ?>" /></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                      <hr align="center" width="98%" size="1" />
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td><strong>Bangalore - OTHERS:</strong></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="147">Company Name: </td>
                          <td width="302"><input name="form_companyname_bothers" type="text" class="formfields" id="form_companyname_bothers" size="50" maxlength="50" value="<?php echo($form_companyname_bothers); ?>" /></td>
                          <td width="69">Contact person:</td>
                          <td width="374"><input name="form_name_bothers" type="text" class="formfields" id="form_name_bothers" size="50" maxlength="50" value="<?php echo($form_name_bothers); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Address:</td>
                          <td colspan="3"><input name="form_address_bothers" type="text" class="formfields" id="form_address_bothers" size="126" maxlength="200" value="<?php echo($form_address_bothers); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Phone:</td>
                          <td><input name="form_phone_bothers" type="text" class="formfields" id="form_phone_bothers" size="50" maxlength="50" value="<?php echo($form_phone_bothers); ?>" /></td>
                          <td>Cell:</td>
                          <td><input name="form_cell_bothers" type="text" class="formfields" id="form_cell_bothers" size="50" maxlength="50" value="<?php echo($form_cell_bothers); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Email ID:</td>
                          <td><input name="form_email_bothers" type="text" class="formfields" id="form_email_bothers" size="50" maxlength="50" value="<?php echo($form_email_bothers); ?>" /></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                      <hr align="center" width="98%" size="1" />
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td><strong>CSD - SPP:</strong></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="147">Company Name: </td>
                          <td width="302"><input name="form_companyname_cspp" type="text" class="formfields" id="form_companyname_cspp" size="50" maxlength="50" value="<?php echo($form_companyname_cspp); ?>" /></td>
                          <td width="69">Contact person:</td>
                          <td width="374"><input name="form_name_cspp" type="text" class="formfields" id="form_name_cspp" size="50" maxlength="50" value="<?php echo($form_name_cspp); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Address:</td>
                          <td colspan="3"><input name="form_address_cspp" type="text" class="formfields" id="form_address_cspp" size="126" maxlength="200" value="<?php echo($form_address_cspp); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Phone:</td>
                          <td><input name="form_phone_cspp" type="text" class="formfields" id="form_phone_cspp" size="50" maxlength="50" value="<?php echo($form_phone_cspp); ?>" /></td>
                          <td>Cell:</td>
                          <td><input name="form_cell_cspp" type="text" class="formfields" id="form_cell_cspp" size="50" maxlength="50" value="<?php echo($form_cell_cspp); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Email ID:</td>
                          <td><input name="form_email_cspp" type="text" class="formfields" id="form_email_cspp" size="50" maxlength="50" value="<?php echo($form_email_cspp); ?>" /></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                      <hr align="center" width="98%" size="1" />
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td><strong>CSD - STO:</strong></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="147">Company Name: </td>
                          <td width="302"><input name="form_companyname_csto" type="text" class="formfields" id="form_companyname_csto" size="50" maxlength="50" value="<?php echo($form_companyname_csto); ?>" /></td>
                          <td width="69">Contact person:</td>
                          <td width="374"><input name="form_name_csto" type="text" class="formfields" id="form_name_csto" size="50" maxlength="50" value="<?php echo($form_name_csto); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Address:</td>
                          <td colspan="3"><input name="form_address_csto" type="text" class="formfields" id="form_address_csto" size="126" maxlength="200" value="<?php echo($form_address_csto); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Phone:</td>
                          <td><input name="form_phone_csto" type="text" class="formfields" id="form_phone_csto" size="50" maxlength="50" value="<?php echo($form_phone_csto); ?>" /></td>
                          <td>Cell:</td>
                          <td><input name="form_cell_csto" type="text" class="formfields" id="form_cell_csto" size="50" maxlength="50" value="<?php echo($form_cell_csto); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Email ID:</td>
                          <td><input name="form_email_csto" type="text" class="formfields" id="form_email_csto" size="50" maxlength="50" value="<?php echo($form_email_csto); ?>" /></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                      <hr align="center" width="98%" size="1" />
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td><strong>CSD - OTHERS:</strong></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="147">Company Name: </td>
                          <td width="302"><input name="form_companyname_cothers" type="text" class="formfields" id="form_companyname_cothers" size="50" maxlength="50" value="<?php echo($form_companyname_cothers); ?>" /></td>
                          <td width="69">Contact person:</td>
                          <td width="374"><input name="form_name_cothers" type="text" class="formfields" id="form_name_cothers" size="50" maxlength="50" value="<?php echo($form_name_cothers); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Address:</td>
                          <td colspan="3"><input name="form_address_cothers" type="text" class="formfields" id="form_address_cothers" size="126" maxlength="200" value="<?php echo($form_address_cothers); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Phone:</td>
                          <td><input name="form_phone_cothers" type="text" class="formfields" id="form_phone_cothers" size="50" maxlength="50" value="<?php echo($form_phone_cothers); ?>" /></td>
                          <td>Cell:</td>
                          <td><input name="form_cell_cothers" type="text" class="formfields" id="form_cell_cothers" size="50" maxlength="50" value="<?php echo($form_cell_cothers); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Email ID:</td>
                          <td><input name="form_email_cothers" type="text" class="formfields" id="form_email_cothers" size="50" maxlength="50" value="<?php echo($form_email_cothers); ?>" /></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                      <hr align="center" width="98%" size="1" />
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td><strong>KKG - SPP:</strong></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="147">Company Name: </td>
                          <td width="302"><input name="form_companyname_kspp" type="text" class="formfields" id="form_companyname_kspp" size="50" maxlength="50" value="<?php echo($form_companyname_kspp); ?>" /></td>
                          <td width="69">Contact person:</td>
                          <td width="374"><input name="form_name_kspp" type="text" class="formfields" id="form_name_kspp" size="50" maxlength="50" value="<?php echo($form_name_kspp); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Address:</td>
                          <td colspan="3"><input name="form_address_kspp" type="text" class="formfields" id="form_address_kspp" size="126" maxlength="200" value="<?php echo($form_address_kspp); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Phone:</td>
                          <td><input name="form_phone_kspp" type="text" class="formfields" id="form_phone_kspp" size="50" maxlength="50" value="<?php echo($form_phone_kspp); ?>" /></td>
                          <td>Cell:</td>
                          <td><input name="form_cell_kspp" type="text" class="formfields" id="form_cell_kspp" size="50" maxlength="50" value="<?php echo($form_cell_kspp); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Email ID:</td>
                          <td><input name="form_email_kspp" type="text" class="formfields" id="form_email_kspp" size="50" maxlength="50" value="<?php echo($form_email_kspp); ?>" /></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                      <hr align="center" width="98%" size="1" />
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td><strong>KKG - STO:</strong></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="147">Company Name: </td>
                          <td width="302"><input name="form_companyname_ksto" type="text" class="formfields" id="form_companyname_ksto" size="50" maxlength="50" value="<?php echo($form_companyname_ksto); ?>" /></td>
                          <td width="69">Contact person:</td>
                          <td width="374"><input name="form_name_ksto" type="text" class="formfields" id="form_name_ksto" size="50" maxlength="50" value="<?php echo($form_name_ksto); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Address:</td>
                          <td colspan="3"><input name="form_address_ksto" type="text" class="formfields" id="form_address_ksto" size="126" maxlength="200" value="<?php echo($form_address_ksto); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Phone:</td>
                          <td><input name="form_phone_ksto" type="text" class="formfields" id="form_phone_ksto" size="50" maxlength="50" value="<?php echo($form_phone_ksto); ?>" /></td>
                          <td>Cell:</td>
                          <td><input name="form_cell_ksto" type="text" class="formfields" id="form_cell_ksto" size="50" maxlength="50" value="<?php echo($form_cell_ksto); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Email ID:</td>
                          <td><input name="form_email_ksto" type="text" class="formfields" id="form_email_ksto" size="50" maxlength="50" value="<?php echo($form_email_ksto); ?>" /></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                      <hr align="center" width="98%" size="1" />
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">
                        <tr>
                          <td><strong>KKG - OTHERS:</strong></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="147">Company Name: </td>
                          <td width="302"><input name="form_companyname_kothers" type="text" class="formfields" id="form_companyname_kothers" size="50" maxlength="50" value="<?php echo($form_companyname_kothers); ?>" /></td>
                          <td width="69">Contact person:</td>
                          <td width="374"><input name="form_name_kothers" type="text" class="formfields" id="form_name_kothers" size="50" maxlength="50" value="<?php echo($form_name_kothers); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Address:</td>
                          <td colspan="3"><input name="form_address_kothers" type="text" class="formfields" id="form_address_kothers" size="126" maxlength="200" value="<?php echo($form_address_kothers); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Phone:</td>
                          <td><input name="form_phone_kothers" type="text" class="formfields" id="form_phone_kothers" size="50" maxlength="50" value="<?php echo($form_phone_kothers); ?>" /></td>
                          <td>Cell:</td>
                          <td><input name="form_cell_kothers" type="text" class="formfields" id="form_cell_kothers" size="50" maxlength="50" value="<?php echo($form_cell_kothers); ?>" /></td>
                        </tr>
                        <tr>
                          <td>Email ID:</td>
                          <td><input name="form_email_kothers" type="text" class="formfields" id="form_email_kothers" size="50" maxlength="50" value="<?php echo($form_email_kothers); ?>" /></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                      <hr align="center" width="98%" size="1" />    
                      <table width="100%" border="0" cellspacing="0" cellpadding="6">

                        <tr>
                          <td width="147">&nbsp;</td>
                          <td width="302">&nbsp;</td>
                          <td width="69">&nbsp;</td>
                          <td width="374"><div align="right">
                            <input name="submit" type="submit" class="formbutton" id="submit" value="Save" />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="reset" type="reset" class="formbutton" id="reset" value="Reset" />
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
                        </tr>
                      </table>
                  </form></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
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
