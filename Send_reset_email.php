<?php
if($_SERVER["REQUEST_METHOD"]=="POST") {
$email=trim(filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL));
    include_once("inc/MySQLConnection.php");
    include("inc/validaremail.php");
    $sql="select * from usuarios where US_EMAIL = '$email'";
   	$query = mysqli_query($connection,$sql);
    $row = mysqli_fetch_assoc($query);
    if($email=="") {
		$error_message="Por favor, llene los campos requeridos";
	}else{
        if(!isset($error_message) && $email!="") {
		  $error_message="Datos erróneos";
	   }else $error_message="Se ha enviado un correo a su email.";
    }
    if($email == $row['US_EMAIL']){
        $US_ID= $row['US_ID'];
        $US_SID= $row['US_SID'];
        $name=$row['US_NAME'];
        $EMAIL= $row['US_EMAIL'];
        $token=rand(1,999999);
    include_once("inc/MySQLConnection.php");
            if($email!=""){
            $query2="INSERT INTO tokens(TO_ID, TO_USERID, TO_NAME, TO_TOKEN, TO_CREATED, TO_STATUS) VALUE ('NULL', $US_ID, $US_SID, md5($token),NOW(), b'1')";
            $sql2=mysqli_query($connection,$query2);
            //$respuesta=$query2;
            }
                if($sql2){
                $respuesta= "Usuario info: $US_ID , $US_SID , $token , $EMAIL .\n";
            include_once("inc/MySQLConnection.php");
                $query3="UPDATE `usuarios` SET `US_PASS` = md5($token) WHERE `usuarios`.`US_ID` = $US_ID";
                $sql3=mysqli_query($connection,$query3);
                $error_message="Se ha realizado el cambio";
                }else echo mysqli_error($connection);   
    } else $error_message="No existe una cuenta asociada a este correo";
    //////////////////////////////////////////Mensaje de correo a enviar////////////////////////
            $rest="Restablece tu contraseña";
            $email_body =".\n ";
            $email_body.="Restablece tu contraseña.\n";
            $email_body.="Hemos recibido una petición para restablecer la contraseña de tu cuenta.\n";
            $email_body.="Te Hemos generado la siguiente contraseña:\n";
            $email_body.=$token;
            $email_body.=".\n Con ella podrá ingresar y realizar el cambio de contraseña.";
    /////////////////////////////// Incluision de libreria para enviar correo ////////////////////
            require("inc/class.phpmailer.php");
            include("inc/class.smtp.php");
            $mail = new PHPMailer;
    //////////////////////////////////////////////////////////////////////////////////////////////
    ?>
    <!--
    /////////////////////////// Configuracion de envio de correo ////////////////////////////////
    require("inc/validaremail.php");
	if(!isset($error_message)) {
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = $servidor;                              // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = $cuenta;                            // SMTP username
		$mail->Password = $pass;                              // SMTP password
		//$mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
		$mail->Port = $puerto;                                // TCP port to connect to
		$mail->setFrom( $cuenta, $quienloenvio);
		$mail->addAddress($email, $name);                     // Add a recipient
		$mail->isHTML(false);                                 // Set email format to HTML
		$mail->Subject = $rest;
		$mail->Body    = $email_body;
        ///////////////////////// Si email fue enviado. /////////////////////////////////////////        
		if($mail->send()) {
	        header("Location: Send_reset_email.php?status=thanks");
            $error_message = " Un correo ha sido enviado a su cuenta de email: $email. con una contraseña provisional.";
			exit;
		}
		$error_message ='Ya hubo P2. ';
		$error_message.='Error: ' . $mail->ErrorInfo;	
	} 
--><?php
}
?>
<!DOCTYPE html>
<html lang='es'>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Restaurar contraseña</title>
    <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap/css/signin.css" rel="stylesheet">
    
    </head>
<body>
    <div class="container">
        <form  id="frmRestablecer" class="form-signin" method="post" name="correo" action="Send_reset_email.php">
            <h2 class="form-signin-heading">Restauración de contraseña</h2><br>
                <?php if(isset($_GET["status"]) && $_GET["status"]=="thanks") {
				echo "<p>En breve llegara un correo, con una clave.</p>";
			     } else {
                    if(isset($error_message)) {
                        echo "<div class='alert alert-warning'>$error_message</div>";
                    } else {
                        echo "<div class='alert alert-warning'>Llene el siguiente formulario</div>";
                    }
                ?>
        <label for="email"> Escribe el email asociado a tu cuenta para recuperar tu contraseña </label>
        <input type="email" id="email" name="email" class="form-control" placeholder="email@ejemplo.com" required autofocus     value="<?php if(isset($email)) { echo $email; } ?>"><br>
        <div >
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Recuperar contraseña" >
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