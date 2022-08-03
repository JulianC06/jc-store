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

$sql_disponibilidad = "SELECT cant_unidades(1,73,61)";
$preparar_disponibilidad = $conn->prepare( $sql_disponibilidad );
$preparar_disponibilidad->execute();
$dato_obtenido = $preparar_disponibilidad->fetch( PDO::FETCH_NUM );
echo $dato_obtenido[0];

?>
<!DOCTYPE html>
<html lang = "en-US">
<head>
<meta charset = "UTF-8">
<meta name = "viewport" content = "width=device-width, initial-scale=1">
<link rel = "stylesheet" href = "css/estilos.css">
<script src = "https://kit.fontawesome.com/e92982434c.js" crossorigin = "anonymous"></script>
<title>Productos</title>
</head>
<body>

<!-- HEADER -->
<header class = "aaa">
<div class = "div para titulo">aqui va un titulo con mensaje</div>
<div class = "div para menu">

<nav class = "menu">
<ul>
<li><a href = "../index.html">Inicio</a></li>
<li><a href = "productos.php?g=hombre">Hombre</a></li>
<li><a href = "">Mujer</a></li>
<li><a href = "">Niño</a></li>
<li><a href = "">Niña</a></li>
</ul>
</nav>

<nav class = "menu-iconos">
<ul>
<li><a href = "">Carrito</a></li>
<li><a href = "">Iniciar sesión</a></li>
<li><a href = "">Buscar</a>
<div>Div para el buscador</div>
<i class = "fas fa-search"><a href = "">Buscar</a></i>
</li>
<li><a href = "">Cuenta</a></li>
</ul>
</nav>

</div>
</header>

<main>
<container>
<div class = "datos_producto">

<?php foreach ( $datos_productos as $productos ): ?>
<h1><?php echo $nombre_producto ?></h1>

<p><?php
$precio = $productos['precio_producto'];
$descuento = $productos['descuento'];
$descuento_total = $precio-( $precio*$descuento );
if ( $descuento != 0 ) {
    echo "<del>Antes:$ $precio COP</del>";
    echo "<p>Ahora:$ $descuento_total COP</p>";
} else {
    echo "<p>$ $precio COP</p>";
}
?>

<p><?php echo $productos['descripcion_producto'] ?></p>
<?php endforeach ?>

</div>
<img src = "" alt = "img producto">

<form method = "post" action = "comprar.php">

<select name = "color">
<option value = "">Seleccione un color</option>
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

<select name = "talla">
<option value = "">Seleccione una talla</option>
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

Unidades a comprar: <input type = "number" name = "unidades_comprar" min = "1" max = "100">

<button name = "comprar" type = "submit">Comprar</button>

</form>

</container>
</main>

<footer>FOOOTER</footer>
</body>
</html>