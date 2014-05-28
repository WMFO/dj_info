<?php
include('./commlib.php');
$conn = mysql_init();
if ($_GET['item'] == "tcexam") {
$djs = tcexam_djs($conn);
$chars = "abcdefghijklmnopqrstuvwxyz1234567890";
function get_random_string($valid_chars, $length)
{
    $random_string = "";
    $num_valid_chars = strlen($valid_chars);
    for ($i = 0; $i < $length; $i++)
    {
        $random_pick = mt_rand(1, $num_valid_chars);
        $random_char = $valid_chars[$random_pick-1];
        $random_string .= $random_char;
    }
    return $random_string;
}
?>
<html>
<head>
<title>Export</title>
</head>
<body>
<table border="1">
<tr><th>user_name</th><th>user_password</th><th>user_email</th><th>user_firstname</th><th>user_lastname</th><th>user_regnumber</th><th>user_level</th><th>user_groups</th></tr>
<?php
$i = 1;
foreach ($djs as $dj) {
    $username = str_replace(array(".","-"," "),"",$dj['F_NAME']) . $i;
    echo "<tr><td>$username</td><td>" . get_random_string($chars, 10) . "</td><td>${dj['EMAIL']}</td><td>${dj['F_NAME']}</td><td>${dj['L_NAME']}</td><td>${dj['ID']}</td><td>1</td><td>dj,";
    echo strtolower($dj['AFFILIATION']);
    echo "</td></tr>";
    $i++;
}
?>
</table>
<?php } elseif ($_GET['item'] == "vol") {
    if (!isset($_GET['sem'])) {
        $stmt = $conn->prepare("SELECT ID,TITLE FROM SEMESTER ORDER BY CREATION_TIME DESC");
        $stmt->execute();
        $stmt->bind_result($id, $title);

        while ($stmt->fetch())
        {
            echo "<a href='?item=vol&sem=$id'>$title</a><br>\n";
        }

        $stmt->close();
        die();

    }
    $sem = $conn->real_escape_string($_GET['sem']);
    $ret = array();
    $res = $conn->query("SELECT F_NAME, M_NAME, PREF_NAME, L_NAME,EMAIL, ID, ACTIVE, SUB, UNSUB, `EXEC`, YEAR_JOINED, AFFILIATION FROM DJ");
    while ($r = $res->fetch_assoc()) {
        $p = array(
            "id" => $r['ID'],
            "fname" => $r['F_NAME'],
            "pref_name" => $r['PREF_NAME'],
            "middle_name" => $r['M_NAME'],
            "lname" => $r['L_NAME'],
            "email" => $r['EMAIL'],
            "active" => $r['ACTIVE'] == "yes" ? true : false,
            "sub" => $r['SUB'] == "yes" ? true : false,
            "unsubscribed" => $r['UNSUB'] == "yes" ? true : false,
            "exec" => $r['EXEC'] == "yes" ? true : false,
            "year_joined" => $r['YEAR_JOINED'],
            "affiliation" => $r['AFFILIATION']
        );
        $volres = $conn->query("SELECT SUM(NUM_HOURS) AS VOL FROM VOLUNTEER WHERE SEMESTER_ID = $sem AND DJ_ID = ${r['ID']} AND SUB_BOOL = 0");
        $volr = $volres->fetch_assoc();
        $volhrs = $volr['VOL'];
        $p['vol_hours'] = $volhrs == NULL ? 0 : $volhrs;
//var_dump($volr);
        $subres = $conn->query("SELECT SUM(NUM_HOURS) AS SUB FROM VOLUNTEER WHERE SEMESTER_ID = $sem AND DJ_ID = ${r['ID']} AND SUB_BOOL = 1");
        $subr = $subres->fetch_assoc();
        $subhrs = $subr['SUB'];
        $p['sub_hours'] = $subhrs == NULL ? 0 : $subhrs;;
        $showres = $conn->query("SELECT `NAME` FROM `SHOW` WHERE SEMESTER_ID = $sem AND DJ_ID = ${r['ID']}");
        $showr = $showres->fetch_assoc();
        $p['show_name'] = $showr['NAME'];
        $ret[] = $p;
        //die("SELECT `NAME` FROM `SHOW` WHERE SEMESTER_ID = $sem AND DJ_ID = ${r['ID']}");
    }
    echo json_encode($ret);
}
?>
</body>
</html>
