<?php

//Security check for Ajax pages

$referurl = parse_url($_SERVER['HTTP_REFERER']);
$referhost = $referurl['host'];


if($referhost <> 'localhost' && $referhost <> 'meghanab' && $referhost <> 'rashmihk' && $referhost <> 'archanaab' &&  $referhost <> 'dealers.relyonsoft.com' && $referhost <> 'www.dealers.relyonsoft.com'&& $referhost <> 'relyonsoft.info'&& $referhost <> 'www.relyonsoft.info' && $referhost <> 'www.lms.relyonsoft.net' &&  $referhost <> 'lms.relyonsoft.net'  &&  $referhost <> 'lms.relyonsoft.in')
{
	echo("Thinking, why u called this page. Anyways, call me on my cell");
	exit;
}

?>
