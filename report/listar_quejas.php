<?php
include 'libreria.php';

// Filtrar por estado si se recibe un parámetro
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';

try {
    if ($estado) {
        $query = "SELECT * FROM quejas WHERE estado = :estado ORDER BY fecha DESC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':estado', $estado);
    } else {
        $query = "SELECT * FROM quejas ORDER BY fecha DESC";
        $stmt = $pdo->prepare($query);
    }

    $stmt->execute();
    $quejas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener las quejas: " . $e->getMessage();
    exit();
}

// Convertimos los resultados a JSON para enviarlos al frontend
header('Content-Type: application/json');
echo json_encode($quejas);
?>
