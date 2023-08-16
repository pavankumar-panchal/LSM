<?
if((lmsgetcookie('applicationid') == '8616153779973246153879') && (lmsgetcookie('sessionkind') <> false) && (lmsgetcookie('lmsusername') <> false) && (lmsgetcookie('lmsusersort') <> false) && (lmsgetcookie('lmslastlogindate') <> false))
{
	$currenturl = fullurl();
	$pagelinksplit = explode('/',$currenturl);
	//print_r($pagelinksplit[4]);print_r(substr($pagelinksplit[5],0,-4));
	$pagelinkvalue = $pagelinksplit[4];
	$pagelinkvalue1 = substr($pagelinksplit[5],0,-4);
	$cookie_username = lmsgetcookie('lmsusername');
	$lmsquery = "SELECT * FROM lms_users WHERE username = '".$cookie_username."'";
	$lmsresultfetch = runmysqlqueryfetch($lmsquery);
	$userslno = $lmsresultfetch['id'];
	switch($pagelinkvalue)
	{
		case 'master':
		{
			switch($pagelinkvalue1)
			{
				case 'index':  $pagetextvalue ='51'; break;
				case 'subadmin':  $pagetextvalue ='52'; break;
				case 'manager':  $pagetextvalue ='53'; break;
				case 'dealer':  $pagetextvalue ='54'; break;
				case 'download':  $pagetextvalue ='55'; break;
				case 'dealermember':  $pagetextvalue ='78'; break;
			}
		}
		break;
		case 'mapping':
		{
			switch($pagelinkvalue1)
			{
				case 'index':  $pagetextvalue ='56'; break;
				case 'unmappedcontact':  $pagetextvalue ='57'; break;
				case 'reconsile':  $pagetextvalue ='58'; break;
				case 'bulkmapping':  $pagetextvalue ='59'; break;
			}
		}
		break;
		case 'leads':
		{
			switch($pagelinkvalue1)
			{
				case 'uploadlead':  $pagetextvalue ='60'; break;
				case 'bulkleadtransfer':  $pagetextvalue ='61'; break;
				case 'bulksms':  $pagetextvalue ='62'; break;
				case 'mcacompanies':  $pagetextvalue ='63'; break;
			}
		}
		break;
		case 'manageleads':
		{
			switch($pagelinkvalue1)
			{
				case 'simplelead':  $pagetextvalue ='64'; break;
				case 'viewleads':  $pagetextvalue ='65'; break;
			}
		}
		break;
		case 'profile':
		{
			switch($pagelinkvalue1)
			{
				case 'password':  $pagetextvalue ='66'; break;
			}
		}
		break;
		case 'reportslms':
		{
			switch($pagelinkvalue1)
			{
				case 'dealerlist':  $pagetextvalue ='67'; break;
				case 'managerlist':  $pagetextvalue ='68'; break;
				case 'mappinginformation':  $pagetextvalue ='69'; break;
				case 'leads':  $pagetextvalue ='70'; break;
				case 'dlrdatachart':  $pagetextvalue ='71'; break;
				case 'dlr-wise-leads':  $pagetextvalue ='72'; break;
				case 'lead-source':  $pagetextvalue ='73'; break;
				case 'lead-upload':  $pagetextvalue ='74'; break;
				case 'dlr-delay-leads':  $pagetextvalue ='75'; break;
				case 'demogivenstats':  $pagetextvalue ='76'; break;
				case 'leadstatuschart':  $pagetextvalue ='77'; break;
			}
		}
		break;
		case 'reportsweb':
		{
			switch($pagelinkvalue1)
			{
				case 'index':  $pagetextvalue ='79'; break;
				case 'downloadstats':  $pagetextvalue ='80'; break;
				case 'subscriptionstats':  $pagetextvalue ='81'; break;
				case 'loginstats':  $pagetextvalue ='82'; break;
			}
		}
		break;
		default:
			$pagetextvalue ='50'; break;
	}
	$eventquery = "insert into lms_logs_event(userid,system,eventtype,eventdatetime) values('".$userslno."','".$_SERVER['REMOTE_ADDR']."','".$pagetextvalue."','".datetimelocal("Y-m-d").' '.datetimelocal("H:i:s")."');";
	$eventresult = runmysqlquery_log($eventquery);
}
	
?>
