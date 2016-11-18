<?php
	session_start();
	require_once("../../../inc/MySQLConnection.php");

	$id = trim(filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT));

	$sql = "SELECT RE_ALIAS, AP_START, 
				   AP_END, US_NAME, US_LASTNAME,
				   AP_LESSON, AP_GRADE, AR_NAME,
				   AP_COMMENTS
			FROM apartados
			JOIN recursos ON AP_RESID = RE_ID
			JOIN usuarios ON AP_LENDTO = US_ID
			JOIN areas ON AP_AREAID = AR_ID
			WHERE AP_ID = $id";

	$query = mysqli_query($connection, $sql);
	if(!$query) error1();

	if(mysqli_num_rows($query) < 1) error2();

	$result = mysqli_fetch_assoc($query);
	$response = array();

	$response[] = array(
		"start" => $result['AP_START'],
		"end" => $result['AP_END'],
		"title" => $result['RE_ALIAS'],
		"name" => "$result[US_NAME] $result[US_LASTNAME]",
		"lesson" => $result['AP_LESSON'],
		"area" => $result['AR_NAME'],
		"comments" => $result['AP_COMMENTS']
	);

	echo json_encode($response);

    function error1() {
		echo "An error ocurred while trying to get the data.";
		exit();
	}

	function error2() {
		echo "There isn't available data for the given ID";
		exit();
	}