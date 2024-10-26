<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos</title>
    <link rel="stylesheet" href="css/deplo.css">
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
</head>
<body>
    <header>
        <h1>Perfil del Alumno</h1>
        <nav>
            <a href="inicio.php">Inicio</a>
            <a href="personal.php">Personal</a>    
            <a href="cursos.php">Cursos</a>
            <a href="perfil.php">Perfil</a>
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
            <div class="curso">
                <ul>
                    <?php
                    // Incluir el archivo de conexión a la base de datos
                    include 'php/conexion.php';

                    // Iniciar sesión
                    session_start();

                    // Obtener el ID del alumno desde la sesión
                    $user_id = $_SESSION['user_id']; // ID del alumno
                    $id_curso = $_GET['id_curso']; // ID del curso desde la URL (asegúrate de que se pase correctamente)

                    // Consultar los subcursos en los que está inscrito el alumno y que pertenecen a un curso específico
                    $query_subcursos = "
                        SELECT s.id_subcurso, s.nombre_subcurso 
                        FROM Subcursos s
                        JOIN Inscripciones i ON s.id_curso = i.id_curso
                        WHERE i.id_alumno = ? AND s.id_curso = ?";
                    
                    $stmt_subcursos = $conn->prepare($query_subcursos);
                    $stmt_subcursos->bind_param("ii", $user_id, $id_curso);
                    $stmt_subcursos->execute();
                    $result_subcursos = $stmt_subcursos->get_result();

                    // Mostrar los subcursos en la lista
                    if ($result_subcursos->num_rows > 0) {
                        while ($subcurso = $result_subcursos->fetch_assoc()) {
                            // Enlace a la página de entregas, pasando el ID del subcurso
                            echo "<li><a href='desploy.php?id_subcurso=" . htmlspecialchars($subcurso['id_subcurso']) . "' class='course-button'>" . htmlspecialchars($subcurso['nombre_subcurso']) . "</a></li>";
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
            <p><strong>Alumno:</strong> MARIA LUZ ANGELA LOAIZA ALVAREZ</p>
            <p><strong>Correo electrónico:</strong> nombre.apellido@uniagustiniana.edu.co</p>
        </div>
    </div>

    <!-- Footer (si es necesario) -->
</body>
</html>
