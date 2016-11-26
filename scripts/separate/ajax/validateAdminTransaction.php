<?php
	session_start();
	$_SESSION['code'] = 1995;
	// The code must be inserted when the user enters the software
	$verificationCode = $_SESSION['code'];

	$codeToCompare = trim(filter_input(INPUT_POST, "verificationCode", FILTER_SANITIZE_STRING));
	// trim(filter_input(INPUT_POST, "verificationCode", FILTER_SANITIZE_NUMBER_INT));

	if($codeToCompare == "")
		exit("FALSE");

	if($verificationCode != $codeToCompare)
		exit("FALSE");

	exit("TRUE");