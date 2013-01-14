<?php
function mysql_init()
{
	$mysql_ret = new mysqli("localhost", "dj_info", "dj_info", "dj_info");
	return $mysql_ret;
}

function add_semester($conn, $title)
{
	$stmt = $conn->prepare("INSERT INTO SEMESTER (TITLE, CREATION_TIME) VALUES (?, NOW())");
	if (!$stmt)
	{
		echo "Unable to prepare statement.\n";
		echo "Error num: " . $conn->error . "\n";
		return;
	}
	$stmt->bind_param("s", $title);
	$stmt->execute();
	$stmt->close();
}

function add_dj($conn, $first, $last, $year, $seniority, $email, $phone)
{
	$stmt = $conn->prepare("INSERT INTO DJ (F_NAME, L_NAME, YEAR_JOINED, SENIORITY_OFFSET, EMAIL, PHONE) VALUES (?, ?, ?, ?, ?, ?)");
	if (!$stmt)
	{
		echo "Unable to prepare statement.\n";
		echo "Error: " . $conn->error . "\n";
		return;
	}
	$stmt->bind_param("ssiiss", $first, $last, $year, $seniority, $email, $phone);
	$stmt->execute();
	$stmt->close();
}

function edit_dj($conn, $first, $last, $year, $seniority, $email, $phone, $id)
{
    $stmt = $conn->prepare("UPDATE DJ SET F_NAME=?, L_NAME=?, YEAR_JOINED=?, SENIORITY_OFFSET=?, EMAIL=?, PHONE=? WHERE ID=?");
	if (!$stmt)
	{
		echo "Unable to prepare statement.\n";
		echo "Error: " . $conn->error . "\n";
		return;
	}
	$result=$stmt->bind_param("ssiissi", $first, $last, $year, $seniority, $email, $phone, $id);
	if (!$result)
	{
		echo "Unable to bind parameters.\n";
		echo "Error: " . $conn->error . "\n";
		return;
	}
	$stmt->execute();
	$stmt->close();
}

function add_show($conn, $dj, $semester, $name)
{
	$stmt = $conn->prepare("INSERT INTO `SHOW` (SEMESTER_ID, DJ_ID, NAME) VALUES (?, ?, ?)");
	if (!$stmt)
	{
		echo "Unable to prepare statement.\n";
		echo "Error: " . $conn->error . "\n";
		return;
	}
	$stmt->bind_param("iis", $semester, $dj, $name);
	$stmt->execute();
	$stmt->close();
}

function add_discipline($conn, $dj, $day, $month, $year, $desc)
{
	$stmt = $conn->prepare("INSERT INTO DISCIPLINE (DJ_ID, DDAY, DMONTH, DYEAR, DESCRIPTION) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt)
        {
                echo "Unable to prepare statement.\n";
                echo "Error: " . $conn->error . "\n";
                return;
        }
	$stmt->bind_param("iiiis", $dj, $day, $month, $year, $desc);
	$stmt->execute();
	$stmt->close();
}

function add_volunteer($conn, $dj, $semester, $day, $month, $year, $hours, $desc, $type)
{
	$stmt = $conn->prepare("INSERT INTO VOLUNTEER (DJ_ID, SEMESTER_ID, DDAY, DMONTH, DYEAR, NUM_HOURS, DESCRIPTION, SUB_BOOL) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt)
        {
                echo "Unable to prepare statement.\n";
                echo "Error: " . $conn->error . "\n";
                return;
        }
	$stmt->bind_param("iiiiiisi", $dj, $semester, $day, $month, $year, $hours, $desc, $type);
	$stmt->execute();
	$stmt->close();
}

function footer()
{
	printf("<hr><a href='./add_semester.php'>Semesters</a> <a href='./add_dj.php'>DJs</a>" .
		" <a href='./volunteer.php'>Volunteering</a> <a href='score.php'>Scoring</a>");
}

?>

