<?php
	// TODO LIST -->
	// - Validar los permisos de los usuarios
	// - Validar entradas
	session_start();
	if(!isset($_SESSION['id']))
		returnFalse();

	//$_SESSION['id'] = 1;
	require_once("../../../inc/MySQLConnection.php");

	$resource = trim(filter_input(INPUT_POST, "resource", FILTER_SANITIZE_NUMBER_INT));
	$start = trim(validateDate($_POST['start']));
	$end = trim(validateDate($_POST['end']));
	$user = $_SESSION['id'];
	$lendTo = trim(filter_input(INPUT_POST, "lendTo", FILTER_SANITIZE_NUMBER_INT));

	$date = date("Y-m-d"); // Can't be wrong, right?
	$address = $_SERVER['REMOTE_ADDR'];
	$grade = trim(filter_input(INPUT_POST, "grade", FILTER_SANITIZE_NUMBER_INT));				//Optional
	$lesson = trim(filter_input(INPUT_POST, "lesson", FILTER_SANITIZE_STRING));					//Optional
	$area = trim(filter_input(INPUT_POST, "area", FILTER_SANITIZE_NUMBER_INT));					//Optional
	$comments = trim(filter_input(INPUT_POST, "comments", FILTER_SANITIZE_STRING));				//Optional
	
	$daysOfTheWeek = trim(filter_input(INPUT_POST, "daysOfTheWeek", FILTER_SANITIZE_STRING));
	$from = trim(validateTime($_POST['from']));
	$to = trim(validateTime($_POST['to']));

	if($resource == "" || $start == "" || $end == "" || $user == "" || $lendTo == "" ||
	   $daysOfTheWeek == "" || $from == "" || $to == "")
		returnFalse();

	if(!idExists($connection, $user, "usuarios", "US_ID"))
		returnFalse();

	if(!idExists($connection, $lendTo, "usuarios", "US_ID"))
		returnFalse();

	if(!idExists($connection, $resource, "recursos", "RE_ID"))
		returnFalse();

	if($start > $end || $start < $date || $end < $date)
		returnFalse();

	if(str_replace(":", "", $from) > str_replace(":", "", $to))
		returnFalse();

	$area = (idExists($connection, $area, "areas", "AR_ID")) ? $area : "";
	$grade = ($grade >= 1 && $grade <= 10) ? $grade : "";

	mysqli_query($connection, "START TRANSACTION");
	$sql = "INSERT INTO apartados 
				(AP_RESID, AP_DATE, AP_START, AP_END, 
				AP_USERID, AP_USERADDR, AP_GRADE, AP_LESSON, 
				AP_AREAID, AP_LENDTO, AP_COMMENTS) 
			VALUES ($resource, '$date', '$start', '$end',  
				$user, '$address', $grade, '$lesson',  
				$area, $lendTo, '$comments')";
	$query = mysqli_query($connection, $sql);
	if(!$query) error(); //die(mysqli_error($connection));

	// Now we ask the server for the previously inserted data
	// to attach the days where the resource has to be separated
	$sql = "SELECT AP_ID FROM apartados ORDER BY AP_ID DESC LIMIT 1";
	$selQuery = mysqli_query($connection, $sql);
	if(!$query) error();

	if(mysqli_num_rows($selQuery) > 0) {
		$fetch = mysqli_fetch_assoc($selQuery);
		$ap_id = $fetch["AP_ID"];
		$days = explode(",", $daysOfTheWeek);
		foreach ($days as $key => $day) {
			$sql = "INSERT INTO horarios 
						(HO_SEPARATEID, HO_DAY, HO_FROM, HO_TO) 
					values ('$ap_id', $day, '$from', '$to')";
			$query = mysqli_query($connection, $sql);
			if(!$query) error();
		}
	} else {
		// The server couldn't retrieve data
		error();
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
    		return ""; //false
    	if($min < 0 || $min > 59)
    		return ""; //false
    	if($sec < 0 || $sec > 59)
    		return ""; //false
    	return $time;
    }

    function idExists($connection, $id, $table, $field) {
    	$sql = "SELECT $field FROM $table WHERE $field = $id";
    	$query = mysqli_query($connection, $sql);
    	if(!$query) error();
    	if(mysqli_num_rows($query) == 0)
    		return false;
    	return true;
    }

    /* 
		A function to call when a query wasn't executed as expected. */
    function error($connection) {
    	mysqli_query($connection, "ROLLBACK");
		echo "FALSE";
		exit();
	}

	function returnFalse() {
		echo "FALSE";
		exit();
	}