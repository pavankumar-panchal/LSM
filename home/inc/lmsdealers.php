<?php
	$query_dealers = "SELECT distinct dealers.id AS selectid, dealers.dlrcompanyname AS selectname 
	FROM dealers left join lms_users on lms_users.referenceid = dealers.id where lms_users.disablelogin <> 'yes' and lms_users.type = 'Dealer' ORDER BY dlrcompanyname;";
    $result_dealers = runmysqlquery($query_dealers);
    while($fetch_dealers = mysqli_fetch_array($result_dealers))
    {
    	echo('<option value="'.$fetch_dealers['selectid'].'">'.$fetch_dealers['selectname'].'</option>');
    }
?>