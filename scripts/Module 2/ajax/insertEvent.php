<?php
	// TODO LIST -->
	// - Validar los permisos de los usuarios
	// - Validar entradas
	// - Controlar las posibles fallas de los queries
	session_start();
	//$_SESSION['id'] = 1;
	require_once("../../../inc/MySQLConnection.php");

	$resource = trim(filter_input(INPUT_POST, "resource", FILTER_SANITIZE_NUMBER_INT));
	$date = date("Y-m-d");
	$start = trim(validateDate($_POST['start']));
	$end = trim(validateDate($_POST['end']));
	$user = $_SESSION['id'];
	$address = $_SERVER['REMOTE_ADDR'];
	$grade = trim(filter_input(INPUT_POST, "grade", FILTER_SANITIZE_NUMBER_INT));
	$lesson = trim(filter_input(INPUT_POST, "lesson", FILTER_SANITIZE_STRING));
	$area = trim(filter_input(INPUT_POST, "area", FILTER_SANITIZE_NUMBER_INT));
	$lendTo = trim(filter_input(INPUT_POST, "lendTo", FILTER_SANITIZE_NUMBER_INT));
	$comments = trim(filter_input(INPUT_POST, "comments", FILTER_SANITIZE_STRING));
	
	$daysOfTheWeek = trim(filter_input(INPUT_POST, "daysOfTheWeek", FILTER_SANITIZE_STRING));
	$from = trim(validateTime($_POST['from']));
	$to = trim(validateTime($_POST['to']));

	mysqli_query($connection, "START TRANSACTION");
	$sql = "INSERT INTO apartados 
				(AP_RESID, AP_DATE, AP_START, AP_END, 
				AP_USERID, AP_USERADDR, AP_GRADE, AP_LESSON, 
				AP_AREAID, AP_LENDTO, AP_COMMENTS) 
			values ($resource, '$date', '$start', '$end',  
				$user, '$address', $grade, '$lesson',  
				$area, $lendTo, '$comments')";
	$query = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	// Now we ask the server for the previously inserted data
	// to attach the days where the resource has to be separated
	$sql = "SELECT AP_ID FROM apartados ORDER BY AP_ID DESC LIMIT 1";
	$selQuery = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	if(mysqli_num_rows($selQuery) > 0) {
		$fetch = mysqli_fetch_assoc($selQuery);
		$ap_id = $fetch["AP_ID"];
		$days = explode(",", $daysOfTheWeek);
		foreach ($days as $key => $day) {
			$sql = "INSERT INTO horarios 
						(HO_SEPARATEID, HO_DAY, HO_FROM, HO_TO) 
					values ('$ap_id', $day, '$from', '$to')";
			$query = mysqli_query($connection, $sql) or die(mysqli_error($connection));
		}
	} else {
		// The server couldn't retrieve data
	}
	mysqli_query($connection, "COMMIT");

	echo "TRUE"; // Response to AJAX

	function validateDate($date) {
        list($year,$month,$day) = array_pad(preg_split("/[\/\\-]/",$date,3),3,0);
        if(!(ctype_digit("$year$month$day") && checkdate($month, $day, $year))) {
            $date = "";
            return $date;
        }
        return "$year-$month-$day";
    }

    function validateTime($time) {
    	list($hr, $min, $sec) = explode(":", $time);
    	if($hr < 0 || $hr > 23)
    		return false;
    	if($min < 0 || $min > 59)
    		return false;
    	if($sec < 0 || $sec > 59)
    		return false;
    	return $time;
    }