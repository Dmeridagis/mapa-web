<?php
// Ajusta la ruta según cada estructura
include 'C:/Users/54261/Downloads/mapa-web-main/report/libreria.php';

// Recuperar el término de búsqueda
$searchQuery = $_POST['search'] ?? '';

// Preparar y ejecutar la consulta
$sql = "SELECT id, usuario, clave, estado FROM usuarios WHERE usuario ILIKE :query OR estado::text ILIKE :query";
$stmt = $pdo->prepare($sql);
$stmt->execute(['query' => '%' . $searchQuery . '%']);

// Obtener los resultados
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<tbody>
    <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?php echo htmlspecialchars($usuario['id']); ?></td>
            <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
            <td><?php echo htmlspecialchars($usuario['clave']); ?></td>
            <td><?php echo htmlspecialchars($usuario['estado'] == 1 ? 'Activo' : 'Inactivo'); ?></td>
            <td><button class="btn-admin">Administrar</button></td>
        </tr>
    <?php endforeach; ?>
</tbody>
