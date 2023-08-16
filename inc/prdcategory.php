<?
//  To display ROC-Code
$query = "select distinct category from products;;";
$result = runmysqlquery($query);
if(mysqli_num_rows($result) > 1)
$count = 1;
while($fetch = mysqli_fetch_array($result))
{
	echo('<option value="'.$fetch['category'].'">'.$fetch['category'].'</option>');
}

?>
