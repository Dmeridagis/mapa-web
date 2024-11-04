<?php

include 'libreria.php';

function listaSalud()
	{
		  $sql="select id, dto, n_ctro, estratif, nombre, domicilio, telefono, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom from public.centros_de_salud";
		  $salud=consultaGeojson2($sql); //envio como consulta sql y devuelvo como consulta json
		  return $salud;
	}
	
	echo listaSalud();

?>