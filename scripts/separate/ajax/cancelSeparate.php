<?php 

session_start();
$user_id = $_SESSION["id"];
$authCode = $_SESSION["code"];
$id = $_POST["id"];
$codigo = $_POST["valCodigo"];
$motivo = $_POST["motivo"];

include_once("../../../inc/MySQLConnection.php");

if($authCode == $codigo){
    $update = "UPDATE apartados  SET AP_CANCEL = 1 WHERE AP_ID = '$id'";
    $query = mysqli_query($connection,$update);

    if(!$query ){
        error1("ERR0 BAD QUERY - $update");
        exit("false: bad query");
    }



    $insert = "INSERT INTO  apartados_cancelados (AC_SEPARATEID,AC_RESPONSIBLE,AC_DATE,AC_REASON) VALUES('$id','$user_id',NOW(),'$motivo'); ";

    $query2 = mysqli_query($connection,$insert);

    if(!$query2){
        error1("ERR0 BAD QUERY - $insert");
        exit("false: bad query");
    }


    exit("TRUE");

}else{
    exit("FALSE");
}

function error1($msg) {
    exit($msg);
}










?>