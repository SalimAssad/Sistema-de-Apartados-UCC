<?php
session_start();
if (!isset($_SESSION['user']))
header("Location: index.php");
else {
    if($_SERVER["REQUEST_METHOD"]=="POST") {
    include("inc/MySQLConnection.php");
    $id = trim(filter_input(INPUT_GET,"id", FILTER_SANITIZE_STRING));
    $Qid = trim(filter_input(INPUT_GET,"QID", FILTER_SANITIZE_STRING));
    $contenido = trim(filter_input(INPUT_GET,"contenido", FILTER_SANITIZE_STRING));
    $activo =trim(filter_input(INPUT_GET,"Active", FILTER_SANITIZE_STRING));
    $abierta = trim(filter_input(INPUT_GET,"abierta", FILTER_SANITIZE_STRING));
    
    
        //   QUERY DE INSERTACIÓN DE //
    
        //   QUERY DE UPDATE DE DATOS //
    $Qupdatep="UPDATE preguntas SET PR_ID='$Qid', PR_QUESTION='$contenido', PR_ACTIVE=b'1',PR_ISOPEN=b'0' WHERE PR_ID='$Qid'";
    $sqlQupdate=mysqli_query($connection,$Qupdatep);
    
        //   QUERY DE CONSULTA ID DE ENCUESTA //
    $query = "SELECT * FROM evaluaciones WHERE EV_ID='$id'";
    $sql = mysqli_query($connection,$query);
    $row = mysqli_fetch_assoc($sql);
        // Se obtiene el id de la pregunta.
    $idQ=$row['EV_QUESTIONID'];
        // Se obtiene el id de la respuestas.  
    $idA=$row['EV_ANSWERID'];
        // QUERY DE PREGUNTAS //  
    $preguntaQ="select * from preguntas where PR_ACTIVE = 1 AND PR_ID = $idQ";
    $sqlp = mysqli_query($connection, $preguntaQ);
        // QUERY DE RESPUESTAS //    
    $repuestaQ="select * from respuestas where RE_ACTIVE = 1 AND RE_QUESTIONID =$idA";
    $sqlp2 = mysqli_query($connection, $repuestaQ);
    }
}
?>

<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset="utf-8">
    <title>Bienvenido al sistema</title>
    <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">

    <script src="utils/jquery-1.12.3.min.js">
    </script>
    <script src="css/bootstrap/js/bootstrap.min.js">
    </script>
    <style type="text/css">
        .center {
            text-align: center;
        }
    </style>
</head>
<body>
<?php
include_once("inc/nav.php");
?>
<div class="container-fluid">
    <?php if($_SESSION['profile']==1 || $_SESSION['profile']==2){ ?>
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////-->
    <div class="row center">
        <h1 class="page-header"> <?php echo $pregunta ?></h1>
        <img src="imgs/escudo_ucc.jpg" width="600px" height="378px" align="middle">
            <div class="container"><br>
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////-->
           <form class="form-signin" method="post" action="agregar.php">
               
            <div class="container">
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////-->   
                <div>
                <label>Pregunta</label><?php echo " $Qid  $contenido" ?>
                <?php while($result = mysqli_fetch_object($sqlp)){
            echo "<input class='form-control col-sm-6' type='text' name='$result->PR_ID' value='$result->PR_QUESTION'> <br> <button class='btn btn-lg btn-success btn-block' type='submit' formaction='agregar.php?QID=$result->PR_ID&contenido=$result->PR_QUESTION'>Guardar</button>";
                }?><br>
                </div>
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////-->
                <div>
                <label>Respuestas: </label><br>
                <?php while($result2 = mysqli_fetch_object($sqlp2)){
            echo "<input class='form-control col-sm-8' type='text' name='$result2->RE_ID' value='$result2->RE_ANSWER'> <br> ";}
                    ?><br>
               </div> 
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////-->
            </div><br>
               <div class="container">
                <input class="btn btn-warning btn-lg " type="submit" name="accion" value="Cancelar" formaction="index.php">
                
                <input class="btn  btn-success btn-lg" type="submit" name="accion" value="Guardar">
                
                
               </div>
          </form>
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////-->
    <?php } else{ ?>
    
                <?php } ?>
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////-->
        </div>
    </div>
</div>
</body>
</html>