<html>
<head>
<title>Volunteer Control Panel</title>
</head>

<body>
<h1>WMFO Volunteer Control Panel</h1><br>

<table border="3">
<tr><h2>Display History</h2></tr>
<form action="./volunteer.php" method="post">
<tr>
<td>Semester: </td><td>
<select name="Semester">

<?php
include 'commlib.php';

$conn = mysql_init();
$stmt = $conn->prepare("SELECT ID,TITLE FROM SEMESTER ORDER BY CREATION_TIME DESC");
$stmt->execute();
$stmt->bind_result($id, $title);

while ($stmt->fetch())
{
	printf("<option value='%s'>%s</option>\n", $id, $title);
}

$stmt->close();
?>

</select></td></tr>
<tr><td>DJ: </td><td>
<select name="DJ">

<?php
$stmt = $conn->prepare("SELECT ID,L_NAME,F_NAME FROM DJ ORDER BY L_NAME ASC");
$stmt->execute();
$stmt->bind_result($id, $lname, $fname);

while ($stmt->fetch())
{
	printf("<option value='%s'>%s, %s</option>\n", $id, $lname, $fname);
}

$stmt->close();

?>

</select></td></tr><tr><td>
<input type="submit" value="Display Records">
</form></td></tr></table>

<h2>Add Hours</h2>
<form action="./volunteer.php" method="post">
<table border="3"><tr><td>
Semester: </td><td>
<select name="Semester">

<?php
$stmt = $conn->prepare("SELECT ID,TITLE FROM SEMESTER ORDER BY CREATION_TIME DESC");
$stmt->execute();
$stmt->bind_result($id, $title);

while ($stmt->fetch())
{
        printf("<option value='%s'>%s</option>\n", $id, $title);
}

$stmt->close();
?>

</select></td></tr><tr><td>
DJ: </td><td>
<select name="DJ">

<?php
$stmt = $conn->prepare("SELECT ID,L_NAME,F_NAME FROM DJ ORDER BY L_NAME ASC");
$stmt->execute();
$stmt->bind_result($id, $lname, $fname);

while ($stmt->fetch())
{
        printf("<option value='%s'>%s, %s</option>\n", $id, $lname, $fname);
}

$stmt->close();

?>

</select></td></tr><tr><td>
Date: </td><td>
<input name="Month" size="2" maxlength="2" value="mm">/<input name="Day" size="2" maxlength="2" value="dd">/<input name="Year" size="4" maxlength="4" value="yyyy"></td></tr><tr><td>
Hours: </td><td>
<input name="Hours" size="3" maxlength="3"></td></tr><tr><td>
Type: </td><td>
<select name="Sub">
	<option value="0">Volunteer</option>
	<option value="1">Sub</option>
</select></td></tr><tr><td>
Description: </td><td>
<textarea name="Description" rows="3" cols="80">No description.</textarea></td></tr><tr><td>
<input type="submit" value="Add Volunteer Event">
</td></tr></table>
</form>
<hr>

<?php
if (
	isset($_REQUEST['DJ']) &&
	isset($_REQUEST['Semester']) )
{
	$stmt = $conn->prepare("SELECT F_NAME,L_NAME,TITLE FROM DJ,SEMESTER WHERE DJ.ID=? AND SEMESTER.ID=?");
	$stmt->bind_param("ii", $_REQUEST['DJ'], $_REQUEST['Semester']);
	$stmt->execute();
	$stmt->bind_result($first, $last, $semester);
	$stmt->fetch();
	printf("<h1>Volunteer Record for %s %s in %s</h1>", $first, $last, $semester);

	$stmt->close();
}
?>


<?php
if (
	isset($_POST['Semester']) &&
	isset($_POST['DJ']) &&
	isset($_POST['Day']) &&
	isset($_POST['Month']) &&
	isset($_POST['Year']) &&
	isset($_POST['Hours']) &&
	isset($_POST['Description']) &&
	isset($_POST['Sub']) &&
	is_numeric($_POST['Day']) &&
	is_numeric($_POST['Month']) &&
	is_numeric($_POST['Year']) &&
	is_numeric($_POST['Hours']) )
{
	add_volunteer($conn, $_POST['DJ'], $_POST['Semester'], 
		$_POST['Day'], $_POST['Month'], $_POST['Year'],
		$_POST['Hours'], $_POST['Description'], $_POST['Sub']);
}

?>

<?php
if (isset($_REQUEST['delete']))
{
	$stmt = $conn->prepare("DELETE FROM VOLUNTEER WHERE ID=?");
	$stmt->bind_param("i", $_REQUEST['delete']);
	$stmt->execute();
	$stmt->close();
}
?>

<table border="3">
<tr>
<th>Month</th>
<th>Day</th>
<th>Year</th>
<th>Hours</th>
<th>Type</th>
<th>Description</th>
<th>Delete</th>
</tr>

<?php

if (
	isset($_REQUEST['DJ']) &&
	isset($_REQUEST['Semester']) )
{
	$stmt = $conn->prepare("SELECT DDAY,DMONTH,DYEAR,NUM_HOURS,DESCRIPTION,SUB_BOOL,ID FROM VOLUNTEER WHERE DJ_ID=? AND SEMESTER_ID=? ORDER BY DYEAR DESC, DMONTH DESC, DDAY DESC");
	$stmt->bind_param("ii", $_REQUEST['DJ'], $_REQUEST['Semester']);
	$stmt->execute();
	$stmt->bind_result($month, $day, $year, $hours, $desc, $sub_bool, $id);

	while ($stmt->fetch())
	{
		if ($sub_bool == 0)
		{
			$type = "VOL";
		}
		else
		{
			$type = "SUB";
		}
		printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td><a href='./volunteer?delete=%s&DJ=%s&Semester=%s'>X</a></td></tr>",
			$day, $month, $year, $hours, $type, $desc, $id, $_REQUEST['DJ'], $_REQUEST['Semester']
		);
	} 
}

?>

</table>

<?php footer(); ?>
</body></html>
