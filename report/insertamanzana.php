<?php

$codigo=$_POST['txtcodigo'];
$b=$_POST['txtidsector'];
$c=$_POST['txtcodmanzana'];
$a=$b.$c;
$d=$_POST['txtgeo'];
$cadenauno = str_replace(",", " ",$d);
$cadenados = str_replace("] [", ", ",$cadenauno);
$coordenadas = str_replace( "[", "",str_replace("]", "",$cadenados));
$poligono='POLYGON(('.$coordenadas.'))';

include 'libreria.php';
switch($codigo){
    case '':
        $sql="insert into public.manzana(id_mzna, id_sector, cod_mzna, geom) 
        values('$a','$b','$c',ST_GeomFromText('$poligono', 4326))";
        break;
    case 0:
        $sql="insert into public.manzana(id_mzna, id_sector, cod_mzna, geom) 
        values('$a','$b','$c',ST_GeomFromText('$poligono', 4326))";   
        break;
    case $codigo>0:
        $sql="update public.manzana set id_mzna='$a',id_sector='$b',cod_mzna='$c', geom=ST_GeomFromText('$poligono', 4326)
        where id=$codigo";        
        break;

}

$w=ejecutar($sql);

?>
