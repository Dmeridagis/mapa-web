<?php
require_once 'libreria.php';

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar los datos enviados por el formulario
    $id_usuario = trim($_POST['id_usuario']);
    $tipo = trim($_POST['tipo']);
    $area = trim($_POST['area']);
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    // Validar que todos los campos estén llenos
    if (empty($id_usuario) || empty($tipo) || empty($area) || empty($nombre) || empty($email) || empty($usuario) || empty($password)) {
        die("Todos los campos son obligatorios.");
    }

    // Validar que el email sea válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("El correo electrónico no es válido.");
    }

    // Encriptar la contraseña antes de guardarla en la base de datos
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Preparar la consulta SQL usando prepared statements
        $sql = "INSERT INTO administradores (id_usuario, tipo, area, nombre, email, usuario, password)
                VALUES (:id_usuario, :tipo, :area, :nombre, :email, :usuario, :password)";

        $stmt = $pdo->prepare($sql);

        // Enlazar los parámetros con los valores correspondientes
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':area', $area, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Administrador creado exitosamente.";
        } else {
            echo "Error al crear el administrador.";
        }

    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
}
?>
