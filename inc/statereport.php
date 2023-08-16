<?
//  To display state
$query = " select distinct statecode,statename from regions order by statename";
$result = runmysqlquery($query);
if(mysqli_num_rows($result) > 1)
while($fetch = mysqli_fetch_array($result))
{
		echo('<option value="'.$fetch['statecode'].'">'.$fetch['statename'].'</option>');
}

?>
