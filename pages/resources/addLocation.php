<?php
include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");

/* --------------------------------------------

    SE INICIALIZAN LAS VARIABLES VACÍAS

-------------------------------------------- */

$campus = "";
$pile = "";
$floor = "";
$room = "";

/* -----------------------------------------------------

    SI SE CUMPLE LA VALIDACIÓN, SE ESTÁ ACTUALIZANDO

----------------------------------------------------- */

if (isset($_GET['idLocation'])) {
    $id = filter_input(INPUT_GET, "idLocation", FILTER_SANITIZE_NUMBER_INT);

    $locationSQL = mysqli_query($connection, "SELECT * FROM ubicaciones WHERE UB_ID = '$id'");
    $locationData = mysqli_fetch_assoc($locationSQL);

    $campus = $locationData['UB_CAMPUS'];
    $pile = $locationData['UB_PILE'];
    $floor = $locationData['UB_FLOOR'];
    $room = $locationData['UB_ROOM'];
}

/* ---------------------------------------------------------------------------------------------------

    EN CASO DE QUE REGRESE UN ERROR, SE PISAN LOS VALORES CON LOS QUE EL USUARIO HABÍA INGRESADO

--------------------------------------------------------------------------------------------------- */

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
                    <?php
                    if (isset($_GET['error'])) {
                    ?>
                    <div class="alert alert-danger">
                        <?php
                        echo $_GET['error'];
                        ?>
                    </div>
                    <?php
                    }
                    ?>
                    <form action="saveLocation.php" method="post">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <h2 class="sub-header">Ubicación</h2>
                                <div class="col-sm-6">
                                    <input type="hidden" name="idLocation" value="<?php if(isset($id)) echo $id; ?>">
                                    <div>
                                        <label for="campus">Campus:</label>
                                    </div>
                                    <div>
                                        <select class="form-control" id="campus"
                                                name="campus" required>
                                            <option value="">Seleccione...</option>
                                            <option value="TORRENTE" <?php if ($campus == "TORRENTE") echo "selected"; ?>>
                                                TORRENTE
                                            </option>
                                            <option value="CALASANZ" <?php if ($campus == "CALASANZ") echo "selected"; ?>>
                                                CALASANZ
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div>
                                        <label for="pile">Edificio:</label>
                                    </div>
                                    <div>
                                        <input type="text" class="form-control" id="pile" name="pile"
                                               value="<?php echo $pile; ?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div>
                                        <label for="floor">Piso:</label>
                                    </div>
                                    <div>
                                        <input type="text" class="form-control" id="floor" name="floor"
                                               value="<?php echo $floor; ?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div>
                                        <label for="room">Habitación:</label>
                                    </div>
                                    <div>
                                        <input type="text" class="form-control" id="room" name="room"
                                               value="<?php echo $room; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                        <div class="row top-margin">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3">
                                <button type="button" class="form-control btn-warning"
                                        onclick="window.location.href='locationList.php'">
                                    Cancelar
                                </button>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="form-control btn-success" name="action"
                                        value="<?php if (isset($id)) echo "update"; else echo "add"; ?>">Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>