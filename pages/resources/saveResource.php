<?php
include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");

/* --------------------------------------------

    SE RECIBEN LOS VALORES

-------------------------------------------- */

if (isset($_POST['resource']))
    $type = trim(filter_input(INPUT_POST, 'resource', FILTER_SANITIZE_STRING));
else
    $type = "";

if (isset($_POST['alias']))
    $alias = trim(filter_input(INPUT_POST, 'alias', FILTER_SANITIZE_STRING));
else
    $alias = "";

if ($type == "EQUIPO") {
    if (isset($_POST['model']))
        $model = trim(filter_input(INPUT_POST, 'model', FILTER_SANITIZE_STRING));
    else
        $model = "";

    if (isset($_POST['serial']))
        $serial = trim(filter_input(INPUT_POST, 'serial', FILTER_SANITIZE_STRING));
    else
        $serial = "";

    if (isset($_POST['inventory']))
        $inventory = trim(filter_input(INPUT_POST, 'inventory', FILTER_SANITIZE_STRING));
    else
        $inventory = "";

    if (isset($_POST['hwType']))
        $hwType = trim(filter_input(INPUT_POST, 'hwType', FILTER_SANITIZE_NUMBER_INT));
    else
        $hwType = "";
} else {
    $model = "";
    $serial = "";
    $inventory = "";
    $hwType = "NULL";
}

if (isset($_POST['location']))
    $location = trim(filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING));
else
    $location = "";

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

if (empty($type))
    $message .= "tipo, ";

if (empty($alias))
    $message .= "alias, ";

if (empty($model) && $type == "EQUIPO")
    $message .= "modelo, ";

if (empty($serial) && $type == "EQUIPO")
    $message .= "no. de serie, ";

if (empty($inventory) && $type == "EQUIPO")
    $message .= "no. de inventorio, ";

if (empty($hwType) && $type == "EQUIPO")
    $message .= "tipo de hardware, ";

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
    $message = "Los campos: ".$message." no pueden ir vacíos";
    $strHeader = "Location: addResource.php?error=$message";
    if(isset($_POST['idResource']))
        $strHeader .= "&idResource=$_POST[idResource]";
    $strHeader .= "&type=$type&alias=$alias&model=$model&serial=$serial&inventory=$inventory&location=$location&campus=$campus&pile=$pile&floor=$floor&room=$room";
    header($strHeader);
    exit;
}

/* --------------------------------------------

    VALIDACIÓN DE REFERENCIAS

-------------------------------------------- */

if (isset($_POST['references'])) {
    $references = $_POST['references'];
    $_SESSION['references'] = $references;
} else {
    header("Location: addResource.php?error=Debes agregar al menos una referencia&type=$type&alias=$alias&model=$model&serial=$serial&inventory=$inventory&hwType=$hwType&location=$location&campus=$campus&pile=$pile&floor=$floor&room=$room");
    exit;
}

//SE DESHABILITA EL AUTOCOMMIT
mysqli_autocommit($connection, false);

if ($location == "new") {
    $insertLocation = mysqli_query($connection, "INSERT INTO ubicaciones(UB_PILE, UB_CAMPUS, UB_FLOOR, UB_ROOM)
                                            VALUES('$pile', '$campus','$floor','$room')");
    $idLocation = mysqli_insert_id($connection);
} else {
    $idLocation = $location;
}

if (filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING) == "add") {

    /* --------------------------------------------

        LÓGICA DE INSERCIÓN

    -------------------------------------------- */
    $insertResource = mysqli_query($connection, "INSERT INTO recursos(RE_MODEL, RE_ALIAS, RE_TYPE, RE_AVAILABLE, RE_SERIAL, RE_INVENTORY, RE_CREATED, RE_LOCATION, RE_HWTYPE) 
                                            VALUES('$model', '$alias', '$type', 1, '$serial', '$inventory', NOW(), $idLocation, $hwType)");
    if ($insertResource) {
        $newId = mysqli_insert_id($connection);

        foreach ($references as $item) {
            $insertReference = mysqli_query($connection, "INSERT INTO recursos_referencias VALUES($newId, $item)");
        }

        if ($insertReference) {
            mysqli_commit($connection);
            unset($_SESSION['references']);
            if ($type == "EQUIPO")
                header("Location: equipmentList.php");
            else
                header("Location: roomList.php");
            exit;
        } else {
            header("Location: addResource.php?error=No se pudo hacer la relación del recurso con sus referencias en la base de datos&type=$type&alias=$alias&model=$model&serial=$serial&inventory=$inventory&hwType=$hwType&location=$location&campus=$campus&pile=$pile&floor=$floor&room=$room");
            exit;
        }
    } else {
        header("Location: addResource.php?error=Ocurrió un error inesperado al insertar el recurso en la base de datos&type=$type&alias=$alias&model=$model&serial=$serial&inventory=$inventory&hwType=$hwType&location=$location&campus=$campus&pile=$pile&floor=$floor&room=$room");
        exit;
    }
} else {

    /* --------------------------------------------

        LÓGICA DE ACTUALIZACIÓN

    -------------------------------------------- */
    $idResource = filter_input(INPUT_POST, "idResource", FILTER_SANITIZE_NUMBER_INT);

    $updateResource = mysqli_query($connection, "UPDATE recursos SET RE_MODEL = '$model', RE_ALIAS = '$alias', RE_TYPE = '$type',
                                             RE_SERIAL = '$serial', RE_INVENTORY = '$inventory', RE_MODIFIED = NOW(), 
                                             RE_LOCATION = $idLocation, RE_HWTYPE = $hwType
                                              WHERE RE_ID = $idResource");
    if ($updateResource) {

        mysqli_query($connection, "DELETE FROM recursos_referencias WHERE RR_RESOURCEID = $idResource");
        foreach ($references as $item) {
            $updateReference = mysqli_query($connection, "INSERT INTO recursos_referencias VALUES($idResource, $item)");
        }

        if ($updateReference) {
            mysqli_commit($connection);
            unset($_SESSION['references']);
            if ($type == "EQUIPO")
                header("Location: equipmentList.php");
            else
                header("Location: roomList.php");
            exit;
        } else {
            header("Location: addResource.php?error=No se pudo hacer la relación del recurso con sus referencias en la base de datos&idResource=$idResource&type=$type&alias=$alias&model=$model&serial=$serial&inventory=$inventory&hwType=$hwType&location=$location&campus=$campus&pile=$pile&floor=$floor&room=$room");
            exit;
        }
    } else {
        header("Location: addResource.php?error=Ocurrió un error inesperado al actualizar el recurso en la base de datos&idResource=$idResource&type=$type&alias=$alias&model=$model&serial=$serial&inventory=$inventory&hwType=$hwType&location=$location&campus=$campus&pile=$pile&floor=$floor&room=$room");
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