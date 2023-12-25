<?php
include_once("../functions/phpfunctions.php");
function dlrname()
{
	$query = "SELECT * FROM dealers order by dlrname";
	#$query ="SELECT DISTINCT dealers.id, dealers.dlrname, lms_users.disablelogin From lms_users INNER JOIN dealers ON lms_users.referenceid=dealers.id WHERE lms_users.disablelogin = 'yes' ORDER BY dealers.dlrname desc";

	$result = runmysqlquery($query);
	if(mysqli_num_rows($result) > 1)
	{
		echo('<option value="" selected="selected">Make a Selection</option>');
	}
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['id'].'">'.$fetch['dlrname'].'</option>');
	}
}
function dlrnameactive()
{
	#$query = "SELECT * FROM dealers order by dlrname";
	$query ="SELECT DISTINCT dealers.id, dealers.dlrname From lms_users INNER JOIN dealers ON lms_users.referenceid=dealers.id WHERE lms_users.disablelogin = 'no' ORDER BY dealers.dlrname";

	$result = runmysqlquery($query);
	if(mysqli_num_rows($result) > 1)
	{
		echo('<option value="" selected="selected">Make a Selection</option>');
	}
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['id'].'">'.$fetch['dlrname'].'</option>');
	}
}

?>