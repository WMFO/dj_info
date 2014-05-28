<html>
<head>
<title>
Access List
</title>
</head>

<body>
<h1>DJ Access List</h1>
<p>This page lists all DJs marked "active" for submitting for station access.</p>
<table border=3>
<tr>
<th>Last Name</th>
<th>First Name</th>
<th>Access Level Needed</th>
<th>Affiliation</th>
</tr>

<?php
include 'commlib.php';

$conn = mysql_init();
if (isset($_POST['resetall'])) {
    $conn->query("UPDATE DJ SET ACTIVE = NULL WHERE 1");
}

$stmt = $conn->prepare("SELECT F_NAME,L_NAME,M_NAME,PREF_NAME,STUDENT_ID,ACCESS,AFFILIATION FROM DJ WHERE ACTIVE = \"yes\" ORDER BY L_NAME ASC");
//printf("\n" . $conn->error . "\n");
$stmt->execute();
$stmt->bind_result($first, $last, $middle, $preferred, $id, $access, $affiliation);

while ($stmt->fetch())
{
	printf("<tr><td>%s</td><td>%s %s</td><td>%s</td><td>%s</td>",
		$last, $first, $middle, $access, $affiliation);
	printf("</tr>\n");
}

$stmt->close();
$conn->close();


?>

</table>
<form method="post" action="">
<p><input type="submit" name="resetall" value="Reset All Active Users"></p>
</form>

<?php footer(); ?>
</body>
</html>
