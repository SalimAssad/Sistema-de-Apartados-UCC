<?php 

session_start();
$user_id = $_SESSION["id"];
$authCode = $_SESSION["code"];
$id = $_POST["id"];
$codigo = $_POST["valCodigo"];
$motivo = $_POST["motivo"];

include_once("../../inc/MySQLConnection.php");




$update = "UPDATE apartados  SET AP_CANCEL = 1 WHERE AP_ID = '$id'";
$query = mysqli_query($connection,$update);

if(!query){
    exit("false: bad query");
}

$row = mysqli_fetch_assoc($query);


if($authCode == $codigo){
    $query = mysqli_query($connection,$consulta);
    
    $insert = "INSERT INTO  apartados_cancelados (AC_SEPARATEID,AC_RESPONSIBLE,AC_DATE,AC_REASON) VALUES('$id','$user_id',NOW(),'$motivo'); ";
    
    $query2 = mysqli_query($connection,$insert);
        
}else{
    exit();
}









?>