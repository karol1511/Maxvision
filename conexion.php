<?php

    $host = "containers-us-west-201.railway.app";
    $user = "root";
    $clave = "q6RwrOFsyWi1J3nlogwd";
    $bd = "ferrocarril";

    $conexion = mysqli_connect($host,$user,$clave,$bd);
    if (mysqli_connect_errno()){
        echo "No se pudo conectar a la base de datos";
        exit();
    }

    mysqli_select_db($conexion,$bd) or die("No se encuentra la base de datos");

    mysqli_set_charset($conexion,"utf8");


?>
