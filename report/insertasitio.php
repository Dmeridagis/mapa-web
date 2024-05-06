<?php

$codigo=$_POST['txtcodigo'];
$b=$_POST['txttipositio'];
$c=$_POST['txtnombresitio'];
$a=$b.$c;
$d=$_POST['txtgeositio'];
$cadenauno = str_replace(",", " ",$d);
$cadenados = str_replace("] [", ", ",$cadenauno);
$coordenadas = str_replace( "[", "",str_replace("]", "",$cadenados));
$punto='POINT('.$coordenadas.')';
/*
echo $a."<br>";
echo $b."<br>";
echo $c."<br>";
echo $d."<br>";
echo $punto."<br>";
die();
*/
include 'libreria.php';
switch($codigo){
    case '':
        $sql="insert into public.sitiointeres(tipo, nombre, descripcion, geom) 
        values('$b','$c','$a',ST_GeomFromText('$punto', 4326))";
        break;
    case 0:
        $sql="insert into public.sitiointeres(tipo, nombre, descripcion, geom) 
        values('$b','$c','$a',ST_GeomFromText('$punto', 4326))";   
        break;
    case $codigo>0:
        $sql="update public.sitiointeres set tipo='$b',nombre='$c',descripcion='$a', geom=ST_GeomFromText('$punto', 4326)
        where id=$codigo";        
        break;

}

$w=ejecutar($sql);

?>
