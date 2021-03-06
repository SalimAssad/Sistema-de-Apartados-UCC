<?php
include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");
include_once("resourceFunctions.php");

$campus = "";
$pile = "";
$floor = "";
$room = "";

if (isset($_POST['query'])) {

    if (isset($_POST['campus'])) {
        $campus = filter_input(INPUT_POST, 'campus', FILTER_SANITIZE_STRING);
    }
    if (isset($_POST['pile'])) {
        $pile = filter_input(INPUT_POST, 'pile', FILTER_SANITIZE_STRING);
    }
    if (isset($_POST['floor'])) {
        $floor = filter_input(INPUT_POST, 'floor', FILTER_SANITIZE_STRING);
    }
    if (isset($_POST['room'])) {
        $room = filter_input(INPUT_POST, 'room', FILTER_SANITIZE_STRING);
    }


    $resultado = getLocationQuery($connection, $campus, $pile, $floor, $room);
} else {
    $resultado = getLocationQuery($connection, "", "", "", "");
}
?>
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset="utf-8">
    <title>Lista de ubicaciones</title>

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
            


            
            <form action="locationList.php" method="post" class="form form-inline form-multiline">

               <div class="form-group">
                <br><label for="campus"> Campus:</label></br>

                <select name="campus" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="TORRENTE" <?php if ($campus == "TORRENTE") echo "selected"; ?>>Torrente</option>
                    <option value="CALASANZ" <?php if ($campus == "CALASANZ") echo "selected"; ?>>Calasanz</option>

                </select>
                </div>
            
            <div class="form-group">
            
                <br><label for="pile">Edificio:</label> </br>
                 <input type="text" name="pile" class="form-control" placeholder="Escribe el edificio" value="<?php echo $pile; ?>"required>
        </div>
                
        <div class="form-group">
            <br><label for="floor">Piso:</label>  </br>
                <input type="text" name="floor" class="form-control" placeholder="Introduce la planta" value="<?php echo $floor; ?>" required>
    </div>
    
    <div class="form-group">
        
        
        <br><label for="room">Departamento:</label> </br>
                <input type="text" name="room" class="form-control" placeholder="Escribe el departamento" value="<?php echo $room; ?>" required>

  
                 <input type="submit" class="btn btn-default" name="query" value="Buscar">
    </div>
            </form>

    <p></p>
<form action="addLocation.php" method="get">
                <button type="submit"  class="btn btn-primary" method="get">Añadir</button>
            
   
        
        <form action="locationList.php" method="get">
        
        <button type="submit" formaction="locationList.php" class="btn btn-info" method="get"> Todo</button>
        </form>
            

            <table class="table">


                <?php
                if ($resultado){
                $cantidad_ubicaciones = mysqli_num_rows($resultado);
                if ($cantidad_ubicaciones > 0){
                ?>

                <thead>
                <tr>
                    <th>Edificio</th>
                    <th>Campus</th>
                    <th>Piso</th>
                    <th> Departamento</th>
                    <th> Opciones</th>

                </tr>
                </thead>
                <tbody>

                <?php
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    ?>

                    <td> <?php echo $fila["UB_PILE"] ?></td>
                    <td> <?php echo $fila["UB_CAMPUS"] ?></td>
                    <td> <?php echo $fila["UB_FLOOR"] ?></td>
                    <td><?php echo $fila["UB_ROOM"] ?></td>
                    <td>


                       <form action="deleteLocation.php" method="post"> <button type="submit" class="btn btn-danger" name="UB_ID" value="<?php echo $fila["UB_ID"] ?>">Eliminar </button></form>
                    </td>

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