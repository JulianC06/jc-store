<?php

include_once 'conexion.php';
session_start();


if ( !isset( $_SESSION['usuario'] ) ) {
}else if(isset($_SESSION['administrador'])){
        echo '<script language="javascript">alert("Error: Los administradores no pueden acceder a esta página.");window.location.href="pagina-administrador.php?g=hombre"</script>';
}
else{
    $identificacion_usr = $_SESSION['usuario'];
    $sql_user="SELECT nombre FROM usuarios WHERE n_identificacion='$identificacion_usr'";
    $preparar_user = $conn->prepare( $sql_user );
    $preparar_user->execute();
    $dato_user = $preparar_user->fetch( PDO::FETCH_NUM );
    $nombre_usuario=$dato_user[0];
}

//Validar que solo puedan comprar usuarios y no administradores
/*
session_start();
$usr = $_SESSION['usuario'];

if ( !isset( $usr ) ) {
    header( "location:login.php" );
}
*/
//-----------------------------------

//Consulta para la apginación
$sql_productos = 'SELECT * FROM productos';
$preparar = $conn->prepare( $sql_productos );
$preparar->execute();
$datos_consulta = $preparar->fetchAll();
//------------------------------

//Formar consulta para los filtros
$condiciones_where = "";

$filtro_genero = "";

if ( !isset( $_GET['g'] ) ) {
    HEADER( 'Location:productos.php?g=hombre' );
    //Forzamos ingresar siempre a la genero como hombre
} else {
    $filtro_genero = $_GET['g'];
}

$filtro_orden = "";
$filtro_categoria = "";
$filtro_color = "";
$filtro_talla = "";

if ( !isset( $_POST['orden'] ) || !isset( $_POST['categoria'] ) || !isset( $_POST['color'] ) || !isset( $_POST['talla'] ) ) {
} else {

    $filtro_orden = $_POST['orden'];
    $filtro_categoria = $_POST['categoria'];
    $filtro_color = $_POST['color'];
    $filtro_talla = $_POST['talla'];

}

if ( isset( $_POST['buscar'] ) ) {

    if ( empty( $_POST['orden'] ) && empty( $_POST['categoria'] ) && empty( $_POST['color'] ) && empty( $_POST['talla'] ) ) {
        echo '<script language="javascript">alert("Error: no seleccionó ningún filtro.");</script>';
    } else if ( empty( $_POST['color'] ) && empty( $_POST['talla'] ) && empty( $_POST['categoria'] ) ) {
        $condiciones_where = "";
    } else if ( empty( $_POST['color'] ) && empty( $_POST['talla'] ) ) {
        $condiciones_where = " AND categoria='".$filtro_categoria."'";
    } else if ( empty( $_POST['categoria'] ) && empty( $_POST['talla'] ) ) {
        $condiciones_where = " AND nombre_color='".$filtro_color."'";
    } else if ( empty( $_POST['categoria'] ) && empty( $_POST['color'] ) ) {
        $condiciones_where = " AND nombre_talla='".$filtro_talla."'";
    } else if ( empty( $_POST['talla'] ) ) {
        $condiciones_where = " AND categoria='".$filtro_categoria."'"." AND nombre_color='".$filtro_color."'";
    } else if ( empty( $_POST['color'] ) ) {
        $condiciones_where = " AND categoria='".$filtro_categoria."'"." AND nombre_talla='".$filtro_talla."'";
    } else if ( empty( $_POST['categoria'] ) ) {
        $condiciones_where = " AND nombre_color='".$filtro_color."'"." AND nombre_color='".$filtro_color."'";
    } else {
        $condiciones_where = " AND categoria='".$filtro_categoria."'"." AND nombre_color='".$filtro_color."'"." AND nombre_talla='".$filtro_talla."'";
    }
}

//Para calculo de la paginación distinct

$productos_pagina = 2;
//Numero de articulos que aparecerán por página

//----------------------------

//$inicio_limit = ( $_GET['p']-1 )*$productos_pagina;
//Operación que calcula el valor inicial para la consulta que usa LIMIT

$sql_busqueda = "SELECT distinct nombre_producto, precio_producto,descuento FROM productos p, colores c, tallas t
WHERE c.id_producto=p.id_producto
AND t.id_producto=p.id_producto AND genero_producto='$filtro_genero'".$condiciones_where." ".$filtro_orden;
$preparar_busqueda = $conn->prepare( $sql_busqueda );
$preparar_busqueda->execute();
$num_resultados = $preparar_busqueda->rowCount();
$datos_busqueda = $preparar_busqueda->fetchAll();
//var_dump($datos_busqueda);
if ( $num_resultados == 0 ) {
    echo '<script language="javascript">alert("Error: La búsqueda no arrojó ningún resultado, ingrese otros parámetros.");window.location.href="productos.php?g=hombre"</script>';

}
//---------------------------------------------------------------
//echo '<script language="javascript">alert("juas");</script>';
//----------------------------

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

    <?php
/*

$inicio_limit = ( $_GET['p']-1 )*$productos_pagina;
//Operación que calcula el valor inicial para la consulta que usa LIMIT

//$sql_productos_limit = 'SELECT * FROM productos LIMIT :inicio,:num_productos';
$sql_productos_limit = 'SELECT * FROM productos LIMIT '.$inicio_limit.','.$productos_pagina;
$preparar2 = $conn->prepare( $sql_productos_limit );
//$preparar2->bindParam( ':inicio', $inicio_limit, PDO::PARAM_INT );
//$preparar2->bindParam( ':num_productos', $productos_pagina, PDO::PARAM_INT );
$preparar2->execute();
$datos_consulta_limit = $preparar2->fetchAll();
*/
?>

    <!-- HEADER -->

    <header class="barra-menu">
        <div class="div para titulo">aqui va un titulo con mensaje</div>
        <div class="logo-place"><img src="../assets/logo1.png" alt="logo_tienda"></div>
        <div class="div para menu">

            <nav class="menu">
                <ul>

                    <li><a href="../index.html"><i class="fas fa-home"></i>Inicio</a></li>
                    <li <?php if($_GET['g']=='hombre'){
    echo 'class="activado"';
    
} ?>><a href="productos.php?g=hombre"><i class="fas fa-male"></i>Hombre</a>

                        <div class="sub-menu1">

                            <ul>
                                <li><a href="">Prueba1</a></li>
                                <li><a href="">Prueba2</a></li>
                                <li><a href="">Prueba3</a></li>
                            </ul>

                        </div>

                    </li>
                    <li <?php if($_GET['g']=='mujer'){
    echo 'class="activado"';
    
} ?>><a href="productos.php?g=mujer"><i class="fas fa-female"></i>Mujer</a></li>
                    <li <?php if($_GET['g']=='niño'){
    echo 'class="activado"';
    
} ?>><a href="productos.php?g=niño"><i class="fas fa-child"></i>Niño</a></li>
                    <li <?php if($_GET['g']=='niña'){
    echo 'class="activado"';
    
} ?>><a href="productos.php?g=niña"><i class="fas fa-child"></i>Niña</a></li>
                    <li><a href=""><i class="far fa-heart"></i>Favoritos</a></li>
                    <li><a href=""><i class="fas fa-shopping-cart"></i>Carrito</a></li>

                    <?php if(isset( $_SESSION['usuario'])): ?>
                    <li><a href="cuenta.php"><i class="fas fa-user-circle"></i><?php echo $nombre_usuario ?></a></li>
                    <?php else:?>
                    <li><a href="login.php"><i class="fas fa-sign-in-alt"></i>Ingresar</a></li>
                    <li><a href="cuenta.php"><i class="fas fa-user-circle"></i>Cuenta</a></li>
                    <?php endif ?>
                </ul>
            </nav>



        </div>
    </header>

    <main>

        <section class="sect">
            <form method="post">
                <div class="box">
                    <select name="orden">
                        <option value="">Ordenar por</option>
                        <option value="ORDER BY precio_producto">Menor precio</option>
                        <option value="ORDER BY precio_producto DESC">Mayor precio</option>
                        <option value="ORDER BY nombre_producto">Nombre A-Z</option>
                        <option value="ORDER BY nombre_producto ASC">Nombre Z-A</option>

                    </select>
                </div>

                <div class="box">
                    <select name="categoria">
                        <option value="">Categoría</option>
                        <?php

$sql_categorias = "SELECT distinct categoria FROM productos WHERE genero_producto='$filtro_genero'";
$preparar_categorias = $conn->prepare( $sql_categorias );
$preparar_categorias->execute();
$datos_categorias = $preparar_categorias->fetchAll();

foreach ( $datos_categorias as $categorias ) {
    echo'<option value="'.$categorias['categoria'].'">'.$categorias['categoria'].'</option>';
}
?>
                    </select>
                </div>
                <div class="box">
                    <select name="color">
                        <option value="">Color</option>
                        <?php

$sql_colores = "SELECT distinct nombre_color FROM colores c, productos p
                    WHERE c.id_producto=p.id_producto
                    AND p.genero_producto='$filtro_genero'";
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
                        <option value="">Talla</option>
                        <?php

$sql_tallas = "SELECT distinct nombre_talla FROM tallas t, productos p
                    WHERE t.id_producto=p.id_producto
                    AND p.genero_producto='$filtro_genero'";
$preparar_tallas = $conn->prepare( $sql_tallas );
$preparar_tallas->execute();
$datos_tallas = $preparar_tallas->fetchAll();

foreach ( $datos_tallas as $tallas ) {
    echo'<option value="'.$tallas['nombre_talla'].'">'.$tallas['nombre_talla'].'</option>';
}
?>
                    </select>
                </div>
                <button name="buscar" type="submit" style="--content: 'Buscar';">
                    <div class="left"></div>
                    Buscar
                    <div class="right"></div>
                </button>

            </form>
        </section>



        <!--
<nav>


<ul class = "paginacion">

<li class = "item-paginacion"><a href = "productos.php?p=<?php echo $_GET['p']-1 ?>&g=<?php echo $_GET['g'] ?>" class = "link-paginacion">Anterior</a></li>
<?php for ( $i = 0; $i<$num_paginas; $i++ ): ?>
<li class = "item-paginacion"><a href = "productos.php?p=<?php echo $i+1 ?>&g=<?php echo $_GET['g'] ?>" class = "link-paginacion"><?php echo $i+1 ?></a></li>
<?php endfor ?>
<li class = "item-paginacion"><a href = "productos.php?p=<?php echo $_GET['p']+1 ?>&g=<?php echo $_GET['g'] ?>" class = "link-paginacion">Siguiente</a></li>

</ul>

</nav>
-->
        <div class="contenido-principal">

            <div class="contenido-pagina">
                <div class="titulo-seccion">Productos <?php $genero=$_GET['g']; echo $genero; ?></div>
                <div class="lista-productos">

                    <?php foreach ( $datos_busqueda as $productos ): ?>


                    <?php
                    
                    $nombrep = $productos['nombre_producto'];
                   
                    $sql_idp = "SELECT id_producto, descripcion_producto FROM productos WHERE nombre_producto='$nombrep'";
                    $preparar_idp = $conn->prepare( $sql_idp );
                    $preparar_idp->execute();
                    $dato_idp = $preparar_idp->fetch( PDO::FETCH_NUM );
                    $idp = $dato_idp[0];
                    $descripcionpr=$dato_idp[1];

                    $sql_imgs = "SELECT MIN(fecha_subida), url_imagen FROM imagenes WHERE id_producto=$idp";
                    $preparar_imgs = $conn->prepare( $sql_imgs );
                    $preparar_imgs->execute();
                    $dato_obtenido = $preparar_imgs->fetch( PDO::FETCH_NUM );
                    $img = $dato_obtenido[1];

                    ?>


                    <div class="caja-productos">


                        <div class="producto">
                            <a href="visualizar-producto.php?np=<?php echo $productos['nombre_producto'] ?>">
                                <img src="<?php echo $img ?>" alt="">
                                <div class="nombre-producto"><?php echo $productos['nombre_producto'] ?></div>
                                <div class="descripcion-producto"><?php echo $descripcionpr ?></div>

                                <?php
                                $precio = $productos['precio_producto'];
                                $descuento = $productos['descuento'];
                                $descuento_total = $precio-( $precio*$descuento );
                                ?>

                                <?php if ( $descuento != 0 ): ?>
                                <div class="precio-producto descuento"><del><?php echo 'COP '.$precio ?></del></div>
                                <div class="precio-producto"><?php echo 'COP '.$descuento_total ?></div>
                                <?php else: ?>
                                <div class="precio-producto"><?php echo 'COP '.$precio ?></div>
                                <?php endif ?>
                                <!--
                                if ( $descuento != 0 ) {
                                echo "<>Antes:$ $precio COP</del>";
                                    echo "<p>Ahora:$ $descuento_total COP</p>";
                                    } else {
                                    echo "<p>$ $precio COP</p>";
                                    }
                                -->

                                <div class="precio-producto"></div>
                                <div class="precio-producto descuento"></div>
                            </a>
                            <div class="aggcf">
                                <a href=""><i class="far fa-heart"></i></a>
                                <a href=""><i class="fas fa-shopping-cart"></i></a>
                            </div>
                        </div>



                    </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>




    </main>

    <footer>FOOOTER</footer>
</body>

</html>
