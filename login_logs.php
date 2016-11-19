<?php
include_once("/inc/validateLogin.php");
include_once("/inc/MySQLConnection.php");
//Tu código que necesites
?>
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset="utf-8">
    <title>Sesiones iniciadas</title>

    <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">

    <script src="utils/jquery-1.12.3.min.js">
    </script>
    <script src="css/bootstrap/js/bootstrap.min.js">
    </script>
</head>
<body>
<?php
include_once("inc/nav.php");
?>
<div class="container-fluid">
    <div class="row">
        <?php
        include_once("inc/sidebar.php");
        ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Sesiones iniciadas</h1>
                     <?php

            $sql = "select * from logs where LO_INOUT = 1";
            $result = mysqli_query($connection, $sql);
            if($result){
                $num_registros = mysqli_num_rows($result);
                if($num_registros > 0){
                    ?>
                    <table class="table table-striped">
                        <thead>
                        <th>Usuario</th>
                        <th>Fecha y Hora</th>
                        <th>Direccion IP</th>
                        </thead>
                        <tbody>
                        <?php
                        while ($fila = mysqli_fetch_assoc($result) ) {
                        ?>
                        <tr>
                            <td><?php echo $fila["LO_USERID"] ?></td>
                            <td><?php echo $fila["LO_DATE"] ?></td>
                            <td><?php echo $fila["LO_IP"] ?></td>
                        </tr>
                        </tbody>




                        <?php } ?>





                    </table>

                <?php } else{  ?>

                    <p>No hay registros</p>
                <?php }} ?>

        </div>
    </div>
</div>
</body>
</html>