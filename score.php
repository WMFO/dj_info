<html>
<head>
<title>
Scoring Control Panel
</title>
</head>

<body>
<h1>WMFO Scoring Control Panel</h1>
<form action="./score.php" method="post">
<table border="3">
<tr><td>Semester 1:</td><td>
<select name="Semester1">
<?php
include 'commlib.php';
$conn = mysql_init();

$stmt = $conn->prepare("SELECT ID,TITLE FROM SEMESTER ORDER BY CREATION_TIME DESC");
$stmt->execute();
$stmt->bind_result($id, $title);

while ($stmt->fetch())
{
    echo "<option value='$id'";
    if ($_POST['Semester1'] == $id) {
        echo " selected";
        $sem1 = $title;
    }
    echo ">$title</option>\n";
	//printf("<option value='%s'>%s</option>\n", $id, $title);
}

$stmt->close();
?>
</select></td></tr>
<tr><td>Semester 2:</td><td>
<select name="Semester2">
<?php

$stmt = $conn->prepare("SELECT ID,TITLE FROM SEMESTER ORDER BY CREATION_TIME DESC");
$stmt->execute();
$stmt->bind_result($id, $title);

while ($stmt->fetch())
{
    echo "<option value='$id'";
    if ($_POST['Semester2'] == $id) {
        echo " selected";
        $sem2 = $title;
    }
    echo ">$title</option>\n";
	//printf("<option value='%s'>%s</option>\n", $id, $title);
}

$stmt->close();
?>
</select></td></tr>
<tr><td>Semester 3:</td><td>
<select name="Semester3">
<?php

$stmt = $conn->prepare("SELECT ID,TITLE FROM SEMESTER ORDER BY CREATION_TIME DESC");
$stmt->execute();
$stmt->bind_result($id, $title);

while ($stmt->fetch())
{
    echo "<option value='$id'";
    if ($_POST['Semester3'] == $id) {
        echo " selected";
        $sem3 = $title;
    }
    echo ">$title</option>\n";
	//printf("<option value='%s'>%s</option>\n", $id, $title);
}

$stmt->close();
?>
</select></td></tr>
<tr><td>
<input type="submit" value="Score DJs">
</td></tr>
</table>
<hr>
<?php
if (
	isset($_POST['Semester1']) &&
	isset($_POST['Semester2']) &&
	isset($_POST['Semester3']) )
{?>
<h1>Scoring</h1>
<table border="3">
<tr><th>Name</th>
<?php
echo "<th>$sem1</th><th>$sem2</th><th>$sem3</th></tr>";
	$sem1 = $_POST['Semester1'];
	$sem2 = $_POST['Semester2'];
	$sem3 = $_POST['Semester3'];

	$stmt = $conn->prepare("SELECT L_NAME,F_NAME,ID,SENIORITY_OFFSET FROM DJ ORDER BY L_NAME ASC, F_NAME ASC");
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($last, $first, $dj_id, $seniority);

	while ($stmt->fetch())
	{
		$sub_bool = 0;
		$vol_hours = 0;
		$sub_hours = 0;
		$stmt2 = $conn->prepare("SELECT SUM(NUM_HOURS) FROM VOLUNTEER,SEMESTER WHERE DJ_ID=? AND SEMESTER_ID=? AND SUB_BOOL=?");
		$stmt2->bind_param("iii", $dj_id, $sem1, $sub_bool);
		$stmt2->execute();
		$stmt2->store_result();
		$stmt2->bind_result($vol_hours);
		if (!$stmt2->fetch())
		{
			$vol_hours = 0;
		}
		$stmt2->free_result();

		$sub_bool = 1;
		$stmt2->execute();
		$stmt2->store_result();
		$stmt2->bind_result($sub_hours);
		if (!$stmt2->fetch())
		{
			$sub_hours = 0;
		}
		$stmt2->free_result();
		$stmt2->close();

		//Two Sub Hours can be moved to volunteer
		if ( ($vol_hours < 5) && ($sub_hours >= 1) )
		{
			$vol_hours = $vol_hours + 1;
			$sub_hours = $sub_hours - 1;
		}

		if ( ($vol_hours < 5) && ($sub_hours >= 1) )
		{
			$vol_hours = $vol_hours + 1;
			$sub_hours = $sub_hours - 1;
		}

		//Calculate Score
		if ($vol_hours == 0)
		{
			$score1 = 0.1*(3*$seniority);
		}
		elseif ($vol_hours < 5)
		{
			$score1 = 2.5*($vol_hours + 0.4*$sub_hours)/5*(3*$seniority);
		}
		else
		{
			$score1 = 5*($vol_hours + 0.4*$sub_hours)/5*(3*$seniority);
		}

		//Check for shows;
		$stmt2 = $conn->prepare("SELECT COUNT(`SHOW`.ID) FROM `SHOW` WHERE DJ_ID=? AND SEMESTER_ID=?");
		$stmt2->bind_param("ii", $dj_id, $sem1);
		$stmt2->execute();
		$stmt2->bind_result($show_count);
		$stmt2->fetch();
		if ($show_count == NULL)
		{
			$has_show_1 = 0;
		}
		else
		{
			$has_show_1 = 1;
		}
		$stmt2->close();

		$sub_bool = 0;
		$vol_hours = 0;
		$sub_hours = 0;
		$stmt2 = $conn->prepare("SELECT SUM(NUM_HOURS) FROM VOLUNTEER,SEMESTER WHERE DJ_ID=? AND SEMESTER_ID=? AND SUB_BOOL=?");
		$stmt2->bind_param("iii", $dj_id, $sem2, $sub_bool);
		$stmt2->execute();
		$stmt2->store_result();
		$stmt2->bind_result($vol_hours);
		if (!$stmt2->fetch())
		{
			$vol_hours = 0;
		}
		$stmt2->free_result();

		$sub_bool = 1;
		$stmt2->execute();
		$stmt2->store_result();
		$stmt2->bind_result($sub_hours);
		if (!$stmt2->fetch())
		{
			$sub_hours = 0;
		}
		$stmt2->free_result();
		$stmt2->close();

		//Two Sub Hours can be moved to volunteer
		if ( ($vol_hours < 5) && ($sub_hours >= 1) )
		{
			$vol_hours = $vol_hours + 1;
			$sub_hours = $sub_hours - 1;
		}

		if ( ($vol_hours < 5) && ($sub_hours >= 1) )
		{
			$vol_hours = $vol_hours + 1;
			$sub_hours = $sub_hours - 1;
		}

		//Calculate Score
		if ($vol_hours == 0)
		{
			$score2 = 0.1*(3*$seniority);
		}
		elseif ($vol_hours < 5)
		{
			$score2 = 2.5*($vol_hours + 0.4*$sub_hours)/5*(3*$seniority);
		}
		else
		{
			$score2 = 5*($vol_hours + 0.4*$sub_hours)/5*(3*$seniority);
		}

		//Check for shows;
		$stmt2 = $conn->prepare("SELECT COUNT(`SHOW`.ID) FROM `SHOW` WHERE DJ_ID=? AND SEMESTER_ID=?");
		$stmt2->bind_param("ii", $dj_id, $sem2);
		$stmt2->execute();
		$stmt2->bind_result($show_count);
		$stmt2->fetch();
		if ($show_count == NULL)
		{
			$has_show_2 = 0;
		}
		else
		{
			$has_show_2 = 1;
		}
		$stmt2->close();



		$sub_bool = 0;
		$vol_hours = 0;
		$sub_hours = 0;
		$stmt2 = $conn->prepare("SELECT SUM(NUM_HOURS) FROM VOLUNTEER,SEMESTER WHERE DJ_ID=? AND SEMESTER_ID=? AND SUB_BOOL=?");
		$stmt2->bind_param("iii", $dj_id, $sem3, $sub_bool);
		$stmt2->execute();
		$stmt2->store_result();
		$stmt2->bind_result($vol_hours);
		if (!$stmt2->fetch())
		{
			$vol_hours = 0;
		}
		$stmt2->free_result();

		$sub_bool = 1;
		$stmt2->execute();
		$stmt2->store_result();
		$stmt2->bind_result($sub_hours);
		if (!$stmt2->fetch())
		{
			$sub_hours = 0;
		}
		$stmt2->free_result();
		$stmt2->close();

		//Two Sub Hours can be moved to volunteer
		if ( ($vol_hours < 5) && ($sub_hours >= 1) )
		{
			$vol_hours = $vol_hours + 1;
			$sub_hours = $sub_hours - 1;
		}

		if ( ($vol_hours < 5) && ($sub_hours >= 1) )
		{
			$vol_hours = $vol_hours + 1;
			$sub_hours = $sub_hours - 1;
		}

		//Calculate Score
		if ($vol_hours == 0)
		{
			$score3 = 0.1*(3*$seniority);
		}
		elseif ($vol_hours < 5)
		{
			$score3 = 2.5*($vol_hours + 0.4*$sub_hours)/5*(3*$seniority);
		}
		else
		{
			$score3 = 5*($vol_hours + 0.4*$sub_hours)/5*(3*$seniority);
		}

		//Check for shows;
		$stmt2 = $conn->prepare("SELECT COUNT(`SHOW`.ID) FROM `SHOW` WHERE DJ_ID=? AND SEMESTER_ID=?");
		$stmt2->bind_param("ii", $dj_id, $sem3);
		$stmt2->execute();
		$stmt2->bind_result($show_count);
		$stmt2->fetch();
		if ($show_count == NULL)
		{
			$has_show_3 = 0;
		}
		else
		{
			$has_show_3 = 1;
		}
		$stmt2->close();


		printf("<tr><td>%s, %s</td>", $last, $first);
		if ($has_show_1)
		{
			printf("<td>%s</td>", $score1);
		}
		else
		{
			printf("<td>%s%s</td>", $score1, "*");
		}

		if ($has_show_2)
		{
			printf("<td>%s</td>", $score2);
		}
		else
		{
			printf("<td>%s%s</td>", $score2, "*");
		}

		if ($has_show_3)
		{
			printf("<td>%s</td>", $score3);
		}
		else
		{
			printf("<td>%s%s</td>", $score3, "*");
		}
		printf("</tr>\n");



	}

}
?>
</table>

<?php footer(); ?>
</body>
</html>
