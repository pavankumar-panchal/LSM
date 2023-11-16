<?php
//  To display state
$query = " select slno, branchname from mca_branchidmapping order by branchname";
$result = runmysqlquery($query);

while ($fetch = mysqli_fetch_array($result)) {
	echo ('<option value="' . $fetch['slno'] . '">' . $fetch['branchname'] . '</option>');

}

?>