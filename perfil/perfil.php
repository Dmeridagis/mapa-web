<?php
session_start(); // Iniciar la sesión para acceder al id_vecino

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['id_vecino'])) {
    echo "<script>alert('Por favor inicie sesión para ver sus reclamos.');
    document.location.href='../login.html';</script>";
    exit();
}

include '../report/libreria.php'; // Incluir el archivo de conexión

// Conectar a la base de datos
conectar();

$id_vecino = $_SESSION['id_vecino']; // Obtener el ID del usuario desde la sesión

// Consulta para obtener los datos del usuario (nombre, correo, etc.)
$sql_usuario = "SELECT usuario, email, dni, direccion, fechanac FROM public.usuarios WHERE id = $1";
$result_usuario = pg_query_params($cnx, $sql_usuario, array($id_vecino));

if (!$result_usuario || pg_num_rows($result_usuario) == 0) {
    die("Error al obtener los datos del usuario: " . pg_last_error($cnx));
}

$usuario = pg_fetch_assoc($result_usuario);

// Consulta para obtener los reclamos del usuario actual
$sql = "SELECT tipo, mensaje, fecha, imagen, n_reclamo FROM public.reclamos_vecinos WHERE id_vecino = $1";
$result = pg_query_params($cnx, $sql, array($id_vecino));

if (!$result) {
    die("Error en la consulta: " . pg_last_error($cnx));
}

// Procesar los datos enviados por el formulario si se está guardando
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $direccion = htmlspecialchars($_POST['direccion']);

    // Construir la consulta de actualización
    $sql_update = "UPDATE public.usuarios SET email = $1, direccion = $2 WHERE id = $3";
    $result_update = pg_query_params($cnx, $sql_update, array($email, $direccion, $id_vecino));

    if (!$result_update) {
        die("Error al actualizar los datos: " . pg_last_error($cnx));
    }

    echo "<script>alert('Perfil actualizado correctamente.'); window.location.href = 'perfil.php';</script>";
    desconectar();
    exit();
}

desconectar();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/login.css">
    <script src="https://kit.fontawesome.com/0273d565ab.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top navbar-custom" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <img src="../assets/img/logomaipu.png" class="logoimg2">
            <h1 class="nav-logo2">MUNI</h1>
        </div>
    </div>
</div>

<div class="contenedor">
    <section class="userinfo">
        <div class="datos">
            <h2>Información del perfil</h2>

            <form id="formEditarPerfil" method="POST">
                <p>
                    <strong>Nombre de usuario: </strong>
                    <span id="usuarioNombre"><?php echo htmlspecialchars($usuario['usuario']); ?></span>
                </p>
                <p>
                    <strong>Correo: </strong>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled>
                </p>
                <p>
                    <strong>Domicilio: </strong>
                    <input type="text" name="direccion" id="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>" disabled>
                </p>
                <p>
                    <strong>DNI: </strong>
                    <span><?php echo htmlspecialchars($usuario['dni']); ?></span>
                </p>
                <p>
                    <strong>Fecha de nacimiento: </strong>
                    <span><?php echo htmlspecialchars($usuario['fechanac']); ?></span>
                </p>
                <button type="button" id="btnEditar" onclick="habilitarEdicion()">Editar Perfil</button>
                <button type="submit" id="btnGuardar" style="display: none;">Guardar Cambios</button>
            </form>
            <button onclick="window.location.href='../report/cerrar.php';" id="cerrarbtn">Cerrar Sesión</button>
        </div>
    </section>

    <div class="listrecla">
        <h2>Lista de Reclamos</h2>
        <div class="stop">
            <button class="tiprecla1">Enviados</button>
            <button class="tiprecla2">En Proceso</button>
            <button class="tiprecla3">Resueltos</button>
        </div>
        
        <div class="reclamos-container">
            <?php
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_assoc($result)) {
                    $mensajeTipo = htmlspecialchars($row['tipo']);
                    $mensaje = htmlspecialchars($row['mensaje']);
                    $n_reclamo = htmlspecialchars($row['n_reclamo']);
                    $fecha = date('Y-m-d H:i', strtotime($row['fecha']));
                    $imagen = $row['imagen'] ? 'data:image/jpeg;base64,' . base64_encode(pg_unescape_bytea($row['imagen'])) : '';

                    echo "<div class='card-reclamo'>";
                    echo "<h3>$mensajeTipo</h3>";
                    echo "<p><strong>Fecha y hora:</strong> $fecha</p>";
                    echo "<p>$mensaje</p>";
                    if ($row['imagen']) {
                        echo "<img src='$imagen' alt='Imagen' class='reclamo-img'>";
                    } else {
                        echo "<p>No hay imagen</p>";
                    }
                    
                    echo "<button class='btn-detalle' onclick=\"mostrarModal('$mensaje', '$fecha', '$imagen', '$n_reclamo')\">Ver Detalles</button>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay reclamos registrados</p>";
            }
            ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="miModal" class="modal2" style="display: none;">
    <div class="modal-content2">
        <span class="close2" onclick="cerrarModal()">&times;</span>
        <div>
            <p><strong>Mensaje:</strong> <span id="mensajeModal"></span></p>
            <p><strong>Fecha y hora:</strong> <span id="fechaModal"></span></p>
            <p><strong>N°Reclamo:</strong> <span id="numeroModal"></span></p>
        </div>
        <div class="imgmodal2">
            <img id="imagenModal" src="" alt="Imagen del reclamo" style="display: none; width: 155px;">
        </div>
    </div>
</div>

<script>
function habilitarEdicion() {
    document.getElementById('email').disabled = false;
    document.getElementById('direccion').disabled = false;

    document.getElementById('btnEditar').style.display = 'none';
    document.getElementById('btnGuardar').style.display = 'inline-block';
}

function mostrarModal(mensaje, fecha, imagen, n_reclamo) {
    document.getElementById('mensajeModal').innerText = mensaje;
    document.getElementById('fechaModal').innerText = fecha;
    document.getElementById('numeroModal').innerText = n_reclamo;

    const imagenElemento = document.getElementById('imagenModal');
    if (imagen) {
        imagenElemento.src = imagen;
        imagenElemento.style.display = 'block';
    } else {
        imagenElemento.style.display = 'none';
    }

    document.getElementById('miModal').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('miModal').style.display = 'none';
}
</script>
</body>
</html>
