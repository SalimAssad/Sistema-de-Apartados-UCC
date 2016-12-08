<?php
include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");
include_once("resourceFunctions.php");

$model = "";
$alias = "";
$serial = "";
$inventory = "";
$hwType = "";
$campus = "";
$reference = "";

if (isset($_POST['query'])) {

    if (isset($_POST['model'])) {
        $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_STRING);
    }
    if (isset($_POST['alias'])) {
        $alias = filter_input(INPUT_POST, 'alias', FILTER_SANITIZE_STRING);
    }
    if (isset($_POST['serial'])) {
        $serial = filter_input(INPUT_POST, 'serial', FILTER_SANITIZE_STRING);
    }
    if (isset($_POST['inventory'])) {
        $inventory = filter_input(INPUT_POST, 'inventory', FILTER_SANITIZE_STRING);
    }
    if (isset($_POST['hwType'])) {
        $hwType = filter_input(INPUT_POST, 'hwType', FILTER_SANITIZE_STRING);
    }
    if (isset($_POST['campus'])) {
        $campus = filter_input(INPUT_POST, 'campus', FILTER_SANITIZE_STRING);
    }
    if (isset($_POST['reference'])) {
        $reference = filter_input(INPUT_POST, 'reference', FILTER_SANITIZE_NUMBER_INT);
    }

    $resultado = getResourceQuery($connection, $model, $alias, $serial, $inventory, $hwType, $campus, $reference, "EQUIPO");
} else {
    $resultado = getResourceQuery($connection, "", "", "", "", "", "", "", "EQUIPO");
}
?>
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset="utf-8">
    <title>Recursos</title>

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
            <h1 class="page-header">Equipos</h1>

            <form action="equipmentList.php" method="post" class="form form-inline form-multiline" role="form">
                <div class="form-group">    
       
         <br><label for="model">Modelo:</label></br> 
         <input type="text" class="form-control" name="model" placeholder="Escribe un modelo" value="<?php echo $model; ?>" required>
          </div>
        
        <div class="form-group">
        <br><label for="alias">Nombre mostrador:</label></br> 
        <input type="text" name="alias" class="form-control" placeholder="Escribe un alias" value="<?php echo $alias; ?>" required> 
        </div>    
                
        <div class="form-group">
        <br><label for="serial">Serial:</label></br> 
        <input type="text" name="serial" class="form-control" placeholder="No. de serie" value="<?php echo $serial; ?>" required>
        </div>  
                
        <div class="form-group">
        <br><label for="inventory">Inventario:</label></br> 
        <input type="text" name="inventory" class="form-control" placeholder="No. de inventario" value="<?php echo $inventory; ?>"required >
        </div>             
                
            <p></p>
    
        <div class="form-group">
        <br><label for="hwType">Tipo de Hardware:</label></br> 
        <input type="text" name="hwType" class="form-control" placeholder="Tipo" value="<?php echo $hwType; ?>" required>
        </div>   
    
        
                
                <div class="form-group"> 
                    <br><label for="campus"> Campus:</label></br>

                <select  class="form-control" name="campus" required>
                    <option value="">Seleccione</option>
                    <option value="TORRENTE" <?php if ($campus == "TORRENTE") echo "selected"; ?>>Torrente
                    </option>
                    <option value="CALASANZ" <?php if ($campus == "CALASANZ") echo "selected"; ?>>Calasanz
                    </option>
                      
                </select>
               </div>

<div class="form-group">
    <br><label for="reference">Referencia:</label></br>

                <select class="form-control" name="reference" required>
                    <option value="">Seleccione</option>
                    <?php
                    $referenceQuery = mysqli_query($connection, "SELECT * FROM referencias");
                    while ($referenceRow = mysqli_fetch_assoc($referenceQuery)) {
                        echo "<option value='$referenceRow[RE_ID]'";
                        if ($referenceRow['RE_ID'] == $reference)
                            echo " selected";
                        echo ">$referenceRow[RE_DESCRIPTION]</option>";
                    }
                    ?>
                                  
</select>
 

      <input type="submit" class="btn btn-default"name="query" value="Buscar"></br>

       </div> 

    </form>
<p></p>
            <form action="addResource.php" method="get">
                <button type="submit" formaction="addResource.php" class="btn btn-primary" method="get">Añadir</button>
            
                <form action="equipmentList.php" method="get">
        
        <button type="submit" formaction="equipmentList.php" class="btn btn-info" method="get"> Todo</button>
        </form>


            <?php
            if ($resultado) {
                $cantidad_recursos = mysqli_num_rows($resultado);

                if ($cantidad_recursos > 0) {
                    ?>


                    <table class="table">
                        <thead>
                        <tr>
                            <th>Nombre del equipo</th>
                            <th>Marca/Modelo</th>
                            <th>No. de Serie</th>
                            <th> No. de Inventario</th>
                            <th> Asignado</th>
                            <th> Modificado</th>
                            <th> Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($fila = mysqli_fetch_assoc($resultado)) {
                            ?>

                            <td><?php echo $fila["RE_ALIAS"] ?></td>
                            <td><?php echo $fila["RE_MODEL"] ?></td>
                            <td><?php echo $fila["RE_SERIAL"] ?></td>
                            <td><?php echo $fila["RE_INVENTORY"] ?></td>
                            <td><?php echo $fila["RE_CREATED"] ?></td>
                            <td> <?php echo $fila["RE_MODIFIED"]?></td>
                            <td><a href="addResource.php?id=<?php echo $fila["RE_ID"] ?>">
                                    <button type="button" formaction="addResource.php" class="btn btn-success">Editar</button>
                                <br><br> <form action="deleteEquipment.php" method="post"> <button type="submit" class="btn btn-danger" name="RE_ID" value="<?php echo $fila["RE_ID"] ?>">Borrar </button></form></td>
                            </tr>
                        <?php }; ?>

                        </tbody>
                    </table>
                    <?php
                } else {?>
    <div class="alert alert-info" role="alert">  <span class="glyphicon glyphicon-info-sign"></span>  <?php   echo "No hay registros"; ?> </div><?php
                }
            }
            ?>
        </div>
    </div>
</div>
</div>
</body>
</html>