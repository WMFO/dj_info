<html>
<head>
<title>
DJ Control Panel
</title>
</head>

<body>
<h1>WMFO DJ Control Panel</h1><br>
<form action="./add_dj.php" method="post">
<table border="3">
<tr><td>First Name: </td><td><input name="FirstName"></td></tr>
<tr><td>Last Name: </td><td><input name="LastName"></td></tr>
<tr><td>Email: </td><td><input name="Email"></td></tr>
<tr><td>Phone: </td><td><input name="Phone"></td><tr>
<tr><td>Year Joined: </td><td><input name="YearJoined"></td></tr>
<tr><td>Seniority Offset: </td><td><input name="SeniorityOffset"></td></tr>
<tr><td><input type="submit" value="Add DJ"></td></tr>
</table>
</form>
<hr>


<h1>Current DJs</h1>
<table border=3>
<tr>
<th>Last Name</th>
<th>First Name</th>
<th>Year Joined</th>
<th>Seniority Offset</th>
<th>Summary</th>
<th>Discipline</th>
<th>Edit</th>
</tr>

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
	is_numeric($_POST['YearJoined']) &&
	is_numeric($_POST['SeniorityOffset']) )
{
	add_dj($conn, $_POST['FirstName'], $_POST['LastName'],
		$_POST['YearJoined'], $_POST['SeniorityOffset'],
		$_POST['Email'], $_POST['Phone']);
}

$stmt = $conn->prepare("SELECT ID,F_NAME,L_NAME,YEAR_JOINED,SENIORITY_OFFSET FROM DJ ORDER BY L_NAME ASC");
//printf("\n" . $conn->error . "\n");
$stmt->execute();
$stmt->bind_result($id, $first, $last, $year, $senior);

while ($stmt->fetch())
{
	printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td>",
		$last, $first, $year, $senior);
	printf("<td><a href='./dj_summary.php?dj=%s'>View</a></td>", $id);
	printf("<td><a href='./discipline.php?dj=%s'>View</a></td>", $id);
	printf("<td><a href='./edit_dj.php?dj=%s'>Edit</a></td>", $id);
	printf("</tr>\n");
}

$stmt->close();
$conn->close();

?>

</table>

<?php footer(); ?>
</body>
</html>
