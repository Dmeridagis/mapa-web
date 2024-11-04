<?php
$codigo = isset($_POST['codigo']) ? $_POST['codigo'] : '';
$gid = isset($_POST['gid']) ? $_POST['gid'] : '';
$id_segurid = isset($_POST['id_segurid']) ? $_POST['id_segurid'] : '';
$id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : '';
$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$geom = isset($_POST['geom']) ? $_POST['geom'] : '';

// Transformar las coordenadas para asegurarse de que están en el formato correcto
$coordenadas = str_replace(array('POINT(', ')'), '', $geom);
$coordenadas_array = explode(' ', $coordenadas);
$punto = 'POINT(' . $coordenadas_array[1] . ' ' . $coordenadas_array[0] . ')'; // Revertir lat y lon si es necesario

include 'libreria.php';

// // Mensajes de depuración
echo "Datos recibidos:<br>";
echo "Código: $codigo<br>";
echo "GID: $gid<br>";
echo "ID Seguridad: $id_segurid<br>";
echo "ID Usuario: $id_usuario<br>";
echo "Teléfono: $telefono<br>";
echo "Dirección: $direccion<br>";
echo "Tipo: $tipo<br>";
echo "Nombre: $nombre<br>";
echo "Geom: $geom<br>";
echo "Punto: $punto<br>";
// die();

switch ($codigo) {
    case '':
    case '0':
        $sql = "INSERT INTO public.seguridad(gid, id_segurid, id_usuario, telefono, direccion, tipo, nombre, geom)
                VALUES('$gid', '$id_segurid', '$id_usuario', '$telefono', '$direccion', '$tipo', '$nombre', ST_GeomFromText('$punto', 4326))";
        break;
    default:
        $sql = "UPDATE public.seguridad
                SET gid='$gid', id_segurid='$id_segurid', id_usuario='$id_usuario', telefono='$telefono', direccion='$direccion', tipo='$tipo', nombre='$nombre', geom=ST_GeomFromText('$punto', 4326)
                WHERE id=$codigo";
        break;
}

$w = ejecutar($sql);

if ($w == 1) {
    echo "Datos insertados correctamente.";
} else {
    echo "Error al insertar datos: " . pg_last_error($cnx);
}
?>
