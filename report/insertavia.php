<?php

$codigo=$_POST['txtcodigo'];
$b=$_POST['txttipo'];
$c=$_POST['txtnombre'];
$a=$b.$c;
$d=$_POST['txtgeovia'];
$cadenauno = str_replace(",", " ",$d);
$cadenados = str_replace("] [", ", ",$cadenauno);
$coordenadas = str_replace( "[", "",str_replace("]", "",$cadenados));
$polilinea='LINESTRING('.$coordenadas.')';

include 'libreria.php';
switch($codigo){
    case '':
        $sql="insert into public.vias(tipo, nombre, via, geom) 
        values('$b','$c','$a',ST_GeomFromText('$polilinea', 4326))";
        break;
    case 0:
        $sql="insert into public.vias(tipo, nombre, via, geom) 
        values('$b','$c','$a',ST_GeomFromText('$polilinea', 4326))";   
        break;
    case $codigo>0:
        $sql="update public.vias set tipo='$b',nombre='$c',via='$a', geom=ST_GeomFromText('$polilinea', 4326)
        where id=$codigo";        
        break;

}

$w=ejecutar($sql);

?>
