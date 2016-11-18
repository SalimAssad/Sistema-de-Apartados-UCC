<?php
    //Tu código que necesites
?>
<!DOCTYPE html>
<html lang='es'>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Restaurar contraseña</title>
        
<link href="pages/Module 4/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="pages/Module%204/dist/css/signin.css" rel="stylesheet">
        <script src="utils/jquery-1.12.3.min.js">
        </script>
    </head>
<body>
    <div class="container">
        <form  id="frmRestablecer" class="form-signin" action="inc/validaremail.php" method="post">
            <h2 class="form-signin-heading">Restauración de contraseña</h2>
<br>

            <label for="inputEmail"> Escribe el email asociado a tu cuenta para recuperar tu contraseña </label>
            <input type="email" id="email" name="email" class="form-control" placeholder="email@ejemplo.com" required autofocus>
    <br>
            <div class="checkbox">
            <input type="submit" class="btn btn-lg btn-primary btn-block" value="Recuperar contraseña" >
            </div>
            </div>
        </form>
<!-- probando
     <div id="mensaje">
          
    </div>
           </div>
    <script src="inc/js/jquery-1.11.1.min.js"></script>
    <script src="inc/js/bootstrap.min.js"></script>
    <script>
      $(document).ready(function(){
        $("#frmRestablecer").submit(function(event){
          event.preventDefault();
          $.ajax({
            url:'validaremail.php',
            type:'post',
            dataType:'json',
            data:$("#frmRestablecer").serializeArray()
          }).done(function(respuesta){
            $("#mensaje").html(respuesta.mensaje);
            $("#email").val('');
          });
        });
      });
    </script>-->
  </body>
</html>
        