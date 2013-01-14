<html>
<head>
<title>Semester Control Panel</title>
</head>

<body>
<h1>WMFO Semester Control Panel</h1><br>
<form action="./add_semester.php" method="post">
<table border="3">
<tr><td>Semester Name: </td>
<td><input name="SemesterName"></td></tr>
<tr><td><input type="Submit" value="Add Semester"></td></tr>
</table>
</form>
<hr>

<h1>Current Semesters</h1><br>


<?php
include 'commlib.php';

$conn = mysql_init();

if (isset($_POST['SemesterName']))
{
	add_semester($conn, $_POST['SemesterName']);
}

//Make a table to show the current semesters
echo "<table border=3><tr><th>Semester Name</th><th>Creation Time</th><th>Shows</th></tr>\n";

$stmt = $conn->prepare("SELECT ID,TITLE,CREATION_TIME FROM SEMESTER ORDER BY CREATION_TIME DESC");
$stmt->execute();
$stmt->bind_result($id, $title, $time);

while ($stmt->fetch())
{
	printf("<tr><td>%s</td><td>%s</td>", $title, $time);
	printf("<td><a href='./add_show?semester=%s'>add</a></td></tr>", $id);
}

$stmt->close();
$conn->close();

?>

</table>

<?php footer(); ?>
</body>
</html>

