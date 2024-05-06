<?php

include 'libreria.php';

$codigo=$_GET["codigo"];

$sql="select id, tipo, nombre, descripcion, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom from public.sitiointeres where id=$codigo";
$sitiointeres=consultaGeojson2($sql);


?>