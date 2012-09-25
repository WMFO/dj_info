<html>
<head>
<title>
Discipline Control Panel
</title>
</head>

<body>
<h1>WMFO Disciplinary Control Panel</h1>
<form action="./discipline.php" method="post">
<table border="3">
<tr><td>Date: </td>
<td><input name="day" size="2" maxlength="2">/<input name="month" size="2" maxlength="2">/<input name="year" size="4" maxlength="4"></td></tr>
<tr><td>Description: </td>
<td><textarea name="description" rows="3" cols="80">No description.</textarea></td></tr>
<tr><td><input type="submit" value="Add Disciplinary Note"></td></tr>
</table>
<?php
include 'commlib.php';
$conn = mysql_init();

printf("<input type='hidden' name='dj' value='%s'>\n", $_REQUEST['dj']);
?>
</form>
<hr>
<?php
$stmt = $conn->prepare("SELECT F_NAME,L_NAME FROM DJ WHERE ID=?");
$stmt->bind_param("i", $_REQUEST['dj']);
$stmt->execute();
$stmt->bind_result($first, $last);
$stmt->fetch();
printf("<h1>Disciplinary Notes for %s %s</h1>\n", $first, $last);
$stmt->close();
?>


<table border="3">
<tr>
<th>Day</th><th>Month</th><th>Year</th><th>Description</th>
</tr>

<?php
if (
	isset($_POST['dj']) &&
	isset($_POST['day']) &&
	isset($_POST['month']) &&
	isset($_POST['year']) &&
	isset($_POST['description']) &&
	is_numeric($_POST['day']) &&
	is_numeric($_POST['month']) &&
	is_numeric($_POST['year']) )
{
	$stmt = $conn->prepare("INSERT INTO DISCIPLINE (DJ_ID,DDAY,DMONTH,DYEAR,DESCRIPTION) VALUES (?,?,?,?,?)");
	$stmt->bind_param("iiiis", $_POST['dj'], $_POST['day'], $_POST['month'], $_POST['year'], $_POST['description']);
	$stmt->execute();
	$stmt->close();
}



$stmt = $conn->prepare("SELECT DDAY,DMONTH,DYEAR,DESCRIPTION FROM DISCIPLINE WHERE DJ_ID=? ORDER BY DYEAR DESC, DMONTH DESC, DDAY DESC");
$stmt->bind_param("i", $_REQUEST['dj']);
$stmt->execute();
$stmt->bind_result($day, $month, $year, $desc);
while ($stmt->fetch())
{
	printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n", $day, $month, $year, $desc);
}

$stmt->close();
?>

</table>

<?php footer(); ?>
</body>
</html>

<?php
$conn->close();
?>

