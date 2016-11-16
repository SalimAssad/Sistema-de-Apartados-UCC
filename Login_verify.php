<?php

include_once("inc/MySQLConnection.php");

$user = $_POST["user"];
$password = $_POST["password"];


$query = mysqli_query($connection, "select US_SID, US_PASS from usuarios where US_SID = $user");


if(mysqli_num_rows($query) > 0){
    $row = mysqli_fetch_assoc($query);

        if (md5($password) == $row['US_PASS']) {
            session_start();
            $_SESSION['user'] = $user;
            header('Location: inicio.php');
        } else {
            header('Location: index.php?error=Datos_erroneos');
        }

}else{
    header('Location: index.php?error');
}

mysqli_close($connection);
