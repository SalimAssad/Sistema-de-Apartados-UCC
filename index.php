<?php
    //Tu código que necesites
?>
<!DOCTYPE html>
<html lang='es'>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Inicia Sesión</title>
        
<link href="pages/Module 4/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="pages/Module%204/dist/css/signin.css" rel="stylesheet">
        <script src="utils/jquery-1.12.3.min.js">
        </script>
    </head>
<body>
    <div class="container">
<form class="form-signin" action="Login_verify.php" method="Post">
    
   <h2 class="form-signin-heading">Bienvenidos</h2>
    
        <label for="inputEmail" class="sr-only">Usuario</label>
        <input type="number" name="user" class="form-control" placeholder="Usuario" required autofocus>
    <br>
        <label for="inputPassword" class="sr-only">Contraseña</label>
        <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
        <div class="checkbox">
          <label>
             <a href="Send_reset_email.php" >Olvidaste tu contraseña? Haz click aquí </a>
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar sesión </button>
    
            <br> <br>
            <div id="error">
                <?php
                if(isset($_GET["error"])){
                    echo '<div class="alert alert-warning">Los datos son incorrectos. </div>';
                }
                ?>
            </div>  
      </form>
        </div>
    </body>
</html>