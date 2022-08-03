<?php

include_once 'conexion.php';

$nombre_producto = "";
if ( !isset( $_GET['np'] ) ) {
    HEADER( 'Location:../index.html' );
    //Forzamos ingresar siempre
} else {
    $nombre_producto = $_GET['np'];
}

$sql_productos = "SELECT * FROM productos WHERE nombre_producto='$nombre_producto'";
$preparar_productos = $conn->prepare( $sql_productos );
$preparar_productos->execute();
$datos_productos = $preparar_productos->fetchAll();

if ( isset( $_POST['comprar'] ) ) {

    if ( empty( $_POST['color'] ) || empty( $_POST['talla'] ) ) {
        echo '<script language="javascript">alert("Error: no seleccionó correctamente.");</script>';

    } else {

    }
}




if ( isset( $_POST['validar'] ) ){
    
    if(empty($_POST['color'])|| empty($_POST['talla'])){
        echo '<script language="javascript">alert("Error: no seleccionó correctamente.");</script>';
    }else{
        /*Obtener id de la talla, el color y el producto*/

$nombre_color=$_POST['color'];
        echo $nombre_color;
$nombre_talla=$_POST['talla'];
        echo $nombre_talla;
$sql_dp = "SELECT p.id_producto, id_talla, id_color FROM productos p, tallas t, colores c
WHERE p.id_producto=t.id_producto
AND p.id_producto=c.id_producto
AND nombre_producto='$nombre_producto'
AND c.nombre_color='$nombre_color'
AND t.nombre_talla='$nombre_talla'";
$preparar_dp = $conn->prepare($sql_dp);
$preparar_dp->execute();
$datos_dp=$preparar_dp->fetch( PDO::FETCH_NUM );
$idpr=$datos_dp[0];
$idtp=$datos_dp[1];
$idcp=$datos_dp[2];
                    
/*Obtener el máximo de unidades del producto que puede comprar el cliente*/
$sql_disponibilidad = "SELECT cant_unidades($idpr,$idtp,$idcp)";
$preparar_disponibilidad = $conn->prepare( $sql_disponibilidad );
$preparar_disponibilidad->execute();
$dato_obtenido = $preparar_disponibilidad->fetch( PDO::FETCH_NUM );
$unidades_disponibles=$dato_obtenido[0];
        
    if($unidades_disponibles>0){
        echo '<script language="javascript">alert("El producto cuenta con unidades disponibles. Puede hacer clic en comprar.");</script>';
    }else{
        echo '<script language="javascript">alert("Esta combinación no cuenta con unidades disponibles. Intente con otra.");</script>';
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
    <title>Productos</title>
</head>

<body>

    <!-- HEADER -->

    <header class="barra-menu">
        <div class="div para titulo">aqui va un titulo con mensaje</div>
        <div class="logo-place"><img src="../assets/logo1.png" alt="logo_tienda"></div>
        <div class="div para menu">

            <nav class="menu">
                <ul>
                    <li class="activado"><a href="../index.html"><i class="fas fa-home"></i>Inicio</a></li>
                    <li><a href="productos.php?g=hombre"><i class="fas fa-male"></i>Hombre</a>

                        <div class="sub-menu1">

                            <ul>
                                <li><a href="">Prueba1</a></li>
                                <li><a href="">Prueba2</a></li>
                                <li><a href="">Prueba3</a></li>
                            </ul>

                        </div>

                    </li>
                    <li><a href=""><i class="fas fa-female"></i>Mujer</a></li>
                    <li><a href=""><i class="fas fa-child"></i>Niño</a></li>
                    <li><a href=""><i class="fas fa-child"></i>Niña</a></li>
                    <li><a href=""><i class="far fa-heart"></i>Favoritos</a></li>
                    <li><a href=""><i class="fas fa-shopping-cart"></i>Carrito</a></li>
                    <li><a href=""><i class="fas fa-sign-in-alt"></i>Ingresar</a></li>
                    <li><a href=""><i class="fas fa-user-circle"></i>Cuenta</a></li>
                </ul>
            </nav>



        </div>
    </header>

    <main class="contenido">

        <section>

            <?php
$nombrep = $_GET['np'];

$sql_img = "SELECT MIN(fecha_subida), url_imagen FROM imagenes i, productos p WHERE i.id_producto=p.id_producto
            AND nombre_producto='$nombrep'";
$preparar_img = $conn->prepare( $sql_img );
$preparar_img->execute();
$dato_obtenido = $preparar_img->fetch( PDO::FETCH_NUM );
$img = $dato_obtenido[1];

?>

            <div class="cont-imagen">
                <img src="<?php echo $img ?>" alt="img producto">
            </div>





            <div class="datos_producto">



                <?php foreach ( $datos_productos as $productos ): ?>

                <h1><?php echo $nombre_producto ?></h1>

                <?php
                $precio = $productos['precio_producto'];
                $descuento = $productos['descuento'];
                $descuento_total = $precio-( $precio*$descuento );
                                ?>

                <?php if ( $descuento != 0 ): ?>

                <h2 class="descuento1">Antes: <del><?php echo 'COP '.$precio ?></del></h2>

                <h2>Ahora: <?php echo 'COP '.$descuento_total ?></h2>

                <?php else: ?>

                <h2><?php echo 'COP '.$precio ?></h2>

                <?php endif ?>

                <h3>Descripción: <br><?php echo $productos['descripcion_producto'] ?></h3>

                <?php endforeach ?>


                <form method="post">
                    <div class="box">
                        <select name="color">
                            <option value="">Seleccione un color</option>
                            <?php

$sql_colores = "SELECT nombre_color FROM colores c, productos p
                    WHERE c.id_producto=p.id_producto
                    AND nombre_producto='$nombre_producto'";
$preparar_colores = $conn->prepare( $sql_colores );
$preparar_colores->execute();
$datos_colores = $preparar_colores->fetchAll();

foreach ( $datos_colores as $colores ) {
    echo'<option value="'.$colores['nombre_color'].'">'.$colores['nombre_color'].'</option>';
}
?>
                        </select>
                    </div>
                    <div class="box">
                        <select name="talla">
                            <option value="">Seleccione una talla</option>
                            <?php

$sql_tallas = "SELECT distinct nombre_talla FROM tallas t, productos p
                    WHERE t.id_producto=p.id_producto
                    AND nombre_producto='$nombre_producto'";
$preparar_tallas = $conn->prepare( $sql_tallas );
$preparar_tallas->execute();
$datos_tallas = $preparar_tallas->fetchAll();

foreach ( $datos_tallas as $tallas ) {
    echo'<option value="'.$tallas['nombre_talla'].'">'.$tallas['nombre_talla'].'</option>';
}
?>
                        </select>
                    </div>



                    <?php 
                    
                   

                    
                    ?>
                    <button name="validar" type="submit">Validar</button>
                    Unidades a comprar: <input type="number" name="unidades_comprar" min="1" max="<?php echo $unidades_disponibles ?>">
                    <br>


                    <button name="comprar" type="submit">Comprar</button>

                </form>


            </div>


        </section>

    </main>

    <footer>FOOOTER</footer>
</body>

</html>
