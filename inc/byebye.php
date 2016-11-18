<?php
include_once("MySQLConnection.php");

session_start();
$id = $_SESSION["id"];
$user = $_SESSION["user"];
$ipadd = $_SERVER['REMOTE_ADDR'];

$query = "insert into logs(LO_USERID, LO_DATE, LO_IP) value ('$user','now()','$ipadd')";
$sql = mysqli_query($connection, $query );

if($sql){
    header("location: index.php");

}
else{
    echo "no mames";
    echo mysqli_error($connection);
}


session_destroy();
$_SESSION = array();
?>