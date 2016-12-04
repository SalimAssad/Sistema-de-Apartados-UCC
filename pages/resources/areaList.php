<?php
//include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");
//Tu código que necesites
?>
<!DOCTYPE html>
<html lang='es'>
    <head>
        <meta charset="utf-8">
        <title>Eliminar un salón></title>

        <link href="../../css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/dashboard.css" rel="stylesheet">
        <link href="../../css/global.css" rel="stylesheet">

        <script src="../../utils/jquery-1.12.3.min.js">
        </script>
        <script src="../../css/bootstrap/js/bootstrap.min.js">
        </script>
        
        
   
        
    </head>
    <body>
        <?php
        include_once("../../inc/nav.php");
        ?>
        <div class="container-fluid">
            <div class="row">
                <?php
                include_once("../../inc/sidebar.php");
                ?>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header"></h1>


                    <h3 class="sub-header">Área</h3>
                    
                    <button type="button" class="btn btn-primary">Dar de alta</button>
                    <table class="table">
                        
                         <?php 
                    $sql="select * from areas";
                    $resultado = mysqli_query($connection, $sql);
                    if($resultado){
                        $cantidad_areas=mysqli_num_rows($resultado); 

                        if($cantidad_areas > 0){
                    ?>

            <table class="table">
    <thead>
      <tr>
       <th>Nombre</th>
        <th>Campus</th>
        <th>Referencias</th>
          <th> Opciones</th>
         
      </tr>
    </thead>
    <tbody>
        <?php 
                            while($fila = mysqli_fetch_assoc($resultado)){
                            ?>
        
      <td><?php echo $fila["AR_NAME"]?></td>
        <td><?php echo $fila["AR_CAMPUS"]?> </td>
        <td> <?php echo $fila["AR_SID"]?> </td>
          <td> 
  
  
  </tr>
 <?php }; ?>
                </tbody>
                    </table>
                    <?php
                        }else{
                            echo "No hay recursos registrados";
                        }
                    }
                        
                    ?>
</div>
                </div>
            </div>
        </div>
    </body>
</html>