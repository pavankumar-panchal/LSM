<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include_once("../inc/checklogin.php");
include_once("../functions/phpfunctions.php");

$username = lmsgetcookie('lmsusername');
$usertype = lmsgetcookie('lmsusersort');
$query = "select * from lms_users where username = '" . $username . "'";
$fetch = runmysqlqueryfetch($query);
if ($fetch['confirmation'] == 'no') {
	switch ($usertype) {
		case "Sub Admin":
			$query1 = "select * from lms_subadmins where id = '" . $fetch['referenceid'] . "'";
			$fetch1 = runmysqlqueryfetch($query1);

			$name = $fetch1['sadname'];
			$cell = $fetch1['cell'];
			$emailid = $fetch1['sademailid'];
			break;

		case "Reporting Authority":
			$query1 = "select * from lms_managers where id = '" . $fetch['referenceid'] . "' ";
			$fetch1 = runmysqlqueryfetch($query1);

			$name = $fetch1['mgrname'];
			$cell = $fetch1['mgrcell'];
			$emailid = $fetch1['mgremailid'];

			break;

		case "Dealer":
			$query1 = "select * from dealers where id = '" . $fetch['referenceid'] . "'";
			$fetch1 = runmysqlqueryfetch($query1);
			$name = $fetch1['dlrname'];
			$cell = $fetch1['dlrcell'];
			$emailid = $fetch1['dlremail'];

			break;

		case "Dealer Member":
			$query1 = "select * from lms_dlrmembers where dlrmbrid = '" . $fetch['referenceid'] . "'";
			$fetch1 = runmysqlqueryfetch($query1);

			$name = $fetch1['dlrmbrname'];
			$cell = $fetch1['dlrmbrcell'];
			$emailid = $fetch1['dlrmbremailid'];

			break;
	}

	?>
	<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Confirmation Page</title>
		<link rel="stylesheet" type="text/css" href="../css/style.css?dummy=<?php echo (rand()); ?>">
		<script src="../functions/jquery-1.4.2.min.js?dummy=<?php echo (rand()); ?>" language="javascript"></script>
		<script src="../functions/jsfunctions.js?dummy=<?php echo (rand()); ?>" language="javascript"></script>
		<script src="../functions/confirmation.js?dummy=<?php echo (rand()); ?>" language="javascript"></script>
	</head>

	<body>
		<table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td style="padding-top:5px">
					<form method="post" name="contactdetails" id="contactdetails" action="">
						<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0"
							style="border:1px solid #666666">
							<tr>
								<td colspan="2" bgcolor="#0099CC">
									<font color="#FFFFFF"><strong>Confirm the Details </strong></font>
								</td>
							</tr>
							<tr>
								<td width="37%">
									<div align="right">Name :</div>
								</td>
								<td width="63%">
									<input type="text" name="name" id="name" value="<?php echo ($name); ?>"
										class="formfields" style="width:55%" autocomplete="off" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right">Cell:</div>
								</td>
								<td>
									<input type="text" name="cell" id="cell" class="formfields" autocomplete="off"
										value="<?php echo ($cell); ?>" style="width:55%" />
								</td>
							</tr>
							<tr>
								<td>
									<div align="right">Email Id:</div>
								</td>
								<td>
									<input type="text" name="emailid" id="emailid" class="formfields" autocomplete="off"
										value="<?php echo ($emailid); ?>" style="width:55%" />
								</td>
							</tr>
							<tr>
								<td colspan="2" height="30px">
									<div align="center" id="message"></div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div align="center">
										<input type="button" name="Update" id="Update" value="Confirm and Proceed"
											class="formbutton" onclick="perform('update')" />&nbsp;&nbsp;<input
											name="logout" type="submit" value="Logout" onclick="perform('logout')"
											class="formbutton" />
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div align="right" class="statusstripclass" style="font-size:12px;"><a href="index.php"
											style="text-decoration:none">Remind me Later &gt;&gt;</a></div>
								</td>
							</tr>
						</table>
					</form>
				</td>
			</tr>
		</table>

	</body>

	</html>
	<?php
} else { 


	 echo "error";
	$url = 'index.php';
	header("location:" . $url);
}
?>