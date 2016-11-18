<?php
session_start();
if (!isset($_SESSION['user']))
    header("Location: index.php");
else {
    include_once("inc/MySQLConnection.php");
}

session_start();
$id = $_SESSION["id"];
$user = $_SESSION["user"];
$ipadd = $_SERVER['REMOTE_ADDR'];

$query = "insert into logs(LO_USERID, LO_DATE, LO_IP, LO_INOUT) value ('$user',NOW(),'$ipadd', 1)";
$sql = mysqli_query($connection, $query );

if($sql){
    header("location: inicio.php");

}
else{
    echo mysqli_error($connection);
}

?>