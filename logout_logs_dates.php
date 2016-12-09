<?php
include_once("/inc/validateLogin.php");
include_once("/inc/validatePermissions.php");
include_once("/inc/MySQLConnection.php");
//Tu código que necesites
?>
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset="utf-8">
    <title>Sesiones cerradas</title>

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
            <h1 class="page-header">Seleccione intervalo de fechas</h1>

            <form action="/logout_logs.php" method="post">
                <label>Fecha 1 </label>
                <input type="date" name="date1" style="border-style: outset" placeholder="Seleccione una fecha" required>
                <label>Fecha 2 </label>
                <input type="date" name="date2" style="border-style: outset" placeholder="Seleccione una fecha" required>
                <button type="submit">Enviar</button>
            </form>

        </div>
    </div>
</div>
</body>
</html>