<?php

include 'libreria.php';

function listaManzanas()
	{
		  $sql="select id, id_mzna, id_sector, cod_mzna, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom from public.manzana";
		  $manzanas=consultaGeojson($sql); //envio como consulta sql y devuelvo como consulta json
		  return $manzanas;
	}


	function listaSalud()
	{
		  $sql="select id, dto, n_ctro, estratif, nombre, domicilio, telefono, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom from public.centros_de_salud";
		  $salud=consultaGeojson($sql); //envio como consulta sql y devuelvo como consulta json
		  return $salud;
	}

?>