<?php
//include_once("../inc/validateLogin.php");
include_once("../inc/MySQLConnection.php");
//Tu código que necesites
?>
<!DOCTYPE html>
<html lang='es'>
    <head>
        <meta charset="utf-8">
        <title>Consulta de disponibilidad</title>



        <link href="../css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/dashboard.css" rel="stylesheet">
        <link href="../css/global.css" rel="stylesheet">   
        <script src="../utils/jquery-1.12.3.min.js">
        </script>   
        <script src="../css/bootstrap/js/bootstrap.min.js">
        </script>
        
        <script>
            $(function(){
               $('#calendar').fullCalendar();
            });
        
        </script>



    </head>
    <body>




        <?php
        include_once("../inc/nav.php");
        ?>
        <div class="container-fluid">
            <div class="row">
                <?php
                include_once("../inc/sidebar.php");
                ?>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">Centro de Cómputo Académico y Recursos Didácticos</h1>


                    <h2 class="sub-header">Consulta de disponibilidad</h2>

                    <div id="calendar"></div>

                    <link rel='stylesheet' href='fullcalendar/fullcalendar.css' />
                    <script src='lib/jquery.min.js'></script>
                    <script src='lib/moment.min.js'></script>
                    <script src='fullcalendar/fullcalendar.js'></script>



                </div>




            </div>
        </div>
    </body>
</html>