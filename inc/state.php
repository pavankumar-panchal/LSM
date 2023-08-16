<?
//  To display state
$query = " select distinct statecode,statename from regions order by statename";
$result = runmysqlquery($query);
if(mysqli_num_rows($result) > 1)
$count = 1;
while($fetch = mysqli_fetch_array($result))
{
	if($count == '1')
	{
		echo('<option value="" selected="selected">- - -Make a Selection- - -</option>');
	}
	else
	{
		echo('<option value="'.$fetch['statecode'].'">'.$fetch['statename'].'</option>');
	}
	$count++;
}

?>
