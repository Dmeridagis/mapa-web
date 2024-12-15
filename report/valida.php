<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../super/prueba.php'; // Asegúrate de que la ruta al archivo de conexión es correcta

session_start();

// Verificar si ya hay una sesión iniciada
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    // Obtener y sanitizar los datos de usuario y contraseña
    $usuario = $_POST['login_usuario']; // Usamos 'login_usuario' para el nombre del campo en el formulario
    $usuario = filter_var($usuario, FILTER_SANITIZE_STRING);
    $pass = $_POST['login_clave']; // Usamos 'login_clave' para el nombre del campo en el formulario

    // Verificar que la conexión con la base de datos sea válida
    if ($conn instanceof PDO) {
        // Consulta para verificar usuario y contraseña
        $select_user = $conn->prepare("SELECT * FROM `usuarios` WHERE usuario = ? AND clave = ?");
        $select_user->execute([$usuario, $pass]);

        // Depuración: Ver cuántas filas devuelve la consulta
        var_dump($select_user->rowCount()); // Esto debería mostrar 1 si se encuentra el usuario

        $row = $select_user->fetch(PDO::FETCH_ASSOC);

        // Depuración: Ver el resultado de la consulta
        var_dump($row);

        if ($select_user->rowCount() > 0) {
            // Usuario encontrado y las credenciales coinciden
            $_SESSION['user_id'] = $row['id'];
            // Redirigir al usuario a la página principal (InicioMuni.html)
            header('Location: ../MuniHome/Incio.html');
            exit();
        } else {
            // Si no se encuentran las credenciales
            $message[] = '¡Contraseña o usuario incorrectos!';
        }
    } else {
        die("Error: La conexión a la base de datos no es válida.");
    }
}
?>

