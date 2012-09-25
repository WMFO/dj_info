<html>
<head>
<title>Show Control Panel</title>
</head>

<body>
<h1>WMFO Show Control Panel</h1>
<br>
<h2>Add Show</h2>
<form action="add_show.php" method="post">
<table border="3">
<tr><td>Name: </td><td><input name="ShowName"></td></tr>
<tr><td>DJ: </td><td>

<?php
include 'commlib.php';

$conn = mysql_init();
$stmt = $conn->prepare("SELECT ID,F_NAME,L_NAME FROM DJ ORDER BY L_NAME ASC, F_NAME ASC");
$stmt->execute();
$stmt->bind_result($id, $first, $last);

printf("<select name='dj'>\n");
while ($stmt->fetch())
{
	printf("<option value='%s'>%s, %s</option>\n", $id, $last, $first);
}

$stmt->close();
printf("</select>\n");


printf("<input type='hidden' name='semester' value='%s'>\n", $_REQUEST['semester']);

?>

</td></tr>
<tr><td><input type="submit" value="Add Show"></td></tr>
</table>
<hr>

<?php
if (
	isset($_POST['dj']) &&
	isset($_POST['ShowName']) &&
	isset($_POST['semester']) )
{
	add_show($conn, $_POST['dj'], $_POST['semester'], $_POST['ShowName']);
}

if (
	isset($_REQUEST['semester']) )
{
	$stmt = $conn->prepare("SELECT TITLE FROM SEMESTER WHERE ID=?");
	$stmt->bind_param("i", $_REQUEST['semester']);
	$stmt->execute();
	$stmt->bind_result($title);
	$stmt->fetch();
	printf("<h1>Shows for %s</h1>", $title);
	$stmt->close();

	printf("<table border='3'><tr><th>Show</th><th>DJ</th></tr>");
	$stmt = $conn->prepare("SELECT NAME,F_NAME,L_NAME FROM DJ,`SHOW` WHERE DJ.ID=`SHOW`.DJ_ID AND `SHOW`.SEMESTER_ID=? ORDER BY NAME ASC");
	$stmt->bind_param("i", $_REQUEST['semester']);
	$stmt->execute();
	$stmt->bind_result($show, $first, $last);
	while ($stmt->fetch())
	{
		printf("<tr><td>%s</td><td>%s, %s</td></tr>\n", $show, $last, $first);
	}
	$stmt->close();
	printf("</table>\n");
}

?>

<?php footer(); ?>

</body></html>

<?php
$conn->close();
?>
