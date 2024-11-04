<?php
include 'libreria.php';


    $codigo = $_GET['codigo'];

    $sql = "SELECT id, dto, n_ctro, estratif, nombre, domicilio, telefono, ST_AsGeoJSON(geom) as geom 
            FROM public.centros_de_salud 
            WHERE id=$codigo";
            $salud=consultaGeojson2($sql);


            
 ?>