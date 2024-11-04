<?php
session_start(); // Asegúrate de que la sesión esté iniciada

// Capturamos los datos enviados por el formulario
$tipoReclamo = $_POST['txtTipoReclamo'];
$detalleReclamo = $_POST['txtDetalleReclamo'];
$fechaReclamo = $_POST['txtFechaReclamo'];
$coordenadas = $_POST['txtgeoreclamo'];

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['id_vecino']) || !isset($_SESSION['user_role'])) {
    echo "<script>alert('Por favor inicie sesión para hacer un reclamo.');
    document.location.href='../login.html';</script>";
    exit();
}

$id_vecino = $_SESSION['id_vecino']; // Obtener el id del usuario desde la sesión

// Incluir la librería para consultar la base de datos
include 'libreria.php';

// Obtener el número de reclamo actual para el usuario específico
$query = "SELECT COUNT(*) AS total_reclamos FROM public.reclamos_vecinos WHERE id_vecino = '$id_vecino'";
$resultado = consultar($query);

// Incrementar el número de reclamo basado en el número de reclamos existentes
$numeroReclamo = $resultado[0]['total_reclamos'] + 1;

// Crear el código del reclamo con el prefijo "RECLA" y el número del reclamo específico del usuario
$n_reclamo = 'RECL-' . str_pad($numeroReclamo, 3, '0', STR_PAD_LEFT); // Ejemplo: RECLA-001, RECLA-002, etc.

// Asignar otros datos
$id_distrito = 1;

// Procesar la imagen si se ha subido
if (isset($_FILES['imagenReclamo']) && $_FILES['imagenReclamo']['error'] == 0) {
    $imagen = pg_escape_bytea(file_get_contents($_FILES['imagenReclamo']['tmp_name']));
} else {
    $imagen = null;
}

// Convertimos las coordenadas en formato POINT (PostGIS)
$cadenaCoordenadas = str_replace(",", " ", $coordenadas);
$punto = 'POINT(' . $cadenaCoordenadas . ')';

// Insertar el reclamo en la base de datos
$sql = "INSERT INTO public.reclamos_vecinos (id_vecino, n_reclamo, tipo, mensaje, imagen, fecha, id_distrito, geom) 
        VALUES ('$id_vecino', '$n_reclamo', '$tipoReclamo', '$detalleReclamo', " . ($imagen ? "'$imagen'" : "NULL") . ", NOW(), '$id_distrito', ST_GeomFromText('$punto', 4326))";

// Ejecutar la consulta
$w = ejecutar($sql);
?>
