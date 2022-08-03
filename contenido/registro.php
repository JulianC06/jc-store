<?php
include_once 'conexion.php';
//session_start();
/*
if ( isset( $_SESSION['usuario'] ) || isset( $_SESSION['administrador'] ) ) {

    echo '<script language="javascript">alert("Error:Ya ha iniciado sesión.");window.location.href="productos.php?g=hombre"</script>';

} else {
    */
    if ( isset( $_POST['agregar-usuario'] ) ) {
        if ( empty( $_POST['nombreu'] ) || empty( $_POST['apellidou'] ) || empty( $_POST['correou'] ) || empty( $_POST['contrau'] ) || empty( $_POST['telefonou'] ) || empty( $_POST['identificacionu'] ) ) {
            echo '<script language="javascript">alert("Error: no es posible agregar. Hay campos vacíos.");</script>';
        }else{
            $nombreu = $_POST['nombreu'];
            $apellidou = $_POST['apellidou'];
            $correou = $_POST['correou'];
            $contrau = $_POST['contrau'];
            $telefonou = $_POST['telefonou'];
            $identificacionu = $_POST['identificacionu'];

            $sql_usr = "SELECT correo_electronico FROM usuarios WHERE correo_electronico='$correou'";
            $preparar_usr = $conn->prepare( $sql_usr );
            $preparar_usr->execute();
            $num_resultados = $preparar_usr->rowCount();
            if($num_resultados>0){
                echo '<script language="javascript">alert("Error:La dirección de correo ya está registrada.")';
            }else{
                $sql_insertar = "SELECT agregar_usuario('$identificacionu','$nombreu','$apellidou','$telefonou','$correou','$contrau')";
                $preparar_insertar = $conn->prepare( $sql_insertar );
                $preparar_insertar->execute();
                $resultado = $preparar_insertar->fetch( PDO::FETCH_NUM );
                $agg = $resultado[0];
                if ( $agg == 1 ) {
                    echo '<script language="javascript">alert("Usuario registrado correctamente.Ahora inicie sesión.")';
                   header( "location:login.php" );
                }else{
                     echo '<script language="javascript">alert("Error inesperado, reporte a un administrador admin@test.com.")';
                }
            }
        }
    }


    ?>
<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/estilos.css">
    <script src="https://kit.fontawesome.com/e92982434c.js" crossorigin="anonymous"></script>
    <title>registro</title>
</head>

<body>
    <div>
        <h1>Agregar usuario</h1>
        <form method="post">
            Correo: <input type="text" name="correou">
            Identificacion: <input type="text" name="identificacionu">
            Nombre: <input type="text" name="nombreu">
            Apellido: <input type="text" name="apellidou">
            Telefono: <input type="text" name="telefonou">
            Contraseña: <input type="password" name="contrau">
            <button name="agregar-usuario">Modificar</button>
        </form>
    </div>
</body>

</html>
