<?php
include 'libreria.php';

// Obtener los datos del formulario de registro
$usuario = $_POST['txtusuario'];
$email = $_POST['txtemail'];
$clave = $_POST['txtclave'];

// Verificar si el usuario ya existe
$q = "SELECT * FROM public.usuarios WHERE usuario='$usuario' OR email='$email'";
$w = consultar($q);

if (count($w) > 0) {
    echo "<script>alert('El usuario o correo ya está registrado.');
    document.location.href='../registro.html';</script>";
} else {
    // Insertar el nuevo usuario en la base de datos
    $q = "INSERT INTO public.usuarios (usuario, email, clave, estado) VALUES ('$usuario', '$email', '$clave', 1)";
    $resultado = ejecutar($q); // Asume que la función 'ejecutar' inserta los datos en la base de datos

    if ($resultado) {
        echo "<script>alert('Registro exitoso!'); document.location.href='../pageuser.php';</script>";
    } else {
        echo "<script>alert('Error en el registro, por favor intente de nuevo.');
        document.location.href='../registro.html';</script>";
    }
}
?>
