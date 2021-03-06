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
        $_POST['MiddleName'], $_POST['PreferredName'],
		$_POST['YearJoined'], $_POST['SeniorityOffset'],
        $_POST['Email'], $_POST['Phone'], $_POST['StudentID'],
        $_POST['Affiliation'], $_POST['Access'], @$_POST['Exec'],
        @$_POST['Active'], $_REQUEST['dj'], @$_POST['Sub'],
        @$_POST['UnSub']);
    header("Location: add_dj.php");
}

if (
    isset($_REQUEST['dj']) &&
    isset($_REQUEST['deleteID']) &&
    is_numeric($_REQUEST['deleteID']) )
{
    delete_show($conn, $_REQUEST['deleteID']);
}
$conn->close();
?>
<html>
<head>
<title>
DJ Control Panel
</title>
</head>
<?php
$conn = mysql_init();
$stmt = $conn->prepare("SELECT F_NAME,M_NAME,PREF_NAME,L_NAME,YEAR_JOINED,SENIORITY_OFFSET,EMAIL,PHONE,STUDENT_ID,AFFILIATION,ACCESS,EXEC,ACTIVE,SUB,UNSUB FROM DJ WHERE ID=?");
$stmt->bind_param("i", $_REQUEST['dj']);
$stmt->execute();
$stmt->bind_result($first, $middle, $preferred, $last, $year, $off, $email, $phone, $student_id, $affiliation, $access, $exec, $active, $sub, $unsub);
$stmt->fetch();
$dj = $_REQUEST['dj'];

printf("<h1>Editing %s %s</h1>\n", $first, $last);
$stmt->close();
?>

<form action="" method="post">
<table border="3">
<?php
printf("<tr><td>First Name: </td><td><input name=\"FirstName\" value=\"$first\"></td></tr>");
printf("<tr><td>Preferred Name: </td><td><input name=\"PreferredName\" value=\"$preferred\"></td></tr>");
printf("<tr><td>Middle Name: </td><td><input name=\"MiddleName\" value=\"$middle\"></td></tr>");
printf("<tr><td>Last Name: </td><td><input name=\"LastName\" value=\"$last\"></td></tr>");
printf("<tr><td>Email: </td><td><input name=\"Email\" value=\"$email\"></td></tr>");
printf("<tr><td>Phone: </td><td><input name=\"Phone\" value=\"$phone\"></td></tr>");
printf("<tr><td>Year Joined: </td><td><input name=\"YearJoined\" value=\"$year\"></td></tr>");
printf("<tr><td>Seniority Offset: </td><td><input name=\"SeniorityOffset\" value=\"$off\"></td></tr>");
printf("<tr><td>Student ID: </td><td><input name=\"StudentID\" value=\"$student_id\"></td></tr>");
printf("<tr><td>Affiliation: </td><td><select name=\"Affiliation\">");
$options = array ("Student", "Alum", "Community");
foreach ($options as $option) {
    printf("<option value=\"$option\"");
    if ($option == $affiliation) {
        printf(" selected");
    }
    printf(">$option</option>");
}
printf("</select></td></tr>");
printf("<tr><td>Access: </td><td><select name=\"Access\">");
$options = array ("General", "MD", "Engineer", "All");
foreach ($options as $option) {
    printf("<option value=\"$option\"");
    if ($option == $access) {
        printf(" selected");
    }
    printf(">$option</option>");
}
printf("</select></td></tr>");
printf("<tr><td>Exec: </td><td><input type=\"checkbox\" name=\"Exec\"");
if ($exec == "yes") { printf(" checked=\"yes\""); }
printf("value=\"yes\"></td></tr>");
printf("<tr><td>Active: </td><td><input type=\"checkbox\" name=\"Active\"");
if ($active == "yes") { printf(" checked=\"yes\""); }
printf("value=\"yes\"></td></tr>");
printf("<tr><td>Sub: </td><td><input type=\"checkbox\" name=\"Sub\"");
if ($sub == "yes") { printf(" checked=\"yes\""); }
printf("value=\"yes\"></td></tr>");
printf("<tr><td>Unsubscribed: </td><td><input type=\"checkbox\" name=\"UnSub\"");
if ($unsub == "yes") { printf(" checked=\"yes\""); }
printf("value=\"yes\"></td></tr>");
printf("<input type=\"hidden\" name=\"dj\" value=\"$dj\">");
?>
<tr><td><input type="submit" value="Update DJ"></td></tr>
</table>
</form>

<hr>
<h2>Shows</h2>
<table border="3">
<tr><th>Show Name</th><th>Semester</th><th>Delete</th></tr>

<?php
$stmt = $conn->prepare("SELECT `NAME`,`SHOW`.ID,TITLE FROM `SHOW`,SEMESTER WHERE DJ_ID=? AND SEMESTER.ID=SEMESTER_ID ORDER BY SEMESTER.CREATION_TIME");
$stmt->bind_param("i", $_REQUEST['dj']);
$stmt->execute();
$stmt->bind_result($show, $showID, $semester);

while ($stmt->fetch())
{
	printf("<tr><td>%s</td><td>%s</td>\n", $show, $semester);
	printf("<td><form action='./edit_dj?dj=%s&deleteID=%s' method='post' onsubmit=\"return confirm('Are you sure you want to delete %s for %s?')\"><input type=\"submit\" value=\"delete\"></form></td></tr>\n", $_REQUEST['dj'], $showID, $show, $semester);
}

$stmt->close();
?>

</table>

<?php footer(); ?>
</body>
</html>
