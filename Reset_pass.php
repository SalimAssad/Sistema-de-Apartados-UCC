<?php
if($_SERVER["REQUEST_METHOD"]=="POST") {
$pass=trim(filter_input(INPUT_POST,"password",FILTER_SANITIZE_STRING));
$pass1=trim(filter_input(INPUT_POST,"password1",FILTER_SANITIZE_STRING));
$pass2=trim(filter_input(INPUT_POST,"password2",FILTER_SANITIZE_STRING));
    include_once("inc/validateLogin.php");
    include_once("inc/MySQLConnection.php");
    $sql="select * from tokens where TO_TOKEN = md5($pass)";
   	$query = mysqli_query($connection,$sql);
    $row = mysqli_fetch_assoc($query);
    $US_ID= $row['TO_USERID'];
    $NAME =$row['TO_NAME'];
    $respuesta= $US_ID;
    if($pass==""||$pass1==""||$pass2=="") {
		$error_message="Por favor, llene los campos requeridos";
	}
        if($pass1!=$pass2) {
            $error_message="No coinciden las contraseñas";
           }else{
                $password=$pass1;
                    if($US_ID!=""){
                    include_once("inc/MySQLConnection.php");
                    $query3="UPDATE `usuarios` SET `US_PASS` = md5('$password') WHERE `usuarios`.`US_ID` = $US_ID";
                    $sql3=mysqli_query($connection,$query3);
                    //$respuesta.=$query3;
                    $error_message="Se a realizado el cambio.";
                    }if($sql3){
                    include_once("inc/MySQLConnection.php");
            $query2="UPDATE `tokens` SET `TO_STATUS` = b'0',`TO_NAME` = '-$NAME' WHERE `tokens`.`TO_USERID` = $US_ID";
                    $sql2=mysqli_query($connection,$query2);
                    //$respuesta.=$query2;
                    $error_message="Se a realizado el cambio.";   
                    header('Location: index.php');
                    }else echo mysqli_error($connection);   
        } 
}
?>
<!DOCTYPE html>
<html lang='es'>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cambiar contraseña</title>
    <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap/css/signin.css" rel="stylesheet">
    </head>
<body>
    <div class="container">
        <form  id="frmRestablecer" class="form-signin" method="post" name="correo" action="Reset_pass.php">
            <h2 class="form-signin-heading">Cambiar contraseña</h2>
                <?php if(isset($_GET["status"]) && $_GET["status"]=="thanks") {
				echo "<p>Su contraseña se ha modifcado.</p>";
			     } else {
                    if(isset($error_message)) {
                        echo "<div class='alert alert-warning'>$error_message</div>";
                    } else {
                        echo "<div class='alert alert-warning'>Llene el siguiente formulario</div>";
                    }
                ?>
        <label for="email"> Escriba aqui el token:  </label>
        <input type="password" name="password" class="form-control" placeholder="Ingrese su token" required autofocus     value="<?php if(isset($password)) { echo $password; } ?>">
            
            <label for="email"> Escriba su nueva contraseña: </label>
        <input type="password" name="password1" class="form-control" placeholder="Contraseña nueva" required autofocus     value="<?php if(isset($password1)) { echo $password1; } ?>">
            
            <label for="email"> Confirme su nueva contraseña: </label>
            <input type="password" name="password2" class="form-control" placeholder="Repita contraseña nueva" required autofocus     value="<?php if(isset($password2)) { echo $password2; } ?>">
        <div >
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Cambiar contraseña" >
        </div>
            <br>
        <div <?php if($respuesta==""){  
                    }else   echo "<div class='alert alert-warning'>$respuesta</div>";?>
        </div>
        </form> 
        <script src="inc/js/jquery-1.11.1.min.js"></script>
        <script src="inc/js/bootstrap.min.js"></script>
        <?php } ?>
    </div>
  </body>
</html>