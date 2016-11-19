<?php
include_once("../../inc/validateLogin.php");
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

if(isset($_POST['references'])) {
    $references = $_POST['references'];
    $_SESSION['references'] = $references;
}else{
    header("Location: addResource.php?error=Debes agregar al menos una referencia&type=$type&alias=$alias&model=$model&serial=$serial&inventory=$inventory&location=$location&campus=$campus&pile=$pile&floor=$floor&room=$room");
    exit;
}

mysqli_autocommit($connection, false);

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

    if ($insertResource) {
        $idResource = mysqli_insert_id($connection);

        foreach ($references as $item){
            $insertReference = mysqli_query($connection, "INSERT INTO recursos_referencias VALUES($idResource, $item)");
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
            header("Location: addResource.php?error=No se pudo hacer la relación del recurso con su referencia en la base de datos&type=$type&alias=$alias&model=$model&serial=$serial&inventory=$inventory&location=$location&campus=$campus&pile=$pile&floor=$floor&room=$room");
            exit;
        }
    } else {
        header("Location: addResource.php?error=No se pudo hacer la relación del recurso con su referencia en la base de datos&type=$type&alias=$alias&model=$model&serial=$serial&inventory=$inventory&location=$location&campus=$campus&pile=$pile&floor=$floor&room=$room");
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
    if ($updateResource) {

        mysqli_query($connection, "DELETE FROM recursos_referencias WHERE RR_RESOURCEID = $idResource");
        foreach ($references as $item){
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
            header("Location: addResource.php?error=No se pudo hacer la relación del recurso con su referencia en la base de datos&idResource=$idResource&type=$type&alias=$alias&model=$model&serial=$serial&inventory=$inventory&location=$location&campus=$campus&pile=$pile&floor=$floor&room=$room");
            exit;
        }
    } else {
        header("Location: addResource.php?error=No se pudo hacer la relación del recurso con su referencia en la base de datos&idResource=$idResource&type=$type&alias=$alias&model=$model&serial=$serial&inventory=$inventory&location=$location&campus=$campus&pile=$pile&floor=$floor&room=$room");
        exit;
    }
}