<?php
	require_once("../../../inc/MySQLConnection.php");
	$type = $_POST['resourceType'];

	if($type != null)
		$sql = "SELECT RE_ID, RE_ALIAS FROM recursos WHERE RE_TYPE = '$type'";
	else
		$sql = "SELECT RE_ID, RE_ALIAS FROM recursos";
	$query = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	if(mysqli_num_rows($query) > 0) {
		$resources = "";
		while($row = mysqli_fetch_assoc($query)) {
			$resources .= "<option value='$row[RE_ID]'>$row[RE_ALIAS]</option>";
		}
	} else {
		$resources = "<option value='na'>Unavailable resources...</option>";
	}

	echo $resources;