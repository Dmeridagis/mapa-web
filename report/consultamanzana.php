<?php

include 'libreria.php';

$codigo=$_GET["codigo"];

$sql="select id, id_mzna, id_sector, cod_mzna, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom from public.manzana where id=$codigo";
$manzanas=consultaGeojson2($sql);


?>