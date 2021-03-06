<?php
	session_start();
	require_once("../../../inc/MySQLConnection.php");

	$id = trim(filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT));

	$sql = "SELECT RE_ALIAS, AP_START, 
				   AP_END, US_NAME, US_LASTNAME,
				   AP_LESSON, AP_GRADE, AR_NAME,
				   AP_COMMENTS, RE_INUSE
			FROM apartados
			JOIN recursos ON AP_RESID = RE_ID
			JOIN usuarios ON AP_LENDTO = US_ID
			JOIN areas ON AP_AREAID = AR_ID
			WHERE AP_ID = $id";

	$query = mysqli_query($connection, $sql);
	if(!$query) error1("ERR0 BAD QUERY - $sql");

	if(mysqli_num_rows($query) < 1) error2("ERR1 DATA NOT FOUND");

	$result = mysqli_fetch_assoc($query);
	$response = array();

	$response[] = array(
		"start" => $result['AP_START'],
		"end" => $result['AP_END'],
		"title" => $result['RE_ALIAS'],
		"name" => "$result[US_NAME] $result[US_LASTNAME]",
		"lesson" => $result['AP_LESSON'],
		"area" => $result['AR_NAME'],
		"comments" => $result['AP_COMMENTS'],
		"inuse" => $result['RE_INUSE']
	);

	$sql = "SELECT HO_DAY, HO_FROM, HO_TO
			FROM horarios
			WHERE HO_SEPARATEID = $id";
	$query = mysqli_query($connection, $sql);
	if(!$query) error1("ERR2 BAD QUERY - $sql");

	if(mysqli_num_rows($query) < 1) error2("ERR3 DATA NOT FOUND");

	$daysOfWeek = "";
	$week = ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sáb"];
	$from = "";
	$to = "";
	while($day = mysqli_fetch_assoc($query)) {
		if($daysOfWeek != "")
			$daysOfWeek .= " - ";
		$daysOfWeek .= $week[$day['HO_DAY']];
		$from = $day['HO_FROM'];
		$to = $day['HO_TO'];
	}
	$response[0]["days"] = $daysOfWeek;
	$response[0]["from"] = $from;
	$response[0]["to"] = $to;

	echo json_encode($response);

    function error1($msg) {
		exit($msg);
	}

	function error2($msg) {
		exit($msg);
	}