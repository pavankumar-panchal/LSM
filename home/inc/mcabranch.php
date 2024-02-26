<?php
$showmcalistvalues = getshowmcapermissionvalue();
$showmcalistvaluessplit = explode('^',$showmcalistvalues);
if($showmcalistvaluessplit[0] == 'yes' && $showmcalistvaluessplit[1] <> '')
	$showmcalisttype = 'where slno = "'.$showmcalistvaluessplit[1].'"';
else
	$showmcalisttype = '';
//Select the list of products for the drop-down
$query = "SELECT slno,branchname FROM lms_branch ".$showmcalisttype." ORDER BY branchname";
$result = runmysqlquery($query);
while($fetch = mysqli_fetch_array($result))
{
	echo('<option value="'.$fetch['slno'].'">'.$fetch['branchname'].'</option>');
}


?>