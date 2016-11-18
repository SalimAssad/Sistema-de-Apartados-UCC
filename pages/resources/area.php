<?php
//include_once("../../inc/validateLogin.php");
//include_once("../../inc/MySQLConnection.php");
//Tu código que necesites
?>
<!DOCTYPE html>
<html lang='es'>
    <head>
        <meta charset="utf-8">
        <title>Eliminar un salón></title>

        <link href="../../css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/dashboard.css" rel="stylesheet">
        <link href="../../css/global.css" rel="stylesheet">

        <script src="../../utils/jquery-1.12.3.min.js">
        </script>
        <script src="../../css/bootstrap/js/bootstrap.min.js">
        </script>
        
        
        
        
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
        
 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   
       
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
             <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
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
                    <h1 class="page-header">Vista general</h1>


                    <h3 class="sub-header">Área</h3>
                    <button type="button" class="btn btn-warning">Dar de alta</button>
                    <button type="button" class="btn btn-primary">Consultar</button>
                    <table class="table">
                        
                         
            <table class="table">
    <thead>
      <tr>
       <th>Sección</th>
        <th>Área</th>
        <th>Aula</th>
          <th> Actividad</th>
         
      </tr>
    </thead>
    <tbody>
      <td>Licenciatura</td>
        <td> Derecho</td>
        <td> TOR-03-001</td>
          <td> 
  
  
  <button type="button" class="btn btn-success">Editar</button>      <button type="button" class="btn btn-danger">Eliminar</button>
</td> </tr>
        <tr>
        <td>Posgrado</td>
        <td> Ingeniería Industrial</td>
        <td> Laboratorio de industrial</td>
          <td> 
  
  
  <button type="button" class="btn btn-success">Editar</button>      <button type="button" class="btn btn-danger">Eliminar</button>
            </td> </tr>
        <tr>
        <td>Bachillerato</td>
        <td> Informática</td>
        <td> TOR-03-001</td>
          <td> 
  
  
  <button type="button" class="btn btn-success">Editar</button>      <button type="button" class="btn btn-danger">Eliminar</button>
        </td> </tr>
</div>
                </div>
            </div>
        </div>
    </body>
</html>