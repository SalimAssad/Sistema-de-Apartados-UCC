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
            <h2 class="page-header">Lista de recursos</h2>


            <form action="addResource.php" method="get">
                <button type="submit" formaction="addResource.php" class="btn btn-primary" method="get">Dar de alta
                </button>
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
                            <th> Asignado en</th>
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
                            <td><?php echo $fila["RE_LOCATION"] ?></td>
                            <td><a href="specificEquipment.php=<?php echo $fila["RE_ID"] ?>">
                                    <button type="button" class="btn btn-success">Ver detalles</button></td>
                            </tr>
                        <?php }; ?>

                        </tbody>
                    </table>
                    <?php
                } else {
                    echo "No hay recursos registrados";
                }
            }
            ?>


            <form action="roomList.php" method="post">
                Alias <br>
                <input type="text" name="alias" value="<?php echo $alias; ?>">
                <br> Campus <br>
                <select name="campus">
                    <option value="">Seleccione</option>
                    <option value="TORRENTE" <?php if ($campus == "TORRENTE") echo "selected"; ?>>Torrente</option>
                    <option value="CALASANZ" <?php if ($campus == "CALASANZ") echo "selected"; ?>>Calasanz</option>

                </select>
                <br> Referencia<br>
                <select name="reference">
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
                <br>
                <br> <input type="submit" name="query" value="Buscar">


            </form>


        </div>
    </div>
</div>
</div>
</body>
</html>