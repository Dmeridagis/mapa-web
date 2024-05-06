<?php

include 'libreria.php';

function listaVias()
	{
		  $sql="select id, tipo, nombre, via, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom from public.vias";
		  $vias=consultaGeojson2($sql);
		  return $vias;
	}

echo listaVias();
?>