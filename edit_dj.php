<html>
<head>
<title>
DJ Control Panel
</title>
</head>

<?php
include 'commlib.php';

$conn = mysql_init();
if (
	isset($_POST['FirstName']) &&
	isset($_POST['LastName']) &&
	isset($_POST['Email']) &&
	isset($_POST['Phone']) &&
	isset($_POST['YearJoined']) &&
	isset($_POST['SeniorityOffset']) &&
    isset($_REQUEST['dj']) &&
	is_numeric($_POST['YearJoined']) &&
	is_numeric($_POST['SeniorityOffset']) )
{
	edit_dj($conn, $_POST['FirstName'], $_POST['LastName'],
		$_POST['YearJoined'], $_POST['SeniorityOffset'],
		$_POST['Email'], $_POST['Phone'], $_REQUEST['dj']);
}
$conn->close();

$conn = mysql_init();
$stmt = $conn->prepare("SELECT F_NAME,L_NAME,YEAR_JOINED,SENIORITY_OFFSET,EMAIL,PHONE FROM DJ WHERE ID=?");
$stmt->bind_param("i", $_REQUEST['dj']);
$stmt->execute();
$stmt->bind_result($first, $last, $year, $off, $email, $phone);
$stmt->fetch();
$dj = $_REQUEST['dj'];

printf("<h1>Editing %s %s</h1>\n", $first, $last);
$stmt->close();
?>

<form action="./edit_dj.php" method="post">
<table border="3">
<?php
printf("<tr><td>First Name: </td><td><input name=\"FirstName\" value=\"$first\"></td></tr>");
printf("<tr><td>Last Name: </td><td><input name=\"LastName\" value=\"$last\"></td></tr>");
printf("<tr><td>Email: </td><td><input name=\"Email\" value=\"$email\"></td></tr>");
printf("<tr><td>Phone: </td><td><input name=\"Phone\" value=\"$phone\"></td></tr>");
printf("<tr><td>Year Joined: </td><td><input name=\"YearJoined\" value=\"$year\"></td></tr>");
printf("<tr><td>Seniority Offset: </td><td><input name=\"SeniorityOffset\" value=\"$off\"></td></tr>");
printf("<input type=\"hidden\" name=\"dj\" value=\"$dj\">");
?>
<tr><td><input type="submit" value="Update DJ"></td></tr>
</table>
</form>

<?php footer(); ?>
</body>
</html>
