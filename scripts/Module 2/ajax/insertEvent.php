<?php
	// TODO LIST -->
	// - Validar los permisos de los usuarios
	session_start();
	$_SESSION['id'] = 1;
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

	mysqli_query($connection, "START TRANSACTION");
	$sql = "INSERT INTO apartados 
				(AP_RESID, AP_DATE, AP_START, AP_END, 
				AP_USERID, AP_USERADDR, AP_GRADE, AP_LESSON, 
				AP_AREAID, AP_LENDTO, AP_COMMENTS) 
			values ('$id', '$date', '$start', '$end',  
				'$user', '$address', '$grade', '$lesson',  
				'$area', '$lendTo', '$comments')";
	$query = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$sql = "SELECT AP_ID FROM apartados ORDER BY AP_ID DESC LIMIT 1";
	$selQuery = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$event = array();
	if(mysqli_num_rows($selQuery) > 0) {
		$fetch = mysqli_fetch_assoc($selQuery);
		$ap_id = $fetch["AP_ID"];


		array_push($event, $fact);
	} else {
		//$event = ;
	}
	mysqli_query($connection, "COMMIT");

	echo json_encode($event);

	function validateDate($date) {
        list($year,$month,$day) = array_pad(preg_split("/[\/\\-]/",$date,3),3,0);
        if(!(ctype_digit("$year$month$day") && checkdate($month, $day, $year))) {
            $date = "";
        }
        return "$year-$month-$day";
    }