<?php
//include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");

$type = "";
$model = "";
$alias = "";
$serial = "";
$inventory = "";
$location = "";

$campus = "";
$pile = "";
$floor = "";
$room = "";

if (isset($_GET['idResource'])) {
    $id = filter_input(INPUT_GET, "idResource", FILTER_SANITIZE_NUMBER_INT);

    $resourceSQL = mysqli_query($connection, "SELECT * FROM recursos WHERE RE_ID = '$id'");
    $resourceData = mysqli_fetch_assoc($resourceSQL);

    $type = $resourceData['RE_TYPE'];
    $model = $resourceData['RE_MODEL'];
    $alias = $resourceData['RE_ALIAS'];
    $serial = $resourceData['RE_SERIAL'];
    $inventory = $resourceData['RE_INVENTORY'];
    $location = $resourceData['RE_LOCATION'];
}

//Si se reciben los siguientes datos hubo error en la validación del servidor y se sobreescriben
if (isset($_GET['type']))
    $type = filter_input(INPUT_GET, "type", FILTER_SANITIZE_STRING);
if (isset($_GET['model']))
    $model = filter_input(INPUT_GET, "model", FILTER_SANITIZE_STRING);
if (isset($_GET['alias']))
    $alias = filter_input(INPUT_GET, "alias", FILTER_SANITIZE_STRING);
if (isset($_GET['serial']))
    $serial = filter_input(INPUT_GET, "serial", FILTER_SANITIZE_STRING);
if (isset($_GET['inventory']))
    $inventory = filter_input(INPUT_GET, "inventory", FILTER_SANITIZE_STRING);
if (isset($_GET['campus']))
    $campus = filter_input(INPUT_GET, "campus", FILTER_SANITIZE_STRING);
if (isset($_GET['pile']))
    $pile = filter_input(INPUT_GET, "pile", FILTER_SANITIZE_STRING);
if (isset($_GET['floor']))
    $floor = filter_input(INPUT_GET, "floor", FILTER_SANITIZE_STRING);
if (isset($_GET['room']))
    $room = filter_input(INPUT_GET, "room", FILTER_SANITIZE_STRING);

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
                        <h2 class="sub-header">Datos</h2>
                        <div>
                            <div>
                                <label>Tipo de recurso:</label>
                            </div>
                            <div>
                                <input type="radio" name="resource" id="equipment" value="EQUIPO"
                                       onclick="typeHandler(this.value)" <?php if($type == "EQUIPO" || $type == "") echo checked ?>><label
                                    for="equipment">Equipo</label>
                                <input type="radio" name="resource" id="space" value="AULA"
                                       onclick="typeHandler(this.value)" <?php if($type == "AULA") echo checked ?>><label for="space">Espacio</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div>
                                <label for="alias">Alias:</label>
                            </div>
                            <div>
                                <input type="text" class="form-control" id="alias" name="alias" value="<?php echo $alias; ?>" required>
                            </div>
                        </div>
                        <div class="col-sm-6 equipment">
                            <div>
                                <label for="model">Modelo:</label>
                            </div>
                            <div>
                                <input type="text" class="form-control equipment" id="model" name="model" value="<?php echo $model; ?>" required>
                            </div>
                        </div>
                        <div class="col-sm-6 equipment">
                            <div>
                                <label for="serial">Número de serie:</label>
                            </div>
                            <div>
                                <input type="text" class="form-control equipment" id="serial" name="serial"  value="<?php echo $serial; ?>"
                                       required>
                            </div>
                        </div>
                        <div class="col-sm-6 equipment">
                            <div>
                                <label for="inventory">Número de inventorio:</label>
                            </div>
                            <div>
                                <input type="text" class="form-control equipment" id="inventory" name="inventory"  value="<?php echo $inventory; ?>"
                                       required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2 class="sub-header">Ubicación</h2>
                        <div class="col-sm-7">
                            <div>
                                <label for="location">Seleccione</label>
                            </div>
                            <div>
                                <select class="form-control" id="location" name="location"
                                        onchange="locationHandler(this.options[this.selectedIndex].text)" required>
                                    <option value="new">Nuevo</option>
                                    <?php
                                    $sql = mysqli_query($connection, "SELECT * FROM ubicaciones ORDER BY UB_CAMPUS");
                                    while ($row = mysqli_fetch_assoc($sql)) {
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
                                    <option value="">Seleccione...</option>
                                    <option value="TORRENTE">TORRENTE</option>
                                    <option value="CALASANZ">CALASANZ</option>
                                </select>
                                <input type="hidden" name="campus" id="hidden-campus" disabled>
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
                <div class="row">
                    <div class="col-sm-6">
                        <button type="button" class="form-control btn-warning">Cancelar</button>
                    </div>
                    <div class="col-sm-6">
                        <button type="submit" class="form-control btn-success" name="action" value="<?php if(isset($id)) echo "update"; else echo "add"; ?>">Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>