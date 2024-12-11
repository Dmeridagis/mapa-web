<?php
// Configuración de la conexión
$servidor = "localhost"; // Servidor local
$usuario = "root"; // Usuario predeterminado de XAMPP
$clave = ""; // Contraseña predeterminada de XAMPP (vacía)
$baseDeDatos = "mapagis"; // Nombre de la base de datos

try {
    // Crear la conexión usando PDO
    $conn = new PDO("mysql:host=$servidor;dbname=$baseDeDatos", $usuario, $clave);
    
    // Configurar atributos de PDO para manejar errores y establecer el modo
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Mensaje de éxito
} catch (PDOException $e) {
    // Capturar errores y mostrar un mensaje
    die("Error de conexión: " . $e->getMessage());
}
?>

