<?php
// error_reporting(0);
// error_reporting(E_ALL);
// ini_set("display_errors",1);

// session_start();
include("./functions/phpfunctions.php");
$isloggedin = 'false';

// Check if the necessary cookies are set for a logged-in user
if (
  lmsgetcookie('applicationid') == '8616153779973246153879' &&
  lmsgetcookie('sessionkind') !== false &&
  lmsgetcookie('lmsusername') !== false &&
  lmsgetcookie('lmsusersort') !== false &&
  lmsgetcookie('lmslastlogindate') !== false
) {
  $cookie_logintype = lmsgetcookie('sessionkind');
  $isloggedin = 'true';
}

// Check for specific logout conditions and session verification
$cookie_logintype = isset($_COOKIE['logintype']) ? $_COOKIE['logintype'] : '';

if (
  $cookie_logintype == 'logoutforthreemin' ||
  $cookie_logintype == 'logoutforsixhr' ||
  $cookie_logintype == 'logoutforever'
) {
  if ($_SESSION['verificationid'] == '4563464364365554545454') {
    $isloggedin = 'true';
  } else {
    $isloggedin = 'false';
  }
}

// Redirect logged-in users to a confirmation page
if ($isloggedin == 'true') {
  header('Location: ./home/confirmation.php');
  exit; // Terminate script execution after redirection
}

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $loggintype = $_POST['loggintype'];
  $message = "";

  // Check if username and password are empty
  if ($username == "" || $password == "") {
    $message = "Please enter your Username and/or Password";
  }

  if ($message == "") {
    // Retrieve user data from the database
    $query = "SELECT * FROM lms_users WHERE username = '" . $username . "' AND disablelogin = 'no'";
    $result = runmysqlquery($query);
    $fetch = mysqli_fetch_array($result);

    if ($fetch['type'] == 'Dealer Member') {
      // Retrieve dealer information
      $query1 = "SELECT referenceid FROM lms_users WHERE username = '" . $username . "'";
      $userReference = runmysqlqueryfetch($query1);

      if ($userReference) {
        $query2 = "SELECT dealerid FROM lms_dlrmembers WHERE dlrmbrid = '" . $userReference['referenceid'] . "'";
        $dealerIdResult = runmysqlqueryfetch($query2);

        if ($dealerIdResult) {
          // Check if the dealer is enabled
          $query3 = "SELECT * FROM lms_users WHERE referenceid = '" . $dealerIdResult['dealerid'] . "' AND disablelogin <> 'yes' AND type = 'Dealer';";
          $result1 = runmysqlquery($query3);
          $count = mysqli_num_rows($result1);

          if ($count > 0) {
            // Perform user authentication
            $query = "SELECT * FROM lms_users WHERE username = '" . $username . "' AND password = '" . $password . "'";
            $result = runmysqlquery($query);
            $presence = mysqli_num_rows($result);

            if ($presence == 0) {
              $message = "This User ID is not valid or Login may be disabled.";
            } else {
              $row = runmysqlqueryfetch($query);
              if ($row['password'] <> $password) {
                $message = "Invalid Password.";
              }
            }
          } else {
            $message = "Sorry, you cannot log in as the dealer is disabled.";
          }
        } else {
          // Handle the case where the dealer ID was not found
        }
      } else {
        // Handle the case where the user's reference ID was not found
      }
    } else {
      // Handle authentication for non-Dealer Member users
      $query = "SELECT * FROM lms_users WHERE username = '" . $username . "' AND password = '" . $password . "'";
      $result = runmysqlquery($query);
      $presence = mysqli_num_rows($result);

      if ($presence == 0) {
        $message = "This User ID is not valid or login may be disabled.";
      } else {
        $row = runmysqlqueryfetch($query);
        if ($row['password'] <> $password) {
          $message = "Invalid Password.";
        }
      }
    }

    if ($message == "") {
      // Get user details and update login information
      $usertype = $row['type'];
      $userslno = $row['id'];
      $lastlogindate = $row['lastlogindate'];
      $lastlogindate = ($lastlogindate == '0000-00-00') ? ("First Time") : (changedateformat($lastlogindate));
      $logincount = $row['logincount'] + 1;
      $logindate = datetimelocal("Y-m-d");
      $logintime = datetimelocal("H:i:s");
      $systemip = $_SERVER['REMOTE_ADDR'];

      // Set session and cookies
      if (
        $loggintype == 'logoutforthreemin' ||
        $loggintype == 'logoutforsixhr' ||
        $loggintype == 'logoutforever'
      ) {
        session_start();
        $_SESSION['verificationid'] = '4563464364365554545454';
      }

      lmscreatecookie('sessionkind', $loggintype);
      lmscreatecookie('applicationid', '8616153779973246153879');
      lmscreatecookie('lmsusername', $username);
      lmscreatecookie('lmsusersort', $usertype);
      lmscreatecookie('lmslastlogindate', $lastlogindate);

      // Update login information in the database
      $query = "UPDATE lms_users SET lastlogindate = '" . $logindate . "', logincount = '" . $logincount . "' WHERE username = '" . $username . "'";
      $result = runmysqlquery($query);

      // Insert login logs
      $query = "INSERT INTO lms_logs_event(userid, system, eventtype, eventdatetime) VALUES ('" . $userslno . "', '" . $systemip . "', '43', '" . $logindate . ' ' . $logintime . "')";
      $result = runmysqlquery_log($query);

      // Redirect user to a confirmation page
      if (isset($_GET['link']) && isurl($_GET['link']) && isvalidhostname($_GET['link'])) {
        header('Location: ' . $_GET['link']);
      } else {
        header('Location: ./home/confirmation.php');
      }
      exit; // Terminate script execution after redirection
    }
  }
}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Lead Management Software | V2</title>
  <link rel="stylesheet" type="text/css" href="./css/style.css?dummy=<?php echo (rand()); ?>">
  <script>
    function dofocus(fieldid) {
      var fieldname = document.getElementById(fieldid);
      fieldname.focus();
    }
  </script>
  <!--[if lt IE 7]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
<![endif]-->
  <style type="text/css">
    .content table tr td table tr .loginbox #loginform table tr td #logoutforthreemin {
      text-align: right;
    }
  </style>
</head>

<body onload="dofocus('username');">
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
                        <td width="14%"><a href="http://lms.relyonsoft.net"><img src="images/lms-logo.gif"
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
      <!-- <td class="bannerbg" height="50" style="background:red;color:#fff;text-align:center;font-size:21px">TEST LINK</td> -->
    </tr>
    <tr>
      <td valign="middle" bgcolor="#D2D8FB" class="contentheader">
        <table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
          <tr>
            <td>Login</td>
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
                    <form id="loginform" name="loginform" method="post" action="">
                      <table width="100%" border="0" cellspacing="0" cellpadding="4">
                        <tr bgcolor="#6295C6">
                          <td colspan="3">
                            <font color="#FFFFFF">Please enter your login details to proceed...</font>
                          </td>
                        </tr>
                        <tr>
                          <td height="20" colspan="3" align="center">
                            <font color="#FF0000">
                              <?php
                              $message = '';
                              echo ($message);
                              ?>
                            </font>
                          </td>
                        </tr>
                        <tr>
                          <td width="5%" height="20"></td>
                          <td width="24%">Login Name:</td>
                          <td width="71%"><input name="username" type="text" class="formfields" id="username" size="40"
                              maxlength="50" /></td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td>Password:</td>
                          <td><input name="password" type="password" class="formfields" id="password" size="40"
                              maxlength="50" /></td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td align="right"><input type="radio" name="loggintype" id="logoutforthreemin"
                              value="logoutforthreemin" checked="checked" /></td>
                          <td><label for="logoutforthreemin">Logout automatically on 3 Minutes idle time</label></td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td align="right"><input type="radio" name="loggintype" id="logoutforsixhr"
                              value="logoutforsixhr" /></td>
                          <td><label for="logoutforsixhr">Logout automatically on 6 Hours idle time</label></td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td align="right"><input type="radio" name="loggintype" id="logoutforever"
                              value="logoutforever" /></td>
                          <td><label for="logoutforever">Logged in forever (For 1 week)</label></td>
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
                              <input name="login" type="submit" class="formbutton" id="login" value="Login" />
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
                            <div align="right"><a href="password.php">Retrieve your Password</a></div>
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
    <tr>
      <!-- <td class="bannerbg" height="50" style="background:red;color:#fff;text-align:center;font-size:21px">TEST LINK</td> -->
    </tr>
  </table>


</body>

</html>