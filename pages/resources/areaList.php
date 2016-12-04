<?php
include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");
include_once("resourceFunctions.php");

$name = "";
$campus = "";
$reference = "";

if (isset($_POST['query'])) {

    if (isset($_POST['name'])) {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    }
    if (isset($_POST['campus'])) {
        $campus = filter_input(INPUT_POST, 'campus', FILTER_SANITIZE_STRING);
    }
    if (isset($_POST['reference'])) {
        $reference = filter_input(INPUT_POST, 'reference', FILTER_SANITIZE_NUMBER_INT);
    }


    $resultado = getAreaQuery($connection, $name, $campus, $reference);
} else {
    $resultado = getAreaQuery($connection, "", "", "");
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
            <h1 class="page-header"></h1>


            <h3 class="sub-header">Área</h3>
            /////////////////////////////////////////////////////////////

            <form action="areaList.php" method="post">


                Name <br>
                <input type="text" name="name" value="<?php echo $name; ?>">

                <br> campus<br>

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

                <input type="submit" name="query" value="buscar">

            </form>


            /////////////////////////////////////////////////////////////////////

            <button type="button" class="btn btn-primary">Dar de alta</button>
            <table class="table">

                <?php
                if ($resultado) {
                    $cantidad_areas = mysqli_num_rows($resultado);

                    if ($cantidad_areas > 0) {
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
                            while ($fila = mysqli_fetch_assoc($resultado)) {
                                ?>

                                <td><?php echo $fila["AR_NAME"] ?></td>
                                <td><?php echo $fila["AR_CAMPUS"] ?> </td>
                                <td> <?php echo $fila["AR_SID"] ?> </td>


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
        </div>
    </div>
</div>
</div>
</body>
</html>