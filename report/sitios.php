<?php

include 'libreria.php';

function listaSitios()
	{
		  $sql="select id, tipo, nombre, descripcion, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom from public.sitiointeres";
		  $sitiointeres=consultaGeojson2($sql);
		  return $sitiointeres;
	}

echo listaSitios();
?>