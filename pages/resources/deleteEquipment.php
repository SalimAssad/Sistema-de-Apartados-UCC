
<?php
include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");



$UB_ID='';
if(!empty($_POST["UB_ID"])){
    $UB_ID=$_POST["UB_ID"];
    $delete="delete from ubicaciones where UB_ID=$_ID";
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