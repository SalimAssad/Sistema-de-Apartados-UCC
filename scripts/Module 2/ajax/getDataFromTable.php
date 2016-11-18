<?php
	// TODO LIST -->
	session_start();
	//$_SESSION['id'] = 2;
	$userID = $_SESSION["id"];
	require_once("../../../inc/MySQLConnection.php");
	$table = $_POST['table'];

	switch($table) {
		case "areas":
			$sql = "SELECT AR_ID, AR_NAME, RE_DESCRIPTION, RE_ID
					FROM areas
					JOIN referencias ON
					AR_TYPE = RE_ID
					ORDER BY RE_DESCRIPTION, AR_NAME";
			$query = mysqli_query($connection, $sql);
			if(!$query) error1();
			if(mysqli_num_rows($query) > 0) {
				$data = "";
				$actualGroup = 0;
				while($row = mysqli_fetch_assoc($query)) {
					if($actualGroup != $row['RE_ID']) {
						if($actualGroup != 0)
							$data .= "</optgroup>";			
						$actualGroup = $row['RE_ID'];
						$data .= "<optgroup label='$row[RE_DESCRIPTION]'>";
					}	
					$data .= "<option value='$row[AR_ID]'>$row[AR_NAME]</option>";
				}
				$data .= "</optgroup>";
			} else {
				$data = "<option value='na'>Unavailable data...</option>";
			}
			break;
		case "usuarios":
			$sql = "SELECT US_ID, US_NAME, US_LASTNAME
					FROM usuarios
					JOIN perfiles ON
					US_PROFILEID = PE_ID
					WHERE PE_SEPARATE = 1"; // Here we restrict the users to show (only those who can separate)
			$query = mysqli_query($connection, $sql);
			if(!$query) error1();
			if(mysqli_num_rows($query) > 0) {
				$data = "";
				while($row = mysqli_fetch_assoc($query)) {
					if($row['US_ID'] == $userID) 
						$data .= "<option value='$row[US_ID]' selected>$row[US_NAME] $row[US_LASTNAME]</option>";
					else
						$data .= "<option value='$row[US_ID]'>$row[US_NAME] $row[US_LASTNAME]</option>";
				}
			} else {
				$data = "<option value='na'>Unavailable data...</option>";
			}
			break;
	}

	echo $data;

	function error1() {
		echo "An error ocurred while trying to get the data.";
		exit();
	}