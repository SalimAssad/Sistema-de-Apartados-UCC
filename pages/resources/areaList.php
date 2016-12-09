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
    <title> Área</title>

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
            <h1 class="page-header">Áreas</h1>


            

            <form action="areaList.php" method="post" class="form form-inline form-multiline">

 <div class="form-group">
     <br><label for="name">Nombre:</label> </br>
                <input type="text" name="name"  class="form-control" placeholder="Escribe el Nombre"  value="<?php echo $name; ?>" >

                </div>
            
            <div class="form-group">
            <br><label for= "campus">Campus:</label><br>

                <select name="campus" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="TORRENTE" <?php if ($campus == "TORRENTE") echo "selected"; ?>>Torrente</option>
                    <option value="CALASANZ" <?php if ($campus == "CALASANZ") echo "selected"; ?>>Calasanz</option>

                </select>
            </div>
            <div class="form-group">
                <br><label for= "reference">Referencia:</label><br>
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

                <input type="submit" name="query" class="btn btn-default" value=" Buscar">
            </div>
            </form>
<p></p>
                <form action="addArea.php" method="get">
                <button type="submit"  class="btn btn-primary" method="get">Añadir</button>
            
   
        
        <form action="areaList.php" method="get">
        
        <button type="submit" formaction="areaList.php" class="btn btn-info" method="get"> Todo</button>
        </form>
        
                

            
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
                    } else {?>
    <div class="alert alert-info" role="alert">  <span class="glyphicon glyphicon-info-sign"></span>  <?php   echo "No hay  registros"; ?> </div><?php
                    }
                }

                ?>
        </div>
    </div>
</div>
</div>
</body>
</html>