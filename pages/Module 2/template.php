<?php
//include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");
//Tu código que necesites
?>
<!DOCTYPE html>
<html lang='es'>
    <head>
        <meta charset="utf-8">
        <title>Apartado de recursos</title>

        <link href="../../css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href='../../css/fullcalendar/fullcalendar.min.css' rel='stylesheet' />
        <link href='../../css/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
        <link href="../../css/dashboard.css" rel="stylesheet">
        <link href="../../css/global.css" rel="stylesheet">

        <script src="../../utils/jquery-1.12.3.min.js"></script>
        <script src='../../utils/jquery-ui.min.js'></script>
        <script src='../../utils/moment.min.js'></script>
        <script src='../../utils/fullcalendar.min.js'></script>
        <script src="../../css/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(function() {
                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    editable: true,
                    eventOverlap:false,
                    droppable: true, // this allows things to be dropped onto the calendar
                    drop: function() {
                        // is the "remove after drop" checkbox checked?
                        if ($('#drop-remove').is(':checked')) {
                            // if so, remove the element from the "Draggable Events" list
                            $(this).remove();
                        }
                    }
                });
            });
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
                    <h1 class="page-header">Apartado de recursos</h1>
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </body>
</html>