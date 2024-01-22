<?php

switch ($cookie_usertype) {
  case "Admin":
    $query = "SELECT id AS selectid, dlrcompanyname AS selectname FROM dealers ORDER BY dlrcompanyname";
    break;
  case "Reporting Authority":
    //Check wheteher the manager is branch head or not
    $query1 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '" . $cookie_username . "' AND lms_users.type = 'Reporting Authority';";
    $result1 = runmysqlqueryfetch($query1);

    if ($result1['branchhead'] == 'yes')
      $branchpiecejoin = "(dealers.branch = '" . $result1['branch'] . "' OR dealers.managerid  = '" . $result1['managerid'] . "')";
    else
      $branchpiecejoin = "lms_users.username = '" . $cookie_username . "' ";

    $query = "SELECT distinct dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE " . $branchpiecejoin . " ORDER BY dealers.dlrcompanyname";
    if ($cookie_username == "srinivasan")
      $query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '" . $cookie_username . "' or  lms_users.username = 'nagaraj' ORDER BY dealers.dlrcompanyname";

    break;
  case "Dealer":
    $query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.id WHERE lms_users.username = '" . $cookie_username . "' ORDER BY dealers.dlrcompanyname";
    break;
  case "Dealer Member":
    $query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN lms_dlrmembers on lms_dlrmembers.dlrmbrid = lms_users.referenceid JOIN dealers ON lms_dlrmembers.dealerid = dealers.id WHERE lms_users.username = '" . $cookie_username . "' ORDER BY dealers.dlrcompanyname";
    break;
  case "Sub Admin":
    $query = "SELECT id AS selectid, dlrcompanyname AS selectname FROM dealers ORDER BY dlrcompanyname";
    break;
}

$result = runmysqlquery($query);
$dealerselect = '';
if (mysqli_num_rows($result) > 1)
  $dealerselect .= '<option value="" selected="selected">- - - - All - - - -</option>';
while ($fetch = mysqli_fetch_array($result)) {
  $dealerselect .= '<option value="' . $fetch['selectid'] . '">' . $fetch['selectname'] . '</option>';
}

// Select dealer list to display in lead contract card.
//if(($cookie_usertype == 'Admin') || ($cookie_usertype == 'Sub Admin') || ($cookie_usertype == 'Reporting Authority'))
//{
switch ($cookie_usertype) {
  case "Admin":
  case "Sub Admin":
    $query1 = "SELECT distinct dealers.id AS selectid, dealers.dlrcompanyname AS selectname 
	FROM dealers left join lms_users on lms_users.referenceid = dealers.id where lms_users.disablelogin <> 'yes' and lms_users.type = 'Dealer' ORDER BY dlrcompanyname;";
    break;
  case "Reporting Authority":
    //Check wheteher the manager is branch head or not
    $query123 = "select lms_managers.branchhead as branchhead, lms_managers.branch as branch, lms_users.referenceid as  managerid from lms_users join lms_managers on lms_managers.id = lms_users.referenceid where lms_users.username = '" . $cookie_username . "' AND lms_users.type = 'Reporting Authority';";
    $result1 = runmysqlqueryfetch($query123);
    if ($result1['branchhead'] == 'yes') {
      $branchpiecejoin = "AND (dealers.branch = '" . $result1['branch'] . "' OR dealers.managerid  = '" . $result1['managerid'] . "')";
      $joinpiece = "";
    } else {
      $branchpiecejoin = "";
      $joinpiece = "lms_users.username = '" . $cookie_username . "' AND ";
    }

    $query1 = "select dealers.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users left join dealers on dealers.id=lms_users.referenceid where dealers.managerid  in (select dealers.managerid from dealers left join lms_users on dealers.managerid =lms_users.referenceid where  " . $joinpiece . " lms_users.type = 'Reporting Authority')
	and  lms_users.type = 'Dealer' and lms_users.disablelogin <> 'yes' " . $branchpiecejoin . " ORDER BY dealers.dlrcompanyname";
    if ($cookie_username == "srinivasan")
      $query1 = "select dealers.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users  left join dealers on dealers.id =lms_users.referenceid where dealers.managerid  in (select dealers.managerid from dealers left join lms_users on dealers.managerid =lms_users.referenceid where lms_users.username = '" . $cookie_username . "'  or lms_users.username = 'nagaraj'and lms_users.type ='Reporting Authority') and lms_users.type = 'Dealer' and lms_users.disablelogin <> 'yes' ORDER BY dealers.dlrcompanyname";
    break;
  case "Dealer":
    $query1 = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.id WHERE lms_users.username = '" . $cookie_username . "' ORDER BY dealers.dlrcompanyname";
    break;
  case "Dealer Member":
    $query1 = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN lms_dlrmembers on lms_dlrmembers.dlrmbrid = lms_users.referenceid JOIN dealers ON lms_dlrmembers.dealerid = dealers.id WHERE lms_users.username = '" . $cookie_username . "' ORDER BY dealers.dlrcompanyname";
    break;

}
$result1 = runmysqlquery($query1); //echo($query1);exit;
$dealerselect1 = '';
if (mysqli_num_rows($result) > 0)
  $dealerselect1 .= '<option value="" selected="selected">- - - - Make A Selection - - - - </option>';
while ($fetch1 = mysqli_fetch_array($result1)) {
  $dealerselect1 .= '<option value="' . $fetch1['selectid'] . '">' . $fetch1['selectname'] . '</option>';
}

if ($cookie_usertype == 'Dealer Member')
  $height = '369px';
else
  $height = '369px';


//Select the list of products and its groups for the drop-down
$query3 = "SELECT id,productname FROM products ORDER BY productname";
$result3 = runmysqlquery($query3);
$productselect = '<option value="" selected="selected">- - - - All - - - -</option>';
$productselect .= '<optgroup label="Products" style = "font-family:"Times New Roman", Times, serif;">';
while ($fetch = mysqli_fetch_array($result3)) {
  $productselect .= '<option value="' . $fetch['id'] . '">' . $fetch['productname'] . '</option>';
}
$productselect .= '</optgroup>';
$query4 = "SELECT distinct category  FROM products ORDER BY category";
$result4 = runmysqlquery($query4);
$productselect .= '<optgroup label="Groups" style = "font-family:"Times New Roman", Times, serif;">';
while ($fetch = mysqli_fetch_array($result4)) {
  $productselect .= '<option value="' . $fetch['category'] . '">' . $fetch['category'] . '</option>';
}
$productselect .= '</optgroup>';


// Select the list of products for product change list
$query10 = "SELECT id,productname FROM products ORDER BY productname";
$result10 = runmysqlquery($query10);
$productchangeselect .= '<option value="" selected="selected">- - - - Make a Selection - - - -</option>';
while ($fetch = mysqli_fetch_array($result10)) {
  $productchangeselect .= '<option value="' . $fetch['id'] . '">' . $fetch['productname'] . '</option>';
}
$productchangeselect .= '</option>';

//Select the list of LEAD STATUS for the drop-down
$query2 = "SELECT distinct leadstatus FROM leads ORDER BY leadstatus";
$result2 = runmysqlquery($query2);
$leadstatusselect = '<option value="" selected="selected">- - - - All - - - -</option>';
while ($fetch = mysqli_fetch_array($result2)) {
  $leadstatusselect .= '<option value="' . $fetch['leadstatus'] . '">' . $fetch['leadstatus'] . '</option>';
}
//Select the list of LEAD SUB STATUS for the drop-down
$query3 = "SELECT distinct leadsubstatus FROM leads where leadsubstatus!='' ORDER BY leadsubstatus ";
$result3 = runmysqlquery($query3);
$leadsubstatusselect = '<option value="" selected="selected">- - - - All - - - -</option>';
while ($fetch3 = mysqli_fetch_array($result3)) {
  $leadsubstatusselect .= '<option value="' . $fetch3['leadsubstatus'] . '">' . $fetch3['leadsubstatus'] . '</option>';
}
// Select List to transfer Leads to dealer Members
$query5 = "select lms_dlrmembers.dlrmbrid AS selectid, lms_dlrmembers.dlrmbrname AS selectname from lms_dlrmembers  left join lms_users on lms_users.referenceid =lms_dlrmembers.dlrmbrid where dealerid in (select dealers.id from dealers left join lms_users on lms_users.referenceid = dealers.id where lms_users.username = '" . $cookie_username . "' and lms_users.type = 'Dealer') and lms_users.disablelogin <> 'yes' and lms_users.type = 'Dealer Member'";
$result5 = runmysqlquery($query5);
$count = mysqli_num_rows($result5);
if ($count > 0) {
  $dlrmbrselect = '<option value = "" selected="selected"> - - - - Make A Selection - - - - </option>';
  while ($fetch5 = mysqli_fetch_array($result5)) {
    $dlrmbrselect .= '<option value="' . $fetch5['selectid'] . '">' . $fetch5['selectname'] . '</option>';
  }
} else
  $dlrmbrselect = '<option value = "" selected="selected"> - - - - Make A Selection - - - - </option>';
// Get date for From date field.

$month = date('m');
if ($month >= '04')
  $date = '01-04-' . date('Y');
else {
  $year = date('Y') - '1';
  $date = '01-04-' . $year; //echo($date);
}
//Get current date for TO DATE field
$defaulttodate = datetimelocal("d-m-Y");
//Get all the user names with respective displaynames, where they are allowed to upload a lead.
switch ($cookie_usertype) {
  case "Admin":
  case "Sub Admin":
    $givenselect = '<option value="" selected="selected">- - - - All - - - -</option><option value="web">Web Downloads</option>';
    // For Last Updated By(Removed Web Downlaods
    $givenselect1 = '<option value="" selected="selected">- - - - All - - - -</option>';
    //Add all Sub Admins
    $query = "select lms_users.id AS selectid, lms_subadmins.sadname AS selectname from lms_users join lms_subadmins on lms_users.referenceid = lms_subadmins.id where lms_users.type = 'Sub Admin' ORDER BY selectname";
    $result = runmysqlquery($query);
    while ($fetch = mysqli_fetch_array($result)) {
      $givenselect .= '<option value="' . $fetch['selectid'] . '">' . $fetch['selectname'] . ' [S]</option>';
      $givenselect1 .= '<option value="' . $fetch['selectid'] . '">' . $fetch['selectname'] . ' [S]</option>';
    }
    //Add all Managers
    $query = "select lms_users.id AS selectid, lms_managers.mgrname AS selectname from lms_users join lms_managers on lms_users.referenceid = lms_managers.id where lms_users.type = 'Reporting Authority' ORDER BY selectname";
    $result = runmysqlquery($query);
    while ($fetch = mysqli_fetch_array($result)) {
      $givenselect .= '<option value="' . $fetch['selectid'] . '">' . $fetch['selectname'] . ' [M]</option>';
      $givenselect1 .= '<option value="' . $fetch['selectid'] . '">' . $fetch['selectname'] . ' [M]</option>';
    }
    //Add all Dealers
    $query = "select lms_users.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users join dealers on lms_users.referenceid = dealers.id where lms_users.type = 'Dealer' ORDER BY selectname";
    $result = runmysqlquery($query);
    while ($fetch = mysqli_fetch_array($result)) {
      $givenselect .= '<option value="' . $fetch['selectid'] . '">' . $fetch['selectname'] . ' [D]</option>';
      $givenselect1 .= '<option value="' . $fetch['selectid'] . '">' . $fetch['selectname'] . ' [D]</option>';
    }
    break;
  case "Reporting Authority":
    $givenselect = '<option value="" selected="selected">- - - - All - - - - </option><option value="web">Web Downloads</option>';
    $givenselect1 = '<option value="" selected="selected">- - - - All - - - - </option>';
    //Add respective manager name
    $query = "select lms_users.id AS selectid, lms_managers.mgrname AS selectname from lms_users join lms_managers on lms_users.referenceid = lms_managers.id where lms_users.username = '" . $cookie_username . "'";
    if ($cookie_username == "srinivasan")
      $query = "select lms_users.id AS selectid, lms_managers.mgrname AS selectname from lms_users join lms_managers on lms_users.referenceid = lms_managers.id where lms_users.username = '" . $cookie_username . "' or  lms_users.username = 'nagaraj'";
    $result = runmysqlquery($query);
    while ($fetch = mysqli_fetch_array($result)) {
      $givenselect .= '<option value="' . $fetch['selectid'] . '">' . $fetch['selectname'] . ' [M]</option>';
      $givenselect1 .= '<option value="' . $fetch['selectid'] . '">' . $fetch['selectname'] . ' [M]</option>';
    }
    //Add all the Dealers, who are under the manager logged in
    $query = "select lms_users.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users join (SELECT dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '" . $cookie_username . "') AS dealers on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' ORDER BY selectname";
    if ($cookie_username == "srinivasan")
      $query = "select lms_users.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users join (SELECT dealers.id AS id, dealers.dlrcompanyname AS dlrcompanyname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '" . $cookie_username . "' or  lms_users.username = 'nagaraj') AS dealers on lms_users.referenceid = dealers.id AND lms_users.type = 'Dealer' ORDER BY selectname";
    $result = runmysqlquery($query);
    while ($fetch = mysqli_fetch_array($result)) {
      $givenselect .= '<option value="' . $fetch['selectid'] . '">' . $fetch['selectname'] . ' [D]</option>';
      $givenselect1 .= '<option value="' . $fetch['selectid'] . '">' . $fetch['selectname'] . ' [D]</option>';
    }
    break;
  case "Dealer":
    $givenselect = '<option value="" selected="selected">- - - - All - - - - </option><option value="web">Web Downloads</option>';
    $givenselect1 = '<option value="" selected="selected">- - - - All - - - -</option>';
    //Add respective Dealer name
    $query = "select lms_users.id AS selectid, dealers.dlrcompanyname AS selectname from lms_users join dealers on lms_users.referenceid = dealers.id where lms_users.username = '" . $cookie_username . "'";
    $result = runmysqlquery($query);
    while ($fetch = mysqli_fetch_array($result)) {
      $givenselect .= '<option value="' . $fetch['selectid'] . '">' . $fetch['selectname'] . ' [D]</option>';
      $givenselect1 .= '<option value="' . $fetch['selectid'] . '">' . $fetch['selectname'] . ' [D]</option>';
    }
    break;
  case "Dealer Member":
    $givenselect = '<option value="" selected="selected">- - - - All - - - -</option><option value="web">Web Downloads</option>';
    $givenselect1 = '<option value="" selected="selected">- - - - All - - - -</option><option value="web">Web Downloads</option>';
    break;
}
// For reference List
$query6 = "select distinct refer from leads where refer <> '' order by refer";
$result6 = runmysqlquery($query6);
$referenceselect .= '<option value="" selected="selected">- - - All - - - </option>';
while ($fetch6 = mysqli_fetch_array($result6)) {
  $referenceselect .= '<option value="' . $fetch6['refer'] . '">' . $fetch6['refer'] . '</option>';
}
$query7 = "select filteredtoexcel from lms_users where username = '" . $cookie_username . "'";
$result7 = runmysqlqueryfetch($query7);
$filteredtoexcel = $result7['filteredtoexcel'];
if ($filteredtoexcel == 'yes') {
  $disabled = "";
} else {
  $disabled = "disabled='disabled'";
}
?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>LMS | Update</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand()); ?>">
  <script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand()); ?>" language="javascript"></script>
  <script src="../functions/jsfunctions.js?dummy=<?php echo (rand()); ?>" language="javascript"></script>
  <script src="../functions/indextable.js?dummy=<?php echo (rand()); ?>" language="javascript"></script>

  <script type="text/javascript">
    $(document).ready(function () {

      var textarea = $('#leadMeetRemarks');
      textarea.hide();

      var leadstatus = $('#form_subleadstatus');
      leadstatus.hide();

      $('#form_leadstatus').change(function () {

        select = $('#form_leadstatus').val();
        if (select == 'Requirement Does not Meet') {
          textarea.show();
        }
        else {
          textarea.val('');
          textarea.hide();
        }

        if (select == 'Not Interested') {
          leadstatus.show();
        }
        else {
          leadstatus.val('');
          leadstatus.hide();
        }
      });

      $('#form_subleadstatus').change(function () {

        selectval = $('#form_subleadstatus').val();
        if (selectval == 'Requirement does not match' || selectval == 'Other') {
          textarea.show();
        }
        else {
          textarea.val('');
          textarea.hide();
        }
      });


    });
  </script>
  <!--ends here -->
</head>

<!-- <body onload="leadgridtab5('1','tabgroupleadgrid','default'),newtog(),updatestatusstrip();"> -->



  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="display:none;">
    <tr>
      <td valign="top" style="border:solid 1px #999999">

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" height="50"><span id="leadisplay">
                <table width="100%" border="0" cellspacing="0" cellpadding="1">
                  <tr height="30px">
                    <td width="125"><strong>Company [id]</strong>: </td>
                    <td colspan="2">
                      <font color="#FF6600"><span id="id"> </span></font>
                      <input type="hidden" name="hiddenid" id="hiddenid" value="" />
                      <input type="hidden" name="hiddencompany" id="hiddencompany" />
                    </td>
                  </tr>
                  <tr height="20px">
                    <td><strong>Contact person</strong>:<font color="#FF6600">
                        <input name="hiddencontact" id="hiddencontact" type="hidden" value="" />
                      </font>
                    </td>
                    <td colspan="2">
                      <font color="#FF6600">
                        <input name="contactperson" id="contactperson" type="text" autocomplete="off"
                          style="width:230px; color:#FF6600;border:1px solid #E1E1E1;" />
                      </font>
                    </td>
                  </tr>
                  <tr height="20px">
                    <td><strong>Address:<font color="#FF6600">
                          <input name="hiddenaddress" id="hiddenaddress" type="hidden" value="" />
                        </font></strong></td>
                    <td colspan="2">
                      <font color="#FF6600">
                        <input name="address" id="address" type="text" autocomplete="off"
                          style="width:230px; color:#FF6600;border:1px solid #E1E1E1;" />
                      </font>
                    </td>
                  </tr>
                  <tr height="20px">
                    <td><strong>District / State</strong>:
                      <input type="hidden" name="hiddendistrictstate" id="hiddendistrictstate" />
                    </td>
                    <td colspan="2">
                      <font color="#FF6600"><span id="district"></span></font>
                    </td>
                  </tr>
                  <tr height="20px">
                    <td><strong>STD Code</strong>:
                      <input type="hidden" name="hiddenstdcode" id="hiddenstdcode" />
                    </td>
                    <td colspan="2">
                      <font color="#FF6600">
                        <input name="stdcode" id="stdcode" type="text" autocomplete="off"
                          style="width:230px; color:#FF6600;border:1px solid #E1E1E1;" />
                      </font>
                    </td>
                  </tr>
                  <tr height="20px">
                    <td><strong>Landline</strong>:
                      <input type="hidden" name="hiddenphone" id="hiddenphone" />
                    </td>
                    <td colspan="2">
                      <font color="#FF6600">
                        <input name="phone" id="phone" type="text" autocomplete="off"
                          style="width:230px; color:#FF6600;border:1px solid #E1E1E1;" />
                      </font>
                    </td>
                  </tr>
                  <tr height="20px">
                    <td><strong>Cell</strong>:
                      <input type="hidden" name="hiddencell" id="hiddencell" />
                    </td>
                    <td colspan="2">
                      <font color="#FF6600">
                        <input name="cell" id="cell" type="text" autocomplete="off"
                          style="width:230px;color:#FF6600;border:1px solid #E1E1E1;" />
                      </font>
                    </td>
                  </tr>
                  <tr height="20px">
                    <td><strong>Email ID</strong>:
                      <input type="hidden" name="hiddenemailid" id="hiddenemailid" />
                    </td>
                    <td colspan="2">
                      <font color="#FF6600">
                        <input name="emailid" id="emailid" type="text" autocomplete="off"
                          style="width:230px;color:#FF6600; border:1px solid #E1E1E1;" />
                      </font>
                    </td>
                  </tr>
                  <tr height="35px">
                    <td valign="top"><strong>Reference [Type] :
                        <input type="hidden" name="hiddenreference" id="hiddenreference" />
                      </strong></td>
                    <td colspan="2" valign="top">
                      <font color="#FF6600"><span id="referencetype"></span></font>
                    </td>
                  </tr>
                  <tr height="20px">
                    <td><strong>Given By :
                        <input type="hidden" name="hiddengivenby" id="hiddengivenby" />
                        <input type="hidden" name="hiddengivenbytext" id="hiddengivenbytext" />
                      </strong></td>
                    <td width="212">
                      <font color="#FF6600"><span id="givenby1"></span></font>
                    </td>
                    <td width="20"><span id="help" style="display:none;"><img src="../images/help-image.gif"
                          onmouseover="tooltip('All')" onMouseout="hidetooltip()" class="imageclass" /></span></td>
                  </tr>
                  <tr height="20px">
                    <td><strong>Date of lead:
                        <input type="hidden" name="hiddendateoflead" id="hiddendateoflead" />
                      </strong></td>
                    <td colspan="2">
                      <font color="#FF6600"><span id="dateoflead"></span></font>
                    </td>
                  </tr>
                  <tr height="20px">
                    <td><strong>Dealer viewed date:
                        <input type="hidden" name="hiddendealerviewdate" id="hiddendealerviewdate" />
                      </strong></td>
                    <td colspan="2">
                      <font color="#FF6600"><span id="dealerviewdate"></span></font>
                    </td>
                  </tr>
                  <tr height="20px">
                    <td><strong>Product:
                        <input type="hidden" name="hiddenproduct" id="hiddenproduct" />
                      </strong></td>
                    <td colspan="2">
                      <font color="#FF6600"><span id="product1"></span></font>
                    </td>
                  </tr>

                  <?php if (($cookie_usertype == 'Admin') || ($cookie_usertype == 'Sub Admin') || ($cookie_usertype == 'Reporting Authority') || ($cookie_usertype == 'Dealer') || ($cookie_usertype == 'Dealer Member')) { ?>
                    <tr height="20px">
                      <td><strong>Dealer:
                          <input type="hidden" name="hiddendealer" id="hiddendealer" />
                          <input type="hidden" name="hiddentype" id="hiddentype"
                            value="<?php echo ($cookie_usertype); ?>" />
                          <input type="hidden" name="hiddendealertext" id="hiddendealertext" />
                        </strong></td>
                      <td>
                        <font color="#FF6600"><span id="dealer1"></span></font>
                      </td>
                      <td><span id="help1" style="display:none;"><img src="../images/help-image.gif"
                            onMouseover="tooltip('Dealer')" onMouseout="hidetooltip()" class="imageclass" /></span></td>
                    </tr>
                    <tr>
                      <td colspan="2" valign="top" height="25px">
                        <div id="link" align="center" style="display:none;"><span onclick="divopenclosefunction()"
                            class="transferleadclass">Transfer this Lead >></span></div>
                        <div id="selectlist" style="display:none;" align="left"><strong>Transfer
                            To:</strong>&nbsp;&nbsp;&nbsp;
                          <select name="dealerlist1" id="dealerlist1" style="width:180px">
                            <?php echo ($dealerselect1); ?>
                          </select>

                          &nbsp;&nbsp;<img src="../images/lmsreset-button.jpeg" onclick="resetlink('open')"
                            class="imageclass" />&nbsp;&nbsp;<img src="../images/lmsclose-button.jpeg"
                            onclick="resetlink('close')" class="imageclass" />
                        </div>
                      </td>
                    </tr>

                    <tr height="20px">
                      <td><strong>Dealer Member:
                          <input type="hidden" name="hiddendealer" id="hiddendealer" />
                          <input type="hidden" name="hiddentype" id="hiddentype"
                            value="<?php echo ($cookie_usertype); ?>" />
                          <input type="hidden" name="hiddendealertext" id="hiddendealertext" />
                        </strong></td>
                      <td>
                        <font color="#FF6600"><span id="dealer2"></span></font>
                      </td>
                      <td><span id="help1" style="display:none;"><img src="../images/help-image.gif"
                            onMouseover="tooltip('Dealer')" onMouseout="hidetooltip()" class="imageclass" /></span></td>
                    </tr>
                  <?php }
                  if (($cookie_usertype == 'Dealer') && $userslno != '1069') { ?>
                    <tr>
                      <td colspan="2" valign="top" height="25px">
                        <div id="link1" align="center" style="display:none;"><span onclick="divopenclosefunction()"
                            class="transferleadclass">Assign this Lead >></span></div>
                        <div id="selectlist1" style="display:none;" align="left"><strong>Transfer To
                            Sams:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <select name="dealermemberlist" id="dealermemberlist" style=" width:180px">
                            <?php echo ($dlrmbrselect); ?>
                          </select>
                          &nbsp;&nbsp;<img src="../images/lmsreset-button.jpeg" onclick="resetlink('open')"
                            class="imageclass" />&nbsp;&nbsp;<img src="../images/lmsclose-button.jpeg"
                            onclick="resetlink('close')" class="imageclass" />
                        </div>
                      </td>
                    </tr>
                  <?php }
                  if (($cookie_usertype == 'Dealer') && $userslno == '1069') { ?>
                    <tr>
                      <td colspan="2" valign="top" height="25px">
                        <div id="link1" align="center" style="display:none;"><span onclick="divopenclosefunction()"
                            class="transferleadclass">Assign this Lead >></span></div>
                        <div id="selectlist1" style="display:none;" align="left"><strong>Transfer To
                            Sams:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <select name="dealermemberlist" id="dealermemberlist" style=" width:180px">
                            <?php include '../inc/lmsdealers.php'; ?>
                          </select>
                          &nbsp;&nbsp;<img src="../images/lmsreset-button.jpeg" onclick="resetlink('open')"
                            class="imageclass" />&nbsp;&nbsp;<img src="../images/lmsclose-button.jpeg"
                            onclick="resetlink('close')" class="imageclass" />
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                  <tr height="20px">
                    <td>
                      <strong>Manager:
                        <input type="hidden" name="hiddenmanager" id="hiddenmanager" />
                        <input type="hidden" name="hiddenmanagertext" id="hiddenmanagertext" />
                      </strong>
                    </td>
                    <td>
                      <font color="#FF6600"><span id="manager"></span></font>
                    </td>
                    <td><span id="help2" style="display:none;"><img src="../images/help-image.gif"
                          onMouseover="tooltip('Manager')" ; onMouseout="hidetooltip()" class="imageclass" /></span>
                    </td>
                  </tr>
                  <tr>
                    <td height="5px" colspan="3"></td>
                  </tr>
                  <tr height="20px">
                    <td colspan="3">

                    </td>
                  </tr>
                </table>
              </span></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <table width="100%" border="0" cellspacing="0" cellpadding="1"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 2;"
    id="overlay-container">
    <tr>
      <td
        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 20px; border: 1px solid #ccc;">
        <span style="position: absolute; top: 10px; right: 10px; cursor: pointer;" onclick="closeOverlay()">✖</span>
        <div id="smallgrid" class="grid-div-small1">
          <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999">
            <tr class="gridheader">
              <td width="9%">Sl No</td>
              <td width="14%">Date</td>
              <td width="37%">Remarks</td>
              <td width="20%">Next Follow-up</td>
              <td width="20%">Entered by</td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
  </table>



  <form id="toexcelform" name="toexcelform" action="" method="post" style="width: 50%;"
    onclick="toggleOverlay('grid-div-small1');" >

    <!-- <form id="toexcelform" name="toexcelform" action="" method="post" style="width: 50%; " onclick="leadgridtab5('3','tabgroupleadgrid','todayfollowup');"> -->
    <div id="tabgroupleadgridc1" style="display: block;" >
      <table width="100%" border="0" cellspacing="0" cellpadding="0"
        style="border: 1px solid #308ebc; border-top: none;"
        onclick="leadgridtab5('3','tabgroupleadgrid','todayfollowup');" >
        <tr class="headerline">
          <td width="50%"><span id="gridprocess"></span></td>
          <td width="50%" align="right" id="gridprocess1"></td>
        </tr>
        <tr>
          <td colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="3">
                  <div id="tabgroupgridc1" style="overflow: auto; height: 260px; width: 945px" align="center">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
                          <div id="tabgroupgridc1_1" align="center"></div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div id="getmorelink" align="left" style="height: 20px;"></div>
                        </td>
                      </tr>
                    </table>
                  </div>
                  <div id="resultgrid" style="overflow: auto; display: none; height: 150px; width: 695px;"
                    align="center">&nbsp;</div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>

    <div id="tabgroupleadgridc3" style="display: none;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0"
        style="border: 1px solid #308ebc; border-top: none;">
        <tr class="headerline">
          <td width="15%">
            <div id="followuptotal">&nbsp;</div>
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="3">
                  <div id="tabgroupgridfup1" style="overflow: auto; height: 260px; width: 947px" align="center">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
                          <div id="tabgroupgridfup1_1" align="center"></div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div id="getmorelinkfup" align="left" style="height: 20px;"> </div>
                        </td>
                      </tr>
                    </table>
                  </div>
                  <div id="resultgridfup" style="overflow: auto; display: none; height: 150px; width: 695px;"
                    align="center">&nbsp;</div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
  </form>

  <script>





    function toggleOverlay(tableClassName) {
      var overlayContainer = document.getElementById("overlay-container");

      // Find the table with the specified class name
      var tableToOverlay = document.querySelector('.' + tableClassName);

      // Show the overlay
      overlayContainer.style.display = "block";
    }

    function closeOverlay() {
      var overlayContainer = document.getElementById("overlay-container");
      overlayContainer.style.display = "none";
  }


    
  </script>


</body>

</html>