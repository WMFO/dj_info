<html>
<head>
<title>
DJ Summary Page
</title>
</head>

<body>

<?php
include 'commlib.php';
$conn = mysql_init();

$stmt = $conn->prepare("SELECT F_NAME,L_NAME,YEAR_JOINED,SENIORITY_OFFSET,EMAIL,PHONE FROM DJ WHERE ID=?");
$stmt->bind_param("i", $_REQUEST['dj']);
$stmt->execute();
$stmt->bind_result($first, $last, $year, $off, $email, $phone);
$stmt->fetch();

printf("<h1>%s %s</h1>\n", $first, $last);
$stmt->close();
?>

<h2>Info</h2>
<table border="3">
<?php
printf("<tr><td>Name: </td><td>%s %s</td></tr>\n", $first, $last);
printf("<tr><td>Email: </td><td>%s</td></tr>\n", $email);
printf("<tr><td>Phone: </td><td>%s</td></tr>\n", $phone);
printf("<tr><td>Year Joined: </td><td>%s</td></tr>\n", $year);
printf("<tr><td>Seniority Offset: </td><td>%s</td></tr>\n", $off);
?>

</table>
<hr>
<h2>Shows</h2>
<table border="3">
<tr><th>Show Name</th><th>Semester</th></tr>

<?php
$stmt = $conn->prepare("SELECT `NAME`,TITLE FROM `SHOW`,SEMESTER WHERE DJ_ID=? AND SEMESTER.ID=SEMESTER_ID ORDER BY SEMESTER.CREATION_TIME");
$stmt->bind_param("i", $_REQUEST['dj']);
$stmt->execute();
$stmt->bind_result($show, $semester);

while ($stmt->fetch())
{
	printf("<tr><td>%s</td><td>%s</td></tr>\n", $show, $semester);
}

$stmt->close();
?>

</table>
<hr>
<h2>Disciplinary Record</h2>
<table border="3">
<tr><th>Day</th><th>Month</th><th>Year</th><th>Description</th></tr>

<?php
$stmt = $conn->prepare("SELECT DDAY,DMONTH,DYEAR,DESCRIPTION FROM DISCIPLINE WHERE DJ_ID=? ORDER BY DYEAR DESC, DMONTH DESC, DDAY DESC");
$stmt->bind_param("i", $_REQUEST['dj']);
$stmt->execute();
$stmt->bind_result($day, $month, $year, $description);

while ($stmt->fetch())
{
	printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n", $day, $month, $year, $description);
}

$stmt->close();
?>

</table>

<hr>
<h2>Volunteer Totals</h2>
<table border="3">
<tr><th>Semester</th><th>Total Hours</th><th>VOL</th><th>SUB</th><tr>

<?php
$stmt = $conn->prepare("SELECT SUM(NUM_HOURS),TITLE,SEMESTER.ID FROM VOLUNTEER,SEMESTER WHERE DJ_ID=? AND SEMESTER.ID=SEMESTER_ID GROUP BY SEMESTER.ID ORDER BY SEMESTER.CREATION_TIME DESC");
$stmt2 = $conn->prepare("SELECT SUM(NUM_HOURS) FROM VOLUNTEER WHERE DJ_ID=? AND SEMESTER_ID=? AND SUB_BOOL=1 GROUP BY SEMESTER_ID");
$stmt->bind_param("i", $_REQUEST['dj']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($hours, $semester, $semester_id);

while ($stmt->fetch())
{
	$stmt2->bind_param("ii", $_REQUEST['dj'], $semester_id);
	$stmt2->execute();
	$stmt2->store_result();
	$stmt2->bind_result($sub_hours);
	$stmt2->fetch();
	printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n", $semester, $hours, $hours - $sub_hours, $sub_hours);
}

$stmt->close();
$stmt2->close();
?>

</table>

<hr>
<h2>Full Volunteer History</h2>
<table border="3">
<tr><th>Month</th><th>Day</th><th>Year</th><th>Semester</th><th>Hours</th><th>Type</th><th>Description</th></tr>

<?php
$stmt = $conn->prepare("SELECT DMONTH,DDAY,DYEAR,TITLE,NUM_HOURS,DESCRIPTION,SUB_BOOL FROM VOLUNTEER,SEMESTER WHERE SEMESTER.ID=SEMESTER_ID AND DJ_ID=? ORDER BY DYEAR DESC, DMONTH DESC, DDAY DESC");
$stmt->bind_param("i", $_REQUEST['dj']);
$stmt->execute();
$stmt->bind_result($month, $day, $year, $semester, $hours, $desc, $sub_bool);

while ($stmt->fetch())
{
	if ($sub_bool == 1)
	{
		$type = "SUB";
	}
	else
	{
		$type = "VOL";
	}
	printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",
		$month, $day, $year, $semester, $hours, $type, $desc);
}

$stmt->close();
?>

</table>

<?php
	$conn->close();
?>


<?php footer(); ?>
</body>
</html>

