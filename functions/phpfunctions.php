<?php
//Include Database Configuration details


if (file_exists("../inc/dbconfig.php")) {
	include("../inc/dbconfig.php");
} else {
	include("inc/dbconfig.php");
}

//Connect to host
$newconnection = mysqli_connect($dbhost, $dbuser, $dbpwd, $dbname) or die("Cannot connect to Mysql server host");

//Connect to log host
$newconnection_log = mysqli_connect($dbhost_log, $dbuser_log, $dbpwd_log, $dbpwd_log) or die("Cannot connect to Mysql server host");

/* -------------------- Get local server time [by adding 5.30 hours] -------------------- */
function datetimelocal($format)
{
	$diff_timestamp = date('U');
	$date = date($format, $diff_timestamp);
	return $date;
}

/* -------------------- Upload ZIP file through PHP -------------------- */
function fileupload($filename, $filetempname)
{
	//check that we have a file
	//Check if the file is JPEG image and it's size is less than 350Kb

	//retrieve the date.
	$date = datetimelocal('YmdHis-');
	$filebasename = $date . basename($filename);
	$ext = substr($filebasename, strrpos($filebasename, '.') + 1);
	if ($ext == "zip") {
		$newname = $_SERVER['DOCUMENT_ROOT'] . '/sssm-beta/upload/' . $filebasename;
		$downloadlink = 'http://' . $_SERVER['HTTP_HOST'] . '/sssm-beta/upload/' . $filebasename;
		if (!file_exists($newname)) {
			if ((move_uploaded_file($filetempname, $newname))) {
				$result = $newname; //Upload successfull
			} else {
				$result = 4; //Problem dusring upload
			}
		} else {
			$result = 3; //File already exists by same name
		}
	} else {
		$result = 2; //Extension doesn't match
	}
	return $result;
}

/* -------------------- Download any file through PHP header -------------------- */
function downloadfile($filelink)
{
	$filename = basename($filelink);
	header('Content-type: application/octet-stream');
	header('Content-Disposition: attachment; filename=' . $filename);
	readfile($filelink);
}

/* -------------------- Run a query to database -------------------- */
function runmysqlquery($query)
{
	global $newconnection;
	$dbname = 'relyon_lms';

	//Connect to Database
	mysqli_select_db($newconnection, $dbname) or die("Cannot connect to database");
	set_time_limit(3600);
	//Run the query
	$result = mysqli_query($newconnection, $query) or die(" run Query Failed in Runquery function1." . $query); //;

	//Return the result
	return $result;
}



/* -------------------- Run a query to log database -------------------- */
function runmysqlquery_log($query)
{
	global $newconnection_log;
	$dbname_log = 'relyon_lms';

	//Connect to Database
	mysqli_select_db($newconnection_log, $dbname_log) or die("Cannot connect to database");
	set_time_limit(3600);
	//Run the query
	$result = mysqli_query($newconnection_log, $query) or die(" run Query Failed in Runquery function1." . $query); //;

	//Return the result
	return $result;
}


/* -------------------- Run a query to database with fetching from SELECT operation -------------------- */
function runmysqlqueryfetch($query)
{
	global $newconnection;
	$dbname = 'relyon_lms';

	//Connect to Database
	mysqli_select_db($newconnection, $dbname) or die("Cannot connect to database");
	set_time_limit(3600);
	//Run the query
	$result = mysqli_query($newconnection, $query) or die(" run Query Failed in Runquery function1." . $query); //;

	//Fetch the Query to an array
	$fetchresult = mysqli_fetch_array($result) or die("Cannot fetch the query result." . $query);

	//Return the result
	return $fetchresult;
}



/* -------------------- Run a query to log database with fetching from SELECT operation -------------------- */
function runmysqlqueryfetch_log($query)
{
	global $newconnection_log;
	$dbname_log = 'relyon_lms';

	//Connect to Database
	mysqli_select_db($newconnection_log, $dbname_log) or die("Cannot connect to database");
	set_time_limit(3600);
	//Run the query
	$result = mysqli_query($newconnection_log, $query) or die(" run Query Failed in Runquery function1." . $query); //;

	//Fetch the Query to an array
	$fetchresult = mysqli_fetch_array($result) or die("Cannot fetch the query result." . $query);

	//Return the result
	return $fetchresult;
}


/* -------------------- To change the date format from DD-MM-YYYY to YYYY-MM-DD or reverse -------------------- */
function changedateformat($date)
{
	if ($date <> "0000-00-00" && $date <> "00-00-0000" && $date <> "") {
		$result = explode("-", $date);
		$date = $result[2] . "-" . $result[1] . "-" . $result[0];
	} else {
		$date = "";
	}
	return $date;
}

function checkdateformat($date) //Valid is 2008-11-15
{
	$returnflag = false;
	$result = explode("-", $date);
	if (count($result) == 3 && checkdate($result[1], $result[2], $result[0]))
		$returnflag = true;
	return $returnflag;
}

function datenumeric($date) //convert date to its numeric value so that it can be compared.
{
	$dateArr = explode("-", $date);
	$dateInt = mktime(0, 0, 0, $dateArr[1], $dateArr[2], $dateArr[0]);
	return $dateInt;
}

/* -------------------- To trim the data for the grid, If it is more than 20 charecters [Say: "This problem is due to the problem in server" -> "This problem is due ..." -------------------- */
function gridtrim30($value)
{
	$desiredlength = 30;
	$length = strlen($value);
	if ($length >= $desiredlength) {
		$value = substr($value, 0, $desiredlength);
		$value .= "...";
	}
	return $value;
}

function gridtrim1($value)
{
	$desiredlength = 20;
	$length = strlen($value);
	if ($length >= $desiredlength) {
		$value = substr($value, 0, $desiredlength);
		$value .= "<br>";
	}
	return $value;
}

function nozerotime($time)
{
	if ($time == "00:00:00") {
		$time = "";
	}
	return $time;
}

function generatepwd()
{
	$charecterset = "1234567890";
	for ($i = 0; $i < 8; $i++) {
		$usrpassword .= $charecterset[mt_rand(0, 9)];
	}
	return $usrpassword;
}

function checkemailaddress($email)
{
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
		if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
			return false;
		}
	}
	if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
		// Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) {
			if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
				return false;
			}
		}
	}
	return true;
}

function replacemailvariable($content, $array)
{
	while ($item = current($array)) {
		if ($item == "")
			$item = "-";
		$content = str_replace(key($array), $item, $content);
		next($array);
	}
	return $content;
}

function fullurl()
{
	$s = (empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on")) ? "s" : "";
	$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
	return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}


function getuserdisplayname($userid)
{
	if ($userid == '') {
		$lastupdatedbyname = 'Web Downloaded';
		return $lastupdatedbyname;
	} else {
		$query3 = "select * from lms_users where id = '" . $userid . "'";
		$result3 = runmysqlqueryfetch($query3);
		switch ($result3['type']) {
			case "Admin":
				$lastupdatedbyname = "Admin";
				break;
			case "Sub Admin":
				$query4 = "SELECT * FROM lms_subadmins WHERE id = '" . $result3['referenceid'] . "'";
				$result4 = runmysqlqueryfetch($query4);
				$lastupdatedbyname = $result4['sadname'] . " [S]";
				break;
			case "Reporting Authority":
				$query4 = "SELECT * FROM lms_managers WHERE id = '" . $result3['referenceid'] . "'";
				$result4 = runmysqlqueryfetch($query4);
				$lastupdatedbyname = $result4['mgrname'] . " [M]";
				break;
			case "Dealer":
				$query4 = "SELECT * FROM dealers WHERE id = '" . $result3['referenceid'] . "'";
				$result4 = runmysqlqueryfetch($query4);
				$lastupdatedbyname = $result4['dlrcompanyname'] . " [D]";
				break;
			case "Implementer":
				$query4 = "SELECT * FROM lms_implementers WHERE id = '" . $result3['referenceid'] . "'";
				$result4 = runmysqlqueryfetch($query4);
				$lastupdatedbyname = $result4['impname'] . " [I]";
				break;
			case "Dealer Member":
				$query4 = "SELECT * FROM lms_dlrmembers WHERE dlrmbrid = '" . $result3['referenceid'] . "'";
				$result4 = runmysqlqueryfetch($query4);
				$lastupdatedbyname = $result4['dlrmbrname'] . " [DM]";
				break;
		}
		return $lastupdatedbyname;
	}
}

function sendsms($servicename, $tonumber, $smstext, $senddate, $sendtime)
{
	if (validatecellno($tonumber) == false)
		return false;
	else {
		/*$senddate = datetimelocal("Y-m-d");
			  $sendtime = datetimelocal("H:i");
			  
			  $accountid = "20010262";
			  $accountpassword = "fcmy7q";
			  $tonumber = (strlen($tonumber) == 10)?$tonumber:substr($tonumber, -10);
			  $smstext = substr($smstext, 0, 159);
		  
			  $targeturl = "http://www.mysmsmantra.co.in/sendurl.asp?";
			  $targeturl .= "user=".$accountid;
			  $targeturl .= "&pwd=".$accountpassword;
			  $targeturl .= "&senderid=RELYON";
			  $targeturl .= "&mobileno=".$tonumber;
			  $targeturl .= "&msgtext=".urlencode($smstext);
			  $targeturl .= "&priority=High";
		  
			  $response = file_get_contents($targeturl);
			  $splitdata = explode(",",$response);
			  $messageid = $splitdata[0];
			  $message = "Sent Successfully. [Message ID = ".$messageid."]";*/

		//Insert to SMS Logs Database
		$query = "insert into `smslogs`(servicename, tonumber, smstext, senddate, sendtime)values('" . $servicename . "', '" . $tonumber . "', '" . $smstext . "', '" . $senddate . "', '" . $sendtime . "')";
		$result = runmysqlquery($query);
		return true;
	}
}


// SMS function to send Single SMS and Bulk SMS
function sendsmsforleads($servicename, $tonumber, $smstext, $senddate, $sendtime, $leadid, $sentby)
{

	if (validatecellno($tonumber) == false) {
		return true;
	} else {
		/*$senddate = datetimelocal("Y-m-d");
			  $sendtime = datetimelocal("H:i");
			  
			  $accountid = "20010262";
			  $accountpassword = "fcmy7q";
			  $tonumber = (strlen($tonumber) == 10)?$tonumber:substr($tonumber, -10);
			  $smstext = substr($smstext, 0, 159);
		  
			  $targeturl = "http://www.mysmsmantra.co.in/sendurl.asp?";
			  $targeturl .= "user=".$accountid;
			  $targeturl .= "&pwd=".$accountpassword;
			  $targeturl .= "&senderid=RELYON";
			  $targeturl .= "&mobileno=".$tonumber;
			  $targeturl .= "&msgtext=".urlencode($smstext);
			  $targeturl .= "&priority=High";
		  
			  $response = file_get_contents($targeturl);
			  $splitdata = explode(",",$response);
			  $messageid = $splitdata[0];
			  $message = "Sent Successfully. [Message ID = ".$messageid."]";*/


		/*	$file = $_SERVER['DOCUMENT_ROOT'].'/LMS/filescreated/'.'SMS.txt';
			   $current = stripslashes($smstext)."\r\n";
			   $fp = fopen($file,'a+');
			   if($fp)
				   fwrite($fp,$current);
			   fclose($fp);
			   
			   //echo('here');exit;*/
		//Insert to SMS Logs Database
		$query = "insert into `smslogs`(servicename, tonumber, smstext, senddate, sendtime,leadid,smssentby)values('" . $servicename . "', '" . $tonumber . "', '" . addslashes($smstext) . "', '" . $senddate . "', '" . $sendtime . "','" . $leadid . "','" . $sentby . "')";
		$result = runmysqlquery($query);
		return true;
	}
}




function sendsmsnew($smsslno, $requestid, $smsnumber, $smstext, $smsfromname, $accounttype, $servicename)
{
	//Define acocunt info
	$accountid = "relyon";
	$accountpassword = "smssoftware";
	$smsfromname = "RSL-LMS";
	//Check the number to be of 10 digits. Else remove preceeding '91'
	$smsnumber = (strlen($smsnumber) == 10) ? $smsnumber : substr($smsnumber, -10);

	//Build API URL
	$targeturl = "http://hapi.smsapi.org/SendSMS.aspx?";
	$targeturl .= "UserName=" . $accountid;
	$targeturl .= "&password=" . $accountpassword;
	$targeturl .= "&MobileNo=91" . $smsnumber;
	$targeturl .= "&SenderID=" . $smsfromname;
	$targeturl .= "&Message=" . urlencode($smstext);

	//Open API URL and get the response message
	$providermessage = file_get_contents($targeturl);

	//Get Provider's message ID
	$explodedresponse = explode('"', $providermessage);
	$providermessageid = $explodedresponse[1];

	//Insert SMS logs
	$query = "insert into `smslogs`(servicename, tonumber, smstext, senddate, sendtime)values('" . $servicename . "', '" . $tonumber . "', '" . $smstext . "', '" . $senddate . "', '" . $sendtime . "')";
	$result = runmysqlquery($query);

	return true;
}


function validatecellno($tonumber)
{
	if ((!preg_match('/^9\d{9}$/', $tonumber)) && (!preg_match('/^919\d{9}$/', $tonumber)) && (!preg_match('/^7\d{9}$/', $tonumber)) && (!preg_match('/^917\d{9}$/', $tonumber)) && (!preg_match('/^8\d{9}$/', $tonumber)) && (!preg_match('/^918\d{9}$/', $tonumber))) {
		return false;
	} else {
		return true;
	}
}

function changedateformatwithtime($date)
{
	if ($date <> "0000-00-00 00:00:00") {
		if (strpos($date, " ")) {
			$result = explode(" ", $date);
			if (strpos($result[0], "-"))
				$dateonly = explode("-", $result[0]);
			$timeonly = explode(":", $result[1]);
			$timeonlyhm = $timeonly[0] . ':' . $timeonly[1];
			$date = $dateonly[2] . "-" . $dateonly[1] . "-" . $dateonly[0] . " " . '(' . $timeonlyhm . ')';
		}

	} else {
		$date = "";
	}
	return $date;
}

// function to create cookie and encoded the cookie name and value
function lmscreatecookie($cookiename, $cookievalue)
{

	//Define prefix and suffix 
	$prefixstring = "AxtIv23";
	$suffixstring = "StPxZ46";
	$stringsuff = "55";

	//Append Value with the Prefix and Suffix
	$Appendvalue = $prefixstring . $cookievalue . $suffixstring;

	// Convert the Appended Value to base64
	$Encodevalue = encodevalue($Appendvalue);

	//Convert Cookie Name to base64
	$Encodename = encodevalue($cookiename);

	//Create a cookie with the encoded name and value
	setcookie($Encodename, $Encodevalue, time() + 2592000);

	//Convert Appended encode value to MD5
	$rescookievalue = md5($Encodevalue);

	//Appended the encoded cookie name with 55(suffix )
	$rescookiename = $Encodename . $stringsuff;

	//Create a cookie
	setcookie($rescookiename, $rescookievalue, time() + 2592000);
	return false;

}


// function to delete cookie and encoded the cookie name and value
function lmsdeletecookie($cookiename)
{
	//Name Suffix for MD5 value
	$stringsuff = "55";

	//Convert Cookie Name to base64
	$Encodename = encodevalue($cookiename);
	//Append the encoded cookie name with 55(suffix ) for MD5 value
	$rescookiename = $Encodename . $stringsuff;

	//Set expiration to negative time, which will delete the cookie
	setcookie($Encodename, "", time() - 3600);
	setcookie($rescookiename, "", time() - 3600);

	setcookie(session_name(), "", time() - 3600);
}

//Function to get cookie and encode it and validate
// function lmsgetcookie($cookiename)
// {

// 	$suff = "55";
// 	// Convert the Cookie Name to base64
// 	$Encodestr = encodevalue($cookiename);

// 	//Read cookie name
// 	$stringret = $_COOKIE[$Encodestr];
// 	$stringret = stripslashes($stringret);
// 	//Convert the read cookie name to md5 encode technique
// 	$Encodestring = md5($stringret);

// 	//Appended the encoded cookie name to 55(suffix)
// 	$resultstr = $Encodestr . $suff;
// 	$cookiemd5 = $_COOKIE[$resultstr];

// 	//Compare the encoded value wit the fetched cookie, if the condition is true decode the cookie value
// 	if ($Encodestring == $cookiemd5) {
// 		$decodevalue = decodevalue($stringret);
// 		//Remove the Prefix/Suffix Characters
// 		$string1 = substr($decodevalue, 7);
// 		$resultstring = substr($string1, 0, -7);
// 		return $resultstring;
// 	} else if (isset($Encodestring) == '') {
// 		return false;
// 	} else {
// 		return false;
// 	}

// }



function lmsgetcookie($cookiename)
{
    $suff = "55";
    // Convert the Cookie Name to base64
    $Encodestr = encodevalue($cookiename);

    // Read cookie name
    if (isset($_COOKIE[$Encodestr])) {
        $stringret = $_COOKIE[$Encodestr];
        if (!is_null($stringret)) {
            $stringret = stripslashes($stringret);
        }

        // Convert the read cookie name to md5 encode technique
        $Encodestring = md5($stringret);

        // Appended the encoded cookie name to 55(suffix)
        $resultstr = $Encodestr . $suff;

        if (isset($_COOKIE[$resultstr])) {
            $cookiemd5 = $_COOKIE[$resultstr];

            // Compare the encoded value with the fetched cookie, if the condition is true decode the cookie value
            if ($Encodestring == $cookiemd5) {
                $decodevalue = decodevalue($stringret);
                // Remove the Prefix/Suffix Characters
                $string1 = substr($decodevalue, 7);
                $resultstring = substr($string1, 0, -7);
                return $resultstring;
            } else {
                // Handle the case where cookies don't match
                return false;
            }
        } else {
            // Handle the case where the resultstr cookie key is not set
            return false;
        }
    } else {
        // Handle the case where the Encodestr cookie key is not set
        return false;
    }
}





//Function to logout (clear cookies)
function lmsuserlogout()
{
	session_start();
	session_unset();
	session_destroy();
	lmsdeletecookie('sessionkind');
	lmsdeletecookie('applicationid');
	lmsdeletecookie('lmsusername');
	lmsdeletecookie('lmsusertype');
	lmsdeletecookie('lmslastlogindate');

}

function lmsuserlogoutredirect()
{
	lmsuserlogout();
	$url = "../index.php?link=" . fullurl();
	header("Location:" . $url);
	exit();
}


function encodevalue($input)
{
	$length = strlen($input);
	$output1 = "";
	for ($i = 0; $i < $length; $i++) {
		$output1 .= $input[$i];
		if ($i < ($length - 1))
			$output1 .= "a";
	}
	$output = "";
	for ($i = 0; $i < strlen($output1); $i++) {
		$output .= chr(ord($output1[$i]) + 7);
	}
	return $output;
}


function decodevalue($input)
{
	$input = str_replace('\\\\', '\\', $input);
	$input = str_replace("\\'", "'", $input);
	$length = strlen($input);
	$output = "";
	for ($i = 0; $i < $length; $i++) {
		if ($i % 2 == 0)
			$output .= chr(ord($input[$i]) - 7);
	}
	$output = str_replace("'", "\'", $output);
	return $output;
}

function replacemailvariablenew($content, $array)
{
	$arraylength = count($array);
	for ($i = 0; $i < $arraylength; $i++) {
		$splitvalue = explode('%^%', $array[$i]);
		$oldvalue = $splitvalue[0];
		$newvalue = $splitvalue[1];
		$content = str_replace($oldvalue, $newvalue, $content);
	}
	return $content;
}

//Function to delete the file 
function fileDelete($filepath, $filename)
{
	$success = FALSE;
	if (file_exists($filepath . $filename) && $filename != "" && $filename != "n/a") {
		unlink($filepath . $filename);
		$success = TRUE;
	}
	return $success;
}

function tooltiptextdetails($id)
{
	$query = "select * from lms_users where id = '" . $id . "'";
	$result = runmysqlqueryfetch($query);
	switch ($result['type']) {
		case "Admin":
			$table = '<table width="100%" border="0"  cellspacing="0" cellpadding="2">';
			$table .= '<tr><td><strong>Name:</strong>Admin </td></tr></table>';
			break;
		case "Sub Admin":
			$query4 = "SELECT sadname,sademailid,cell FROM lms_subadmins WHERE id = '" . $result['referenceid'] . "'";
			$fetch = runmysqlqueryfetch($query4);
			if ($fetch['cell'] <> '') {
				$table = '<table width="100%" border="0"  cellspacing="0" cellpadding="0">';
				$table .= '<tr><td><strong>Name:</strong> ' . $fetch['sadname'] . ' [S]</td></tr>';
				$table .= '<tr><td><strong>Cell:</strong> ' . $fetch['cell'] . '</td></tr>';
				$table .= '<tr><td><strong>EmailId:</strong> ' . $fetch['sademailid'] . '</td></tr>';
				$table .= '</table>';
			} else {
				$table = '<table width="100%" border="0"  cellspacing="0" cellpadding="0">';
				$table .= '<tr><td><strong>Name:</strong> ' . $fetch['sadname'] . ' [S]</td></tr>';
				$table .= '<tr><td><strong>EmailId:</strong> ' . $fetch['sademailid'] . '</td></tr>';
				$table .= '</table>';
			}
			break;
		case "Reporting Authority":
			$query4 = "SELECT mgrname,mgrlocation,mgrcell,mgremailid FROM lms_managers WHERE id = '" . $result['referenceid'] . "'";
			$fetch = runmysqlqueryfetch($query4);
			$table = '<table width="100%" border="0"  cellspacing="0" cellpadding="0">';
			$table .= '<tr><td><strong>Name:</strong> ' . $fetch['mgrname'] . ' [M]</td></tr>';
			$table .= '<tr><td><strong>District:</strong> ' . $fetch['mgrlocation'] . '</td></tr>';
			$table .= '<tr><td><strong>Cell:</strong> ' . $fetch['mgrcell'] . '</td></tr>';
			$table .= '<tr><td><strong>Email Id:</strong> ' . $fetch['mgremailid'] . '</td></tr>';
			$table .= '</table>';
			break;
		case "Dealer":
			$query4 = "SELECT dlrcompanyname,dlrname,district,state,dlrcell,dlrphone,dlremail FROM dealers WHERE id = '" . $result['referenceid'] . "'";
			$fetch = runmysqlqueryfetch($query4);
			$table = '<table width="100%" border="0"  cellspacing="0" cellpadding="0">';
			$table .= '<tr><td ><strong>Company:</strong> ' . $fetch['dlrcompanyname'] . '</td></tr>';
			$table .= '<tr><td ><strong>Contact Person:</strong> ' . $fetch['dlrname'] . '</td></tr>';
			$table .= '<tr><td ><strong>Place:</strong>' . $fetch['district'] . ',' . $fetch['state'] . '</td></tr>';
			$table .= '<tr><td ><strong>Phone:</strong> ' . $fetch['dlrphone'] . '</td></tr>';
			$table .= '<tr><td ><strong>Cell:</strong> ' . $fetch['dlrcell'] . '</td></tr>';
			$table .= '<tr><td><strong>Email Id:</strong> ' . $fetch['dlremail'] . '</td></tr>';
			$table .= '</table>';
			break;

		case "Dealer Member":
			$query4 = "SELECT *,dealers.dlrcompanyname from lms_dlrmembers left join dealers on dealers.id = lms_dlrmembers.dealerid where dlrmbrid = '" . $result['referenceid'] . "'";
			$fetch = runmysqlqueryfetch($query4);
			$table = '<table width="100%" border="0"  cellspacing="0" cellpadding="0">';
			$table .= '<tr><td ><strong>Name:</strong> ' . $fetch['dlrmbrname'] . '</td></tr>';
			$table .= '<tr><td ><strong>Dealer Company:</strong> ' . $fetch['dlrcompanyname'] . '</td></tr>';
			$table .= '<tr><td ><strong>Cell:</strong>' . $fetch['dlrmbrcell'] . '</td></tr>';
			$table .= '<tr><td><strong>Email Id:</strong> ' . $fetch['dlrmbremailid'] . '</td></tr>';
			$table .= '</table>';
			break;
	}
	return $table;
}

// Function to display amount in Indian Format (Eg:123456 : 1,23,456)

function formatnumber($number)
{
	if (is_numeric($number)) {
		$numbersign = "";
		$numberdecimals = "";

		//Retain the number sign, if present
		if (substr($number, 0, 1) == "-" || substr($number, 0, 1) == "+") {
			$numbersign = substr($number, 0, 1);
			$number = substr($number, 1);
		}

		//Retain the decimal places, if present
		if (strpos($number, '.')) {
			$position = strpos($number, '.'); //echo($position.'<br/>');
			$numberdecimals = substr($number, $position); //echo($numberdecimals.'<br/>');
			$number = substr($number, 0, ($position)); //echo($number.'<br/>');
		}

		//Apply commas
		if (strlen($number) < 4) {
			$output = $number;
		} else {
			$lastthreedigits = substr($number, -3);
			$remainingdigits = substr($number, 0, -3);
			$tempstring = "";
			for ($i = strlen($remainingdigits), $j = 1; $i > 0; $i--, $j++) {
				if ($j % 2 <> 0)
					$tempstring = ',' . $tempstring;
				$tempstring = $remainingdigits[$i - 1] . $tempstring;
			}
			$output = $tempstring . $lastthreedigits;
		}
		$finaloutput = $numbersign . $output . $numberdecimals;
		return $finaloutput;
	} else {
		$finaloutput = 0;
		return $finaloutput;
	}
}
function getshowmcapermissionvalue()
{
	//Check who is making the entry
	$cookie_username = lmsgetcookie('lmsusername');
	$query = "select * from lms_users where lms_users.username = '" . $cookie_username . "'";
	$result = runmysqlqueryfetch($query);
	$enteredbyuserid = $result['id'];
	$referenceid = $result['referenceid'];
	$cookie_usertype = lmsgetcookie('lmsusersort');

	switch ($cookie_usertype) {
		case 'Dealer': {
				$query = "select showmcacompanies,branch from dealers where id = '" . $referenceid . "';";
				$resultfetch = runmysqlqueryfetch($query);
				$showmcacompanies = $resultfetch['showmcacompanies'];
				$branch = $resultfetch['branch'];
			}
			break;
		case 'Sub Admin': {
				$query = "select showmcacompanies from lms_subadmins where id = '" . $referenceid . "';";
				$resultfetch = runmysqlqueryfetch($query);
				$branch = '';
				$showmcacompanies = $resultfetch['showmcacompanies'];
			}
			break;
		case 'Reporting Authority': {
				$query = "select showmcacompanies,branch from lms_managers where id = '" . $referenceid . "';";
				$resultfetch = runmysqlqueryfetch($query);
				$showmcacompanies = $resultfetch['showmcacompanies'];
				$branch = $resultfetch['branch'];
			}
			break;
		case 'Admin': {
				$showmcacompanies = 'yes';
			}
			break;
	}
	return $showmcacompanies . '^' . $branch . '^' . $query;
}

function isvalidhostname()
{
	if ($_SERVER['HTTP_HOST'] == 'rashmihk' || $_SERVER['HTTP_HOST'] == 'meghanab1' || $_SERVER['HTTP_HOST'] == 'vijaykumar' || $_SERVER['HTTP_HOST'] == 'dealers.relyonsoft.com' || $_SERVER['HTTP_HOST'] == 'rwmserver')
		return true;
	else
		return false;
}

function isurl($url)
{
	return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}
?>