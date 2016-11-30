<?php
if($_SERVER["REQUEST_METHOD"]=="POST") {
    $question=trim(filter_input(INPUT_POST,"question",FILTER_SANITIZE_STRING));
    include_once("inc/validateLogin.php");
    include_once("inc/MySQLConnection.php");
}
?>
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset="utf-8">
        <title>Añadir Encuesta</title>
        <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/dashboard.css" rel="stylesheet">
        <link href="css/global.css" rel="stylesheet">
        <script src="utils/jquery-1.12.3.min.js">
        </script>
        <script src="css/bootstrap/js/bootstrap.min.js">
        </script>
        <script src="scripts/addResourceScript.js">
        </script>
    </head>
    <body>
    <?php
    include_once("inc/nav.php");
    ?>
    <div class="container-fluid">
        <div class="row">
            <?php
            include_once("inc/sidebar.php");
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
<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////   -->     
                <form action="encuesta.php" method="post">
                     <div class="col-sm-12">
                                <h2 class="sub-header">Encuestas</h2>
                                <div class="col-sm-12">
                                    <div>
                                        <label for="reference">Crear Encuesta:</label>
                                    </div>
                                    <div>
                                        <div class="col-sm-8">
                                            <input type="text" name="encuesta" class="form-control" id="reference" placeholder="Ingrese su encuesta1 aqui">   
                                            </input>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="button" class="form-control btn-success"
                                            onclick="addPreguntas()">
                                            Añadir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="reference-container" class="col-sm-12">
                                    
                                </div>
                            </div>
                       <div class="col-sm-12">
                                <h2 class="sub-header">Preguntas</h2>
                                <div class="col-sm-12">
                                    <div>
                                        <label for="reference">Añadir Pregunta:</label>
                                    </div>
                                    <div>
                                        <div class="col-sm-8">
                                            <input type="text" name="question" class="form-control" id="reference" placeholder="Ingrese su pregunta aqui">   
                                            </input>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="button" class="form-control btn-success"
                                            onclick="addPreguntas()">
                                            Añadir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="reference-container" class="col-sm-12">
                                    
                                </div>
                            </div>
<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////   -->     
                            <div class="col-sm-12">
                                <h2 class="sub-header">Respuestas</h2>
                                <div class="col-sm-12">
                                    <div>
                                        <label for="reference">Añadir Respuesta</label>
                                    </div>
                                    <div>
                                        <div class="col-sm-2">
                                            <select class="form-control" id="reference">
                                                <option value="">Seleccione la pregunta asociada...</option>
                                                <?php
                                                $auxSQL = mysqli_query($connection, "SELECT * FROM preguntas");
                                                $strOptions = "";
                                                while ($row = mysqli_fetch_assoc($auxSQL)) {
                                                    $strOptions = $strOptions . "<option value='$row[PR_ID]-$row[PR_QUESTION]'";
                                                    if (isset($reference) && $reference == $row["PR_ID"]) {
                                                        $strOptions = $strOptions . " selected";
                                                    }
                                                    $strOptions = $strOptions . ">$row[PR_QUESTION]</option>";
                                                    echo $strOptions;
                                                    $strOptions = "";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <div class="col-sm-2">
                                            <select class="form-control" id="reference">
                                                <option value="">Seleccione..</option>
                                                <option value="1">Abierta</option>
                                                <option value="0">Opción multiple</option>
                                            </select>
                                        </div>
                                        
                                         <div class="col-sm-4">
                                            <input class="form-control" id="reference" placeholder="Ingrese las opciones si es de opción multiple">
                                               
                                            </input>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="button" class="form-control btn-success"
                                                    onclick="addQuestion()">
                                                Añadir
                                            </button>
                                        </div>
                                    </div>
                                </div>
<!--////////////////////////////////////////////////////////  ////////////////////////////////////////////////-->
    <div id="reference-container" class="col-sm-12">
        <h2 class="sub-header"> </h2>
        <div class="row top-margin">
            <div class="col-sm-6">
                <button type="button" class="form-control btn-warning"
                onclick="window.location.href='<?php if ($returnTo == "equipment") echo "equipmentList.php"; else echo "roomList.php"; ?>'">
                Cancelar
                </button>
            </div>
                <div class="col-sm-6">
                <button type="submit" class="form-control btn-success" name="action"
                        value="<?php if (isset($id)) echo "update"; else echo "add"; ?>">Guardar
                </button>
            </div>
        </div>
    </div>
                            </div>
                        </div>                
                </form>
            </div>
        </div>
    </div>
    </body>
    </html>
<?php
unset($_SESSION['references']);
?>