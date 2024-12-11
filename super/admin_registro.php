<?php

include 'prueba.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
   $role = $_POST['role'];
   $role = filter_var($role, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `administradores` WHERE nombre_usuario = ?");
   $select_admin->execute([$name]);

   if($select_admin->rowCount() > 0){
      $message[] = '¡ya existe este usuario!';
   }else{
      if($pass != $cpass){
         $message[] = '¡Las contraseñas no coinciden!';
      }else{
         $insert_admin = $conn->prepare("INSERT INTO `administradores`(nombre_usuario, clave, tipo_rol) VALUES(?,?,?)");
         $insert_admin->execute([$name, $cpass, $role]);
         $message[] = '¡nuevo administrador creado existosamente!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>MuniWeb</title>
   <link rel="icon" href="images/icon.png" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/super.css">

</head>
<body>

<?php include '../super/admin_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Crear Admin</h3>
      <input type="text" name="name" required placeholder="ingresar usuario" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="ingresar contraseña" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="confirmar contraseña" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <!-- Selección de tipo de rol -->
      <select name="role" required class="box">
         <option value="" disabled selected>Seleccionar rol</option>
         <option value="quejas">Reclamos</option>
         <option value="localizaciones">Localizaciones</option>
      </select>

      <input type="submit" value="Registrar" class="btn" name="submit">
   </form>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>