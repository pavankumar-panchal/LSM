<?php
	$query = "SELECT id, productname FROM products ORDER BY productname";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['id'].'">'.$fetch['productname'].'</option>');
	}
?>
