<?php

switch ($cookie_usertype) {
	case "Admin":
		$query = "SELECT id AS selectid, dlrcompanyname AS selectname FROM dealers ORDER BY dlrcompanyname";
		break;
	case "Reporting Authority":
		$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '" . $cookie_username . "' ORDER BY dealers.dlrcompanyname";
		if ($cookie_username == "srinivasan")
			$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.managerid WHERE lms_users.username = '" . $cookie_username . "' or  lms_users.username = 'nagaraj' ORDER BY dealers.dlrcompanyname";

		break;
	case "Dealer":
		$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN dealers ON lms_users.referenceid = dealers.id WHERE lms_users.username = '" . $cookie_username . "' ORDER BY dealers.dlrcompanyname";
		break;
	case "Dealer Member":
		$query = "SELECT dealers.id AS selectid, dealers.dlrcompanyname AS selectname FROM lms_users JOIN lms_dlrmembers on lms_dlrmembers.dlrmbrid = lms_users.referenceid JOIN dealers ON lms_dlrmembers.dealerid = dealers.id WHERE lms_users.username = '" . $cookie_username . "' ORDER BY dealers.dlrcompanyname";
		break;
	case "Sub Admin":
		$query = "SELECT id AS selectid, dlrcompanyname AS selectname FROM dealers ORDER BY dlrcompanyname";
		break;
}

$result = runmysqlquery($query);
$dealerselect = '';
if (mysqli_num_rows($result) > 1)

	while ($fetch = mysqli_fetch_array($result)) {
		$dealerselect .= '<label><input name="dealercheckbox[]" id = "' . $fetch['selectname'] . '" type="checkbox" value = "' . $fetch['selectid'] . '">' . $fetch['selectname'] . '</input></label>';
		$dealerselect .= '<br/>';
	}
echo ($dealerselect);

?>