<?php
include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");
//Tu código que necesites
?>
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset="utf-8">
    <title>Añadir equipo</title>

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
            <h1 class="page-header">Datos</h1>
            <form action="saveResource.php" method="post">
                <div class="">
                    <div>
                        <label for="alias">Alias:</label>
                    </div>
                    <div>
                        <input type="text" id="alias" name="alias" required>
                    </div>
                </div>
                <div class="">
                    <div>
                        <label for="model">Modelo:</label>
                    </div>
                    <div>
                        <input type="text" id="model" name="model" required>
                    </div>
                </div>
                <div class="">
                    <div>
                        <label for="serial">Número de serie:</label>
                    </div>
                    <div>
                        <input type="text" id="serial" name="serial" required>
                    </div>
                </div>
                <div class="">
                    <div>
                        <label for="inventory">Número de inventorio:</label>
                    </div>
                    <div>
                        <input type="text" id="inventory" name="inventory" required>
                    </div>
                </div>
                <h2 class="sub-header">Ubicación</h2>

            </form>
        </div>
    </div>
</div>
</body>
</html>