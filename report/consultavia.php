<?php

include 'libreria.php';

$codigo=$_GET["codigo"];

$sql="select id, tipo, nombre, via, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom from public.vias where id=$codigo";
$vias=consultaGeojson2($sql);


?>