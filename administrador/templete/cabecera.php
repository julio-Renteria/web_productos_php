 <?php
    //BLOQUEAR EL ACCESO A LAS PAGINA 
    session_start();
    if (!isset($_SESSION['usuario'])) { //lo que dice en esta linea es si no hay usuario logueado 
        //se dirigira a index.php
        header("location:../index.php");   //adonde se va si no hay usuario logueado

    } else {

        if ($_SESSION['usuario'] == "ok") {
            $nombreUsuario = $_SESSION["nombreUsuario"];
        }
    }

    ?>

 <!doctype html>
 <html lang="en">

 <head>
     <title>Title</title>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
 </head>

 <body>
     <!-- para direccionar la url y octener datos del host -->
     <?php $url = "http://" . $_SERVER['HTTP_HOST'] . "/ploco/sitioweb"; ?>



     <nav class="navbar navbar-expand navbar-light bg-light">
         <div class="nav navbar-nav">
             <a class="nav-item nav-link active" href="#">Administrador Web <span class="sr-only">(current)</span></a>

             <!--CON ESTE CODIGO CONECTAMOS Y NOS SALIMOS DE CARPETA PARA ACEDER A ARCHIVOS DE OTRA CARPETA MEDINATE LA URL DE ARRIBA-->
             <a class="nav-item nav-link" href="<?php echo $url; ?>/administrador/inicio.php">Inicio</a>
             <a class="nav-item nav-link" href="<?php echo $url; ?>/administrador/seccion/productos.php">Libros</a>
             <a class=" nav-item nav-link" href="<?php echo $url; ?>/administrador/seccion/cerrar.php">Cerrar</a>
             <a class="nav-item nav-link" href="<?php echo $url; ?>">Ver sitio web</a>
         </div>
     </nav>

     <div class="container">
         <br>
         <div class="row">