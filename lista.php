<?php
// Conexión a la base de datos
include 'libreria.php'; // Incluye la conexión a la base de datos

// Consulta para obtener las quejas
$query = $pdo->prepare ("SELECT * FROM reclamos ORDER BY fecha DESC");
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);

// Enviar los datos como respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($results);
?>
