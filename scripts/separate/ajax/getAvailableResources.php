<?php
	require_once("../../../inc/MySQLConnection.php");
	$type = trim(filter_input(INPUT_POST, "resourceType", FILTER_SANITIZE_STRING));
	$resources = "<option value=''>Seleccione un recurso...</option>";
	$actualGroup = 0;

	if($type == "AULA") {
		$sql = "SELECT RE_ID, RE_ALIAS, UB_CAMPUS, UB_ID
				FROM recursos
				JOIN ubicaciones ON
				RE_LOCATION = UB_ID
				WHERE RE_TYPE = '$type'
				AND RE_AVAILABLE = 1
				ORDER BY UB_CAMPUS, RE_ALIAS";
		$query = mysqli_query($connection, $sql);
		if(!$query) error1();
		if(mysqli_num_rows($query) > 0) {
			while($row = mysqli_fetch_assoc($query)) {
				if($actualGroup != $row['UB_ID']) {
					if($actualGroup != 0)
						$resources .= "</optgroup>";			
					$actualGroup = $row['UB_ID'];
					$resources .= "<optgroup label='$row[UB_CAMPUS]'>";
				}	
				$resources .= "<option value='$row[RE_ID]'>$row[RE_ALIAS]</option>";
			}
			$resources .= "</optgroup>";
		} else {
			$resources = "<option value=''>Unavailable data...</option>";
		}
	} else {
		$sql = "SELECT RE_ID, RE_ALIAS, TI_DESCRIPTION, TI_ID
				FROM recursos
				JOIN tipos_equipos ON
				RE_HWTYPE = TI_ID
				WHERE RE_TYPE = '$type'
				AND RE_AVAILABLE = 1
				ORDER BY TI_DESCRIPTION, RE_ALIAS";
		$query = mysqli_query($connection, $sql);
		if(!$query) error1();
		if(mysqli_num_rows($query) > 0) {
			while($row = mysqli_fetch_assoc($query)) {
				if($actualGroup != $row['TI_ID']) {
					if($actualGroup != 0)
						$resources .= "</optgroup>";			
					$actualGroup = $row['TI_ID'];
					$resources .= "<optgroup label='$row[TI_DESCRIPTION]'>";
				}
				$resources .= "<option value='$row[RE_ID]'>$row[RE_ALIAS]</option>";
			}
			$resources .= "</optgroup>";
		} else {
			$resources = "<option value=''>Unavailable data...</option>";
		}
	}

	echo $resources;

	function error1() {
		echo "<option value=''>Unavailable data...</option>";
		exit();
	}