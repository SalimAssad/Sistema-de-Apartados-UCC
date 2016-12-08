<?php
session_start();
$user_id = $_SESSION["id"];
$id = $_POST["id"];
$matricula = $_POST["matricula"];
$inuse = $_POST["inuse"];

include_once("../../inc/MySQLConnection.php");

$sql = "SELECT US_SID FROM usuarios JOIN AP_LENDTO=US_ID WHERE AP_ID='$id' ;";
$query = mysqli_query($connection,$sql);

if(!$query){
    exit("false: bad query");
}

$row = mysqli_fetch_row($query);

$us_id = $row["US_SID"];

if($us_id == $matricula ){

    $type = ($inuse == "1") ? "D" : "E";

    $insert = "INSERT INTO entregas_devoluciones (ED_SEPARATEID,ED_TYPE,ED_DATE,ED_USERID) VALUES($id,$type,NOW(),$user_id)";

    $insquery = mysqli_query($connection,$insert);

    if(!$insquery){
        exit("false: bad insert");
    }
    
    exit("TRUE");
}else{
    exit("FALSE WRONG USER");
}









?>  