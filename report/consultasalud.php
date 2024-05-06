<?php

include 'libreria.php';

$codigo=$_GET["codigo"];

$sql="select id, dto, n_ctro, estratif, nombre, domicilio, telefono, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom from public.centros_de_salud where id=$codigo";

$salud=consultaGeojson2($sql);                          


?>