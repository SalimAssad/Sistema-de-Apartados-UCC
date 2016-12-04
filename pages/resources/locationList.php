<?php
//include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");
//Tu código que necesites
?>
<!DOCTYPE html>
<html lang='es'>
    <head>
        <meta charset="utf-8">
        <title></title>

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


                    <h3 class="sub-header">Ubicación</h3>
                    <form action="addLocation.php" method="get">
                    <div>
                        <button type="submit" class="btn btn-primary">Dar de alta</button>
                    </div>
                    </form>
                    
                    
                    
                    ///////////////////
                        <form action="locationList.php" method="post"> 
    
   
            
                 <br> Edificio <br>   
                 <br> <input type="text"  name="pile" value= "">
                <br> Piso <br>
                   <input type="text" name="floor" value= "">
            <br> Departamento<br>
    <input type="text" name="room" value="">
                
                            
                            <br> Campus<br>
          
                        <select name="campus">
                                                <option value="">Seleccione</option>
                                                <option value="TORRENTE">Torrente</option>
                            <option value="CALASANZ">Calasanz</option><br>
    <br>
                            
               <br> <input type="submit" value="buscar">
                
                </form>
                        
                        
                        ///////////////////
                        
                    <table class="table">
                        
                        
                        
                        
                        
                        
                        
                        <?php 
                    $sql="select * from ubicaciones";
                    $resultado = mysqli_query($connection, $sql);
                    if($resultado){
                        $cantidad_ubicaciones=mysqli_num_rows($resultado); 

                        if($cantidad_ubicaciones > 0){
                    ?>
                        
    <thead>
      <tr>
       <th>Edificio</th>
        <th>Campus</th>
        <th>Piso</th>
          <th> Departamento</th>
          <th> Opciones </th>
         
      </tr>
    </thead>
    <tbody>
        
        <?php 
                            while($fila = mysqli_fetch_assoc($resultado)){
                            ?>
        
      <td> <?php echo $fila["UB_PILE"]?></td>
        <td> <?php echo $fila["UB_CAMPUS"]?></td>
        <td> <?php echo $fila["UB_FLOOR"]?></td>
        <td><?php echo $fila["UB_ROOM"]?></td>
          <td> 
  
  
  <form action="deleteEquipment.php" method="get" ><button type="submit" class="btn btn-danger">Eliminar</button>      
</td>
          
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