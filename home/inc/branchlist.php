<?php

//Select the list of products for the drop-down
$query = "SELECT slno,branchname FROM lms_branch ORDER BY branchname";
$result = runmysqlquery($query);
while($fetch = mysqli_fetch_array($result))
{
	echo('<option value="'.$fetch['slno'].'">'.$fetch['branchname'].'</option>');
}

?>