<?php
/*//////////////////////////////////////////////////////////////
       B O R R A D O       D E      A U L A S 
 
 */////////////////////////////////////////////////////////////



include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");
include_once("resourceFunctions.php");




$UB_ID='';
if(!empty($_POST["UB_ID"])){
    $UB_ID=$_POST["UB_ID"];
    $delete="delete from ubicaciones where UB_ID=$UB_ID";
    $resultado=mysqli_query($connection,$delete);
    
    if($resultado){
        header("location: locationList.php");
    }else{
        exit("Parece que no se ha podido eliminar lo que seleccionó: " .mysqli_error($connection));
    }
}else{
    echo "Error: No parece haber un ID que cuadre con lo que selecciona"; 
}




?>