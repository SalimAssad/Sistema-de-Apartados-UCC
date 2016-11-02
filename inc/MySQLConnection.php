<?php
$connection = mysqli_connect("localhost", "USUARIO", "CONTRASEñA", "BASE_DE_DATOS");

if(!$connection)
    exit("Error - no se pudo conectar con la base de datos");