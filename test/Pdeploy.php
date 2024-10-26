<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aula Virtual</title>
    <link rel="stylesheet" href="css/deplo.css">
</head>
<body>
    <header>
        <h1>Perfil del Docente</h1>
        <nav>
            <a href="Pinicio.php">Inicio</a>
            <a href="Ppersonal.php">Personal</a>    
            <a href="Pcursos.php">Cursos</a>
            <a href="Pperfil.php">Perfil</a>
            <a href="index.php">Cerrar sesión</a>
        </nav>
    </header>
    <div class="container">
        <h1>MARIA LUZ ANGELA LOAIZA ALVAREZ</h1>

        <!-- Tabs de navegación -->
        <ul class="tabs">
            <li class="active"><a href="#">Curso</a></li>
            <li><a href="#">Participantes</a></li>
            <li><a href="#">Calificaciones</a></li>
            <li><a href="#">Competencias</a></li>
        </ul>

        <!-- Información del curso -->
        <div class="course-info">
            <h2>Subcursos</h2> <!-- Título para la sección de subcursos -->
            <div class="curso">
                <ul>
                    <?php
// Incluir el archivo de conexión a la base de datos
include 'php/conexion.php';

// Iniciar sesión
session_start();

// Obtener el ID del docente desde la sesión
$user_id = $_SESSION['user_id']; // ID del docente

// Obtener el ID del curso desde la URL
$id_curso = isset($_GET['id_curso']) ? intval($_GET['id_curso']) : 0;

// Consultar los subcursos que imparte el docente para el curso específico
$query_subcursos = "
    SELECT sc.id_subcurso, sc.nombre_subcurso 
    FROM Subcursos sc 
    WHERE sc.id_docente = ? AND sc.id_curso = ?";
$stmt_subcursos = $conn->prepare($query_subcursos);
$stmt_subcursos->bind_param("ii", $user_id, $id_curso); // "i" para ID de docente y ID de curso
$stmt_subcursos->execute();
$result_subcursos = $stmt_subcursos->get_result();

// Mostrar los subcursos en la lista
if ($result_subcursos->num_rows > 0) {
    while ($subcurso = $result_subcursos->fetch_assoc()) {
        // Enlace a la página de actividades, pasando el ID del subcurso
        echo "<li><a href='actividades.php?id_subcurso=" . htmlspecialchars($subcurso['id_subcurso']) . "' class='course-button'>" . htmlspecialchars($subcurso['nombre_subcurso']) . "</a></li>";
    }
} else {
    echo "<li>No hay subcursos disponibles para este curso.</li>";
}

// Cerrar la conexión
$stmt_subcursos->close();
$conn->close();
?>
                </ul>
            </div>
        </div>

        <!-- Sección de bienvenida -->
        <div class="bienvenido">
            <h2>Bienvenido</h2>
            <p><strong>Docente:</strong> MARIA LUZ ANGELA LOAIZA ALVAREZ</p>
            <p><strong>Correo electrónico:</strong> nombre.apellido@uniagustiniana.edu.co</p>
        </div>
    </div>

    <!-- Footer (si es necesario) -->
</body>
</html>
