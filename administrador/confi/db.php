<?php

//Conexion a BD
$host = "localhost:3308";
$bd = "sitioweb";
$usuario = "root";
$contrasenia = "";

//estractura para conexion o login 
try {
    $conexion = new PDO("mysql:host=$host;dbname=$bd", $usuario, $contrasenia);

    //mostrara mesaje si hay una conexion
    // if ($conexion) {  echo "conectado a sistemas "; } */(EL ANTERIOR MENSAJE COMENTADO ES PARA HACER PRUEBAS Y VERIFICAR QUE CONECTE A LA BD)

} catch (Exception $ex) {
    echo $ex->getMessage();
}

?>




<!-- para direccionar la url y octener datos del host -->
<?php $url = "http://" . $_SERVER['HTTP_HOST'] . "/ploco/sitioweb"; ?>