<?php
$codigo = isset($_POST['txtcodigo']) ? $_POST['txtcodigo'] : '';
$dto = isset($_POST['dto']) ? $_POST['dto'] : '';
$n_ctro = isset($_POST['n_ctro']) ? $_POST['n_ctro'] : '';
$estratif = isset($_POST['estratif']) ? $_POST['estratif'] : '';
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$domicilio = isset($_POST['domicilio']) ? $_POST['domicilio'] : '';
$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
$geom = isset($_POST['geom']) ? $_POST['geom'] : '';
$punto = 'POINT(' . str_replace(array('[', ']', ','), array('', '', ' '), $geom) . ')';

include 'libreria.php';

// Mensajes de depuración para verificar que se reciben los datos
echo "Datos recibidos:<br>";
echo "Código: $codigo<br>";
echo "DTO: $dto<br>";
echo "N° Centro: $n_ctro<br>";
echo "Estratificación: $estratif<br>";
echo "Nombre: $nombre<br>";
echo "Domicilio: $domicilio<br>";
echo "Teléfono: $telefono<br>";
echo "Geom: $geom<br>";
echo "Punto: $punto<br>";
// die(); // Puedes comentar esto una vez que hayas verificado los datos

if ($codigo === '' || $codigo == 0) {
    $sql = "INSERT INTO public.centros_de_salud (dto, n_ctro, estratif, nombre, domicilio, telefono, geom)
            VALUES ('$dto', '$n_ctro', '$estratif', '$nombre', '$domicilio', '$telefono', ST_GeomFromText('$punto', 4326))";
} else {
    $sql = "UPDATE public.centros_de_salud
            SET dto='$dto', n_ctro='$n_ctro', estratif='$estratif', nombre='$nombre', domicilio='$domicilio', telefono='$telefono', geom=ST_GeomFromText('$punto', 4326)
            WHERE id=$codigo";
}

echo "Consulta SQL: " . $sql; // Mostrar la consulta SQL para depuración
$w = ejecutar($sql);

if ($w) {
    echo "Registro actualizado exitosamente";
} else {
    echo "Error al actualizar el registro: " . pg_last_error($cnx);
}
?>
