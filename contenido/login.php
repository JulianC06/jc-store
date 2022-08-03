<?php
    include_once 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login/registro</title>
</head>
<body>
   
   <form action="validar-login.php" method="post">
       Correo:<input type="text" name="correo">
       Contraseña:<input type="password" name="contra">
       <button type="submit">Ingresar</button>
   </form>
    
</body>
</html>