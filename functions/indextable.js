
function gridtoform(id) {
  sendsms("reset");
  resetproductchange();
  $("#showeditimage").hide();
  $("#leadremarksbox").hide();
  var error = $("#messagebox1");
  if (
    $("#hiddentype").val() == "Admin" ||
    $("#hiddentype").val() == "Sub Admin" ||
    $("#hiddentype").val() == "Reporting Authority"
  ) {
    $("#selectlist").hide();
  } else if ($("#hiddentype").val() == "Dealer") {
    $("selectlist1").hide();
  }
  if ($("#hiddenid").val() != id) {
    leadtrack("tracker");
  }
  error.html("");
  var passdata =
    "&submittype=gridtoform&form_recid=" +
    id +
    "&dummy=" +
    Math.floor(Math.random() * 100032680100); //alert(passdata)
  var queryString = "../ajax/indextable.php";
  ajaxobjext59 = $.ajax({
    type: "POST",
    url: queryString,
    data: passdata,
    cache: false,
    success: function (response, status) {
      if (response == "Thinking to redirect") {
        window.location = "../logout.php";
        return false;
      } else {
        var response3 = response.split("|^|"); // alert(response3);
        if (response3[0] == "1") {
          $("#help").show();
          $("#help1").show();
          $("#help2").show();
          if (
            $("#hiddentype").val() == "Admin" ||
            $("#hiddentype").val() == "Sub Admin" ||
            $("#hiddentype").val() == "Reporting Authority"
          ) {
            //document.getElementById('displaydiv').style.display = 'block';
            $("#link").show();
            $("#dealerlist1").val("");
          } else if ($("#hiddentype").val() == "Dealer") {
            $("#link1").show();
            $("#dealermemberlist").val("");
          }
          $("#form_recid").val(response3[1]); //alert($('#form_recid').val())
          $("#hiddenid").val(response3[3]); //alert(document.getElementById('hiddenid').value );
          $("#id").html(response3[2] + " " + "[" + response3[3] + "]");
          $("#hiddencompany").val(
            response3[2] + " " + "[" + response3[3] + "]"
          );
          $("#newleadcompany").html(response3[2]);
          $("#hiddennewleadcompany").val(response3[2]);
          $("#contactperson").val(response3[4]);
          $("#hiddencontact").val(response3[4]);
          $("#newleadcontactperson").val(response3[4]);
          $("#hiddennewleadcontact").val(response3[4]);
          $("#address").val(response3[5]);
          $("#hiddenaddress").val(response3[5]);
          $("#district").html(response3[6] + " " + "/" + " " + response3[7]);
          $("#hiddendistrictstate").val(
            response3[6] + " " + "/" + " " + response3[7]
          );
          $("#stdcode").val(response3[8]);
          $("#hiddenstdcode").val(response3[8]);
          $("#phone").val(response3[9]);
          $("#hiddenphone").val(response3[9]);
          $("#cell").val(response3[10]);
          $("#hiddencell").val(response3[10]);
          $("#smscell").html(response3[10]);
          $("#hiddensmscell").val(response3[10]);
          $("#newleadcell").val(response3[10]);
          $("#hiddennewleadcell").val(response3[10]);
          $("#emailid").val(response3[11]);
          $("#hiddenemailid").val(response3[11]);
          $("#newleademailid").val(response3[11]);
          $("#hiddennewleademailid").val(response3[11]);
          $("#referencetype").html(
            response3[12] + " " + "[" + response3[13] + "]"
          );
          $("#hiddenreference").val(
            response3[12] + " " + "[" + response3[13] + "]"
          );
          $("#newleadsource").val(response3[12]);
          $("#hiddennewleadsource").val(response3[12]);
          $("#givenby1").html(response3[14]);
          $("#hiddengivenby").val(response3[14]);
          $("#dateoflead").html(response3[15]);
          $("#hiddendateoflead").val(response3[15]);
          $("#dealerviewdate").html(response3[16]);
          $("#hiddendealerviewdate").val(response3[16]);
          $("#product1").html(response3[17]);
          $("#hiddenproduct").val(response3[17]); //alert(response3[17])
          $("#dealer1").html(response3[33]);
          $("#dealer2").html(response3[34]);
          $("#hiddendealer").val(response3[18]);
          $("#manager").html(response3[20]);
          $("#hiddenmanager").val(response3[20]);
          $("#form_leadstatus").val(response3[21]);
          //autoselect('form_leadstatus',response3[21]);
          $("#leadremarks").html(response3[24]);
          if (response3[24] != "Not Available") {
            $("#hiddenleadremarks").val(response3[24]);
          } else $("#hiddenleadremarks").val("");

          $("#hiddengivenbytext").val(response3[27]);
          $("#hiddendealertext").val(response3[28]); //alert( response3[28]);
          $("#hiddenmanagertext").val(response3[29]); //alert( response3[29]);
          //document.getElementById('productchangeform').productchangeselect.value = response3[30];
          $("#changeproductdealerlist").val(response3[31]);
          $("#msg_box").html("");
          jumpToAnchor("leadview");
          showfollowups(response3[1]); //alert(response3[1])
          showsmslogs(response3[3], "");
          //newfollowup();
          if (response3[26] == response3[32]) {
            $("#showeditimage").show();
          }
          //alert(response3[21]);
          if (response3[21] == "Requirement Does not Meet") {
            ///$('#form_leadMeetRemarks').hide();
            $("#leadMeetRemarks").show();
            $("#leadMeetRemarks").val(response3[35]);
            //alert(response3[35]);
          } else {
            $("#leadMeetRemarks").hide();
            $("#leadMeetRemarks").val("");
          }

          if (response3[21] == "Not Interested") {
            $("#form_subleadstatus").show();
            $("#form_subleadstatus").val(response3[36]);
            if (
              response3[36] == "Requirement does not match" ||
              response3[36] == "Other"
            ) {
              $("#leadMeetRemarks").show();
              $("#leadMeetRemarks").val(response3[35]);
            } else {
              $("#leadMeetRemarks").hide();
              $("#leadMeetRemarks").val("");
            }
          } else {
            $("#form_subleadstatus").hide();
            $("#form_subleadstatus").val("");
          }
          checkDataRow(response3[3]);
        } else {
          $("#msg_box").html("");
        }
      }
    },
    error: function (a, b) {
      $("#tabgroupgridc1_1").html(scripterror1());
    },
  });
}

function showfollowups(leadid) {
  //var form_recid = $("#form_recid");
  $("#followupmessage").html(processing());
  var passdata =
    "&submittype=showfollowups&form_recid=" +
    leadid +
    "&dummy=" +
    Math.floor(Math.random() * 100032680100);
  var queryString = "../ajax/indextable.php";
  ajaxobjext62 = $.ajax({
    type: "POST",
    url: queryString,
    data: passdata,
    cache: false,
    success: function (response, status) {
      if (response == "Thinking to redirect") {
        window.location = "../logout.php";
        return false;
      } else {
        var response6 = response.split("^");
        if (response6[0] == "1") {
          $("#smallgrid").html(response6[1]);
          $("#followupmessage").html("");
          gridtab5("1", "tabgroupgrid", "followup");
        } else {
          $("#followupmessage").html(scripterror1());
        }
      }
    },
    error: function (a, b) {
      $("#followupmessage").html(scripterror1());
    },
  });
}

function leadgridtab5(activetab, groupname, activetype) {
  var totaltabs = 5;
  var activetabclass = "grid-active-leadtabclass";
  var tabheadclass = "grid-leadtabclass";
  for (var i = 1; i <= totaltabs; i++) {
    var tabhead = groupname + "h" + i;
    var tabcontent = groupname + "c" + i;
    if (i == activetab) {
      $("#" + tabhead).attr("class", activetabclass);
      $("#" + tabcontent).show();
      if (activetype == "default") {
        griddata("");
      } else if (activetype == "todayfollowup") {
        followupforday("");
      } else if (activetype == "nofollowup") {
        nofollowup("");
      } else if (activetype == "notviewed") {
        notviewed("");
      }
    } else {
      $("#" + tabhead).attr("class", tabheadclass);
      $("#" + tabcontent).hide();
    }
  }
}

function followupforday(startlimit) {
  $("#followuptotal").html(
    processing() +
      "  " +
      '<span onclick = "abortfollowupajaxprocess(\'initial\')" class="abort">(STOP)</span>'
  );
  var passdata =
    "&submittype=followupforday&startlimit=" +
    startlimit +
    "&dummy=" +
    Math.floor(Math.random() * 10230000000); //alert(passdata)
  var queryString = "../ajax/indextable.php";
  ajaxobjext63 = $.ajax({
    type: "POST",
    url: queryString,
    data: passdata,
    cache: false,
    success: function (response, status) {
      if (response == "Thinking to redirect") {
        window.location = "../logout.php";
        return false;
      } else {
        var ajaxresponse = response.split("^"); //alert(ajaxresponse);
        $("followuptotal").html("");
        if (ajaxresponse[0] == "1") {
          $("#tabgroupgridfup1_1").html(ajaxresponse[1]);
          $("#getmorelinkfup").html(ajaxresponse[2]);
          $("#followuptotal").html(
            '<font color="#FFFFFF"><strong>&nbsp;Total Count :  ' +
              ajaxresponse[3] +
              "</strong></font>"
          );
        } else {
          $("#followuptotal").html(scripterror1());
        }
      }
    },
    error: function (a, b) {
      $("#tabgroupgridc1_1").html(scripterror1());
    },
  });
}

function leadtrack(type) {
  if (type == "tracker") {
    $("#tracker").show();
    $("#changetheproduct").hide();
    $("#sendsms").hide();
  } else if (type == "productchange") {
    $("#errordisplay").html("");
    $("#leadremarks1").val("");
    $("#changetheproduct").show();
    $("#sendsms").hide();
    $("#tracker").hide();
    $("#productchangeselect").val("");
 
  } else if (type == "sendsms") {
    getmynumber();
    $("#tracker").hide();
    $("#changetheproduct").hide();
    $("#sendsms").show();
    sendsms("reset");
    $("#prior-sms-error").html("");
    var cellnumber = $("#smscell");
    var leadcardcell = $("#cell");
    var contactperson = $("#contactperson");
    var address = $("#address");
    var stdcode = $("#stdcode");
    var phone = $("#phone");
    var cell = $("#cell");
    var emailid = $("#emailid");
    var newleadsource = $("#newleadsource");
    if (
      contactperson.val() == "" &&
      address.val() == "" &&
      stdcode.val() == "" &&
      phone.val() == "" &&
      cell.val() == "" &&
      emailid.val() == ""
    ) {
      $("#prior-sms-error").html(errormessage("Please Select a Lead First"));
      return false;
    } else if (cellnumber.html() == "" && leadcardcell.val() == "") {
      $("#prior-sms-error").html(
        errormessage("Please Save Cell Number and then proceed.")
      );
      leadcardcell.focus();
      return false;
    }
  }
}

function resetproductchange() {
  $("#productchangeselect").val("");
  $("#leadremarks1").val("");
  $("#samedealer").attr("checked", true);
  $("#changeproductdealerlist").val("");
  $("#errordisplay").html("");
  $("#newleadcompany").html($("#hiddennewleadcompany").val());
  $("#newleadcontactperson").val($("#hiddennewleadcontact").val());
  $("#newleadcell").val($("#hiddennewleadcell").val());
  $("#newleademailid").val($("#hiddennewleademailid").val());
  $("#newleadsource").val($("#hiddennewleadsource").val());
  $("#dealerlistdiv").hide();
}

// Function to send SMS

  function sendsms(actiontype) {
    if (actiontype == "sms") {
      var cellnumber = $("#smscell"); //alert(cellnumber.innerHTML)
      var leadcardcell = $("#cell");
      var smstext = $("#smstext");
      var error = $("#sms-error");
      var contactperson = $("#contactperson");
      var address = $("#address");
      var stdcode = $("#stdcode");
      var phone = $("#phone");
      var cell = $("#cell");
      var emailid = $("#emailid");
      var leadid = $("#hiddenid");
      if (
        contactperson.val() == "" &&
        address.val() == "" &&
        stdcode.val() == "" &&
        phone.val() == "" &&
        cell.val() == "" &&
        emailid.val() == ""
      ) {
        error.html(errormessage("Please Select a Lead First"));
        return false;
      } else if (leadcardcell.val() == "" && cellnumber.html() == "") {
        error.html(errormessage("Please Save Cell Number and then proceed."));
        leadcardcell.focus();
        return false;
      } else if (smstext.val() == "") {
        error.html(errormessage("Please Enter SMS Text."));
        smstext.focus();
        return false;
      } else if (!validatecell(cellnumber.html())) {
        error.html(errormessage("Please Enter Valid Cell Number."));
        cellnumber.focus();
        return false;
      }
      error.html("");
      var val = "Are you sure you want to send SMS as '" + smstext.val() + "' ?";
      var confirmation = confirm(val);
      if (confirmation) {
        error.html(processing());
        var passdata =
          "&submittype=sendsms&cellnumber=" +
          cellnumber.html() +
          "&smstext=" +
          smstext.val() +
          "&leadid=" +
          leadid.val() +
          "&dummy=" +
          Math.floor(Math.random() * 10230000000); //alert(passdata)
        var queryString = "../ajax/indextable.php";
        ajaxobjext76 = $.ajax({
          type: "POST",
          url: queryString,
          data: passdata,
          cache: false,
          success: function (response, status) {
            if (response == "Thinking to redirect") {
              window.location = "../logout.php";
              return false;
            } else {
              var ajaxresponse = response.split("^"); //alert(ajaxresponse);
              if (ajaxresponse[0] == "1") {
                error.html(successmessage(ajaxresponse[1]));
                smstext.val("");
                showsmslogs(leadid, "");
              } else if (ajaxresponse[0] == "2") {
                error.html(errormessage(ajaxresponse[1]));
              } else {
                error.html(scripterror());
              }
            }
          },
          error: function (a, b) {
            error.html(scripterror());
          },
        });
      } else return false;
    } else if (actiontype == "reset") {
      $("#smscell").html($("#hiddensmscell").val());
      $("#smstext").val("");
      $("#sms-error").html("");
      $("#prior-sms-error").html("");
    }
  }