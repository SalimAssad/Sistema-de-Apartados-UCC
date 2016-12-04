<?php
//include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");

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
                    <h2 class="page-header">Recursos</h2>


                 <form action="addResource.php" method="get" >   <button type="submit" formaction="addResource.php" class="btn btn-primary" method="get">Añadir </button></form>


                    <?php 
                    $sql="select * from recursos";
                    $resultado = mysqli_query($connection, $sql);
                    if($resultado){
                        $cantidad_recursos=mysqli_num_rows($resultado); 

                        if($cantidad_recursos > 0){
                    ?>


<form action="equipmentList.php" method="post"> 
    
   
    
    
                 modelo <br>   
                  <input type="text"  name="model" value= "">
                <br> Alias <br>
                   <input type="text" name="alias" value= "">
            <br> serial<br>
                    <input type="text" name="serial" value="">
    <br> No. Inventario <br>   
                  <input type="text"  name="inventory" value= "">
                <br> Tipo Hardware <br>
                   <input type="text" name="hwtype" value= "">
            <br> campus<br>
          
                        <select  name="campus">
                                                <option value="">Seleccione</option>
                                                <option value="TORRENTE">Torrente</option>
                            <option value="CALASANZ">Calasanz</option>
                    
                    </select>
    <br> Referencia<br>
                    <input type="text" name="reference" value="">
                
                <input type="submit" value="buscar">
                
                </form>
                    
                    
                    
                    
                    

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre del equipo</th>
                                <th>Marca/Modelo</th>
                                <th>No. de Serie</th>
                                <th> No. de Inventario</th>
                                <th> Asignado en</th>
                                <th> Opciones</th>
                            </tr>
                        </thead>
                    <tbody>
                        <?php 
                            while($fila = mysqli_fetch_assoc($resultado)){
                            ?>
                        
                            <td><?php echo $fila["RE_ALIAS"]?></td>
                            <td><?php echo $fila["RE_MODEL"]?></td>
                            <td><?php echo $fila["RE_SERIAL"]?></td>
                            <td><?php echo $fila["RE_INVENTORY"]?></td>
                            <td><?php echo $fila["RE_LOCATION"]?></td>
                            <td> <a href="specificEquipment.php=<?php echo $fila["RE_ID"] ?>"><button type="button" class="btn btn-success">Ver detalles</button>    </td>
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