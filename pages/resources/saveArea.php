<?php
include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");

/* -----------------------------------------------------

    SE RECIBEN LOS VALORES

----------------------------------------------------- */


if (isset($_POST['name']))
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
else
    $name = "";

if (isset($_POST['campus']))
    $campus = filter_input(INPUT_POST, 'campus', FILTER_SANITIZE_STRING);
else
    $campus = "";

if (isset($_POST['reference']))
    $reference = filter_input(INPUT_POST, 'reference', FILTER_SANITIZE_NUMBER_INT);
else
    $reference = "";

/* -----------------------------------------------------

    SE VALIDAN LOS VALORES

----------------------------------------------------- */

$message = "";

if (empty($name))
    $message .= "Nombre, ";

if (empty($campus))
    $message .= "campus, ";

if (empty($reference))
    $message .= "referencia";

if ($message != "") {
    $message = "Los campos: ".$message." no pueden estar vacíos";
    $strHeader = "Location: addArea.php?error=$message";
    if (isset($_POST['idArea']))
        $strHeader .= "&idArea=$_POST[idArea]";
    $strHeader .= "&name=$name&campus=$campus&reference=$reference&description=$description";
    header($strHeader);
    exit;
}


if (filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING) == "add") {

    /* -----------------------------------------------------

        LÓGICA DE ADICIÓN

    ----------------------------------------------------- */

    $insertArea = mysqli_query($connection, "INSERT INTO areas(AR_NAME, AR_CAMPUS, AR_TYPE) VALUES('$name', '$campus', '$reference')");

    if ($insertArea) {
        header("Location: areaList.php");
        exit;
    } else {
        header("Location: addArea.php?error=No se pudo ingresar el recurso a la base de datos&name=$name&campus=$campus&reference=$reference");
        exit;
    }
} else {

    /* -----------------------------------------------------

        LÓGICA DE ACTUALIZACIÓN

    ----------------------------------------------------- */

    $idArea = filter_input(INPUT_POST, 'idArea', FILTER_SANITIZE_NUMBER_INT);
    $updateArea = mysqli_query($connection, "UPDATE areas SET AR_NAME = '$name', AR_CAMPUS = '$campus', AR_TYPE = '$reference'
                                              WHERE AR_ID = $idArea");
    if ($updateArea) {
        header("Location: areaList.php");
        exit;
    } else {
        header("Location: addArea.php?error=No se pudo actualizar el recurso en la base de datos&idArea=$idArea&name=$name&campus=$campus&reference=$reference");
        exit;
    }
}