<?php

include 'prueba.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admins = $conn->prepare("DELETE FROM `administradores` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:admin_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cuentas Admins</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/super.css">

</head>
<body>

<?php include '../super/admin_header.php'; ?>

<section class="accounts">

   <h1 class="heading">Cuentas de Administradores</h1>

   <div class="box-container">

   <div class="box">
      <p>Agregar Administrador</p>
      <a href="admin_registro.php" class="option-btn">Crear Admin</a>
   </div>

   <?php
      $select_accounts = $conn->prepare("SELECT * FROM `administradores`");
      $select_accounts->execute();
      if($select_accounts->rowCount() > 0){
         while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){   
   ?>
   <div class="box">
      <p> Admin ID : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> Admin Nombre : <span><?=htmlspecialchars($fetch_profile['nombre_usuario']); ?></span> </p> 
      <div class="flex-btn">
         <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('¿eliminar esta cuenta?')" class="delete-btn">Eliminar</a>
         <?php
            if($fetch_accounts['id'] == $admin_id){
               echo '<a href="actu_perfil.php" class="option-btn">Actualizar</a>';
            }
         ?>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">¡no hay cuentas!</p>';
      }
   ?>

   </div>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>