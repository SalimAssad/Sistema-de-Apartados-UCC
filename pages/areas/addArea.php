<?php
include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");

/* -----------------------------------------

    SE INICIALIZAN LAS VARIABLES VACÍAS

----------------------------------------- */

$name = "";
$campus = "";
$reference = "";

/* ---------------------------------------------------------------

    SI SE CUMPLE LA VALIDACIÓN, SE ESTÁ ACTUALIZANDO

--------------------------------------------------------------- */

if (isset($_GET['idArea'])) {
    $id = filter_input(INPUT_GET, "idArea", FILTER_SANITIZE_NUMBER_INT);

    $areaSQL = mysqli_query($connection, "SELECT * FROM areas WHERE AR_ID = '$id'");
    $areaData = mysqli_fetch_assoc($areaSQL);

    $name = $areaData['AR_NAME'];
    $campus = $areaData['AR_CAMPUS'];
    $reference = $areaData['AR_TYPE'];
}

/* ---------------------------------------------------------------

    SI SE RECIBEN, OCURRIÓ UN ERROR Y SE PISAN LOS VALORES

--------------------------------------------------------------- */

if (isset($_GET['name']))
    $name = filter_input(INPUT_GET, "name", FILTER_SANITIZE_STRING);
if (isset($_GET['campus']))
    $campus = filter_input(INPUT_GET, "campus", FILTER_SANITIZE_STRING);
if (isset($_GET['reference']))
    $reference = filter_input(INPUT_GET, "reference", FILTER_SANITIZE_STRING);

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
            <form action="saveArea.php" method="post">
                <?php
                if (isset($id))
                    echo "<input type='hidden' name='idArea' value='$id'>";
                ?>
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <h2 class="sub-header">Datos</h2>
                        <div class="col-sm-6 equipment">
                            <div>
                                <label for="name">Nombre:</label>
                            </div>
                            <div>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="<?php echo $name; ?>" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">
                            <div>
                                <label for="campus">Campus:</label>
                            </div>
                            <div>
                                <select class="form-control" id="campus" name="campus" required>
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
                                <label for="location">Referencia</label>
                            </div>
                            <div>
                                <select class="form-control" id="reference" name="reference"
                                        onchange="locationHandler(this.options[this.selectedIndex].text)" required>
                                    <option value="">Seleccione...</option>
                                    <?php
                                    $auxSQL = mysqli_query($connection, "SELECT * FROM referencias");
                                    $strOptions = "";
                                    while ($row = mysqli_fetch_assoc($auxSQL)) {
                                        $strOptions = $strOptions . "<option value='$row[RE_ID]'";
                                        if (isset($reference) && $reference == $row["RE_ID"]) {
                                            $strOptions = $strOptions . " selected";
                                            $description = $row['RE_DESCRIPTION'];
                                        }
                                        $strOptions = $strOptions . ">$row[RE_DESCRIPTION]</option>";
                                        echo $strOptions;
                                        $strOptions = "";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                    </div>
                </div>
                <div class="row top-margin">
                    <div class="col-md-3"></div>
                    <div class="col-sm-3">
                        <button type="button" class="form-control btn-warning">Cancelar</button>
                    </div>
                    <div class="col-sm-3">
                        <button type="submit" class="form-control btn-success" name="action"
                                value="<?php if (isset($id)) echo "update"; else echo "add"; ?>">Guardar
                        </button>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>