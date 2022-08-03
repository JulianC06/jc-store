<?php 

/*Cerrar la sesión*/

session_start();


if(!isset($_SESSION['usuario']) && !isset($_SESSION['administrador'])){    
    header("location:../index.html"); 
}

if(isset($_SESSION['usuario'])){
    //session_unset($_SESSION['usuario']);
    session_destroy();
    header("location:../index.html");

}else if(isset($_SESSION['administrador'])){
    //session_unset($_SESSION['administrador']);
    session_destroy();
    echo 'este';
    header("location:../index.html");    
}


?>
