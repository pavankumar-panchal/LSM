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
</head>

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
<form id="toexcelform" name="toexcelform" action="" method="post" style="width: 50%;"  onclick="toggleOverlay('grid-div-small1');">
  <div id="tabgroupleadgridc1" style="display: block;" >
 
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #308ebc; border-top: none;" >
     
  <tr class="headerline">
        <td width="50%"><span id="gridprocess" style="cursor:pointer;"  onclick="leadgridtab5('3','tabgroupleadgrid','todayfollowup');">&nbsp;SHOW </span></td>
        <td width="50%" align="right" id="gridprocess1"></td>
      </tr>
      <tr>
     
      <tr class="" style="display:flex;flex-direction:row;  justify-content:space-around; width:100%; background-color:#b2cffe; width:60rem;">
      <td nowrap="nowrap" class="tdborderlead" >&nbsp;Sl No</td>
      <td nowrap="" class="tdborderlead">Lead ID</td>
      <td nowrap="" class="tdborderlead">Followup ID</td>
      <td nowrap="" class="tdborderlead">Followup Date</td>
      <td nowrap="" class="tdborderlead">Remarks</td>
      <td nowrap="" class="tdborderlead">Next Follow-up</td>
      <td nowrap="" class="tdborderlead">Enterd by</td>
    </tr>
        <td colspan="2">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" >
            <tr>
              <td colspan="3">
                <div id="tabgroupgridc1" style="overflow: auto; height: 260px; width: 945px;" align="center">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
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
                <div id="resultgrid" style="overflow: auto; display: none; height: 150px; width: 695px;" align="center">&nbsp;</div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      
    </table>
  </div>
  

  <div id="tabgroupleadgridc3" style="display: none;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #308ebc; border-top: none;">
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
                <div id="tabgroupgridfup1" style="overflow: auto; height: 260px; width: 947px;" align="center">
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
                <div id="resultgridfup" style="overflow: auto; display: none; height: 150px; width: 695px;" align="center">&nbsp;</div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>

  <!-- Your table goes here -->

</form>



<script>
  function toggleOverlay(tableClassName) {
    var overlayContainer = document.getElementById("overlay-container");
    
    // Find the table with the specified class name
    var tableToOverlay = document.querySelector('.' + tableClassName);

    // Check if the table has any rows
    if (tableToOverlay && tableToOverlay.getElementsByTagName('tr').length > 1) {
      // Show the overlay
      overlayContainer.style.display = "block";
    } else {
      // Don't show the overlay if the table is empty
      overlayContainer.style.display = "none";
    }
  }

  function closeOverlay() {
    var overlayContainer = document.getElementById("overlay-container");
    overlayContainer.style.display = "none";
  }
</script>



</body>

</html>