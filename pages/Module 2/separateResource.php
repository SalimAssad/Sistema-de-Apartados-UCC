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
                                <select name="" id="resource" class="form-control inputs">
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
                                <div class="form-group">
                                    <label>Días a apartar</label>
                                    <div class="form-group">
                                        <label class="col-sm-4" for="sun">
                                            <input type="checkbox" name="days" id="sun" class="daysOfTheWeek" value="0">
                                            Dom</label>
                                        <label class="col-sm-4" for="mon">
                                            <input type="checkbox" name="days" id="mon" class="daysOfTheWeek" value="1">
                                            Lun</label>
                                        <label class="col-sm-4" for="tue">
                                            <input type="checkbox" name="days" id="tue" class="daysOfTheWeek" value="2">
                                            Mar</label>
                                        <label class="col-sm-4" for="wed">
                                            <input type="checkbox" name="days" id="wed" class="daysOfTheWeek" value="3">
                                            Mie</label>
                                        <label class="col-sm-4" for="thu">
                                            <input type="checkbox" name="days" id="thu" class="daysOfTheWeek" value="4">
                                            Jue</label>
                                        <label class="col-sm-4" for="fri">
                                            <input type="checkbox" name="days" id="fri" class="daysOfTheWeek" value="5">
                                            Vie</label>
                                        <label class="col-sm-4" for="sat">
                                            <input type="checkbox" name="days" id="sat" class="daysOfTheWeek" value="6">
                                            Sab</label>
                                    </div>
                                </div>
                                <legend> </legend>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="lendTo">Solicitante</label>
                                <input type="hidden" name="" id="from" class="inputs">
                                <input type="hidden" name="" id="to" class="inputs">
                                <select name="" id="lendTo" class="form-control inputs">
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="grade">Semestre</label>
                                <select name="" id="grade" class="form-control inputs">
                                    <option value="">Selecciona un semestre...</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="lesson">Materia / Actividad</label>
                                <input type="text" name="" id="lesson" class="form-control inputs">
                            </div>
                            <div class="form-group">
                                <label for="area">Licenciatura / Área</label>
                                <select name="" id="area" class="form-control inputs">
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="comments">Comentarios</label>
                                <textarea name="" id="comments" cols="30" rows="5" class="form-control inputs">
                                </textarea>
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