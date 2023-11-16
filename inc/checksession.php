<?php




if((lmsgetcookie('sessionkind') == 'logoutforthreemin'))
{
	session_cache_expire(1);
	$cache_expire = session_cache_expire();
}
elseif((lmsgetcookie('sessionkind') == 'logoutforsixhr'))
{
	session_cache_expire(1);
	$cache_expire = session_cache_expire();
}
elseif((lmsgetcookie('sessionkind') == 'logoutforever'))
{
    session_cache_expire(1);
	$cache_expire = session_cache_expire();
}
session_start();


/*if(isset($_SESSION['sessiontype']) <> 'logoutforever')
{
	if(!isset($_SESSION['verificationid']) || ($_SESSION['verificationid'] <> '4563464364365554545454'))
	{
		$url = '../index.php'; header("Location:".$url);
	}
}*/


?>