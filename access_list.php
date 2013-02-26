<html>
<head>
<title>
Access List
</title>
</head>

<body>
<h1>DJ Access List</h1>
<table border=3>
<tr>
<th>Last Name</th>
<th>First Name</th>
<th>Student ID</th>
<th>Access Level Needed</th>
<th>Affiliation</th>
</tr>

<?php
include 'commlib.php';

$conn = mysql_init();

$stmt = $conn->prepare("SELECT F_NAME,L_NAME,STUDENT_ID,ACCESS,AFFILIATION FROM DJ ORDER BY L_NAME ASC");
//printf("\n" . $conn->error . "\n");
$stmt->execute();
$stmt->bind_result($first, $last, $id, $access, $affiliation);

while ($stmt->fetch())
{
	printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>",
		$last, $first, $id, $access, $affiliation);
	printf("</tr>\n");
}

$stmt->close();
$conn->close();

?>

</table>

<?php footer(); ?>
</body>
</html>
