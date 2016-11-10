<?php
	// TODO LIST -->
	// - Validar los permisos de los usuarios
	session_start();
	$_SESSION['id'] = 1;
	require_once("../../../inc/MySQLConnection.php");
	$namedDays = array("sunday", "monday", "tuesday", "wednesday", "thursday", "saturday");
	$namedMonths = array(1 => "january", 2 => "february", 3 => "march", 4 => "april", 
						 5 => "may", 6 => "june", 7 => "july", 8 => "august", 
						 9 => "september", 10 => "october", 11 => "november", 12 => "december");

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

	$events = array();
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

		$sql = "SELECT HO_FROM, HO_TO, HO_DAY, AP_START, AP_END, RE_ALIAS, AP_ID 
				FROM horarios 
				JOIN apartados ON
				AP_ID = HO_SEPARATEID
				JOIN recursos ON
				RE_ID = AP_RESID
				WHERE HO_SEPARATEID = '$ap_id'";
		$query = mysqli_query($connection, $sql);

		if(mysqli_num_rows($query) > 0){
			while($row = mysqli_fetch_assoc($query)) {
				$day = $row['HO_DAY'];
				list($y, $m, $d) = explode("-", $row['AP_START']);
				$first = strtotime("first $namedDays[$day] of $namedMonths[$m] $y");
				$lastday = date($row['AP_END']); //strtotime()

				$date = $first;
				do {
					$id = $row['AP_ID'];
					$startDate = date('Y-m-d', $date)."T".$row['HO_FROM'];
					$endDate = date('Y-m-d', $date)."T".$row['HO_TO'];
					$title = $row['RE_ALIAS'];

					$events[] = array(
						"id" => $id, 
						"start" => $startDate,
						"end" => $endDate,
						"title" => $title
					);

				   	$date += 7 * 86400;
				} while ($date < $lastday);
			}
		}
	} else {
		// The server couldn't retrieve data
	}
	mysqli_query($connection, "COMMIT");

	echo json_encode($events);

	function validateDate($date) {
        list($year,$month,$day) = array_pad(preg_split("/[\/\\-]/",$date,3),3,0);
        if(!(ctype_digit("$year$month$day") && checkdate($month, $day, $year))) {
            $date = "";
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