<?php
include '../super/prueba.php';

session_start();

// Verificar si ya hay una sesión iniciada
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

if (isset($_POST['submit'])) {

   // Recoger los datos del formulario
   $name = $_POST['reg_usuario'];  // Cambié de 'name' a 'usuario'
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = $_POST['reg_clave'];  // Cambié de 'pass' a 'clave'
   $cpass = $_POST['reg_cpass']; // Confirmar contraseña

   // Nuevos campos: DNI, Fecha de nacimiento y Domicilio
   $dni = $_POST['reg_dni'];
   $dni = filter_var($dni, FILTER_SANITIZE_STRING);
   $fecha_nac = $_POST['reg_fechanac'];
   $domicilio = $_POST['reg_direccion'];

   // Validar DNI (solo números, 8 caracteres)
   if (!preg_match("/^[0-9]{8}$/", $dni)) {
      $message[] = 'El DNI debe ser de 8 dígitos numéricos';
   }

   // Validar la fecha de nacimiento (formato YYYY-MM-DD)
   $fecha_regex = "/^\d{4}-\d{2}-\d{2}$/";
   if (!preg_match($fecha_regex, $fecha_nac)) {
      $message[] = 'Fecha de nacimiento debe ser en formato: YYYY-MM-DD';
   }

   // Verificar si el nombre de usuario ya está registrado
   $select_user = $conn->prepare("SELECT * FROM `usuarios` WHERE usuario = ?");
   $select_user->execute([$name]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if ($select_user->rowCount() > 0) {
      $message[] = 'Este nombre de usuario ya fue usado';
   } else {
      // Verificar si las contraseñas coinciden
      if ($pass != $cpass) {
         $message[] = 'Las contraseñas no coinciden';
      } else {
         // Insertar usuario en la base de datos sin encriptar la contraseña
         $insert_user = $conn->prepare("INSERT INTO `usuarios`(usuario, clave, dni, fechanac, domicilio) VALUES(?, ?, ?, ?, ?)");
         $insert_user->execute([$name, $pass, $dni, $fecha_nac, $domicilio]);

         // Mensaje de éxito
         $message[] = '¡Registro completado, Inicia Sesión!';

         // Redirigir al usuario al formulario después de un registro exitoso
         header('Location: ../formulario.html'); // Redirigir a formulario.html
         exit(); // Asegurarse de que no se ejecute el código posterior
      }
   }
}
?>




