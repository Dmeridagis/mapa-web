<?php

include 'libreria.php';

function listaDeportes()
{
    $sql = "select id, gid, id_deporte, id_usuario, tipo, nombre, servicios, horarios, direccion, telefono, id_distrit, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom from public.deportes";
    $deportes = consultaGeojson2($sql); // Envío como consulta SQL y devuelvo como consulta JSON
    return $deportes;
}

echo listaDeportes();

?>
