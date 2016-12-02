<?php
	session_start();
	// The code must be inserted when the user enters the software
	$verificationCode = $_SESSION['code'];

	$codeToCompare = trim(filter_input(INPUT_POST, "verificationCode", FILTER_SANITIZE_STRING));
	// trim(filter_input(INPUT_POST, "verificationCode", FILTER_SANITIZE_NUMBER_INT));

	if($codeToCompare == "")
		exit("BLANK POST ERROR");

	if($verificationCode != $codeToCompare)
		exit("CODES NOT EQUAL ERROR");

	exit("TRUE");