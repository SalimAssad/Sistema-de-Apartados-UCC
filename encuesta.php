<?php
session_start();
if (!isset($_SESSION['user']))
header("Location: index.php");
else {
    include("inc/MySQLConnection.php");
        //  Query de encuestas.
    $sql = "SELECT * FROM evaluaciones";
    $req = mysqli_query($connection,$sql);
        //  Query de preguntas
    $pregunta="select * from preguntas where PR_ACTIVE = 1";
    $sqlp = mysqli_query($connection, $pregunta);
    
        // QUERY DE RESPUESTAS //    
    $repuestaQ="select * from respuestas where RE_ACTIVE = 1";
    $sqlp2 = mysqli_query($connection, $repuestaQ); 
    
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    if($_SERVER["REQUEST_METHOD"]=="POST") {
    $respuesta = trim(filter_input(INPUT_POST,"respuesta", FILTER_SANITIZE_STRING));
       
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
        <h1 class="page-header">Seleccione una opción A</h1>
        <img src="imgs/escudo_ucc.jpg" width="600px" height="378px" align="middle">
            <div class="container">
<!-- /////////////////////////            visualizacion de encuestas                ////////////////////-->
    <div class="container"> 
        <form method="post">
        <h1>Encuestas</h1>
            <?php while($result = mysqli_fetch_object($req)){
                echo '<button class="btn btn-lg btn-primary btn-block" type="submit" formaction="agregar.php?id='.$result->EV_ID.'">Encuesta Número: '.$result->EV_ID.'</button>';
            }?><br>
    	<button class="btn btn-lg btn-primary btn-block" type="submit" formaction="agregar.php">+ Agregar nueva encuesta</button>
        </form>
     </div>
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////-->
    <?php } else{ ?>
    <div class="row center">
        <h1 class="page-header">Por favor, Llena la siguiente encuesta: </h1>
        <img src="imgs/escudo_ucc.jpg" width="600px" height="378px">
        <div class="container">
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////-->
            <form class="form-signin" action="encuesta.php" method="Post">
               <div><br>
                <?php while($result = mysqli_fetch_object($sqlp)){ echo "<label> $result->PR_ID -. $result->PR_QUESTION </label>";}?><br>
                </div>
                
               <div>
                    <label>Respuestas: </label><br>
                    <?php while($result2 = mysqli_fetch_object($sqlp2)){ echo "<input class='form-control col-sm-8' type='text' name='$result2->RE_ID' value='$result2->RE_ANSWER'>";}?><br>
               </div><br>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Terminar encuesta</button>
                <br>
                                 
          </form>
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////-->
        </div>
    </div>
    <?php } ?>
        
    </div>
</div>
</body>
</html>