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
    <title>Aula Virtual</title>
    <link rel="stylesheet" href="css/personal.css">
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
</head>
<body>
    <header>
        <h1>AULA VIRTUAL</h1>
        <nav>
            <a href="inicio.php">Inicio</a>
            <a href="personal.php">Personal</a>    
            <a href="cursos.php">Cursos</a>
            <a href="perfil.php">Perfil</a>
            <a href="index.php">Cerrar sesion</a>
            <span></span>
        </nav>
    </header>
    <main>
    <section>
      <h1>Área personal</h1>
      <section class="timeline">
          <h2>Línea de tiempo</h2>
          <div class="timeline-filters">
              <select>
                  <option value="7dias">Próximos 7 días</option>
              </select>
              <select>
                  <option value="fecha">Ordenar por fecha</option>
              </select>
              <input type="text" placeholder="Buscar por tipo o nombre de actividad">
          </div>
          <div class="no-activities">
              <img src="img/icono.jpg" alt="No hay actividades">
              <p>No hay actividades que requieran acciones</p>
          </div>
      </section>
      <section>
          <ul>
              <?php
              // Mostramos los cursos obtenidos de la consulta
              if ($result_materias->num_rows > 0) {
                  while ($row = $result_materias->fetch_assoc()) {
                      echo "<li>
                              <h3>{$row['nombre_curso']}</h3>
                            </li>";
                  }
              } else {
                  echo "<p>No estás inscrito en ningún curso.</p>";
              }
              ?>
          </ul>
      </section>
      <footer>
        <p>&copy; 2024 Aula Virtual. Todos los derechos reservados.</p>
      </footer>
    </main>
</body>
</html>

<?php
// Cierra la conexión y la declaración
$stmt->close();
$conectar->close();
?>

