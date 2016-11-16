<?php
    //Tu código que necesites
?>
<!DOCTYPE html>
<html lang='es'>
    <head>
        <meta charset="utf-8">
        <title>Inicia Sesión</title>
        
        <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/global.css" rel="stylesheet">
        <script src="utils/jquery-1.12.3.min.js">
        </script>
    </head>
 
    <body bgcolor="#EEEEEE">

    <br><br>
    <div align="center">

        <div style="width: 340px; height: 190px; padding: 20px; border: double">


            <form action="Login_verify.php" method="Post">
                
                <label style="font-size: 14pt"><b>Matrícula:    </b></label>
                
                <input class="form-group has-success" style="border-radius:15px;" type="number" name="user" required>
                
                <label  style="font-size: 14pt"><b>Contraseña: </b></label>
                
                <input class="form-group has-success" style="border-radius:15px;" type="password" name="password" required>
               <br>
                <input class="btn btn-primary" type="submit" value="Iniciar sesión">
                <br> <br>
                
                <a href="inc/Send_reset_email.php" >Olvidaste tu contraseña? Haz click aquí </a>
            </form>

            <br> <br>
            <div id="error">
                <?php
                if(isset($_GET["error"])){
                    echo "Los datos son incorrectos";
                }
                ?>
            </div>
            <div>
               
            </div>     
        </div>
    </div>
    </body>
</html>