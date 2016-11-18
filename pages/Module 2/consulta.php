<?php
//include_once("../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");
//Tu código que necesites
?>
<!DOCTYPE html>
<html lang='es'>
    <head>
        <meta charset="utf-8">
        <title>Consulta de disponibilidad</title>



        <link href="../../css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href='../../css/fullcalendar/fullcalendar.min.css' rel='stylesheet' />
        <link href='../../css/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
        <link href="../../utils/jqueryui/jquery-ui.css" rel="stylesheet">
        <link href="../../css/dashboard.css" rel="stylesheet">
        <link href="../../css/global.css" rel="stylesheet">
        <link href="../../css/Module 2/separateResource.css" rel="stylesheet">

        <script src="../../utils/jquery-1.12.3.min.js"></script>
        <script src='../../utils/jqueryui/jquery-ui.min.js'></script>
        <script src='../../utils/moment.min.js'></script>
        <script src='../../utils/fullcalendar.min.js'></script>
        <script src="../../css/bootstrap/js/bootstrap.min.js"></script>
        <script src="../../scripts/Module 2/consulta.js"></script>





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
                   


                    <h1 class="sub-header">Consulta de disponibilidad</h1>

                    <div>
                        <div id='calendar'></div>
                    </div>



                </div>




            </div>
        </div>
            <div id="eventContent" title="Event Details" style="display:none;">
                Start: <span id="startTime"></span><br>
                End: <span id="endTime"></span><br>
                name: <span id="name"></span><br>
                lesson: <span id="lesson"></span><br>
                area: <span id="area"></span><br>
                resourceName: <span id="resourceName"></span><br>
                <p id="eventInfo"></p>
                <p><strong><a id="eventLink" href="" target="">Read More</a></strong></p>
            </div>
    </body>
</html>