<?php
include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");
include_once("resourceFunctions.php");

$alias = "";
$campus = "";
$reference = "";

if (isset($_POST['query'])) {

    if (isset($_POST['alias'])) {
        $alias = filter_input(INPUT_POST, 'alias', FILTER_SANITIZE_STRING);
    }
    if (isset($_POST['campus'])) {
        $campus = filter_input(INPUT_POST, 'campus', FILTER_SANITIZE_STRING);
    }
    if (isset($_POST['reference'])) {
        $reference = filter_input(INPUT_POST, 'reference', FILTER_SANITIZE_NUMBER_INT);
    }


    $resultado = getResourceQuery($connection, "", $alias, "", "", "", $campus, $reference, "AULA");
} else {
    $resultado = getResourceQuery($connection, "", "", "", "", "", "", "", "AULA");
}

?>
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset="utf-8">
    <title>Aulas</title>

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
            <h1 class="page-header">Aulas</h1>

            
            
                     <form action="roomList.php" method="post" class="form form-inline form-multiline" role="form">
                
                <div class="form-group">
                    <br><label for="alias">Nombre de Aula:</label></br>
                <input type="text" name="alias"  class="form-control" placeholder="Alias" value="<?php echo $alias; ?>" required>
                </div>
        <div class="form-group">
                
                <br> <label for="campus">Campus: </label></br>
                <select name="campus" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="TORRENTE" <?php if ($campus == "TORRENTE") echo "selected"; ?>>Torrente</option>
                    <option value="CALASANZ" <?php if ($campus == "CALASANZ") echo "selected"; ?>>Calasanz</option>

                </select>
    </div>
    
    <div class="form-group">
                <br><label for="reference">Referencia:</label><br>
                <select name="reference" class="form-control" required>
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
    
                
                 <input type="submit" class="btn btn-default" name="query" value="Buscar">
</div>

            </form>
            
            
<p></p>
            


            <?php
            if ($resultado) {
                $cantidad = mysqli_num_rows($resultado);

                if ($cantidad > 0) {
                    ?>


                    <table class="table">
                        <thead>
                        <tr>
                            <th>Nombre del aula</th>
                            <th>Campus</th>
                            <th>Referencia</th>
                            
                            <th> Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($fila = mysqli_fetch_assoc($resultado)) {
                            ?>

                            <td><?php echo $fila["RE_ALIAS"] ?></td>
                            
                            
                            <td><?php echo $fila["UB_CAMPUS"] ?></td>
                             <td><?php echo $fila["RE_DESCRIPTION"] ?></td>
                            <td><form action="deleteRoom.php" method="post"> <button type="submit" class="btn btn-danger" name="UB_ID" value="<?php echo $fila["UB_ID"] ?>">Eliminar </button></form></td>
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