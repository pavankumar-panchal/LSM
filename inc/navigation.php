<script src="../functions/navigation.js" language="javascript"></script>

<ul id="primary-navigation">
  <li><a href="../home/index.php"><span>Dashboard</span></a> </li>
<? if($cookie_usertype == "Admin" || $cookie_usertype == "Dealer") { ?>
  <li><a><span>Masters</span></a>
    <div>
      <ul>
<? if($cookie_usertype == "Admin") { ?>
        <li><a href="../master/index.php">Regions</a></li>
        <li><a href="../master/subadmin.php">Sub-Admins</a></li>
        <li><a href="../master/manager.php">Reporting Authority</a></li>
        <li><a href="../master/dealer.php">Dealers</a></li>
        <li><a href="../master/download.php">Advanced Downloads</a></li>
        <li><a href="../master/master_search.php">Master Search</a></li>
        <li><a href="../mapping/statetransfer.php">Transfer State Mapping </a></li>
<? } ?>
<? if($cookie_usertype == "Dealer") { ?>
        <li><a href="../master/dealermember.php">Dealer Members</a></li>
<? } ?>
      </ul>
    </div>
  </li>
<? } ?>
<? if($cookie_usertype == "Admin") { ?>
  <li><a><span>Lead Mapping</span></a>
    <div>
      <ul>
        <li><a href="../mapping/index.php">Mapping Dealers</a></li>
        <li><a href="../mapping/unmappedcontact.php">Unmapped Contact Point</a></li>
        <li><a href="../mapping/reconsile.php">Reconsile Mapping</a></li>
         <li><a href="../mapping/bulkmapping.php">Bulk Mapping</a></li>
      </ul>
    </div>
  </li>
<? } ?>
<? if($cookie_usertype == "Admin" || $cookie_usertype == "Sub Admin" || $cookie_usertype == "Reporting Authority" || $cookie_usertype == "Dealer" || $cookie_usertype == "Implementer" || $cookie_usertype == "Dealer Member" ) { ?>
  <li><a><span>Leads</span></a>
    <div>
      <ul>
        <li><a href="../leads/uploadlead.php">Manual Upload of Lead</a></li>
<? if($cookie_usertype == "Admin" || $cookie_usertype == "Sub Admin" || $cookie_usertype == "Reporting Authority") { ?>
          <li><a href="../leads/bulkleadtransfer.php">Bulk Lead Transfer</a></li> 
   <? } ?>
   <? if($cookie_usertype == "Admin" || $cookie_usertype == "Sub Admin" || $cookie_usertype == "Reporting Authority" || $cookie_usertype == "Dealer") { ?>
         <!-- <li><a href="../leads/bulksms.php">Send Bulk SMS</a></li> -->
<? } ?>
<? $showmcalistvalues = getshowmcapermissionvalue();
$showmcalistvaluessplit = explode('^',$showmcalistvalues); //echo($showmcalistvalues);
if($showmcalistvaluessplit[0] == 'yes') {?>
		 <li><a href="../leads/mcacompanies.php">MCA-Companies</a></li> <? } ?>
      </ul>
    </div>
  </li>
<? } ?>
<? if($cookie_usertype == "Admin" || $cookie_usertype == "Sub Admin" || $cookie_usertype == "Reporting Authority" || $cookie_usertype == "Dealer" || $cookie_usertype == "Dealer Member") { ?>
  <li><a><span>Lead Management</span></a>
    <div>
      <ul>
      <li><a href="../manageleads/simplelead.php">Update Lead</a></li>
      <? if($cookie_usertype == "Admin" || $cookie_usertype == "Sub Admin" || $cookie_usertype == "Reporting Authority" || $cookie_usertype == "Dealer" ) { ?>
         <li><a href="../manageleads/viewleads.php">View Uploaded Leads</a></li> 
       
        <? } ?>
       
      </ul>
    </div>
  </li>
<? } ?>
<? if($cookie_usertype == "Admin") { ?>
<!--  <li><a><span>Email Administration</span></a>
    <div>
      <ul>
        <li><a href="../emails/managercomp.php">Compose Manager emails</a></li>
        <li><a href="../emails/dealercomp.php">Compose Dealer Emails</a></li>
        <li><a href="../emails/leadcomp.php">Compose Lead emails</a></li>
        <li><a href="../emails/index.php">Send emails</a></li>
      </ul>
    </div>
  </li>-->
<? } ?>
  <li><a><span>Profile</span></a>
    <div>
      <ul>
<? if($cookie_usertype == "Dealer") { ?>
        <li><a href="../profile/dealerprofile.php">Edit Profile</a></li>
<? } ?>
        <li><a href="../profile/password.php">Change Password</a></li>
      </ul>
    </div>
  </li>
<? if($cookie_usertype == "Admin" || $cookie_usertype == "Sub Admin" || $cookie_usertype == "Reporting Authority" || $cookie_usertype == "Dealer") { ?>
  <li><a><span>LMS Reports</span></a>
    <div>
      <ul>
<? if($cookie_usertype == "Admin" || $cookie_usertype == "Sub Admin" || $cookie_usertype == "Reporting Authority") { ?>
        <li><a href="../reportslms/dealerlist.php">Dealer List</a></li>
<? } ?>
<? if($cookie_usertype == "Admin" || $cookie_usertype == "Sub Admin") { ?>
        <li><a href="../reportslms/managerlist.php">Manager List</a></li>
<? } ?>
<? if($cookie_usertype == "Admin") { ?>
        <li><a href="../reportslms/mappinginformation.php">Mapping Information</a></li>
<? } ?>
<? if($cookie_usertype == "Sub Admin") { ?>
        <li><a href="../reportslms/mappinginformation.php">Mapping Information</a></li>
       
<? } ?>
        <li><a href="../reportslms/leads.php">Leads</a></li>
        <li><a href="../reportslms/dlrdatachart.php">Dealer Data Chart</a></li>
        <li><a href="../reportslms/dlr-wise-leads.php">Dealer wise leads</a></li>
        <li><a href="../reportslms/lead-source.php">Lead Source Stats</a></li>
        <li><a href="../reportslms/lead-upload.php">Lead Upload Stats</a></li>
        <li><a href="../reportslms/lead-upload-demogiven.php">Lead Upload Stats(Demo Given)</a></li>
        <li><a href="../reportslms/dlr-delay-leads.php">Dealer Delay of leads</a></li>
  <? if($cookie_usertype == "Admin" || $cookie_usertype == "Sub Admin") { ?>
       
         <li><a href="../reportslms/demogivenstats.php">Demo Given Stats</a></li>
        <li><a href="../reportslms/leadstatuschart.php">Lead Status Chart</a></li>
<? } ?>     
<? if($cookie_usertype == "Admin") { ?>
       
         <li><a href="../reportslms/activitylog.php">Activity Log</a></li>
<? } ?>    
      </ul>
    </div>
  </li>
<? } ?>
<? if($cookie_usertype == "Admin") { ?>
<!--  <li class="last"><a><span>Web Reports</span></a>
    <div>
      <ul>
        <li><a href="../reportsweb/index.php">Lead Statistics</a></li>
        <li><a href="../reportsweb/downloadstats.php">Product Download Statistics</a></li>
        <li><a href="../reportsweb/subscriptionstats.php">Subscription Statistics</a></li>
        <li><a href="../reportsweb/loginstats.php">User: Login Statistics</a></li>
      </ul>
    </div>
  </li>-->
<? } ?>
</ul>
