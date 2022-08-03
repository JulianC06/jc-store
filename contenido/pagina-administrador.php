<?php

include_once 'conexion.php';
//Comprobamos que la sesión que viene es de un administrador
session_start();

$identificacion_adm = $_SESSION['administrador'];

if ( !isset( $identificacion_adm ) ) {
    header( "location:login.php" );
}

////sql para obtener datos del admin
$sql_usr = "SELECT * FROM usuarios WHERE n_identificacion=$identificacion_adm";
$preparar_usr = $conn->prepare( $sql_usr );
$preparar_usr->execute();
$datos_usr = $preparar_usr->fetchAll();
foreach ( $datos_usr as $usr ) {
    $nombre_adm = $usr['nombre']." ".$usr['apellido'];
}
/////

$sql_general = "SELECT * FROM productos";
$preparar_general = $conn->prepare( $sql_general );
$preparar_general->execute();
$datos_busqueda = $preparar_general->fetchAll();

if ( !$_GET ) {
    header( "location:pagina-administrador.php?list=ap" );
}

if ( isset( $_POST['agregar'] ) ) {
    if ( empty( $_POST['nombrep'] ) || empty( $_POST['categoriap'] ) || empty( $_POST['generop'] ) || empty( $_POST['preciop'] ) || empty( $_POST['descripcionp'] ) ) {
        echo '<script language="javascript">alert("Error: no es posible agregar. Hay campos vacíos.");</script>';
    } else {

        $nombrep = $_POST['nombrep'];
        $categoriap = $_POST['categoriap'];
        $generop = $_POST['generop'];
        $preciop = $_POST['preciop'];
        $descripcionp = $_POST['descripcionp'];
        $descuentop = $_POST['descuentop'];

        echo $_POST['nombrep'];
        $sql_comprobar = "SELECT * FROM productos WHERE nombre_producto='$nombrep'";
        $preparar_comprobar = $conn->prepare( $sql_comprobar );
        $preparar_comprobar->execute();
        $num_resultados = $preparar_comprobar->rowCount();

        if ( $num_resultados>0 ) {
            echo '<script language="javascript">alert("Error, el producto ya existe. Revise los datos ingresados.");</script>';

        } else {
            if ( empty( $_POST['descuentop'] ) ) {
                $sql_agregar = "INSERT INTO productos (id_producto,nombre_producto,descripcion_producto,precio_producto,categoria,genero_producto,descuento) VALUES(NULL,'$nombrep','$descripcionp',$preciop,'$categoriap','$generop',NULL)";

            } else {
                $sql_agregar = "INSERT INTO productos (id_producto,nombre_producto,descripcion_producto,precio_producto,categoria,genero_producto,descuento) VALUES(NULL,'$nombrep','$descripcionp',$preciop,'$categoriap','$generop',$descuentop)";
            }

            $preparar_agg = $conn->prepare( $sql_agregar );
            $preparar_agg->execute();
            echo '<script language="javascript">alert("Producto agregado correctamente.");</script>';
        }

    }

} else if ( isset( $_POST['aggi'] ) ) {
    if ( empty( $_POST['urli'] ) || empty( $_POST['traenombre4'] ) ) {
        echo '<script language="javascript">alert("Error: hay campos vacíos.");</script>';
    } else {

        $traenombre = $_POST['traenombre4'];
        $urli = $_POST['urli'];

        $sql_idp = "SELECT id_producto FROM productos WHERE nombre_producto='$traenombre'";
        $preparar_idp = $conn->prepare( $sql_idp );
        $preparar_idp->execute();
        $dato_obtenido = $preparar_idp->fetch( PDO::FETCH_NUM );
        $idp = $dato_obtenido[0];

        $sql_aggi = "INSERT INTO imagenes (id_imagen,id_producto,url_imagen) VALUES (NULL,$idp,'$urli')";
        $preparar_aggi = $conn->prepare( $sql_aggi );
        $preparar_aggi->execute();
        echo '<script language="javascript">alert("Imagen añadida correctamente.");</script>';
    }

} else if ( isset( $_POST['editar'] ) ) {

    if ( empty( $_POST['nombrep2'] ) || empty( $_POST['categoriap2'] ) || empty( $_POST['generop2'] ) || empty( $_POST['preciop2'] ) || empty( $_POST['descripcionp2'] ) ) {
        echo '<script language="javascript">alert("Error: no es posible agregar. Hay campos vacíos.");</script>';
    } else {

        $nombrep = $_POST['nombrep2'];
        $categoriap = $_POST['categoriap2'];
        $generop = $_POST['generop2'];
        $preciop = $_POST['preciop2'];
        $descripcionp = $_POST['descripcionp2'];
        $descuentop = $_POST['descuentop2'];
        $traenombre = $_POST['traenombre2'];
        if ( empty( $_POST['descuentop2'] ) ) {
            $sql_editar = "UPDATE productos SET nombre_producto='$nombrep',descripcion_producto='$descripcionp',precio_producto=$preciop,categoria='$categoriap',genero_producto='$generop' WHERE productos.nombre_producto='$traenombre'";

        } else {
            $sql_editar = "UPDATE productos SET nombre_producto='$nombrep',descripcion_producto='$descripcionp',precio_producto=$preciop,categoria='$categoriap',genero_producto='$generop', descuento=$descuentop WHERE productos.nombre_producto='$traenombre'";

        }

        $preparar_ed = $conn->prepare( $sql_editar );
        $preparar_ed->execute();
        echo '<script language="javascript">alert("Producto editado correctamente.");</script>';
    }

} else if ( isset( $_POST['borrar'] ) ) {

    $traenombre = $_POST['traenombre3'];
    $sql_borrar = "DELETE FROM productos WHERE productos.nombre_producto='$traenombre'";
    $preparar_bo = $conn->prepare( $sql_borrar );
    $preparar_bo->execute();
    echo '<script language="javascript">alert("Producto eliminado correctamente.");</script>';
} else if ( isset( $_POST['aggp'] ) ) {

    if ( empty( $_POST['uproducidas'] ) || empty( $_POST['pproduccion'] ) || empty( $_POST['colores'] ) || empty( $_POST['tallas'] ) || empty( $_POST['traenombre4'] ) ) {
        echo '<script language="javascript">alert("Error: Hay campos vacío.");</script>';
    } else {

        /*
        $sql_comprobar_combinacion = "SELECT id_producto,id_talla,id_color F";

        $preparar_pr = $conn->prepare( $sql_aggproduccion );
        $preparar_pr->execute();
        $num_resultados = $preparar_pr->rowCount();
        */

        $nombre_producto = $_POST['traenombre4'];
        $sql_tids = "SELECT p.id_producto,id_color,id_talla
        FROM productos p, tallas t, colores c
        WHERE p.id_producto=t.id_producto
        AND p.id_producto=c.id_producto
        AND nombre_producto='$nombre_producto'";
        $preparar_tids = $conn->prepare( $sql_tids );
        $preparar_tids->execute();
        $num_resultados = $preparar_tids->rowCount();
        $datos_tids = $preparar_tids->fetchAll();

        $idp = "";
        $idt = "";
        $idc = "";
        $fecha = "current_timestamp()";
        $pp = $_POST['pproduccion'];
        $unidp = $_POST['uproducidas'];
        $obs = $_POST['observacionppr'];

        if ( $num_resultados != 0 ) {
            foreach ( $datos_tids as $tids ) {
                $idp = $tids['id_producto'];
                $idt = $tids['id_talla'];
                $idc = $tids['id_color'];
            }
            echo $idp.$idc.$idt;
            if ( empty( $_POST['observacionppr'] ) ) {

                $sql_aggproduccion = "INSERT INTO producciones (numero_produccion,id_producto,id_talla,id_color,observacion,fecha_produccion,precio_produccion,unidades_producidas)
            VALUES (NULL,$idp,$idt,$idc,NULL,$fecha,$pp,$unidp)";

            } else {
                $sql_aggproduccion = "INSERT INTO producciones (numero_produccion,id_producto,id_talla,id_color,observacion,fecha_produccion,precio_produccion,unidades_producidas)
            VALUES (NULL,$idp,$idt,$idc,'$obs',$fecha,$pp,$unidp)";
            }

            $preparar_pr = $conn->prepare( $sql_aggproduccion );
            $preparar_pr->execute();

            echo '<script language="javascript">alert("Producción agregada correctamente.");</script>';
        } else {
            echo '<script language="javascript">alert("Error, la combinación seleccionada no existe.");</script>';
        }

    }

} else if ( isset( $_POST['aggc'] ) ) {

    if ( !empty( $_POST['colores'] ) && !empty( $_POST['traenombre5'] ) ) {

        $color = $_POST['colores'];
        $nombre_producto = $_POST['traenombre5'];

        $sql_obtenerid = "SELECT id_producto FROM productos WHERE nombre_producto='$nombre_producto'";
        $preparar_obi = $conn->prepare( $sql_obtenerid );
        $preparar_obi->execute();
        $dato_obtenido = $preparar_obi->fetch( PDO::FETCH_NUM );
        $idp = $dato_obtenido[0];

        $sql_validar = "SELECT COUNT(id_producto) FROM colores WHERE id_producto=$idp AND nombre_color='$color'";
        $preparar_validar = $conn->prepare( $sql_validar );
        $preparar_validar->execute();
        $dato_obtenido2 = $preparar_validar->fetch( PDO::FETCH_NUM );
        $num_r = $dato_obtenido2[0];

        if ( $num_r == 0 ) {
            $sql_aggc = "INSERT INTO colores (id_color,id_producto,nombre_color) VALUES (NULL,$idp,'$color')";
            $preparar_aggc = $conn->prepare( $sql_aggc );
            $preparar_aggc->execute();
            echo '<script language="javascript">alert("Color agregado correctamente.");</script>';
        } else {
            echo '<script language="javascript">alert("Error: el color ya existe en ese producto.");</script>';
        }
    } else {
        echo '<script language="javascript">alert("Error: hay campos vacíos.");</script>';
    }
} else if ( isset( $_POST['aggt'] ) ) {
    if ( !empty( $_POST['tallas'] ) && !empty( $_POST['traenombre6'] ) ) {

        $talla = $_POST['tallas'];
        $nombre_producto = $_POST['traenombre6'];

        $sql_obtenerid = "SELECT id_producto FROM productos WHERE nombre_producto='$nombre_producto'";
        $preparar_obi = $conn->prepare( $sql_obtenerid );
        $preparar_obi->execute();
        $dato_obtenido = $preparar_obi->fetch( PDO::FETCH_NUM );
        $idp = $dato_obtenido[0];

        $sql_validar = "SELECT COUNT(id_producto) FROM tallas WHERE id_producto=$idp AND nombre_talla='$talla'";
        $preparar_validar = $conn->prepare( $sql_validar );
        $preparar_validar->execute();
        $dato_obtenido2 = $preparar_validar->fetch( PDO::FETCH_NUM );
        $num_r = $dato_obtenido2[0];

        if ( $num_r == 0 ) {
            $sql_aggt = "INSERT INTO tallas (id_talla,id_producto,nombre_talla) VALUES (NULL,$idp,'$talla')";
            $preparar_aggt = $conn->prepare( $sql_aggt );
            $preparar_aggt->execute();
            echo '<script language="javascript">alert("Talla agregada correctamente.");</script>';
        } else {
            echo '<script language="javascript">alert("Error: la talla ya existe en ese producto.");</script>';
        }
    } else {
        echo '<script language="javascript">alert("Error: hay campos vacíos.");</script>';
    }
} else if ( isset( $_POST['editarn'] ) ) {

    if ( empty( $_POST['nombrep2'] ) || empty( $_POST['traenombre2'] ) ) {
        echo '<script language="javascript">alert("Error: hay campos vacíos.");</script>';
    } else {
        $traenombre = $_POST['traenombre2'];
        $nuevo_nombre = $_POST['nombrep2'];
        $sql_cn = "UPDATE productos SET nombre_producto='$nuevo_nombre' WHERE productos.nombre_producto='$traenombre'";
        $preparar_cn = $conn->prepare( $sql_cn );
        $preparar_cn->execute();
        echo '<script language="javascript">alert("Nombre editado correctamente.");</script>';
    }
} else if ( isset( $_POST['descuento'] ) ) {
    if ( empty( $_POST['descuentop'] ) || empty( $_POST['traenombre2'] ) ) {
        echo '<script language="javascript">alert("Error: hay campos vacíos.");</script>';
    } else {
        $traenombre = $_POST['traenombre2'];
        $nuevo_descuento = $_POST['descuentop'];
        $sql_ede = "UPDATE productos SET descuento='$nuevo_descuento' WHERE productos.nombre_producto='$traenombre'";
        $preparar_ede = $conn->prepare( $sql_ede );
        $preparar_ede->execute();
        echo '<script language="javascript">alert("Descuento asignado correctamente.");</script>';
    }
} else if ( isset( $_POST['edescripcion'] ) ) {
    if ( empty( $_POST['descripcionp'] ) || empty( $_POST['traenombre2'] ) ) {
        echo '<script language="javascript">alert("Error: hay campos vacíos.");</script>';
    } else {
        $traenombre = $_POST['traenombre2'];
        $nueva_descripcion = $_POST['descripcionp'];
        $sql_edes = "UPDATE productos SET descripcion_producto='$nueva_descripcion' WHERE productos.nombre_producto='$traenombre'";
        $preparar_edes = $conn->prepare( $sql_edes );
        $preparar_edes->execute();
        echo '<script language="javascript">alert("Descripcion cambiada correctamente.");</script>';
    }
} else if ( isset( $_POST['eprecio'] ) ) {
    if ( empty( $_POST['preciop'] ) || empty( $_POST['traenombre2'] ) ) {
        echo '<script language="javascript">alert("Error: hay campos vacíos.");</script>';
    } else {
        $traenombre = $_POST['traenombre2'];
        $nuevo_precio = $_POST['preciop'];
        $sql_edpr = "UPDATE productos SET precio_producto='$nuevo_precio' WHERE productos.nombre_producto='$traenombre'";
        $preparar_edpr = $conn->prepare( $sql_edpr );
        $preparar_edpr->execute();
        echo '<script language="javascript">alert("Precio cambiado correctamente.");</script>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body>
    <h1><?php echo 'Bienvenido '.$nombre_adm ?></h1>
    <a href="salir.php">cerrar sesión</a>

    <section class="contiene">
        <div class="opciones">

            <nav class="menu">
                <ul>
                    <li><a href="pagina-administrador.php?list=ap">Agregar producto</a>

                        <ul>
                            <li><a href="pagina-administrador.php?list=au">Agregar unidades a producto</a></li>
                            <li><a href="pagina-administrador.php?list=ai">Agregar imagen a producto</a></li>
                            <li><a href="pagina-administrador.php?list=ac">Agregar color a producto</a></li>
                            <li><a href="pagina-administrador.php?list=at">Agregar talla a producto</a></li>
                        </ul>

                    </li>

                    <li><a href="pagina-administrador.php?list=ep">Editar producto</a>

                        <ul>
                            <li><a href="pagina-administrador.php?list=cn">Cambiar nombre</a></li>
                            <li><a href="pagina-administrador.php?list=ad">Asignar descuento</a></li>
                            <li><a href="pagina-administrador.php?list=cd">Cambiar descripción</a></li>
                            <li><a href="pagina-administrador.php?list=cp">Cambiar precio</a></li>

                        </ul>

                    </li>
                    <li><a href="pagina-administrador.php?list=elp">Eliminar producto</a></li>
                    <li><a href="pagina-administrador.php?list=rep">Reporte</a></li>
                    <li><a href="pagina-administrador.php?list=co">Compras</a></li>
                </ul>
            </nav>

        </div>

        <div class="informacion">
            <!--agregar-->
            <div <?php

if ( $_GET['list'] == 'ap' ) {
    echo "style='display:block'";
} else {
    echo "style=display:none";
}
?>>
                <form method="post">
                    <h4>Agregar producto</h4>
                    Nombre del producto<input type="text" name="nombrep">
                    Categoria del producto<input type="text" name="categoriap" placeholder="Ejemplo= pantalon">
                    Genero del producto<input type="text" name="generop" placeholder="Ejemplo= niña">
                    Precio del producto<input type="number" name="preciop" placeholder="Ejemplo= 50000" min="0">
                    Descuento<input type="text" name="descuentop" placeholder="Ejemplo= 50000" min="0">
                    Descripción<textarea name="descripcionp" id="" cols="30" rows="5" placeholder="Ejemplo: Porducto de tela xx, con una enorme suavidad.."></textarea>
                    <button name="agregar" type="submit">Agregar</button>
                </form>

            </div>

            <div <?php

if ( $_GET['list'] == 'ai' ) {
    echo "style='display:block'";
} else {
    echo "style=display:none";
}

?>>
                <form method="post">
                    <h4>Agregar imagen</h4>
                    <select name="traenombre4" id="">

                        <option value="">Seleccione el producto</option>

                        <?php foreach ( $datos_busqueda as $productos ): ?>

                        <option value="<?php echo $productos['nombre_producto'] ?>"><?php echo $productos['nombre_producto'] ?></option>

                        <?php endforeach ?>
                    </select>
                    Url img:<input type="text" name="urli">
                    <button name="aggi" type="submit">Agregar</button>
                </form>

            </div>

            <div <?php

if ( $_GET['list'] == 'au' ) {
    echo "style='display:block'";
} else {
    echo "style=display:none";
}

?>>
                <form method="post">
                    <h4>Agregar producción</h4>

                    <select name="traenombre4" id="">

                        <option value="">Seleccione el producto</option>

                        <?php foreach ( $datos_busqueda as $productos ): ?>

                        <option value="<?php echo $productos['nombre_producto'] ?>"><?php echo $productos['nombre_producto'] ?></option>

                        <?php endforeach ?>
                    </select>

                    <select name="colores" id="">

                        <option value="">Seleccione el color</option>
                        <?php
$sql_colores = "SELECT distinct nombre_color FROM colores c, productos p
                    WHERE c.id_producto=p.id_producto";
$preparar_colores = $conn->prepare( $sql_colores );
$preparar_colores->execute();
$datos_colores = $preparar_colores->fetchAll();

foreach ( $datos_colores as $colores ) {
    echo'<option value="'.$colores['nombre_color'].'">'.$colores['nombre_color'].'</option>';
}
?>
                    </select>

                    <select name="tallas">
                        <option value="">Seleccione la talla</option>
                        <?php

$sql_tallas = "SELECT distinct nombre_talla FROM tallas t, productos p
                    WHERE t.id_producto=p.id_producto";
$preparar_tallas = $conn->prepare( $sql_tallas );
$preparar_tallas->execute();
$datos_tallas = $preparar_tallas->fetchAll();

foreach ( $datos_tallas as $tallas ) {
    echo'<option value="'.$tallas['nombre_talla'].'">'.$tallas['nombre_talla'].'</option>';
}
?>
                    </select>
                    Unidades producidas: <input type="text" name="uproducidas">
                    Precio producción: <input type="text" name="pproduccion">
                    <textarea name="observacionppr" id="" cols="30" rows="10" placeholder="Escriba la observación de la producción"></textarea>
                    <button name="aggp" type="submit">Agregar</button>
                </form>

            </div>

            <div <?php

if ( $_GET['list'] == 'ac' ) {
    echo "style='display:block'";
} else {
    echo "style=display:none";
}

?>>
                <form method="post">
                    <h4>Agregar color</h4>

                    <select name="traenombre5" id="">

                        <option value="">Seleccione el producto</option>

                        <?php foreach ( $datos_busqueda as $productos ): ?>

                        <option value="<?php echo $productos['nombre_producto'] ?>"><?php echo $productos['nombre_producto'] ?></option>

                        <?php endforeach ?>
                    </select>

                    Escriba el color: <input type="text" name="colores">
                    <button name="aggc" type="submit">Agregar</button>
                </form>

            </div>

            <div <?php

if ( $_GET['list'] == 'at' ) {
    echo "style='display:block'";
} else {
    echo "style=display:none";
}

?>>

                <form method="post">
                    <h4>Agregar talla</h4>

                    <select name="traenombre6" id="">

                        <option value="">Seleccione el producto</option>

                        <?php foreach ( $datos_busqueda as $productos ): ?>

                        <option value="<?php echo $productos['nombre_producto'] ?>"><?php echo $productos['nombre_producto'] ?></option>

                        <?php endforeach ?>
                    </select>

                    Escriba la talla: <input type="text" name="tallas">
                    <button name="aggt" type="submit">Agregar</button>
                </form>

            </div>

            <!--fin agregar-->

            <!--editar-->
            <div <?php

if ( $_GET['list'] == 'ep' ) {
    echo "style='display:block'";
} else {
    echo "style=display:none";
}

?>>

                <form method="post">
                    <h4>Editar todos los datos del producto</h4>

                    <select name="traenombre2" id="">

                        <option value="">Seleccione el producto</option>

                        <?php foreach ( $datos_busqueda as $productos ): ?>

                        <option value="<?php echo $productos['nombre_producto'] ?>"><?php echo $productos['nombre_producto'] ?></option>

                        <?php endforeach ?>
                    </select>
                    Nuevo nombre<input type="text" name="nombrep2">
                    Nueva categoría<input type="text" name="categoriap2" placeholder="Ejemplo= pantalon">
                    Nuevo Genero del producto<input type="text" name="generop2" placeholder="Ejemplo= niña">
                    Nuevo Precio del producto<input type="number" name="preciop2" placeholder="Ejemplo= 50000" min="0">
                    Nuevo Descuento<input type="text" name="descuentop2" placeholder="Ejemplo= 50000" min="0">
                    Nueva descripción<textarea name="descripcionp2" id="" cols="30" rows="5" placeholder="Ejemplo: Porducto de tela xx, con una enorme suavidad.."></textarea>
                    <button name="editar" type="submit">Editar</button>
                </form>

            </div>

            <div <?php

if ( $_GET['list'] == 'cn' ) {
    echo "style='display:block'";
} else {
    echo "style=display:none";
}

?>>
                <form method="post">
                    <h4>Cambiar nombre</h4>

                    <select name="traenombre2" id="">

                        <option value="">Seleccione el producto</option>

                        <?php foreach ( $datos_busqueda as $productos ): ?>

                        <option value="<?php echo $productos['nombre_producto'] ?>"><?php echo $productos['nombre_producto'] ?></option>

                        <?php endforeach ?>
                    </select>
                    Nuevo nombre<input type="text" name="nombrep2" placeholder="Ejemplo=Pantalon dama nuevo invierno">
                    <button name="editarn" type="submit">Editar</button>
                </form>
            </div>

            <div <?php

if ( $_GET['list'] == 'ad' ) {
    echo "style='display:block'";
} else {
    echo "style=display:none";
}

?>>
                <form method="post">
                    <h4>Asignar descuento</h4>

                    <select name="traenombre2" id="">

                        <option value="">Seleccione el producto</option>

                        <?php foreach ( $datos_busqueda as $productos ): ?>

                        <option value="<?php echo $productos['nombre_producto'] ?>"><?php echo $productos['nombre_producto'] ?></option>

                        <?php endforeach ?>
                    </select>
                    Descuento: <input type="text" name="descuentop" placeholder="Ejemplo: 0.2">
                    <button name="descuento" type="submit">Editar</button>
                </form>

            </div>

            <div <?php

if ( $_GET['list'] == 'cd' ) {
    echo "style='display:block'";
} else {
    echo "style=display:none";
}

?>>
                <form method="post">
                    <h4>Cambiar Descripción</h4>

                    <select name="traenombre2" id="">

                        <option value="">Seleccione el producto</option>

                        <?php foreach ( $datos_busqueda as $productos ): ?>

                        <option value="<?php echo $productos['nombre_producto'] ?>"><?php echo $productos['nombre_producto'] ?></option>

                        <?php endforeach ?>
                    </select>
                    Descripción: <textarea name="descripcionp" id="" cols="30" rows="5" placeholder="Ejemplo: Producto elaborado con tela x en un proceso manual...."></textarea>
                    <button name="edescripcion" type="submit">Editar</button>
                </form>

            </div>

            <div <?php

if ( $_GET['list'] == 'cp' ) {
    echo "style='display:block'";
} else {
    echo "style=display:none";
}

?>>
                <form method="post">
                    <h4>Cambiar precio</h4>

                    <select name="traenombre2" id="">

                        <option value="">Seleccione el producto</option>

                        <?php foreach ( $datos_busqueda as $productos ): ?>

                        <option value="<?php echo $productos['nombre_producto'] ?>"><?php echo $productos['nombre_producto'] ?></option>

                        <?php endforeach ?>
                    </select>
                    Nuevo precio: <input type="text" name="preciop" placeholder="Ejemplo= 50000">
                    <button name="eprecio" type="submit">Editar</button>
                </form>

            </div>
            <!--Fin editar-->
            <!--eliminar-->
            <div <?php

if ( $_GET['list'] == 'elp' ) {
    echo "style='display:block'";
} else {
    echo "style=display:none";
}

?>>

                <form method="post">
                    <h4>Eliminar producto</h4>

                    <select name="traenombre3" id="">

                        <option value="">Seleccione el producto</option>

                        <?php foreach ( $datos_busqueda as $productos ): ?>

                        <option value="<?php echo $productos['nombre_producto'] ?>"><?php echo $productos['nombre_producto'] ?></option>

                        <?php endforeach ?>
                    </select>
                    <button name="borrar" type="submit">Borrar</button>
                </form>

            </div>
            <!--fin eliminar-->

            <!-- Reporte-->

            <div <?php

if ( $_GET['list'] == 'rep' ) {
    echo "style='display:block'";
} else {
    echo "style=display:none";
}

?>>


                <h1>Reporte</h1>

                <?php

                    $sql_masv = "SELECT SUM(unidades_producto), id_producto FROM productos_en_compras GROUP BY id_producto ORDER BY unidades_producto DESC limit 1";
                    $preparar_masv= $conn->prepare( $sql_masv );
                    $preparar_masv->execute();
                    $dato_masv = $preparar_masv->fetch( PDO::FETCH_NUM );
                    $idmasv = $dato_masv[1];
                    
                    $sql_obtn="SELECT nombre_producto FROM productos WHERE id_producto='$idmasv'";
                    $preparar_obtn= $conn->prepare( $sql_obtn );
                    $preparar_obtn->execute();
                    $dato_obtn = $preparar_obtn->fetch( PDO::FETCH_NUM );
                    $nombre_obtn = $dato_obtn[0];
?>
                <br>
                <h2>Producto más vendido: <?php echo $nombre_obtn."(".$dato_masv[0]." unidades)" ?></h2>

                <?php

                    $sql_tgan = "SELECT SUM(total) FROM productos_en_compras";
                    $preparar_tgan= $conn->prepare( $sql_tgan );
                    $preparar_tgan->execute();
                    $dato_tgan = $preparar_tgan->fetch( PDO::FETCH_NUM );
                    $totalgan = $dato_tgan[0];
?>

                <h2>Total ganancias: COP <?php echo $totalgan ?> </h2>

            </div>
            <!-- Fin reporte-->

        </div>
    </section>

    <nav>

    </nav>

</body>

</html>
