<?php
include 'prueba.php'; // Conexión a la base de datos
session_start();

if (isset($_POST['submit'])) {
    // Captura y sanitiza los datos del formulario
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = sha1($_POST['password']); // Encripta la contraseña con SHA-1

    // Consulta para verificar el usuario y obtener su tipo de rol
    $query = $conn->prepare("SELECT id, tipo_rol FROM administradores WHERE nombre_usuario = ? AND clave = ?");
    $query->execute([$username, $password]);
    
    // Comprueba si se encontró una fila
    if ($query->rowCount() > 0) {
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $_SESSION['admin_id'] = $row['id']; // Guarda el ID del administrador en la sesión
        $role = $row['tipo_rol']; // Obtén el tipo de rol

        // Redirección según el tipo de rol
        if ($role === 'SUPERADMIN') {
            header('Location: tablero.php');
            exit;

        } elseif ($role === 'localizaciones') {
            header('Location: mapa.html');
            exit;

        } elseif ($role === 'quejas') {
            header('Location: lista.html');
            exit;

        } else {
            // Si el rol no es reconocido
            $message = 'Rol no reconocido.';
        }
    } else {
        // Si las credenciales son incorrectas
        $message = 'Usuario o contraseña incorrectos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión Admin</title>
    <link rel="stylesheet" href="Iniciar-Admin.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-form">
                <h1>Iniciar Sesión</h1>
                <p class="subtitle">¿Cómo empiezo? Complete los siguientes campos</p>
                
                <!-- Mostrar mensaje si existe -->
                <?php if (isset($message)) : ?>
                    <div class="message">
                        <span><?= htmlspecialchars($message) ?></span>
                        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="input-group">
                        <label for="username">
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23333333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2'/%3E%3Ccircle cx='12' cy='7' r='4'/%3E%3C/svg%3E" alt="Usuario">
                        </label>
                        <input type="text" id="username" name="username" placeholder="Nombre de usuario" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="password">
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23333333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='11' width='18' height='11' rx='2' ry='2'/%3E%3Cpath d='M7 11V7a5 5 0 0 1 10 0v4'/%3E%3C/svg%3E" alt="Contraseña">
                        </label>
                        <input type="password" id="password" name="password" placeholder="Contraseña" required>
                    </div>

                    <button type="submit" name="submit" class="login-button">Iniciar Sesión</button>
                </form>

                <div class="social-login">
                    <p>Existen 2 Tipos de Administradores además del SuperAdmin</p>
                    <div class="social-buttons">
                        <button class="google-btn">Admin Reclamos</button>
                        <button class="facebook-btn">Admin Localizaciones</button>
                    </div>
                </div>
            </div>
            <div class="login-image">
                <img src="admin.jpg" alt="Administrador del sistema">
            </div>
        </div>
    </div>
</body>
</html>
