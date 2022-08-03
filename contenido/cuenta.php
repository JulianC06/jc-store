<?php 
include_once 'conexion.php';
session_start();

$identificacion_usr = $_SESSION['usuario'];

if ( !isset( $identificacion_usr ) ) {
    header( "location:login.php" );
}

if(!$_GET){
    header("location:cuenta.php?ops=inf"); 
}


$sql_usr="SELECT * FROM usuarios WHERE n_identificacion=$identificacion_usr";
$preparar_usr = $conn->prepare( $sql_usr );
$preparar_usr->execute();
$datos_usr = $preparar_usr->fetchAll();

foreach($datos_usr as $user){
    $nombre_usr=$user['nombre']." ".$user['apellido'];
    $correo_usr=$user['correo_electronico'];
    $telefono_usr=$user['telefono'];
}

//$num_usuarios = $preparar_usuario->rowCount();

if(isset($_POST['cambiar-datos'])){
    if(empty($_POST['ncorreo'])||empty($_POST['ntelefono'])){
        echo '<script language="javascript">alert("Error: no es posible agregar. Hay campos vacíos.");</script>';
    }else{
        $telefono=$_POST['ntelefono'];
        $correo=$_POST['ncorreo'];
        $sql_aggd="UPDATE usuarios SET telefono='$telefono', correo_electronico='$correo' WHERE n_identificacion=$identificacion_usr";
        $preparar_aggd=$conn->prepare( $sql_aggd );
        $preparar_aggd->execute();
        echo '<script language="javascript">alert("Datos actualizados correctamente.");</script>';
    }
}else if(isset($_POST['cambiar-contra'])){
    if(empty($_POST['anc'])||empty($_POST['nuc'])){
        echo '<script language="javascript">alert("Error: no es posible actualizar. Hay campos vacíos.");</script>';
    }else{
        $ant=$_POST['anc'];
        $nuv=$_POST['nuc'];
        $sql_acc="SELECT actualizar_contra('$identificacion_usr','$nuv','$ant')";
        $preparar_acc=$conn->prepare( $sql_acc );
        $preparar_acc->execute();
        $resultado=$preparar_acc->fetch( PDO::FETCH_NUM );
        $validar=$resultado[0];
        
        if($validar==1){
            echo '<script language="javascript">alert("La contraseña se ha actualizado correctamente.");</script>';
        }else{
            echo '<script language="javascript">alert("Error: La contraseña ingresada es la misma.");</script>';
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/estilos.css">
    <script src="https://kit.fontawesome.com/e92982434c.js" crossorigin="anonymous"></script>
    <title>Cuenta</title>
</head>

<body>
    <header class="barra-menu">
        <div class="div para titulo">aqui va un titulo con mensaje</div>
        <div class="logo-place"><img src="../assets/logo1.png" alt="logo_tienda"></div>
        <div class="div para menu">

            <nav class="menu">
                <ul>
                    <li><a href="../index.html"><i class="fas fa-home"></i>Inicio</a></li>
                    <li><a href="productos.php?g=hombre"><i class="fas fa-male"></i>Hombre</a></li>
                    <li><a href=""><i class="fas fa-female"></i>Mujer</a></li>
                    <li><a href=""><i class="fas fa-child"></i>Niño</a></li>
                    <li><a href=""><i class="fas fa-child"></i>Niña</a></li>
                    <li><a href=""><i class="far fa-heart"></i>Favoritos</a></li>
                    <li><a href=""><i class="fas fa-shopping-cart"></i>Carrito</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="contiene">
        <div class="opciones">

            <nav class="menu">
                <ul class="ul1">
                    <li><a href="cuenta.php?ops=inf">Información</a></li>
                    <li><a href="cuenta.php?ops=comp">Compras</a></li>
                    <li><a href="cuenta.php?ops=cco">Cambiar contraseña</a></li>
                    <li><a href="salir.php">Cerrar sesión</a></li>

                </ul>
            </nav>

        </div>

        <div class="informacion">

            <?php if($_GET['ops']=='inf'): ?>
            <div class="info-usuario">

                <h3>Nombre: <?php echo $nombre_usr ?> </h3>
                <h3>Correo: <?php echo $correo_usr ?></h3>
                <h3>Télefono: <?php echo $telefono_usr ?></h3>
                <a href="cuenta.php?ops=mod">Modificar</a>

            </div>
            <?php endif ?>




            <?php if($_GET['ops']=='mod'): ?>

            <div>
                <h1>Modificar datos</h1>
                <form method="post">
                    Correo: <input type="text" name="ncorreo">
                    Telefono: <input type="text" name="ntelefono">
                    <button name="cambiar-datos">Modificar</button>
                </form>
            </div>
            <?php endif ?>

            <?php if($_GET['ops']=='cco'): ?>
            <div>
                <h1>Modificar contraseña</h1>
                <form method="post">
                    Contraseña anterior: <input type="text" name="anc">
                    Nueva contraseña: <input type="text" name="nuc">
                    <button name="cambiar-contra">Modificar</button>
                </form>
            </div>
            <?php endif ?>

            <div class="compras-usuario"></div>
            <div class="datos-"></div>
        </div>
    </section>

</body>

</html>
