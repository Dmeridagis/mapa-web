<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
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
<header class="header">

   <section class="flex">

      <a href="../super/tablero.php" class="logo">Mapa<span>Web</span></a>

      <nav class="navbar">
         <a href="../super/tablero.php">Inicio</a>
         <a href="../super/admin_cuentas.php">Administradores</a>
         <a href="../super/admin_registro.php">Crear Admin</a>
         <a href="../super/users_cuentas.php">Usuarios</a>

      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `administradores` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
        <p><?= htmlspecialchars($fetch_profile['nombre_usuario']); ?></p>
         <a href="../super/actu_perfil.php" class="btn">Actualizar Perfil</a>
         <div class="flex-btn">
            <a href="../super/admin_registro.php" class="option-btn">Registrarse</a>
            <a href="../super/admin_login.php" class="option-btn">Iniciar Sesión</a>
         </div>
         <a href="../super/admin_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">Cerrar Sesión</a> 
      </div>

   </section>

</header>
