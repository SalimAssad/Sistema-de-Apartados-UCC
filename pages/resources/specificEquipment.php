<?php
include_once("../../inc/validateLogin.php");
include_once("../../inc/MySQLConnection.php");
include_once("resourceFunctions.php");
?>
<!DOCTYPE html>
<html lang='es'>
    <head>
        <meta charset="utf-8">
        <title></title>

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
                    <h1 class="page-header">Detalles</h1>


                   
                    <button type="button" class="btn btn-primary">Consultar </button>
                        
                    <?php 
                    $sql="select * from recursos";
                    $resultado = mysqli_query($connection, $sql);
                    if($resultado){
                        $cantidad_recursos=mysqli_num_rows($resultado); 

                        if($cantidad_recursos > 0){
                    ?>
                    
                    
                    
                    <table class="table">
    <thead>
      <tr>
       <th>Modelo</th>
        <th>Display Name</th>
        <th>Área/Equipo</th>
          <th> Disponibilidad</th>
          <th> No. de serie</th>
          <th> No. de inventario</th>
          <th> Registro</th>
          <th> Modificación</th>
          <th> Asignación</th>
          <th> Tipo de recurso</th>
          <th> Opciones</th>
      </tr>
    </thead>
    <tbody>
        
         <?php 
                            while($fila = mysqli_fetch_assoc($resultado)){
                            ?>
        
        
      <td><?php echo $fila["RE_MODEL"]?></td>
        <td><?php echo $fila["RE_ALIAS"]?></td>
        <td> <?php echo $fila["RE_TYPE"]?></td>
          <td><?php echo $fila["RE_AVAILABLE"]?></td>
          <td> <?php echo $fila["RE_SERIAL"]?></td>
        <td><?php echo $fila["RE_INVENTORY"]?></td>
        <td> <?php echo $fila["RE_CREATED"]?></td>
        <td> <?php echo $fila["RE_MODIFIED"]?></td>
        <td> <?php echo $fila["RE_LOCATION"]?></td>
        <td> <?php echo $fila["RE_HWTYPE"]?></td>
        <td><form action="addResource.php" method="post" ><button type="submit" class="btn btn-success">Editar</button></form>
            <form action="deleteEquipment.php" method="post"> <button type="submit" class="btn btn-danger" name="RE_ID" value="<?php echo $fila["RE_ID"] ?>">Eliminar </button></form>  </td>
 </td>
      </tr>
                <?php }; ?>
     
    </tbody>
  </table>
        <?php 
                    }else{
                            echo "No hay recursos registrados";
                        }
                    }
                    ?>
</div>
                </div>
            </div>
        </div>
<div class="row">
                        <div class="col-md-6">
                            <h2 class="sub-header">Datos</h2>
                            <div class="col-sm-6">
                                <div>
                                    <label>Tipo de recurso:</label>
                                </div>
                                <div>
                                    <input type="radio" name="resource" id="equipment" value="EQUIPO"
                                           onclick="typeHandler(this.value)" <?php if ($type == "EQUIPO" || $type == "") echo "checked" ?>><label
                                        for="equipment">Equipo</label>
                                    <input type="radio" name="resource" id="space" value="AULA"
                                           onclick="typeHandler(this.value)" <?php if ($type == "AULA") echo "checked" ?>><label
                                        for="space">Espacio</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div>
                                    <label for="alias">Alias:</label>
                                </div>
                                <div>
                                    <input type="text" class="form-control" id="alias" name="alias"
                                           value="<?php echo $alias; ?>" required>
                                </div>
                            </div>
                            <?php
                            if ($type == "EQUIPO" || $type == "") {
                                ?>
                                <div class="col-sm-6 equipment">
                                    <div>
                                        <label for="model">Modelo:</label>
                                    </div>
                                    <div>
                                        <input type="text" class="form-control equipment" id="model" name="model"
                                               value="<?php echo $model; ?>" required>
                                    </div>
                                </div>
                                <?php
                            }
                            if ($type == "EQUIPO" || $type == "") {
                                ?>
                                <div class="col-sm-6 equipment">
                                    <div>
                                        <label for="serial">Número de serie:</label>
                                    </div>
                                    <div>
                                        <input type="text" class="form-control equipment" id="serial" name="serial"
                                               value="<?php echo $serial; ?>"
                                               required>
                                    </div>
                                </div>
                                <?php
                            }
                            if ($type == "EQUIPO" || $type == "") {
                                ?>
                                <div class="col-sm-6 equipment">
                                    <div>
                                        <label for="inventory">Número de inventorio:</label>
                                    </div>
                                    <div>
                                        <input type="text" class="form-control equipment" id="inventory"
                                               name="inventory"
                                               value="<?php echo $inventory; ?>"
                                               required>
                                    </div>
                                </div>
                                <?php
                            }
                            if ($type == "EQUIPO" || $type == "") {
                                ?>
                                <div class="col-sm-6 equipment">
                                    <div>
                                        <label for="hw-type">Tipo de hardware:</label>
                                    </div>
                                    <div>
                                        <select class="form-control equipment" name="hwType" id="hw-type" required>
                                            <option value="">Seleccione...</option>
                                            <?php
                                            $auxSQL = mysqli_query($connection, "SELECT * FROM tipos_equipos");
                                            $strOptions = "";
                                            while ($row = mysqli_fetch_assoc($auxSQL)) {
                                                $strOptions = $strOptions . "<option value='$row[TI_ID]'";
                                                if (isset($hwType) && $hwType == $row["TI_ID"]) {
                                                    $strOptions = $strOptions . " selected";
                                                }
                                                $strOptions = $strOptions . ">$row[TI_DESCRIPTION]</option>";
                                                echo $strOptions;
                                                $strOptions = "";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="col-sm-12">
                                <h2 class="sub-header">Referencias</h2>
                                <div class="col-sm-12">
                                    <div>
                                        <label for="reference">Referencia a añadir</label>
                                    </div>
                                    <div>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="reference">
                                                <option value="">Seleccione...</option>
                                                <?php
                                                $auxSQL = mysqli_query($connection, "SELECT * FROM referencias");
                                                $strOptions = "";
                                                while ($row = mysqli_fetch_assoc($auxSQL)) {
                                                    $strOptions = $strOptions . "<option value='$row[RE_ID]-$row[RE_DESCRIPTION]'";
                                                    if (isset($reference) && $reference == $row["RE_ID"]) {
                                                        $strOptions = $strOptions . " selected";
                                                    }
                                                    $strOptions = $strOptions . ">$row[RE_DESCRIPTION]</option>";
                                                    echo $strOptions;
                                                    $strOptions = "";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="button" class="form-control btn-success"
                                                    onclick="addReference()">
                                                Añadir
                                            </button>
                                        </div>
                                    </div>
                                </div>
    </body>
</html>