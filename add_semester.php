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

if (isset($_REQUEST['semesterToDelete']) )
{
	delete_semester($conn, $_REQUEST['semesterToDelete']);
}

//Make a table to show the current semesters
echo "<table border=3><tr><th>Semester Name</th><th>Creation Time</th><th>Shows</th><th>Delete</th></tr>\n";

$stmt = $conn->prepare("SELECT ID,TITLE,CREATION_TIME FROM SEMESTER ORDER BY CREATION_TIME DESC");
$stmt->execute();
$stmt->bind_result($id, $title, $time);

while ($stmt->fetch())
{
	printf("<tr><td>%s</td><td>%s</td>\n", $title, $time);
	printf("<td><a href='./add_show?semester=%s'>View/Add</a></td>\n", $id);
	printf("<td><form action='./add_semester?semesterToDelete=%s' method='post' onsubmit=\"return confirm('Are you sure you want to delete %s?')\"><input type=\"submit\" value=\"delete\"></form></td></tr>\n", $id, $title);
}

$stmt->close();
$conn->close();

?>

</table>

<?php footer(); ?>
</body>
</html>

