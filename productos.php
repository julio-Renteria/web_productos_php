<?php

include("templete/cabecera.php");
include("administrador/confi/db.php");

//ESTO PERMITIRA MOSTRAR LOS LIBROS EN LA WEB (ESTO HACE LA CONSULTA)
$sentenciaSQL = $conexion->prepare("SELECT * FROM libros ORDER BY id DESC");
$sentenciaSQL->execute();
$listaProductos = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);





?>


<!--col md 3 divide la pantalla en 3 seciones -->
<!--primera card-->
<?php foreach ($listaProductos as $libro) { //--ESTE FOREACH VA A LEER TODOS LOS REGISTROS DE LA BASE DE DATOS Y LOS VA A TRADUCCIR COMO UN LIBRO 
?>

    <div class="col-md-3">

        <div class="card">

            <img class="card-img-top" src="./img/<?php echo $libro['imagen'] ?>" alt="">

            <div class="card-body">
                <!--AQUI ESTOY LEYENDO EL NOMBRE DEL LIBRO (LO CUAL TRAIDO DE LA TABLA LIBRO EN CORCHETE LLAMO EL CAMPO QUE NECESITO IMPRIMIR)-->
                <h4 class="card-title"><?php echo $libro['nombre'] ?></h4>

                <a name="" id="" class="btn btn-primary" href="https://bootswatch.com/" role="button">ver mas</a>
            </div>
        </div>
    </div>

<?php } ?>
<!--segunda card-->



<!--tercera card-->




<!--cuarta card-->






<?php include("templete/pie.php"); ?>