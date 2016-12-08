<?php
include_once("/inc/validatePermissions.php");
include_once("inc/MySQLConnection.php");

$user = trim(filter_input(INPUT_POST,"user", FILTER_SANITIZE_NUMBER_INT));
$password = trim(filter_input(INPUT_POST,"password", FILTER_SANITIZE_STRING));
$code = trim(filter_input(INPUT_POST,"code", FILTER_SANITIZE_STRING));

$query = mysqli_query($connection, "select * from usuarios where US_SID = $user");
/////////////////////////////////////////////////////////////////////////////////////////////////////


$query2="select * from tokens where TO_NAME = $user";
$sql2 = mysqli_query($connection, $query2);
$row = mysqli_fetch_assoc($sql2);
$status=$row['TO_STATUS'];


if(mysqli_num_rows($query) > 0){
    $row = mysqli_fetch_assoc($query);
    if (md5($password) == $row['US_PASS']) {
        session_start();
        $_SESSION['user'] = $user;
        $_SESSION['id'] = $row['US_ID'];
        $_SESSION['profile'] = $row['US_PROFILEID'];
        $_SESSION['nombre']=$row['US_NAME'];
        $_SESSION['apellidos']=$row['US_LASTNAME'];
        $_SESSION['code'] =$code;

        if($status==1){
            header('Location: Reset_pass.php');
            echo " $user, $status ";
        }else
            header('Location: reg_login.php');

    } else {
        header('Location: index.php?error=Datos_erroneos');
    }
}else{
    header('Location: index.php?error');
}
mysqli_close($connection);