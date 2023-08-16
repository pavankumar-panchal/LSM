<?php
exit();
include("./functions/phpfunctions.php");

if ($_POST['send']) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $message = "";

  if ($username == "" or $email == "")
    $message = "Please enter your Username and/or Email ID";

  if ($message == "") {
    $query = "SELECT * FROM lms_users WHERE username = '" . $username . "'";
    $result = runmysqlquery($query);
    $presence = mysqli_num_rows($result);

    if ($presence == 0)
      $message = "This User ID is not valid.";
    else {
      $row = runmysqlqueryfetch($query);
      $type = $row['type'];
      $password = $row['password'];
      $referenceid = $row['referenceid'];
      switch ($type) {
        case "Admin":
          $message = "Unable to proceed for Admin Username.";
          break;
        case "Sub Admin":
          $query = "Select sademailid AS email from lms_subadmins where id = '" . $referenceid . "'";
          $result = runmysqlqueryfetch($query);
          $dbemail = $result['email'];
          if ($dbemail <> $email)
            $message = "Email ID did not match.";
          break;
        case "Reporting Authority":
          $query = "Select mgremailid AS email from lms_managers where id = '" . $referenceid . "'";
          $result = runmysqlqueryfetch($query);
          $dbemail = $result['email'];
          if ($dbemail <> $email)
            $message = "Email ID did not match.";
          break;
        case "Dealer":
          $query = "Select dlremail AS email from dealers where id = '" . $referenceid . "'";
          $result = runmysqlqueryfetch($query);
          $dbemail = $result['email'];
          if ($dbemail <> $email)
            $message = "Email ID did not match.";
          break;
        case "Implementer":
          $query = "Select impemailid AS email from lms_implementers where id = '" . $referenceid . "'";
          $result = runmysqlqueryfetch($query);
          $dbemail = $result['email'];
          if ($dbemail <> $email)
            $message = "Email ID did not match.";
          break;
      }
    }
    if ($message == "") {
      //Email download information to user
      $Toemailid = $email;
      $FromName = "Relyon Dealers";
      $FromAddress = "samar.s@relyonsoft.com";
      $MailSubject = "Login Information for Dealer Zone.";
      $headers = 'From: ' . $FromName . ' <' . $FromAddress . '>' . "\r\n";
      $msg = file_get_contents("./inc/mail-password-user.php");
      $array = array("##EMAIL##" => $email, "##LOGINNAME##" => $username, "##PASSWORD##" => $password);
      $msg = replacemailvariable($msg, $array);

      if (mail($Toemailid, $MailSubject, $msg, $headers)) {
        $message = "Login details have been emailed to " . $email . ".";
      }
    }
  }
}

?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>LMS | Get Password</title>
  <link rel="stylesheet" type="text/css" href="./css/style.css">

</head>

<body>
  <table width="950" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td class="pageheader">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="28">
              <table width="99%" border="0" align="center" cellpadding="2" cellspacing="0" class="header1">
                <tr>
                  <td width="82%"><strong>Relyon Softech Ltd</strong></td>
                  <td width="18%">Lead Management Software</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td height="58" valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="4"></td>
                </tr>
                <tr>
                  <td height="54">
                    <table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
                      <tr>
                        <td width="14%"><a href="http://useradmin.relyonsoft.com"><img src="images/lms-logo.gif"
                              alt="Lead Management Software" width="100" height="50" border="0" /></a></td>
                        <td width="86%"><span style="color: #477AC3; font-size:14px">Relyon Dealer Zone
                            <strong>||</strong> Gateway to Leads, Billing, and other Advanced Services.</span></td>
                      </tr>
                    </table>
                  </td>
                </tr>

              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td height="1"></td>
    </tr>
    <tr>
      <td valign="middle" bgcolor="#D2D8FB" class="contentheader">
        <table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
          <tr>
            <td>Get Password for your username</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td height="2"></td>
    </tr>
    <tr>
      <td height="1" bgcolor="#006699"></td>
    </tr>
    <tr>
      <td valign="top" class="content">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="20"></td>
          </tr>
          <tr>
            <td height="300" valign="top">
              <table width="40%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="20"></td>
                </tr>
                <tr>
                  <td class="loginbox">
                    <form id="passwordform" name="loginform" method="post" action="">
                      <table width="100%" border="0" cellspacing="0" cellpadding="4">
                        <tr bgcolor="#6295C6">
                          <td colspan="3">
                            <font color="#FFFFFF">Password retrival tool</font>
                          </td>
                        </tr>
                        <tr>
                          <td height="20" colspan="3" align="center">
                            <font color="#FF0000">
                              <?php echo ($message); ?>
                            </font>
                          </td>
                        </tr>
                        <tr>
                          <td width="7%" height="20"></td>
                          <td width="30%">Login Name:</td>
                          <td width="63%"><input name="username" type="text" class="formfields" id="username" size="35"
                              maxlength="50" /></td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td>Your email:</td>
                          <td><input name="email" type="text" class="formfields" id="email" size="35" maxlength="50" />
                          </td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td></td>
                          <td>
                            <div align="center">
                              <input name="send" type="submit" class="formbutton" id="send" value="Send Details" />
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input name="clear" type="reset" class="formbutton" id="clear" value="Clear" />
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td></td>
                          <td>
                            <div align="right"><a href="index.php">Back to Login page</a></div>
                          </td>
                        </tr>
                      </table>
                    </form>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td height="20"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td height="1"></td>
    </tr>
    <tr>
      <td valign="top" class="pagefooter">
        <table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td height="8"></td>
          </tr>
          <tr>
            <td>
              <table width="100%" border="0" cellpadding="4" cellspacing="0" class="footer">
                <tr>
                  <td width="50%">Copyright © Relyon Softech Limited. All rights reserved. </td>
                  <td width="50%">
                    <div align="right"><a href="http://www.relyonsoft.com" target="_blank">www.relyonsoft.com</a> | <a
                        href="http://www.saraltaxoffice.com" target="_blank">www.saraltaxoffice.com</a> | <a
                        href="http://www.saralpaypack.com" target="_blank">www.saralpaypack.com</a> | <a
                        href="http://www.saraltds.com" target="_blank">www.saraltds.com</a></div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>


</body>

</html>