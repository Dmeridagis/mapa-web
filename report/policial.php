<?php

include 'libreria.php';

function listaPolicial()
{
    $sql = "select id, gid, id_segurid, id_usuario, telefono, direccion, tipo, nombre, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom from public.seguridad";
    $seguridad = consultaGeojson2($sql); // Envío como consulta SQL y devuelvo como consulta JSON
    return $seguridad;
}

echo listaPolicial();

?>
