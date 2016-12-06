<?php 

session_start();
$user_id = $_SESSION["id"];
$id = $_POST["id"];
$matricula = $_POST["matricula"];
$inuse = $_POST["inuse"];

include_once("../../inc/MySQLConnection.php");




$cancelar = '0';
$consulta = "UPDATE apartados  SET AP_CANCEL = 1 WHERE AP_ID = '$id'";
$query = mysqli_query($connection,$consulta);

if(!query){
    exit("false: bad query");
}

$row = mysqli_fetch_assoc($query);

$id = row["AP_ID"];










?>