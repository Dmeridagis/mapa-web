<?php

include 'libreria.php';

function listaDistritos()
{
    $sql = "select id, distrito, id_distrit, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom from public.distritos";
    $distritos = consultaGeojson2($sql); // Envío como consulta SQL y devuelvo como consulta JSON
    return $distritos;
}

echo listaDistritos();

?>
