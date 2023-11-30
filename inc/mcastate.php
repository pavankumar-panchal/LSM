<?php
//  To display state
$query = " select slno,statename from mca_stateidmapping order by statename";
$result = runmysqlquery($query);
if(mysqli_num_rows($result) > 1)

while($fetch = mysqli_fetch_array($result))
{
	echo('<option value="'.$fetch['slno'].'">'.$fetch['statename'].'</option>');
}

?>
