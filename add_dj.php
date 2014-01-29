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
<tr><td>First Name: </td><td><input name="FirstName" required></td></tr>
<tr><td>Last Name: </td><td><input name="LastName" required></td></tr>
<tr><td>Email: </td><td><input type="email" name="Email" required></td></tr>
<tr><td>Phone: </td><td><input name="Phone"></td><tr>
<tr><td>Year Joined: </td><td><input name="YearJoined" required></td></tr>
<tr><td>Seniority Offset: </td><td><input name="SeniorityOffset" required></td></tr>
<tr><td>Student ID: </td><td><input name="StudentID"></td></tr>
<tr><td>Affiliation: </td><td><select name="Affiliation">
<option value="Student">Student</option>
<option value="Alum">Alum</option>
<option value="Community">Community</option></select></td></tr>
<tr><td>Access: </td><td><select name="Access">
<option value="General">General</option>
<option value="MD">MD</option>
<option value="Engineer">Engineer</option>
<option value="All">All</option></select></td></tr>
<tr><td>Exec: </td><td><input type="checkbox" name="Exec" value="yes"></td></tr>
<tr><td>Active: </td><td><input type="checkbox" name="Active" value="yes"></td></tr>
<tr><td><input type="submit" value="Add DJ"></td></tr>
</table>
</form>
<hr>
<form method="post" action="">
<h1>Current DJs</h1>
<table border=3>
<tr>
<th>Last Name</th>
<th>First Name</th>
<th>Year Joined</th>
<th>Seniority Offset</th>
<th>Student ID</th>
<th>Summary</th>
<th>Affiliation</th>
<th>Discipline</th>
<th>Edit</th>
<th>Active</th>
</tr>

<?php
include 'commlib.php';

$conn = mysql_init();
if (isset($_POST['activate'])) {
    $notactivated = array();
    $toactivate = array();
    $conn = mysql_init();
    $allDJs = $conn->query("SELECT * FROM DJ");
    while ($DJ = $allDJs->fetch_assoc()) {
        if (in_array($DJ['ID'], $_POST['active'])) {
            $toactivate[] = $DJ['ID'];
        } else {
            $notactivated[] = $DJ['ID'];
        }
    }
    foreach ($toactivate as $person) {
        $conn->query("UPDATE DJ SET ACTIVE = \"yes\" WHERE ID = $person");
    }
    foreach ($notactivated as $person) {
        $conn->query("UPDATE DJ SET ACTIVE = NULL WHERE ID = $person");
    }
}
if (isset($_POST['resetall'])) {
        $conn->query("UPDATE DJ SET ACTIVE = NULL WHERE 1");
} 
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
        $_POST['Email'], $_POST['Phone'], $_POST['StudentID'],
    $_POST['Affiliation'], $_POST['Access'], $_POST['Exec'], $_POST['Active']);
}

$stmt = $conn->prepare("SELECT ID,F_NAME,L_NAME,YEAR_JOINED,SENIORITY_OFFSET,ACTIVE,STUDENT_ID,AFFILIATION FROM DJ ORDER BY L_NAME ASC");
//printf("\n" . $conn->error . "\n");
$stmt->execute();
$stmt->bind_result($id, $first, $last, $year, $senior, $active, $studentid, $affiliation);

while ($stmt->fetch())
{
    if ($active == "yes") {
        $checked = " checked=\"yes\"";
    } else {
        $checked = NULL;
    }
	printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>",
		$last, $first, $year, $senior, $studentid, $affiliation);
	printf("<td><a href='./dj_summary.php?dj=%s'>Summary</a></td>", $id);
	printf("<td><a href='./discipline.php?dj=%s'>Discipline</a></td>", $id);
	printf("<td><a href='./edit_dj.php?dj=%s'>Edit</a></td>", $id);
	printf("<td><input type=\"checkbox\" name=\"active[]\" value=\"%s\" %s></td>", $id, $checked);
	printf("</tr>\n");
}

$stmt->close();
$conn->close();

?>
</table>
<p><input type="submit" value="Update Activation" name="activate"></p>
<p><input type="submit" name="resetall" value="Reset All Active Users"></p>
</form>

<?php footer(); ?>
</body>
</html>
