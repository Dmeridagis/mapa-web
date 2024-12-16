<?php
include 'libreria.php';

function listaReclamos()
{
    $sql = "
        SELECT 
            id_acciones AS id,
            tipo,
            mensaje,
            fecha,
            imagen,
            id_vecino,
            id_distrito,
            ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geom
        FROM public.reclamos_vecinos";

    // Llama a la función que genera los datos en formato GeoJSON
    return consultaGeojson2($sql);
}

// Imprime los datos como JSON
echo listaReclamos();
?>
