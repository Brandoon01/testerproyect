<?php
// Incluir el archivo de conexión a la base de datos
session_start();
include 'php/conexion.php'; // Asegúrate de que la ruta sea correcta

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Si no está logueado, redirige al inicio de sesión
    header("Location: index.php");
    exit();
}

// Obtener el ID del usuario desde la sesión
$alumno_id = $_SESSION['user_id'];

// Consulta para obtener los datos del alumno
$sql = "SELECT nombre, apellido, carrera, email, telefono FROM alumnos WHERE id_alumno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $alumno_id);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró el alumno
if ($result->num_rows > 0) {
    // Obtener los datos del alumno
    $alumno_datos = $result->fetch_assoc();
} else {
    echo "No se encontraron datos para el alumno.";
}
$stmt->close();

// Consulta para obtener las materias en las que el alumno está inscrito
$query = "SELECT c.nombre_curso
          FROM cursos c 
          JOIN inscripciones i ON c.id_curso = i.id_curso 
          WHERE i.id_alumno = ?";
$stmt = $conn->prepare($query); // Prepara la consulta
$stmt->bind_param("i", $alumno_id);    // Asigna el ID del alumno
$stmt->execute();
$result_materias = $stmt->get_result(); // Obtiene los resultados de la consulta
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Estudiante</title>
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
</head>
<body>
    <header>
        <h1>Perfil del Estudiante</h1>
        <nav>
            <a href="inicio.php">Inicio</a>
            <a href="personal.php">Personal</a>    
            <a href="cursos.php">Cursos</a>
            <a href="perfil.php">Perfil</a>
            <a href="index.php">Cerrar sesión</a>
        </nav>
    </header>
    <main>
        <section class="perfil">
            <h2>Información Personal</h2>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($alumno_datos['nombre']); ?></p>
            <p><strong>Apellido:</strong> <?php echo htmlspecialchars($alumno_datos['apellido']); ?></p>
            <p><strong>Carrera:</strong> <?php echo htmlspecialchars($alumno_datos['carrera']); ?></p>
        </section>
        <section class="materias">
            <h2>Materias Inscritas</h2>
            <ul>
                <?php
                // Verifica si el alumno tiene materias inscritas
                if ($result_materias->num_rows > 0) {
                    // Recorre los cursos y los muestra
                    while ($row = $result_materias->fetch_assoc()) {
                        echo '<li>' . htmlspecialchars($row['nombre_curso'], ENT_QUOTES, 'UTF-8') . '</li>';
                    }
                } else {
                    // Si no tiene cursos
                    echo '<p>No tienes cursos registrados.</p>';
                }
                ?>
            </ul>
        </section>
        <section class="contacto">
            <h2>Contacto</h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($alumno_datos['email']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($alumno_datos['telefono']); ?></p>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Juan Pérez</p>
    </footer>
</body>
</html>

<?php
// Cierra la conexión y la declaración
$stmt->close();
$conn->close();
?>
