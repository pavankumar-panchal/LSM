<?php

include("../functions/phpfunctions.php");

if ((lmsgetcookie('sessionkind') == 'logoutforthreemin') || (lmsgetcookie('sessionkind') == 'logoutforsixhr')) {

	if (lmsgetcookie('sessionkind') == 'logoutforthreemin') {

		if (isset($_COOKIE[session_name()])) {
			ini_set('session.gc_maxlifetime', 180);
			ini_set('session.gc_probability', 1);
			ini_set('session.gc_divisor', 1);
			$sessionCookieExpireTime = 180;
			session_start();
			if (!isset($_SESSION['verificationid']) || ($_SESSION['verificationid'] <> '4563464364365554545454')) {
				lmsuserlogout();
				$url = "../index.php";
				header("Location:" . $url);
			}
			setcookie(session_name(), $_COOKIE[session_name()], time() + $sessionCookieExpireTime, "/");
		} else {
			lmsuserlogout();
			$url = "../index.php";
			header("Location:" . $url);
		}
	} elseif (lmsgetcookie('sessionkind') == 'logoutforsixhr') {
		if (isset($_COOKIE[session_name()])) {
			ini_set('session.gc_maxlifetime', 21600);
			ini_set('session.gc_probability', 1);
			ini_set('session.gc_divisor', 1);
			$sessionCookieExpireTime = 21600;
			session_start();
			if (!isset($_SESSION['verificationid']) || ($_SESSION['verificationid'] <> '4563464364365554545454')) {
				lmsuserlogout();
				$url = "../index.php";
				header("Location:" . $url);
			}
			setcookie(session_name(), $_COOKIE[session_name()], time() + $sessionCookieExpireTime, "/");
		} else {
			lmsuserlogout();
			$url = "../index.php";
			header("Location:" . $url);
		}
	}
}
$currenturl = fullurl();
$loginfailureurl = "../index.php?link=" . $currenturl;
$login = true;

if ((lmsgetcookie('applicationid') <> false) && (lmsgetcookie('lmsusername') <> false) && (lmsgetcookie('lmsusersort') <> false) && (lmsgetcookie('lmslastlogindate') <> false)) {
	$cookie_username = lmsgetcookie('lmsusername');
	$cookie_usertype = lmsgetcookie('lmsusersort');
	$cookie_lastlogindate = lmsgetcookie('lmslastlogindate');
} else
	$login = false;

if ($login == false) {
	lmsuserlogout();
	$url = "../index.php";
	header("Location:" . $url);
}

?>