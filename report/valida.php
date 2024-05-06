<?php
include 'libreria.php';

$usuario=$_POST['txtusuario'];
$clave=$_POST['txtclave'];

$q="select * from public.usuarios where usuario='$usuario' and clave='$clave'";

$w=consultar($q);

switch (count($w)) {
    case 0:
		session_destroy();
        echo "<script>alert('Usuario no registrado!');
		document.location.href='../login.html';</script>";
        break;
    case 1:
	
		session_start();
		if($w[0]["estado"]==1){
			$_SESSION['key']='PHP1.$#@'; // marcador
			header('location: ../mapa.php');
		}else{
			session_destroy();
			echo "<script>alert('Estado desactivado!');
			document.location.href='../login.html';</script>";
		}	
        break;
}
?>