<?php
session_start();
include('php/conexion.php'); // Incluye la conexión a la base de datos

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Si no está logueado, redirige al inicio de sesión
    header("Location: index.php");
    exit();
}

// Obtén el ID del docente
$user_id = $_SESSION['user_id'];

// Prepara la consulta para obtener los cursos impartidos por el docente
$query = "SELECT nombre_curso,id_curso
          FROM cursos 
          WHERE id_docente = ?";

$stmt = $conectar->prepare($query); // Prepara la consulta
$stmt->bind_param("i", $user_id);    // Asigna el ID del docente
$stmt->execute();
$result = $stmt->get_result();       // Obtiene los resultados de la consulta
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
                <a href="Pinicio.php">Inicio</a>
                <a href="Ppersonal.php">Personal</a>    
                <a href="Pcursos.php">Cursos</a>
                <a href="Pperfil.php">Perfil</a>
                <a href="index.php">Cerrar sesion</a>
                <span></span>
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
    // Verifica si el docente tiene cursos registrados
    if ($result->num_rows > 0) {
        // Recorre los cursos y los muestra
        while ($row = $result->fetch_assoc()) {
            echo '<div class="course">';
            echo '<img src="cursos/course2' . '.jpg" alt="Curso">'; // Imagen genérica del curso
            // Cambia el nombre del curso a un enlace
            echo '<a href="Pdeploy.php?id_curso=' . htmlspecialchars($row['id_curso']) . '">Curso de ' . htmlspecialchars($row['nombre_curso']) . '</a>';
        

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

    <script src="java/script.js"></script>
</body>
</html>
<?php
// Cierra la conexión y la declaración
$stmt->close();
$conectar->close();
?>
