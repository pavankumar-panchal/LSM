<?php
//  To display ROC-Code
$query = "select distinct roccode from mca_companies order by roccode;";
$result = runmysqlquery($query);
if(mysqli_num_rows($result) > 1)
$count = 1;
while($fetch = mysqli_fetch_array($result))
{
	echo('<option value="'.$fetch['roccode'].'">'.$fetch['roccode'].'</option>');
}

?>
