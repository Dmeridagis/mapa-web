<?php
include 'libreria.php';

// Obtener los datos del formulario de registro
$usuario = $_POST['txtusuario'];
$email = $_POST['txtemail'];
$clave = $_POST['txtclave'];
$direccion = $_POST['txtdireccion'];
$fechanac = $_POST['txtfechanac'];
$dni = $_POST['txtdni'];

// Verificar si el usuario ya existe
$q = "SELECT * FROM public.usuarios WHERE usuario='$usuario' OR email='$email'";
$w = consultar($q);

if (count($w) > 0) {
    echo "<script>alert('El usuario o correo ya está registrado.');
    document.location.href='../registro.html';</script>";
} else {
    // Insertar el nuevo usuario en la base de datos
    $q = "INSERT INTO public.usuarios (usuario, email, clave, estado, direccion, fechanac, dni) VALUES ('$usuario', '$email', '$clave', 1, '$direccion', '$fechanac', '$dni')";
    $resultado = ejecutar($q); // Asume que la función 'ejecutar' inserta los datos en la base de datos

    if ($resultado) {
        // Obtener el ID del nuevo usuario para iniciar sesión automáticamente
        // Suponiendo que el ID es autoincremental
        $q = "SELECT id FROM public.usuarios WHERE email='$email' AND usuario='$usuario' LIMIT 1";
        $resultado_usuario = consultar($q);

        if (count($resultado_usuario) > 0) {
            $id_vecino = $resultado_usuario[0]['id'];

            // Iniciar la sesión y almacenar el ID del usuario
            session_start();  // Iniciar la sesión
            $_SESSION['id_vecino'] = $id_vecino;  // Guardar el ID del usuario en la sesión

            // Redirigir al perfil del usuario
            echo "<script>alert('Registro exitoso!'); document.location.href='../pageuser.php';</script>";
        } else {
            echo "<script>alert('Error al obtener el ID del usuario.'); document.location.href='../registro.html';</script>";
        }
    } else {
        echo "<script>alert('Error en el registro, por favor intente de nuevo.');
        document.location.href='../registro.html';</script>";
    }
}
?>

