<?php

// Capturamos los datos enviados por el formulario
$tipoReclamo = $_POST['txtTipoReclamo'];        // Tipo de reclamo
$detalleReclamo = $_POST['txtDetalleReclamo'];  // Detalles del reclamo
$fechaReclamo = $_POST['txtFechaReclamo'];      // Fecha del reclamo
$coordenadas = $_POST['txtgeoreclamo'];         // Coordenadas en formato JSON


$id_vecino = 1; // ID del vecino (debería venir del sistema de autenticación)
$n_reclamo = "RECL-001"; // Número de reclamo (puede ser un campo generado automáticamente)
$id_distrito = 1; // ID del distrito (podrías obtenerlo de otro campo o selección en el formulario)



// Procesar la imagen si se ha subido
if (isset($_FILES['imagenReclamo']) && $_FILES['imagenReclamo']['error'] == 0) {
    // Escapar los datos binarios de la imagen antes de insertarlos en la base de datos
    $imagen = pg_escape_bytea(file_get_contents($_FILES['imagenReclamo']['tmp_name']));
} else {
    $imagen = null; // Si no hay imagen, la guardamos como NULL
}



// Convertimos las coordenadas en formato POINT (PostGIS)
$cadenaCoordenadas = str_replace(",", " ", $coordenadas);
$punto = 'POINT(' . $cadenaCoordenadas . ')';

// Incluimos la librería de funciones
include 'libreria.php';

// Insertar el reclamo en la base de datos
$sql = "INSERT INTO public.reclamos_vecinos (id_vecino, n_reclamo, tipo, mensaje, imagen, fecha, id_distrito, geom) 
        VALUES ('$id_vecino', '$n_reclamo', '$tipoReclamo', '$detalleReclamo', " . ($imagen ? "'$imagen'" : "NULL") . ", '$fechaReclamo', '$id_distrito', ST_GeomFromText('$punto', 4326))";

// Ejecutamos la consulta
$w = ejecutar($sql);

?>
