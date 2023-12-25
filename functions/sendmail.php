<?php
function send_mail($sub,$file_htm,$file_txt)
{
//	global $myemail;
	global $cookie_username;
	global $emailidusername;
	global $newpassword;

	$date = date_default_timezone_set('Asia/Kolkata');
    $today = date("d.m.Y, g:i:s a T");
	
	
#########  Mailing Starts -----------------------------------
	#$mymail = 'webmaster@relyonsoft.com'. ', ';
	$mymail = 'webmaster@relyonsoft.com,'.$emailidusername;
	#$mymail = 'webmaster@relyonsoft.com,bhavesh.d@relyonsoft.com';
	$emailarray = explode(',',$mymail);
	$emailcount = count($emailarray);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailadd($emailarray[$i]))
		{
				$mymails[$emailarray[$i]] = $emailarray[$i];
		}
	}
	$mail = 'lms@relyon.co.in';
	$fromname = 'Webmaster';
	$fromemail = $mail;
	$msg = file_get_contents($file_htm);
	$textmsg = file_get_contents($file_txt);
	require_once("../inc/RSLMAIL_MAIL.php");
	
	$array = array();
	$array[] = "##USER##%^%".$cookie_username;
	$array[] = "##PASS##%^%".$newpassword;
	$array[] = "##DATE##%^%".$today;

	
	$textarray = array();
	$textarray[] = "##USER##%^%".$cookie_username;
	$textarray[] = "##PASS##%^%".$newpassword;
	$textarray[] = "##DATE##%^%".$today;

	$toarray = $mymails;
	$bccmymails['bigmail'] ='samar.s@relyonsoft.com';
	$bccmymails['Relyonimax'] ='relyonimax@gmail.com';
	#$bccmymails['Relyonimax'] ='bhavesh@relyonsoft.com';
	$bccarray = $bccmymails;
	
	
	$msg = replacemailvar($msg,$array);
	$textmsg = replacemailvar($textmsg,$textarray);
	$subject = $sub;
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html, null,$bccarray, null);
}
	// #### PHP Function Start here ### //

function checkemailadd($mail) 
{
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $mail)) 
	{
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $mail);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) 
	{
		if (!preg_match("/^(([A-Za-z0-9!#$%&'*+=?^_`{|}~-][A-Za-z0-9!#$%&'*+=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) 
		{
			return false;
		}
	}
	if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) 
	{ 
		// Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) 
		{
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) 
		{
			if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) 
			{
				return false;
			}
		}
	}
	return true;
}
	
function replacemailvar($content,$array)
{
	$arraylength = count($array);
	for($i = 0; $i < $arraylength; $i++)
	{
		$splitvalue = explode('%^%',$array[$i]);
		$oldvalue = $splitvalue[0];
		$newvalue = $splitvalue[1];
		$content = str_replace($oldvalue,$newvalue,$content);
	}
	return $content;
}

?>