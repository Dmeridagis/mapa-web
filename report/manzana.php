<?php

include 'libreria.php';

function listaManzanas()
	{
		  $sql="select id, id_mzna, id_sector, cod_mzna, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom from public.manzana";
		  $manzanas=consultaGeojson2($sql);
		  return $manzanas;
	}

echo listaManzanas();
?>