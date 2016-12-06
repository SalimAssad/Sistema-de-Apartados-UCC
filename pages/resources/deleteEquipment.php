<?php
include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");
include_once("resourceFunctions.php");




$RE_ID='';
if(!empty($_POST["RE_ID"])){
    $RE_ID=$_POST["RE_ID"];
    $delete="delete from recursos where RE_ID=$RE_ID";
    $resultado=mysqli_query($connection,$delete);
    
    if($resultado){
        header("location: equipmentList.php");
    }else{
        exit("No se pudo borrar el recurso seleccionado: " .mysqli_error($connection));
    }
}else{
    echo "Error ID Vacío"; 
}



/* Borrar equipo*/
?>