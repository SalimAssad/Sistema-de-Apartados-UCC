<?php
include_once("MySQLConnection.php");

session_start();
$id = $_SESSION["id"];
$user = $_SESSION["user"];
$ipadd = $_SERVER['REMOTE_ADDR'];

$query = "insert into logs(LO_USERID, LO_DATE, LO_IP, LO_INOUT) value ('$user',NOW(),'$ipadd', 0)";
$sql = mysqli_query($connection, $query );

if($sql){
    header("location: ../index.php");

}
else{
    echo mysqli_error($connection);
}


session_destroy();
$_SESSION = array();
?>