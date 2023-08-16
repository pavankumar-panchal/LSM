<?

//Select the list of products for the drop-down
$query = "SELECT id,productname FROM products ORDER BY productname";
$result = runmysqlquery($query);
while($fetch = mysqli_fetch_array($result))
{
	$productselect .= '<label><input name = "productcheckbox[]" id ="'.$fetch['productname'].'"  value="'.$fetch['id'].'" type="checkbox">'.$fetch['productname'].'</input></label>';
	$productselect .= '<br>';
}
echo($productselect);

?>