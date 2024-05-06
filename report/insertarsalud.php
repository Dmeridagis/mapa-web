<?php

$codigo=$_POST['txtcodigo'];
$b=$_POST['txtdtosalud'];
$c=$_POST['txtnsalud'];
$d=$_POST['txtnombresalud'];
$e=$_POST['txtdomiciliosalud'];
$g=$_POST['txttelefonosalud'];
$j=$_POST ['txtestratif'];
$h=$_POST['txtgeosalud'];
$cadenauno = str_replace(",", " ",$h);
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
        $sql="insert into public.centros_de_salud(dto, n_ctro, nombre, domicilio, telefono, estratif, geom) 
        values('$b','$c','$d','$e','$g','$h',ST_GeomFromText('$punto', 4326))";
        break;
    case 0:
        $sql="insert into public.centros_de_salud(dto, n_ctro, nombre, domicilio, telefono, geom) 
        values('$b','$c','$d','$e','$g','$h',ST_GeomFromText('$punto', 4326))";  
        break;
    case $codigo>0:
        $sql="update public.centros_de_salud set dto='$b', n_ctro='$c',nombre='$d', domicilio='$e',telefono='$g', estratif='$h', geom=ST_GeomFromText('$punto', 4326)
        where id=$codigo";        
        break;

}

$w=ejecutar($sql);

?>