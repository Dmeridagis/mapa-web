<?php
include 'libreria.php';
$codigo = $_GET['codigo'];
$sql = "SELECT id, gid, id_segurid, id_usuario, telefono, direccion, tipo, nombre, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom FROM public.seguridad WHERE id = $codigo";
$seguridad = consultaGeojson2($sql);
echo json_encode($seguridad);
?>
