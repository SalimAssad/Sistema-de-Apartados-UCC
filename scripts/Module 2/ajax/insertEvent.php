<?php
	session_start();
	require_once("../../../inc/MySQLConnection.php");

	$id = trim(filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT));
	$date = date("Y-m-d");
	$start = trim(validateDate($_POST['startDate']));
	$end = trim(validateDate($_POST['endDate']));
	$user = $_SESSION['id'];
	$address = $_SERVER['REMOTE_ADDR'];
	$grade = trim(filter_input(INPUT_POST, "grade", FILTER_SANITIZE_NUMBER_INT));
	$lesson = trim(filter_input(INPUT_POST, "lesson", FILTER_SANITIZE_STRING));
	$area = trim(filter_input(INPUT_POST, "area", FILTER_SANITIZE_NUMBER_INT));
	$lendTo = trim(filter_input(INPUT_POST, "lendTo", FILTER_SANITIZE_NUMBER_INT));
	$comments = trim(filter_input(INPUT_POST, "comments", FILTER_SANITIZE_STRING));
	
	$dayOfTheWeek = trim(filter_input(INPUT_POST, "dayOfTheWeek", FILTER_SANITIZE_NUMBER_INT));
	$from = trim($_POST['from']);
	$to = trim($_POST['to']);

	$sql = "INSERT INTO apartados () values () ";
	$query = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	if(mysqli_num_rows($query) > 0) {
		$resources = "";
		while($row = mysqli_fetch_assoc($query)) {
			$resources .= "<option value='$row[RE_ID]'>$row[RE_ALIAS]</option>";
		}
	} else {
		$resources = "<option value='na'>Unavailable resources...</option>";
	}

	echo $resources;

	function validateDate($date) {
        list($year,$month,$day) = array_pad(preg_split("/[\/\\-]/",$date,3),3,0);
        if(!(ctype_digit("$year$month$day") && checkdate($month, $day, $year))) {
            $date = "";
        }
        return "$year-$month-$day";
    }