<?php
include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");

/* --------------------------------------------

    SE RECIBEN LOS VALORES

-------------------------------------------- */


if (isset($_POST['campus']))
    $campus = trim(filter_input(INPUT_POST, 'campus', FILTER_SANITIZE_STRING));
else
    $campus = "";

if (isset($_POST['pile']))
    $pile = trim(filter_input(INPUT_POST, 'pile', FILTER_SANITIZE_STRING));
else
    $pile = "";

if (isset($_POST['floor']))
    $floor = trim(filter_input(INPUT_POST, 'floor', FILTER_SANITIZE_STRING));
else
    $floor = "";

if (isset($_POST['room']))
    $room = trim(filter_input(INPUT_POST, 'room', FILTER_SANITIZE_STRING));
else
    $room = "";

/* --------------------------------------------

    SE VALIDAN LOS VALORES RECIBIDOS

-------------------------------------------- */

$message = "";

if (empty($campus))
    $message .= "campus, ";

if (empty($pile))
    $message .= "edificio, ";

if (empty($floor))
    $message .= "piso, ";

if (empty($room))
    $message .= "referencias";

/* ----------------------------------------------------------------------------------------------

    SI "$message" NO ESTÁ VACÍO, HUBO ARGUMENTOS INVALIDOS, SE GENERA EL HEADER PARA REGRESAR

---------------------------------------------------------------------------------------------- */

if($message != ""){
    $message = "Los campos: ".$message." no pueden ir vacíos.";
    $strHeader = "Location: addLocation.php?error=$message";
    if(isset($_POST['idLocation']))
        $strHeader .= "&idLocation=$_POST[idLocation]";
    $strHeader .= "&campus=$campus&pile=$pile&floor=$floor&room=$room";
    header($strHeader);
    exit;
}

if (filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING) == "add") {

    /* --------------------------------------------

        LÓGICA DE INSERCIÓN

    -------------------------------------------- */

    $insertLocation = mysqli_query($connection, "INSERT INTO ubicaciones(UB_PILE, UB_CAMPUS, UB_FLOOR, UB_ROOM)
                                            VALUES('$pile', '$campus','$floor','$room')");
    if($insertLocation){
        header("Location: locationList.php");
        exit;
    }else{
        header("Location: addLocation.php?error=No se pudo agregar la nueva ubicación&campus=$campus&pile=$pile&floor=$floor&room=$room");
        exit;
    }
} else {

    /* --------------------------------------------

        LÓGICA DE ACTUALIZACIÓN

    -------------------------------------------- */

    $idLocation = filter_input(INPUT_POST, 'idLocation', FILTER_SANITIZE_NUMBER_INT);
    $updateLocation = mysqli_query($connection, "UPDATE ubicaciones SET UB_PILE = '$pile', UB_CAMPUS = '$campus', UB_FLOOR = '$floor' UB_ROOM = '$room' WHERE UB_ID = $idLocation");
    if($updateLocation){
        header("Location: locationList.php");
        exit;
    }else{
        echo mysqli_error($connection);
        exit;
        header("Location: addLocation.php?idLocation=$idLocation&error=No se pudo actualizar la ubicación&campus=$campus&pile=$pile&floor=$floor&room=$room");
        exit;
    }
}

function validate($var)
{
    if (empty($var))
        return false;
    else
        return true;
}