<html>
<head>
<title>Send Volunteer Audit Emails</title>
</head>

<body>
<h1>WMFO Volunteer Email System</h1><br>

<table border="3">
<form action="" method="post">
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
	#printf("<option value='%s'>%s</option>\n", $id, $title);
  echo "<option value='$id'";
  if ($_POST['Semester'] == $id)
    echo " selected";
  echo ">$title</option>\n";
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
    #printf("<option value='%s'>%s, %s</option>\n", $id, $lname, $fname);
  echo "<option value='$id'";
  if ($_POST['DJ'] == $id)
    echo " selected";
  echo ">$lname, $fname</option>\n";

}

$stmt->close();

?>

</select></td></tr>
<tr><td colspan="2"><input type="submit" value="Email Volunteer Records">
</form></td></tr></table>

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
/*
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
*/
?>

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
