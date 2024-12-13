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
$sql_usuario = "SELECT usuario, email, dni, direccion, fechanac FROM public.usuarios WHERE id = '$id_vecino'";
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#000000">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="css/bootstrap.min.css">  <!--HEADER-->
    <link rel="stylesheet" href="../css/login.css">
    <script src="https://kit.fontawesome.com/0273d565ab.js" crossorigin="anonymous"></script>
    <title>Mi perfil</title>
    <link rel="Icon" href="../assets/img/logomaipu2.png">
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top navbar-custom" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
            <img src="../assets/img/logomaipu.png" class="logoimg2">
          <h1 class="nav-logo2">MUNI</h1>

          <label for="menu" class="nav-label">
             <i class="fa-solid fa-bars"></i>
          </label>
          <input type="checkbox" class="nav-input" id="menu">

        <div class="nav-menu">

        <li class="nav-item">
            <a href="perfil.php">
              <i class="fa-solid fa-user"></i>&nbsp;&nbsp;Perfil
            </a>
          </li>

          <li class="nav-item">
              <a href="#">
              <i class="fa-solid fa-envelope"></i>&nbsp;&nbsp;Reclamos
              </a>
          </li>

          <li class="nav-item">
              <a href="../pageuser.php" >
              <i class="fa-solid fa-map"></i>&nbsp;&nbsp;Mapa
               </a>
          </li>
          </div>

        </div>
      </div>
    </div>
    
    <!-- Sección de información del usuario -->
    <div class="contenedor">
        <section class="userinfo">
            <div class="datos">
            <h2>Información del perfil</h2>
            <img src="../assets/img/perfiluser.png" alt="">
            <p><strong>Nombre de usuario: <br></strong> <?php echo htmlspecialchars($usuario['usuario']); ?></p>
            <p><strong>Correo: <br></strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p><strong>Domicilio: <br></strong> <?php echo htmlspecialchars($usuario['direccion']); ?></p>
            <p><strong>DNI: <br></strong> <?php echo htmlspecialchars($usuario['dni']); ?></p>
            <p><strong>Fecha de nacimiento: <br></strong> <?php echo htmlspecialchars($usuario['fechanac']); ?></p>
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
    desconectar();
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


