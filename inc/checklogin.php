<?php

include("../functions/phpfunctions.php"); 
include("../inc/eventloginsert.php");
session_start();



		if((lmsgetcookie('applicationid') == '8616153779973246153879') && (lmsgetcookie('sessionkind') <> false) && (lmsgetcookie('lmsusername') <> false) && (lmsgetcookie('lmsusersort') <> false) && (lmsgetcookie('lmslastlogindate') <> false))
		{
			$cookie_logintype = lmsgetcookie('sessionkind');
			$cookie_username = lmsgetcookie('lmsusername');
			$cookie_usertype = lmsgetcookie('lmsusersort'); 
			$cookie_lastlogindate = lmsgetcookie('lmslastlogindate');
		}
		else
		{
			lmsuserlogoutredirect();
		}

		$checklogin_data = "select disablelogin from lms_users where username = '$cookie_username'";
		$result_checklogin = runmysqlqueryfetch($checklogin_data);
		$result_check_disable = $result_checklogin['disablelogin'];
		if($result_check_disable == 'yes')
		{
			//echo "u r logged out!"
		  header("Location: http://localhost/project/lms/logout.php");
			
		}
		 else{

		switch($cookie_logintype)
		{
			case "logoutforthreemin":
				if($_SESSION['verificationid'] == '4563464364365554545454')
				{
					ini_set('session.gc_maxlifetime',180);
					ini_set('session.gc_probability',1);
					ini_set('session.gc_divisor',1);
					$sessionCookieExpireTime = 180;
					session_start();
					setcookie(session_name(), $_COOKIE[session_name()], time() + $sessionCookieExpireTime, "/");
				}
				else
				{
					lmsuserlogoutredirect();
				}
				break;

			case "logoutforsixhr":
				if($_SESSION['verificationid'] == '4563464364365554545454')
				{
					ini_set('session.gc_maxlifetime',21600);
					ini_set('session.gc_probability',1);
					ini_set('session.gc_divisor',1);
					$sessionCookieExpireTime = 21600;
					session_start();
					setcookie(session_name(), $_COOKIE[session_name()], time() + $sessionCookieExpireTime, "/");
				}
				else
				{
					lmsuserlogoutredirect();
				}
				break;

			case "logoutforever":
					if($_SESSION['verificationid'] == '4563464364365554545454')
				{
					ini_set('session.gc_maxlifetime',604800);
					ini_set('session.gc_probability',1);
					ini_set('session.gc_divisor',1);
					$sessionCookieExpireTime = 604800;
					session_start();
					setcookie(session_name(), $_COOKIE[session_name()], time() + $sessionCookieExpireTime, "/");
				}
				else
				{
					lmsuserlogoutredirect();
				}
				//session_start();
				break;
			
			default:
				lmsuserlogoutredirect();		
		}
	}


?>