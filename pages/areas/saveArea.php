<?php
//include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$campus = filter_input(INPUT_POST, 'campus', FILTER_SANITIZE_STRING);

$reference = filter_input(INPUT_POST, 'reference', FILTER_SANITIZE_STRING);

if (filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING) == "add") {
    if ($reference == "new") {
        $insertLocation = mysqli_query($connection, "INSERT INTO ubicaciones(UB_PILE, UB_CAMPUS, UB_FLOOR, UB_ROOM)
                                            VALUES('$pile', '$campus','$floor','$room')");
        $idReference = mysqli_insert_id($connection);
    } else {
        $idReference = $reference;
    }
    $insertResource = mysqli_query($connection, "INSERT INTO recursos(RE_MODEL, RE_ALIAS, RE_TYPE, RE_AVAILABLE, RE_SERIAL, RE_INVENTORY, RE_CREATED, RE_LOCATION) 
                                            VALUES('$model', '$name', '$type', 1, '$serial', '$inventory', NOW(), $idReference)");

    if($insertResource) {
        header("Location: areas.php");
        exit;
    }else {
        header("Location: addArea.php?error=No se pudo ingresar el recurso a la base de datos");
        exit;
    }
} else {    //Lógica de actualización
    $idArea = filter_input(INPUT_POST, 'idArea', FILTER_SANITIZE_NUMBER_INT);
    if ($reference == "new") {
        $insertLocation = mysqli_query($connection, "INSERT INTO ubicaciones(UB_PILE, UB_CAMPUS, UB_FLOOR, UB_ROOM)
                                            VALUES('$pile', '$campus','$floor','$room')");
        $idReference = mysqli_insert_id($connection);
    } else {
        $idReference = $reference;
    }
    $updateResource = mysqli_query($connection, "UPDATE recursos SET RE_MODEL = '$model', RE_ALIAS = '$name', RE_TYPE = '$type',
                                             RE_SERIAL = '$serial', RE_INVENTORY = '$inventory', RE_MODIFIED = NOW(), 
                                             RE_LOCATION = $idReference
                                              WHERE RE_ID = $idArea");
    if($updateResource) {
        header("Location: areas.php");
        exit;
    }else {
        header("Location: addArea.php?error=No se pudo actualizar el recurso en la base de datos");
        exit;
    }
}