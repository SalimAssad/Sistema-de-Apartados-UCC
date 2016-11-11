<?php
//include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");

$type = filter_input(INPUT_POST, 'resource', FILTER_SANITIZE_STRING);
$alias = filter_input(INPUT_POST, 'alias', FILTER_SANITIZE_STRING);
if ($type == "EQUIPO") {
    $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_STRING);
    $serial = filter_input(INPUT_POST, 'serial', FILTER_SANITIZE_STRING);
    $inventory = filter_input(INPUT_POST, 'inventory', FILTER_SANITIZE_STRING);
} else {
    $model = "";
    $serial = "";
    $inventory = "";
}

$location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
$campus = filter_input(INPUT_POST, 'campus', FILTER_SANITIZE_STRING);
$pile = filter_input(INPUT_POST, 'pile', FILTER_SANITIZE_STRING);
$floor = filter_input(INPUT_POST, 'floor', FILTER_SANITIZE_STRING);
$room = filter_input(INPUT_POST, 'room', FILTER_SANITIZE_STRING);

if (filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING) == "add") {
    if ($location == "new") {
        $insertLocation = mysqli_query($connection, "INSERT INTO ubicaciones(UB_PILE, UB_CAMPUS, UB_FLOOR, UB_ROOM)
                                            VALUES('$pile', '$campus','$floor','$room')");
        $idLocation = mysqli_insert_id($connection);
    } else {
        $idLocation = $location;
    }
    $insertResource = mysqli_query($connection, "INSERT INTO recursos(RE_MODEL, RE_ALIAS, RE_TYPE, RE_AVAILABLE, RE_SERIAL, RE_INVENTORY, RE_CREATED, RE_LOCATION) 
                                            VALUES('$model', '$alias', '$type', 1, '$serial', '$inventory', NOW(), $idLocation)");

    if($insertResource) {
        if($type == "EQUIPO")
            header("Location: equipments.php");
        else
            header("Location: classRooms.php");
        exit;
    }else {
        header("Location: addResource.php?error=No se pudo ingresar el recurso a la base de datos");
        exit;
    }
} else {    //Lógica de actualización
    $idResource = filter_input(INPUT_POST, 'idResource', FILTER_SANITIZE_NUMBER_INT);
    if ($location == "new") {
        $insertLocation = mysqli_query($connection, "INSERT INTO ubicaciones(UB_PILE, UB_CAMPUS, UB_FLOOR, UB_ROOM)
                                            VALUES('$pile', '$campus','$floor','$room')");
        $idLocation = mysqli_insert_id($connection);
    } else {
        $idLocation = $location;
    }
    $updateResource = mysqli_query($connection, "UPDATE recursos SET RE_MODEL = '$model', RE_ALIAS = '$alias', RE_TYPE = '$type',
                                             RE_SERIAL = '$serial', RE_INVENTORY = '$inventory', RE_MODIFIED = NOW(), 
                                             RE_LOCATION = $idLocation
                                              WHERE RE_ID = $idResource");
    if($updateResource) {
        if($type == "EQUIPO")
            header("Location: equipments.php");
        else
            header("Location: classRooms.php");
        exit;
    }else {
        header("Location: addResource.php?error=No se pudo actualizar el recurso en la base de datos");
        exit;
    }
}