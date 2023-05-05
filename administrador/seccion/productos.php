<?php include("../templete/cabecera.php"); ?>

<?php
//print_r($_POST); (verificaba que llegara informacion por post)
//print_r($_FILES);(verificabamos que llegara informacion de la imagen)

//aqui se reciben y se validan los datos del formulario

$txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : ""; //lo que tigo es que si hay algo en txtID en tomces txtID sera = a la variable txtID (que cree) si no hay nada qiedara vacio
$txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : ""; //lo que tigo es que si hay algo en txtID en tomces txtID sera = a la variable txtID (que cree) si no hay nada qiedara vacio
$txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : ""; //lo que tigo es que si hay algo en txtID en tomces txtID sera = a la variable txtID (que cree) si no hay nada qiedara vacio
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : ""; //lo que tigo es que si hay algo en txtID en tomces txtID sera = a la variable txtID (que cree) si no hay nada qiedara vacio


include("../confi/db.php");

/*
PRUEBAS DE QUE LLEGARA LOS DATOS DEL FORMULARIO 
echo $txtID . "<br/>";
echo $txtNombre . "<br/>";
echo $txtImagen . "<br/>";
echo $accion . "<br/>";
 */ //ESTE CODIGO ANTERIOR COMENTADO HAGO PRUEBAS DEL BONTON Y QUE PUEDA RECIBIR LOS DATOS DEL FORMULARIO POR POST




switch ($accion) {
    case 'Agregar':
        //Para agregar datos a la base de datos 
        // INSERT INTO `libros` (`id`, `nombre`, `imagen`) VALUES (NULL, 'Libro de Php', 'imagen.jpg');

        $sentenciaSQL = $conexion->prepare("INSERT INTO libros (nombre,imagen) VALUES (:nombre, :imagen)");
        $sentenciaSQL->bindParam(':nombre', $txtNombre); //Aqui estoy pasando el parametro de nombre para que me inserte el que el usuario escriba

        //AGREGAR O SUBIR  IMAGEN A LA CARPETA IMG

        $fecha = new DateTime(); //              tiempo   para nombre de img |  nombre del archivo  |   si no existe pone este nombre
        $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "noimgen.jpg";

        $imagenTemp = $_FILES["txtImagen"]["tmp_name"];
        //ahora vamos a validar si el tempora name o iamgen tempora tiene algo o trae algo
        if ($imagenTemp != "") {
            //ESTO ES PARA GUARDAR LA IMAGEN EN LA CARPETA IMG
            move_uploaded_file($imagenTemp, "../../img/" . $nombreArchivo); //aqui digo que si si hay algo entonces lo voy a mover a la carpeta img de mi proyecto
        }


        $sentenciaSQL->bindParam(':imagen', $nombreArchivo); //Aqui estoy pasando el parametro de imamgen para que me inserte el que el usuario escriba
        $sentenciaSQL->execute(); //aqui se ejecuta la sentemcia sql

        header("location:productos.php");
        break;


    case 'Modificar':
        // echo "presionado boton Modificar";
        $sentenciaSQL = $conexion->prepare("UPDATE libros SET nombre=:nombre WHERE id=:id"); //(aqui digo que me actualice el dato cuando se igual a ida seleccionado)
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();

        //ACA VAMOS A ACTUALIZAR EL CAMPO DE IMAGEN SI NO ESTA VACIO
        //ACA PRIMERO INSERTO LA IMAGEN NUEVA Y ABAJO BORRO LA ANTERIOR
        if ($txtImagen != "") { //(en esta condicion digo si la variable txt imagen es diferente a vacio entonce se ejecutara la sentencia de abajo)

            $fecha = new DateTime(); //              tiempo   para nombre de img |  nombre del archivo  |   si no existe pone este nombre
            $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "noimgen.jpg";
            $imagenTemp = $_FILES["txtImagen"]["tmp_name"];

            move_uploaded_file($imagenTemp, "../../img/" . $nombreArchivo);

            //borra la imagen anterior subida (AQUI BORRO LA IMAGEN VIEJA)

            $sentenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();
            $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if (isset($libro["imagen"]) && ($libro["imagen"] != "noimagen.jpg")) {

                if (file_exists("../../img/" . $libro["imagen"])) { //comprabamos que exista la imagen en la carpeta

                    unlink("../../img/" . $libro["imagen"]); //se procede a eliminar 
                }
            }


            //(AQUI ACTUALIZAMOS LOS DEMAS DATOS CON EL NUEVONOMBRE DE IMAGEN Y LOS DEMAS DATOS)

            $sentenciaSQL = $conexion->prepare("UPDATE libros SET imagen=:imagen WHERE id=:id");
            $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();
        }
        header("location:productos.php");
        break;


    case 'Cancelar':
        /* echo "presionado boton Cancelar"; */

        header("location:productos.php");

        break;

        //BORRAR


        //BOTONES DE LA TABLA DE PRODUCTOS DE BR (BORRAR Y SELECCIONAR)

    case 'Borrar':

        //BORRAR IAMGEN DE CARPETA IMG

        $sentenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if (isset($libro["imagen"]) && ($libro["imagen"] != "noimagen.jpg")) {

            if (file_exists("../../img/" . $libro["imagen"])) { //comprabamos que exista la imagen en la carpeta

                unlink("../../img/" . $libro["imagen"]); //se procede a eliminar 
            }
        }

        //BORRAR ID Y PRODUCTO

        //echo "presionado boton borrar"; (para validar que este funcionaod cuando preciono boton)
        $sentenciaSQL = $conexion->prepare("DELETE FROM libros WHERE id=:id"); //le digo que elimine cuando id sea igual al id que recibo
        $sentenciaSQL->bindParam(':id', $txtID); //le asigno el paaremetro
        $sentenciaSQL->execute(); //se ejecuta la serencia
        header("location:productos.php");
        break;



        //EDITAR 
    case 'Seleccionar':
        $sentenciaSQL = $conexion->prepare("SELECT * FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtNombre = $libro['nombre'];
        $txtImagen = $libro['imagen'];


        //echo "presionado boton seleccionar";
        break;
}


//PARA MOSTRAR LOS DATOS DE LA BASE DE DATOS
$sentenciaSQL = $conexion->prepare("SELECT * FROM libros ORDER BY id DESC"); //muestra todos los datos de la BD
$sentenciaSQL->execute(); //aqui sejecuto la sentencia anterior

$listaProductos = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC); //(genera una asociasion de los datos que vienen de la tabla)con esta linea recupero todo los datos de la bd para que puedan ser almacenados en la varible (Listaproductos)




?>




<div class="col-md-5">

    <div class="card">
        <div class="card-header">
            Datos de Libros
        </div>


        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">


                <div class="form-group">
                    <label for="txtID">ID:</label>
                    <input type="text" required readonly class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ID">
                    <!--(value="</?php echo $txtID; ?> con est sentencia de php hago que se muestre el id a en el formulario para editar)-->
                    <!--(required readonly) ESTA PROPIEDAD HACE QUE EL CAMPO SEA REQUEREDI PERO SOLO COMO LECTURA -->
                </div>


                <div class="form-group">
                    <label for="txtNombre">Nombre:</label>
                    <input type="text" required class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre" placeholder="Nombre del Libro">
                </div>


                <div class="form-group">
                    <label for="txtImagen">Imagen:</label>

                    <!--//CON ESTO VOY A MOSTRAR LA IMAGEN EN  MINIATURA EN EL FORMULARIO AL EDITAR-->

                    <!-- <?php echo $txtImagen; ?> comentado muetsra el nombre de la imagen en el formulario-->

                    <br />
                    <?php if ($txtImagen != "") { ?>

                        <img class="img-thumbnail rounded" src="../../img/<?php echo $txtImagen; ?>" width="50" alt="" srcset="">


                    <?php } ?>




                    <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Nombre del Libro">
                </div>

                <!--(<?php echo ($accion == "Seleccionar") ? "disable" : "" ?>) la anterior intruccion es para desactivar botones-->

                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" name="accion" <?php echo ($accion == "seleccionar") ? "disabled" : ""; ?> value="Agregar" class="btn btn-success">Agregar</button>
                    <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : ""; ?>value="Modificar" class="btn btn-warning">Modificar</button>
                    <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : ""; ?>value="Cancelar" class="btn btn-info">Cancelar</button>
                </div>


            </form>


        </div>
    </div>

</div>




<!--session 2 (va a mostrar datos guardado en base de datos) (tabla de bd)-->
<div class="col-md-7">

    <table class="table table-bordered  table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($listaProductos as $libro) { //ingreso la lista de productos que me los lea como si fuera un libro
            ?>
                <tr>
                    <td><?php echo $libro['id']; //aqui imprimo el campo que voy a mostrar en la tabla  
                        ?></td>
                    <td><?php echo $libro['nombre']; //aqui imprimo el campo que voy a mostrar en la tabla
                        ?></td>

                    <td>
                        <!--  //aqui MUESTRO LA IMAGEN en miniatura  el campo que voy a mostrar en la tabla-->

                        <img class="img-thumbnail rounded" src="../../img/<?php echo $libro['imagen']; ?>" width="50" alt="" srcset="">



                    </td>





                    <!--FORMULARIO PARA BOTONES DE LA TABLA SELECCIONA Y BORRAR-->
                    <td>


                        <form method="POST">

                            <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id']; //con esto me saca el id y y me lo muestra (EL *HIDDEN es para que el id este oculto*)
                                                                                ?>">


                            <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary" />

                            <input type="submit" name="accion" value="Borrar" class="btn btn-danger" />

                        </form>


                    </td>

                </tr>
            <?php } //aqui cierro el foreach
            ?>
        </tbody>
    </table>


</div>



<?php include("../templete/pie.php"); ?>