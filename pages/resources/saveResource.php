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

$reference = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_NUMBER_INT);

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

    if ($insertResource) {
        $idResource = mysqli_insert_id($connection);
        $insertReference = mysqli_query($connection, "INSERT INTO recursos_referencias VALUES($idResource, $insertReference)");

        if ($insertReference) {
            if ($type == "EQUIPO")
                header("Location: equipmentList.php");
            else
                header("Location: classRoomList.php");
            exit;
        } else {
            header("Location: addResource.php?error=No se pudo hacer la relación del recurso con su referencia en la base de datos a la base de datos
                                        &type=$type
                                        &alias=$alias
                                        &model=$model
                                        &serial=$serial
                                        &inventory=$inventory
                                        &reference=$reference
                                        &location=$location
                                        &campus=$campus
                                        &pile=$pile
                                        &floor=$floor
                                        &room=$room");
            exit;
        }
    } else {
        header("Location: addResource.php?error=No se pudo ingresar el recurso a la base de datos
                                        &type=$type
                                        &alias=$alias
                                        &model=$model
                                        &serial=$serial
                                        &inventory=$inventory
                                        &reference=$reference
                                        &location=$location
                                        &campus=$campus
                                        &pile=$pile
                                        &floor=$floor
                                        &room=$room");
        exit;
    }
} else {    //Lógica de actualización
    $idResource = filter_input(INPUT_POST, 'idResource', FILTER_SANITIZE_NUMBER_INT);
    $oldReference = filter_input(INPUT_POST, 'oldReference', FILTER_SANITIZE_NUMBER_INT);
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

        $updateReference = mysqli_query($connection, "UPDATE recursos_referencias SET RR_REOURCEID = $idResource AND RR_REFERENCEID = $reference
                                                      WHERE RR_RESOURCEID = $idResource AND RR_REFERENCEID = $oldReference");

        if ($updateReference) {
            if ($type == "EQUIPO")
                header("Location: equipmentList.php");
            else
                header("Location: classRoomList.php");
            exit;
        } else {
            header("Location: addResource.php?error=No se pudo hacer la relación del recurso con su referencia en la base de datos a la base de datos
                                        &type=$type
                                        &alias=$alias
                                        &model=$model
                                        &serial=$serial
                                        &inventory=$inventory
                                        &reference=$reference
                                        &location=$location
                                        &campus=$campus
                                        &pile=$pile
                                        &floor=$floor
                                        &room=$room");
            exit;
        }
    } else {
        header("Location: addResource.php?error=No se pudo actualizar el recurso en la base de datos
                                        &type=$type
                                        &alias=$alias
                                        &model=$model
                                        &serial=$serial
                                        &inventory=$inventory
                                        &reference=$reference
                                        &location=$location
                                        &campus=$campus
                                        &pile=$pile
                                        &floor=$floor
                                        &room=$room");
        exit;
    }
}