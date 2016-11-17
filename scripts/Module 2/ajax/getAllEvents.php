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
	
	$sql = "SELECT HO_FROM, HO_TO, HO_DAY, AP_START, 
				   AP_END, RE_ALIAS, AP_ID, TI_COLOR,
				   RE_ID
			FROM horarios 
			JOIN apartados ON
			AP_ID = HO_SEPARATEID
			JOIN recursos ON
			RE_ID = AP_RESID
			JOIN tipos_equipos ON
			TI_ID = RE_HWTYPE
			WHERE 
				(AP_START BETWEEN '$start' AND '$end') 
				OR 
				(AP_END BETWEEN '$start' AND '$end')
				OR 
				(
					(AP_START <= '$start') 
					AND
					(AP_END >= '$end')
				)
			ORDER BY AP_ID"; // Ordered to avoid errors at setting the color hehe
	$query = mysqli_query($connection, $sql);
	
	$events = array();
	$actualID = 0;
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
					$color = $row['TI_COLOR'];
					$resourceID = $row['RE_ID'];

					$events[] = array(
						"id" => $id,
						"start" => $startDate,
						"end" => $endDate,
						"title" => $title,
						"color" => $color,
						"resourceID" => $resourceID
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

	/*function generateRandomColor($colorsToIgnore) {
	    $r = 0; $g = 0; $b = 0;
	    $color = "";
	    do {
	        $r = dechex(rand(0, 255));
	        $g = dechex(rand(0, 255));
	        $b = dechex(rand(0, 255));
	        $color = "#$r$g$b";
	    } while(in_array($color, $colorsToIgnore));
	    return $color;
	}*/