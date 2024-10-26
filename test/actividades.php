<?php
include 'php/conexion.php';

// Iniciar sesión
session_start();

// Obtener el ID del subcurso desde la URL
$id_subcurso = isset($_GET['id_subcurso']) ? intval($_GET['id_subcurso']) : 0;

// Insertar nueva actividad
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_entrega = $_POST['fecha_entrega'];
    $id_docente = $_SESSION['user_id']; // Asumimos que el ID del docente está en la sesión

    $sql = "INSERT INTO Actividades (titulo, descripcion, fecha_entrega, id_subcurso, id_docente) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssii', $titulo, $descripcion, $fecha_entrega, $id_subcurso, $id_docente);

    if ($stmt->execute()) {
        echo "Actividad creada exitosamente.";
    } else {
        echo "Error al crear la actividad: " . $stmt->error;
    }
    $stmt->close();
}

// Mostrar actividades del subcurso específico
$sql = "SELECT * FROM Actividades WHERE id_subcurso = ?";
$stmt_actividades = $conn->prepare($sql);
$stmt_actividades->bind_param('i', $id_subcurso);
$stmt_actividades->execute();
$result = $stmt_actividades->get_result();

// Obtener el nombre del subcurso
$sql_subcurso = "SELECT nombre_subcurso FROM Subcursos WHERE id_subcurso = ?";
$stmt_subcurso = $conn->prepare($sql_subcurso);
$stmt_subcurso->bind_param('i', $id_subcurso);
$stmt_subcurso->execute();
$stmt_subcurso->bind_result($nombre_subcurso);
$stmt_subcurso->fetch();
$stmt_subcurso->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Actividades</title>
    <link rel="stylesheet" href="css/archivo.css">
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
</head>
<body>
<header>
        <h1>Actividades del Docente</h1>
        <nav>
            <a href="Pinicio.php">Inicio</a>
            <a href="Ppersonal.php">Personal</a>    
            <a href="Pcursos.php">Cursos</a>
            <a href="Pperfil.php">Perfil</a>
            <a href="index.php">Cerrar sesión</a>
        </nav>
    </header>

<h1>Crear Nueva Actividad</h1>
<form action="actividades.php?id_subcurso=<?php echo $id_subcurso; ?>" method="post">
    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" required><br>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion" required></textarea><br>

    <label for="fecha_entrega">Fecha de Entrega:</label>
    <input type="date" id="fecha_entrega" name="fecha_entrega" required><br>

    <label for="id_docente">ID Docente:</label>
    <input type="number" id="id_docente" name="id_docente" value="<?php echo $_SESSION['user_id']; ?>" readonly><br>

    <button type="submit">Crear Actividad</button>
</form>

<h2>Lista de Actividades del Subcurso: <?php echo htmlspecialchars($nombre_subcurso); ?></h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Descripción</th>
        <th>Fecha de Entrega</th>
        <th>ID Subcurso</th>
        <th>ID Docente</th>
    </tr>
    <?php while ($actividad = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $actividad['id_actividad']; ?></td>
        <td><?php echo htmlspecialchars($actividad['titulo']); ?></td>
        <td><?php echo htmlspecialchars($actividad['descripcion']); ?></td>
        <td><?php echo htmlspecialchars($actividad['fecha_entrega']); ?></td>
        <td><?php echo htmlspecialchars($actividad['id_subcurso']); ?></td>
        <td><?php echo htmlspecialchars($actividad['id_docente']); ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
