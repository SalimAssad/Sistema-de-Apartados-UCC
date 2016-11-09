<?php
$connection = mysqli_connect("localhost", "root", "", "centrodecomputo");

if(!$connection){
    exit("Error - no se pudo conectar con la base de datos");
    };