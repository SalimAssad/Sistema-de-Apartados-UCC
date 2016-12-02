<?php
session_start();
if (!isset($_SESSION['user']))
header("Location: index.php");
else {
    include_once("inc/MySQLConnection.php");
}
?>

<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset="utf-8">
    <title>Bienvenido al sistema</title>

    <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">

    <script src="utils/jquery-1.12.3.min.js">
    </script>
    <script src="css/bootstrap/js/bootstrap.min.js">
    </script>
    <style type="text/css">
        .center {
            text-align: center;
        }
    </style>
</head>
<body>
<?php
include_once("inc/nav.php");
?>
<div class="container-fluid">
    <div class="row center">
        <?php
        if($_SESSION['profile']==1 || $_SESSION['profile']==2){
            include_once("inc/sidebar.php")?>

                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Seleccione una opción</h1>
            <img src="imgs/escudo_ucc.jpg" width="600px" height="378px" align="middle">
                    <?php
        }else{
        ?>
            <h1 class="page-header">Seleccione una opción</h1>
            <img src="imgs/escudo_ucc.jpg" width="600px" height="378px">
        </div>
        <?php
        }
?>
    </div>
</div>
</body>
</html>