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
    <script src="../../scripts/addResourceScript.js">
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
            <form action="saveResource.php" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="page-header">Datos</h2>
                        <div>
                            <div>
                                <label>Tipo de recurso:</label>
                            </div>
                            <div>
                                <input type="radio" name="resource" id="equipment" value="EQUIPO" onclick="typeHandler(this.value)" checked><label for="equipment">Equipo</label>
                            </div>
                            <div>
                                <input type="radio" name="resource" id="space" value="AULA" onclick="typeHandler(this.value)"><label for="space">Espacio</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div>
                                <label for="alias">Alias:</label>
                            </div>
                            <div>
                                <input type="text" class="form-control" id="alias" name="alias" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div>
                                <label for="model">Modelo:</label>
                            </div>
                            <div>
                                <input type="text" class="form-control" id="model" name="model" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div>
                                <label for="serial">Número de serie:</label>
                            </div>
                            <div>
                                <input type="text" class="form-control" id="serial" name="serial" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div>
                                <label for="inventory">Número de inventorio:</label>
                            </div>
                            <div>
                                <input type="text" class="form-control" id="inventory" name="inventory" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <h2 class="sub-header">Ubicación</h2>
                            <div>
                                <div>
                                    <label for="location">Seleccione</label>
                                </div>
                                <div>
                                    <select id="location" name="location" required>
                                        <option value="new">Nuevo</option>
                                        <?php
                                        $sql = mysqli_query($connection, "SELECT * FROM ubicaciones ORDER BY UB_CAMPUS");
                                        while ($row = mysqli_fetch_assoc($sql)){
                                            echo "<option value='$row[UB_ID]'>$row[UB_CAMPUS]: $row[UB_PILE], $row[UB_FLOOR], $row[UB_ROOM]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div>
                                    <label for="campus">Campus:</label>
                                </div>
                                <div>
                                    <select class="form-control" id="campus" name="campus" required>
                                        <option>Seleccione...</option>
                                        <option value="TORRENTE">Torrente</option>
                                        <option value="CALASANZ">Calasanz</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div>
                                    <label for="pile">Edificio:</label>
                                </div>
                                <div>
                                    <input type="text" class="form-control" id="pile" name="pile" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div>
                                    <label for="floor">Piso:</label>
                                </div>
                                <div>
                                    <input type="text" class="form-control" id="floor" name="floor" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div>
                                    <label for="room">Habitación:</label>
                                </div>
                                <div>
                                    <input type="text" class="form-control" id="room" name="room" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <button type="button" class="form-control btn-warning">Cancelar</button>
                    </div>
                    <div class="col-sm-6">
                        <button type="submit" class="form-control btn-success" name="action" value="add">Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>