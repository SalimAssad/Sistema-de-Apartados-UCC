<?php
session_start();
$user_id = $_SESSION["id"];
$authCode = $_SESSION["code"];
$id = $_POST["id"];
$matricula = $_POST["matricula"];
$inuse = $_POST["inuse"];

require_once("../../../inc/MySQLConnection.php");

$sql = "SELECT US_SID, AP_RESID FROM usuarios JOIN apartados ON AP_LENDTO = US_ID WHERE AP_ID = '$id'";
$query = mysqli_query($connection,$sql);

if(!$query){
    exit("false: bad query");
}

$row = mysqli_fetch_assoc($query);

$us_id = $row["US_SID"];
$resource_id = $row["AP_RESID"];

if($us_id == $matricula ){

    $type = ($inuse == "1") ? "D" : "E";
    $insert = "INSERT INTO entregas_devoluciones (ED_SEPARATEID,ED_TYPE,ED_DATE,ED_USERID) VALUES($id,'$type',NOW(),$user_id)";
    $insquery = mysqli_query($connection,$insert);
    if(!$insquery){
        exit("false: bad insert $insert");
    }

    $newType = ($inuse == "1") ? 0 : 1;
    $update = "UPDATE recursos SET RE_INUSE = $newType WHERE RE_ID = $resource_id";
	$updquery = mysqli_query($connection,$update);
    if(!$updquery){
        exit("false: bad update $update");
    }

    exit("TRUE");
}else{
	echo $sql;
    exit("FALSE WRONG USER");
}









?>  