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

// Consulta para obtener los datos del usuario (nombre y correo)
$sql_usuario = "SELECT usuario, email FROM public.usuarios WHERE id = '$id_vecino'";
$result_usuario = pg_query($cnx, $sql_usuario);

if (!$result_usuario || pg_num_rows($result_usuario) == 0) {
    die("Error al obtener los datos del usuario: " . pg_last_error($cnx));
}

$usuario = pg_fetch_assoc($result_usuario);

// Consulta para obtener los reclamos del usuario actual
$sql = "SELECT tipo, mensaje, fecha, imagen, n_reclamo FROM public.reclamos_vecinos WHERE id_vecino = '$id_vecino'";
$result = pg_query($cnx, $sql);

if (!$result) {
    die("Error en la consulta: " . pg_last_error($cnx));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="perfil.css">
    <script src="https://kit.fontawesome.com/0273d565ab.js" crossorigin="anonymous"></script>
    <title>Mi perfil</title>
</head>
<body>
    <header>
        <h1 id="logo">MUNI</h1>
        <nav>
            <input type="checkbox" name="check" id="check">
            <label for="check" class="checkbtn">
                <i class="fa-solid fa-bars"></i>
            </label>
            <ul>
                <li><a href="perfil.php">Perfil</a></li>
                <li><a href="perfil.php">Reclamos</a></li>
                <li><a href="../pageuser.php">Mapa</a></li>
                <li><a href="#">Inicio</a></li>
            </ul>
        </nav>
    </header>
    
    <!-- Sección de información del usuario -->
    <div class="contenedor">
        <section class="userinfo">
            <h2>Información del perfil</h2>
            <p><strong>Nombre de usuario: <br></strong> <?php echo htmlspecialchars($usuario['usuario']); ?></p>
            <p><strong>Correo: <br></strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <button onclick="window.location.href='../report/cerrar.php';" id="cerrarbtn">Cerrar Sesión</button>
        </section>

        <div class="listrecla">
            <h2>Lista de Reclamos</h2>
            <div class="stop">
            <button class="tiprecla1">Enviados</button>
            <button class="tiprecla2">En Proceso</button>
            <button class="tiprecla3">Resueltos</button>
            </div>
            <div class="scroll-table">
            <table class="reclamos">
                <thead>
                <tr>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th>Imagen</th>
                </tr>
                </thead>
                <?php
                // Verificar si hay resultados
                if (pg_num_rows($result) > 0) {
                    while ($row = pg_fetch_assoc($result)) {
                        $mensajeTipo = htmlspecialchars($row['tipo']);
                        $mensaje = htmlspecialchars($row['mensaje']);
                        $n_reclamo = htmlspecialchars($row['n_reclamo']);
                        $fecha = date('Y-m-d H:i', strtotime($row['fecha']));
                        $imagen = $row['imagen'] ? 'data:image/jpeg;base64,' . base64_encode(pg_unescape_bytea($row['imagen'])) : '';

                        echo "<tr>";
                        echo "<td class='clickable' onclick=\"mostrarModal('$mensaje', '$fecha', '$imagen','$n_reclamo')\">$mensajeTipo</td>";
                        echo "<td>$fecha</td>";
                        echo "<td>";
                        if ($row['imagen']) {
                            echo "<img src='$imagen' alt='Imagen' width='100'>";
                        } else {
                            echo "No hay imagen";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No hay reclamos registrados</td></tr>";
                }
                desconectar();
                ?>
            </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="miModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <div>
            <p><strong>Mensaje:</strong> <span id="mensajeModal"></span></p>
            <p><strong>Fecha:</strong> <span id="fechaModal"></span></p>
            <p><strong>N°Reclamo:</strong> <span id="numeroModal"></span></p>
            </div>
            <div class="imgmodal">
            <img id="imagenModal" src="" alt="Imagen del reclamo" style="display: none; width: 155px;">
            </div>
        </div>
    </div>
    
    <script>
        function mostrarModal(mensaje, fecha, imagen,n_reclamo) {
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


