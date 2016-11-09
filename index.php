<?php
    //Tu código que necesites
?>


<!DOCTYPE html>
<html lang='es'>
    <head>
        <meta charset="utf-8">
        <title><!-- Tu título --></title>
        
        <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/global.css" rel="stylesheet">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="login.js"></script>
        <script src="utils/jquery-1.12.3.min.js">
        </script>
    </head>
    <body bgcolor="#EEEEEE">
    <div align="center">
        <div style="width: 200px; height: 170px; padding: 20px; background-color: rgba(0, 0, 0, 0); border: double">
            <form id="form" action="Login_verify.php" method="post">
                <label>Matrícula</label>
                <br>
                <input class="form-control" type="text" name="user" required>
                <br>
                <label>Contraseña</label>
                <br>
                <input class="form-control" type="password" name="password" required>
                <br>
                <br>
                <input type="submit" value="Iniciar sesión">
                <br> <br>
                <a href="Send_reset_email.php" >Olvidaste tu contraseña? Haz click aquí </a>



            </form>

            <br> <br>
            <div id="error">
                <?php
                if(isset($_GET["error"])){
                    echo "Los datos son incorrectos";
                }
                ?>
            </div>

        </div>
    </div>
    </body>
</html>