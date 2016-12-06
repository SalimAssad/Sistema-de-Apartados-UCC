<?php
	// TODO LIST -->
	// - Validar los permisos de los usuarios, todos serán administradores por el alcance
	session_start();
	if(!isset($_SESSION['id']))
		returnFalse("SESSION ERROR");

	require_once("../../../inc/MySQLConnection.php");

	$resource = trim(filter_input(INPUT_POST, "resource", FILTER_SANITIZE_NUMBER_INT));
	$start = trim(validateDate($_POST['start']));
	$end = trim(validateDate($_POST['end']));
	$user = $_SESSION['id'];
	$lendTo = trim(filter_input(INPUT_POST, "lendTo", FILTER_SANITIZE_NUMBER_INT));
	$area = trim(filter_input(INPUT_POST, "area", FILTER_SANITIZE_NUMBER_INT));

	$date = date("Y-m-d"); // Can't be wrong, right?
	$address = $_SERVER['REMOTE_ADDR'];
	$grade = trim(filter_input(INPUT_POST, "grade", FILTER_SANITIZE_NUMBER_INT));				//Optional
	$lesson = trim(filter_input(INPUT_POST, "lesson", FILTER_SANITIZE_STRING));					//Optional
	$comments = trim(filter_input(INPUT_POST, "comments", FILTER_SANITIZE_STRING));				//Optional
	
	$daysOfTheWeek = trim(filter_input(INPUT_POST, "daysOfTheWeek", FILTER_SANITIZE_STRING));
	$from = trim(validateTime($_POST['from']));
	$to = trim(validateTime($_POST['to']));

	if($resource == "" || $start == "" || $end == "" || $user == "" || $lendTo == "" ||
	   $daysOfTheWeek == "" || $from == "" || $to == "" || $area == "")
		returnFalse("BLANK FIELD ERROR");

	if(!idExists($connection, $user, "usuarios", "US_ID"))
		returnFalse("ADMIN DOESN'T EXIST ERROR");

	if(!idExists($connection, $lendTo, "usuarios", "US_ID"))
		returnFalse("USER DOESN'T EXIST ERROR");

	if(!idExists($connection, $resource, "recursos", "RE_ID"))
		returnFalse("RESOURCE DOESN'T EXIST ERROR");

	if(!idExists($connection, $area, "areas", "AR_ID"))
		returnFalse("AREA DOESN'T EXIST ERROR");

	if(strtotime($start) > strtotime($end) || strtotime($start) < $date || strtotime($end) < $date)
		returnFalse("DATE ERROR");

	if(str_replace(":", "", $from) > str_replace(":", "", $to))
		returnFalse("TIME ERROR");

	$grade = ($grade >= 1 && $grade <= 10) ? $grade : "";

	if(!canBeSeparated($resource, $start, $end, $from, $to, $connection))
		returnFalse("ALREADY SEPARATED");

	mysqli_query($connection, "START TRANSACTION");
	$sql = "INSERT INTO apartados 
				(AP_RESID, AP_DATE, AP_START, AP_END, 
				AP_USERID, AP_USERADDR, AP_GRADE, AP_LESSON, 
				AP_AREAID, AP_LENDTO, AP_COMMENTS) 
			VALUES ($resource, '$date', '$start', '$end',  
				$user, '$address', '$grade', '$lesson',  
				$area, $lendTo, '$comments')";
	$query = mysqli_query($connection, $sql);
	if(!$query) error($connection, "QUERY ERROR - IIA"); //die(mysqli_error($connection));

	// Now we ask the server for the previously inserted data
	// to attach the days where the resource has to be separated
	$sql = "SELECT AP_ID FROM apartados ORDER BY AP_ID DESC LIMIT 1";
	$selQuery = mysqli_query($connection, $sql);
	if(!$query) error($connection);

	if(mysqli_num_rows($selQuery) > 0) {
		$fetch = mysqli_fetch_assoc($selQuery);
		$ap_id = $fetch["AP_ID"];
		$days = explode(",", $daysOfTheWeek);
		foreach ($days as $key => $day) {
			$sql = "INSERT INTO horarios 
						(HO_SEPARATEID, HO_DAY, HO_FROM, HO_TO) 
					values ('$ap_id', $day, '$from', '$to')";
			$query = mysqli_query($connection, $sql);
			if(!$query) error($connection, "QUERY ERROR - IIH");
		}
	} else {
		// The server couldn't retrieve data
		error($connection, "DATA RETRIEVE ERROR");
	}
	mysqli_query($connection, "COMMIT");

	echo "TRUE"; // Response to AJAX

	/* 
		Searches all the separatings that a resource has in the future with those
		parameters to prevent inconsistences, so if there's a result, then, the
		resource can't be separated because it already is, otherwise, go on! 
		Parameters:
			$resID: the id of the resource that they want to separate //Integer
			$start: a "yyyy-mm-dd" formatted date, the period's beginning date //String
			$end: 	a "yyyy-mm-dd" formatted date, the period's ending date //String
			$from:	a "hh:mm:ss" formatted time, the resource won't be available from this hour //String
			$to:	a "hh:mm:ss" formatted time, the resource won't be available until this hour //String
			$connection: $connection: the connection to the DataBase 	//Mysqli connection
		Return:
			boolean: true if there aren't coincidences with the selection
					 false if there are */
	function canBeSeparated($resID, $start, $end, $from, $to, $connection) {
		$from = date("H:i:s", strtotime("$from + 1 minute"));
		$to = date("H:i:s", strtotime("$to - 1 minute"));
		$sql = "SELECT HO_FROM, HO_TO, HO_DAY, AP_START, 
					   AP_END, RE_ALIAS, AP_ID, RE_ID
				FROM horarios 
				JOIN apartados ON
				AP_ID = HO_SEPARATEID
				JOIN recursos ON
				RE_ID = AP_RESID
				JOIN tipos_equipos ON
				TI_ID = RE_HWTYPE
				WHERE 
					((AP_START BETWEEN '$start' AND '$end') 
					OR 
					(AP_END BETWEEN '$start' AND '$end')
					OR 
					(
						(AP_START <= '$start') 
						AND
						(AP_END >= '$end')
					))
					AND
					((HO_FROM BETWEEN '$from' AND '$to') 
					OR 
					(HO_TO BETWEEN '$from' AND '$to')
					OR 
					(
						(HO_FROM <= '$from') 
						AND
						(HO_TO >= '$to')
					))
					AND AP_CANCEL = 0
					AND RE_ID = $resID";
		$query = mysqli_query($connection, $sql);
		if(!$query) returnFalse("QUERY ERROR - CBS");

		if(mysqli_num_rows($query) > 0)
			return false; // ALREADY SEPARATED

		return true; // YES, CAN BE SEPARATED
	}

	/* 
		Function that validates a date */
	function validateDate($date) {
        list($year,$month,$day) = array_pad(preg_split("/[\/\\-]/",$date,3),3,0);
        if(!(ctype_digit("$year$month$day") && checkdate($month, $day, $year))) {
            $date = "";
            return $date;
        }
        return "$year-$month-$day";
    }

    /* 
    	This function validates the time that the user sent 
    	it must be between 7 and 22 due to the requirements */
    function validateTime($time) {
    	list($hr, $min, $sec) = explode(":", $time);
    	if($hr < 7 || $hr > 22)
    		return ""; //false
    	if($min < 0 || $min > 59)
    		return ""; //false
    	if($sec < 0 || $sec > 59)
    		return ""; //false
    	return $time;
    }

    /*
    	This function searches for ids in the database
    	Parameters:
    		$connection: the connection to the DataBase 	//Mysqli connection
    		$id: the id to search for 	//integer
    		$table: the name of the table in which the id should be 	//String
    		$field: the name of the field that should contains the id 	//String
    	Returns:
    		boolean true if the id does exist in the defined table 
    		boolean false if not */
    function idExists($connection, $id, $table, $field) {
    	$sql = "SELECT $field FROM $table WHERE $field = '$id'";
    	$query = mysqli_query($connection, $sql);
    	if(!$query) error($connection);
    	if(mysqli_num_rows($query) == 0)
    		return false;
    	return true;
    }

    /* 
		A function to call when a query wasn't executed as expected. */
    function error($connection, $msg) {
    	mysqli_query($connection, "ROLLBACK");
		exit($msg);
	}

    /* 
		A function to call when some data is wrong. */
	function returnFalse($msg) {
		exit($msg);
	}