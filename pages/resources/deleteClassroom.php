

<?php
include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");



$RE_ID='';
if(!empty($_POST["RE_ID"])){
    $RE_ID=$_POST["RE_ID"];
    $delete="delete from recursos where RE_ID=$RE_ID";
    $resultado=mysqli_query($connection,$delete);
    
    if($resultado){
        header("location: roomList.php");
    }else{
        exit("No se pudo borrar el aula seleccionada: " .mysqli_error($connection));
    }
}else{
    echo "Error ID Vacío"; 
}



/* Borrar equipo*/
?>