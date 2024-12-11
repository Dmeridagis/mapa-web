<?php

include 'prueba.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit;
}

// Obtener datos del administrador
$query_profile = $conn->prepare("SELECT nombre_usuario FROM administradores WHERE id = ?");
$query_profile->execute([$admin_id]);

if ($query_profile->rowCount() > 0) {
    $fetch_profile = $query_profile->fetch(PDO::FETCH_ASSOC);
} else {
    $fetch_profile = ['nombre_usuario' => 'Usuario desconocido']; // Valor predeterminado
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tablero</title>
   <link rel="icon" href="images/favicon.png" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/super.css">

</head>
<body>

<?php include '../super/admin_header.php'; ?>

<section class="dashboard">

   <h1 class="heading">Tablero</h1>

   <div class="box-container">

      <div class="box">
         <h3>Bienvenido!</h3>
         <p><?= htmlspecialchars($fetch_profile['nombre_usuario']); ?></p>
         <a href="actu_perfil.php" class="btn">Actualizar Perfil</a>
      </div>

      <div class="box">
         <?php
            $select_users = $conn->prepare("SELECT * FROM `usuarios`");
            $select_users->execute();
            $number_of_users = $select_users->rowCount()
         ?>
         <h3><?= $number_of_users; ?></h3>
         <p>Usuarios Clientes</p>
         <a href="users_cuentas.php" class="btn">Ver Usuarios</a>
      </div>
      <div class="box">
         <?php
            $select_admins = $conn->prepare("SELECT * FROM `administradores`");
            $select_admins->execute();
            $number_of_admins = $select_admins->rowCount()
         ?>
         <h3><?= $number_of_admins; ?></h3>
         <p>Administradores</p>
         <a href="admin_cuentas.php" class="btn">Ver admins</a>
      </div>


   </div>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>