<?php

//Security check for Ajax pages

// $referurl = parse_url($_SERVER['HTTP_REFERER']);
// $referhost = $referurl['host'];

// if ($referhost <> 'localhost' && $referhost <> 'meghanab' && $referhost <> 'rashmihk' && $referhost <> 'archanaab' && $referhost <> 'dealers.relyonsoft.com' && $referhost <> 'www.dealers.relyonsoft.com' && $referhost <> 'relyonsoft.info' && $referhost <> 'www.relyonsoft.info' && $referhost <> 'www.lms.relyonsoft.net' && $referhost <> 'lms.relyonsoft.net' && $referhost <> 'lms.relyonsoft.in') {
// 	echo ("Thinking, why u called this page. Anyways, call me on my cell");
// 	exit;
// }

?>

<?php

if (isset($_SERVER['HTTP_REFERER'])) {
	$referurl = parse_url($_SERVER['HTTP_REFERER']);
	$referhost = $referurl['host'];

	$allowedHosts = array(
		'localhost',
		'meghanab',
		'rashmihk',
		'archanaab',
		'dealers.relyonsoft.com',
		'www.dealers.relyonsoft.com',
		'relyonsoft.info',
		'www.relyonsoft.info',
		'www.lms.relyonsoft.net',
		'lms.relyonsoft.net',
		'lms.relyonsoft.in'
	);

	if (!in_array($referhost, $allowedHosts)) {
		echo "Thinking, why u called this page. Anyways, call me on my cell";
		exit;
	}
} else {
	echo "HTTP_REFERER header is not set. Access denied.";
	exit;
}

?>