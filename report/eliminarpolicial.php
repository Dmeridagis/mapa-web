<?php
include 'libreria.php';
$codigo=$_GET['codigo'];
$q="delete from public.seguridad where id=$codigo";

$ex=ejecutar($q);
if($ex){
    header('location: ../mapa.php');
}else{
    echo "Error en ejecucion. <br>$q";
}
?>