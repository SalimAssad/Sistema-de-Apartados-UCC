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
        <link href="../../utils/jqueryui/jquery-ui.css" rel="stylesheet">
        <link href="../../css/dashboard.css" rel="stylesheet">
        <link href="../../css/global.css" rel="stylesheet">
        <link href="../../css/Module 2/separateResource.css" rel="stylesheet">

        <script src="../../utils/jquery-1.12.3.min.js"></script>
        <script src='../../utils/jqueryui/jquery-ui.min.js'></script>
        <script src='../../utils/moment.min.js'></script>
        <script src='../../utils/fullcalendar.min.js'></script>
        <script src="../../css/bootstrap/js/bootstrap.min.js"></script>
        <script src="../../scripts/Module 2/separateResource.js"></script>
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
                    <div class="row">
                        <div class="col-sm-4 col-md-3 separate">
                            <div class="form-group">
                                <fieldset>
                                    <legend>Tipo de recurso</legend>
                                    <div class="col-sm-6">
                                        <input type="radio" name="resourceType" id="device" data-type="d" class="resourceType" checked>
                                        <label for="device">Equipo</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="radio" name="resourceType" id="space" data-type="s" class="resourceType">
                                        <label for="space">Espacio</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="form-group">
                                <label for="resource">Recurso</label>
                                <select name="" id="resource" class="form-control">
                                    <option value="">Selecciona un recurso...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <fieldset>
                                    <legend>Tipo de apartado</legend>
                                    <div class="col-sm-6 col-md-6">
                                        <input type="radio" name="type" id="occasional" data-type="o" class="type" checked>
                                        <label for="occasional">Ocasional</label>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <input type="radio" name="type" id="temporary" data-type="t" class="type">
                                        <label for="temporary">Temporal</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="temporary">
                                <div class="form-group">
                                    <label for="startDate">Fecha de inicio</label>
                                    <input type="text" class="form-control datepick" name="" id="startDate">
                                </div>
                                <div class="form-group">
                                    <label for="endDate">Fecha de fin</label>
                                    <input type="text" class="form-control datepick" name="" id="endDate">
                                </div>
                            </div>
                            <button class="btn btn-primary col-sm-12 col-md-12" type="button" id="separate">Apartar recurso</button>
                        </div>
                        <div>
                            <div id='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>