<?php

include_once 'conexion.php';

$correo = $_POST['correo'];
$contra = $_POST['contra'];

$sql_v = "SELECT correo_electronico,tipo,n_identificacion FROM usuarios WHERE correo_electronico='$correo'";
$preparar_v= $conn->prepare( $sql_v );
$preparar_v->execute();
$dato_v = $preparar_v->fetchAll();
$num_datos = $preparar_v->rowCount();

if($num_datos>0){
    
    $sql_vcontra="SELECT verificar_contra('$contra','$correo')";
    $preparar_vcontra= $conn->prepare( $sql_vcontra );
    $preparar_vcontra->execute();
    $resultado=$preparar_vcontra->fetch( PDO::FETCH_NUM );
    $dato=$resultado[0];
    
    if($dato==1){
        foreach ( $dato_v as $usuario ) {
            $id_usuario = $usuario['n_identificacion'];
            $tipo=$usuario['tipo'];
        }
        
        if($tipo=='usuario'){
            session_start();
            $_SESSION['usuario'] = $id_usuario;
            header( "location:cuenta.php" );
            exit();
        }else{
            session_start();
            $_SESSION['administrador'] = $id_usuario;
            header( "location:pagina-administrador.php" );
            exit();
        }
    }else{
        echo 'Datos incorrectos';
        echo '<a href="login.php">Regresar</a>';
    }
}
/*
if ( $num_usuarios>0 ) {
    session_start();
    $_SESSION['usuario'] = $id_usuario;
    header( "location:cuenta.php" );
    exit();

} else if ( $num_admins>0 ) {
    session_start();
    $_SESSION['administrador'] = $id_administrador;
    header( "location:pagina-administrador.php" );
    exit();

} else {

    echo 'Datos incorrectos';

    //header( "location:login.php" );
    echo '<a href="login.php">Regresar</a>';
}
*/
?>
