<?php
// Datos de conexión a la base de datos
$NAMEHOSTBD = 'localhost';
$USERNAMEBD = 'root';
$PASSWORDBD = '';
$BDNAME     = 'prototipo'; 

// Realizar la conexión a la base de datos
$conn = new mysqli($NAMEHOSTBD, $USERNAMEBD, $PASSWORDBD, $BDNAME);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
$conectar = mysqli_connect($NAMEHOSTBD, $USERNAMEBD, $PASSWORDBD, $BDNAME);
if (!$conectar) {
    die("No se pudo conectar a la base de datos: " . mysqli_connect_error());
}

?>
