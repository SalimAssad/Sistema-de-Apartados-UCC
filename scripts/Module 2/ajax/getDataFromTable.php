<?php
	// TODO LIST -->
	// - Validar que el id que se esté usando sea el id y no el sid
	session_start();
	$_SESSION['id'] = 2;
	$userID = $_SESSION["id"];
	require_once("../../../inc/MySQLConnection.php");
	$table = $_POST['table'];
	$fields = $_POST['fields'];
	$filter = $_POST['filter'];

	$sql = "SELECT $fields FROM $table";
	$query = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	if(mysqli_num_rows($query) > 0) {
		$data = "";
		while($row = mysqli_fetch_assoc($query)) {
			list($id, $value) = explode(",", $fields);
			if($row[$id] == $userID && $filter == "true") 
				$data .= "<option value='$row[$id]' selected>$row[$value]</option>";
			else
				$data .= "<option value='$row[$id]'>$row[$value]</option>";
		}
	} else {
		$data = "<option value='na'>Unavailable data...</option>";
	}

	echo $data;