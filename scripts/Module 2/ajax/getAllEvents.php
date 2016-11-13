<?php
	// TODO LIST -->
	// - Validar los permisos de los usuarios
	session_start();
	require_once("../../../inc/MySQLConnection.php");

	$start = trim(validateDate($_POST['start']));
	$end = trim(validateDate($_POST['end']));

	if($start == "" && $end == ""){
		exit("Error en los datos recibidos");
	}
	
	$sql = "SELECT HO_FROM, HO_TO, HO_DAY, AP_START, AP_END, RE_ALIAS, AP_ID 
			FROM horarios 
			JOIN apartados ON
			AP_ID = HO_SEPARATEID
			JOIN recursos ON
			RE_ID = AP_RESID
			WHERE 
				(AP_START BETWEEN '$start' AND '$end') 
				OR 
				(AP_END BETWEEN '$start' AND '$end')
				OR 
				(
					(AP_START <= '$start') 
					AND
					(AP_END >= '$end')
				)";
	$query = mysqli_query($connection, $sql);
	
	$events = array();
	if(mysqli_num_rows($query) > 0){
		while($row = mysqli_fetch_assoc($query)) {
			$day = $row['HO_DAY'];
			$date = strtotime($row['AP_START']);
			$lastday = strtotime($row['AP_END']);
			while ($date <= $lastday) {
				if($day == date("w", $date)) {
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
					$date += (3600 * 24 * 7); // If the day matches, let's add 7 to get the next same day
				} else {
			   		$date += (3600 * 24); // If not, we just keep searching for "The Day" ;)
				}
			}
		}
	}
	echo json_encode($events);

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