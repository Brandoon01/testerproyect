<?php
// Incluir el archivo de conexión a la base de datos
include 'php/conexion.php';

// Iniciar sesión
session_start();

// Obtener el ID del alumno desde la sesión
$user_id = $_SESSION['user_id']; // ID del alumno

// Consultar los cursos en los que está inscrito el alumno
$query_cursos = "
    SELECT c.id_curso, c.nombre_curso 
    FROM Cursos c 
    JOIN Inscripciones i ON c.id_curso = i.id_curso 
    WHERE i.id_alumno = ?";
$stmt_cursos = $conn->prepare($query_cursos);
$stmt_cursos->bind_param("i", $user_id);
$stmt_cursos->execute();
$result_cursos = $stmt_cursos->get_result();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Cursos</title>
    <link rel="stylesheet" href="css/cursos.css">
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
</head>
<body>
    <header>
        <div class="container">
            <h1>AULA VIRTUAL</h1>
            <nav>
                <a href="inicio.php">Inicio</a>
                <a href="personal.php">Personal</a>    
                <a href="cursos.php">Cursos</a>
                <a href="perfil.php">Perfil</a>
                <a href="index.php">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="container">
            <h2>Mis cursos</h2>

            <div class="filters">
                <select name="filter" id="filter">
                    <option value="todos">Todos</option>
                    <option value="pendientes">Pendientes</option>
                    <option value="aprobados">Aprobados</option>
                </select>
                <input type="text" placeholder="Buscar" id="search">
                <select name="order" id="order">
                    <option value="nombre">Nombre del curso</option>
                    <option value="fecha">Fecha de inicio</option>
                </select>
            </div>

            <div class="courses">
    <?php
    // Verifica si el alumno tiene cursos registrados
if ($result_cursos->num_rows > 0) {
    // Recorre los cursos y los muestra
    while ($row = $result_cursos->fetch_assoc()) {
        echo '<div class="course">';
        echo '<img src="cursos/course.jpg" alt="Curso">'; // Imagen genérica del curso
        
        // Cambia el nombre del curso a un enlace
        echo '<a href="deploy.php?id_curso=' . htmlspecialchars($row['id_curso']) . '">Subcursos de ' . htmlspecialchars($row['nombre_curso']) . '</a>';
        
        echo '</div>';
    }
} else {
    // Si no tiene cursos
    echo '<p>No tienes cursos registrados.</p>';
}
    ?>
</div>

        </section>
    </main>

    <footer>
        <p>&copy; 2023 Aula Virtual</p>
    </footer>
</body>
</html>

<?php
// Cierra la conexión y la declaración
$stmt->close();
$conectar->close();
?>
