<table width="99%" border="0" align="center" cellpadding="2" cellspacing="0" class="header1">
  <tr>
    <td width="69%"><strong>Logged in as:</strong>
      <?php echo ($cookie_username); ?> | Type:
      <?php echo ($cookie_usertype); ?> | Last Login:
      <?php echo ($cookie_lastlogindate); ?>
    </td>
    <td width="31%">
      <div align="right"><a href="../logout.php">Logout</a> | Version: Beta 2
        <?php if ($cookie_usertype == "Dealer") {
          echo (' | <a href="../home/lms-help-dlr.pdf" target="_blank">Help [PDF]</a>');
        } elseif ($cookie_usertype == "Dealer Member") {
          echo (' | <a href="../home/lms-help-dlrmbr.pdf" target="_blank">Help [PDF]</a>');
        } elseif ($cookie_usertype == "Reporting Authority") {
          echo (' | <a href="../home/lms-help-mbr.pdf" target="_blank">Help [PDF]</a>');
        } ?>
      </div>
    </td>
  </tr>
</table>