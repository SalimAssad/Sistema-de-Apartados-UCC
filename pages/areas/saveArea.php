<?php
//include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$campus = filter_input(INPUT_POST, 'campus', FILTER_SANITIZE_STRING);

$reference = filter_input(INPUT_POST, 'reference', FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

if (filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING) == "add") {
    if ($reference == "new") {
        $insertReference = mysqli_query($connection, "INSERT INTO referencias(RE_DESCRIPTION) VALUES('$reference')");
        $idReference = mysqli_insert_id($connection);
    } else {
        $idReference = $reference;
    }
    $insertArea = mysqli_query($connection, "INSERT INTO area(AR_NAME, AR_CAMPUS, AR_TYPE) VALUES('$name', '$campus', '$reference')");

    if($insertArea) {
        header("Location: areas.php");
        exit;
    }else {
        header("Location: addArea.php?error=No se pudo ingresar el recurso a la base de datos");
        exit;
    }
} else {    //Lógica de actualización
    $idArea = filter_input(INPUT_POST, 'idArea', FILTER_SANITIZE_NUMBER_INT);
    if ($reference == "new") {
        $insertReference = mysqli_query($connection, "INSERT INTO referencias(RE_DESCRIPTION) VALUES('$reference')");
        $idReference = mysqli_insert_id($connection);
    } else {
        $idReference = $reference;
    }
    $updateReference = mysqli_query($connection, "UPDATE area SET AR_NAME = '$name', AR_CAMPUS = '$campus', AR_TYPE = '$reference'
                                              WHERE RE_ID = $idArea");
    if($updateReference) {
        header("Location: areas.php");
        exit;
    }else {
        header("Location: addArea.php?error=No se pudo actualizar el recurso en la base de datos");
        exit;
    }
}