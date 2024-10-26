<?php
include 'php/conexion.php';

// Iniciar sesión
session_start();

// Obtener el ID del subcurso desde la URL
$id_subcurso = isset($_GET['id_subcurso']) ? intval($_GET['id_subcurso']) : 0;

// Insertar nueva entrega
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['archivo'])) {
    $id_actividad = $_POST['id_actividad'];
    $id_alumno = $_SESSION['user_id']; // Asumimos que el ID del alumno está en la sesión
    $nombre_archivo = $_FILES['archivo']['name'];
    $ruta_archivo = 'uploads/' . basename($nombre_archivo);

    // Mover el archivo a la carpeta de destino
    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_archivo)) {
        // Insertar la entrega en la base de datos
        $sql = "INSERT INTO Entregas (id_actividad, id_alumno, nombre_archivo, ruta_archivo) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiss', $id_actividad, $id_alumno, $nombre_archivo, $ruta_archivo);

        if ($stmt->execute()) {
            echo "Archivo subido y entrega registrada con éxito.";
        } else {
            echo "Error al registrar la entrega: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error al subir el archivo.";
    }
}

// Mostrar entregas de las actividades del subcurso específico
$sql_entregas = "
    SELECT e.*, a.titulo AS actividad_titulo, al.nombre AS alumno_nombre
    FROM Entregas e
    JOIN Actividades a ON e.id_actividad = a.id_actividad
    JOIN Alumnos al ON e.id_alumno = al.id_alumno
    WHERE a.id_subcurso = ?";
$stmt_entregas = $conn->prepare($sql_entregas);
$stmt_entregas->bind_param('i', $id_subcurso);
$stmt_entregas->execute();
$result_entregas = $stmt_entregas->get_result();

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
    <title>Gestión de Entregas</title>
    <link rel="stylesheet" href="css/archivo.css">
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
</head>
<body>
<header>
        <h1>Entregas del Alumno</h1>
        <nav>
            <a href="inicio.php">Inicio</a>
            <a href="personal.php">Personal</a>    
            <a href="cursos.php">Cursos</a>
            <a href="perfil.php">Perfil</a>
            <a href="index.php">Cerrar sesión</a>
        </nav>
    </header>

<h1>Lista de Entregas del Subcurso: <?php echo htmlspecialchars($nombre_subcurso); ?></h1>

<!-- Formulario para subir entrega -->
<h2>Subir Nueva Entrega</h2>
<form action="entregas.php?id_subcurso=<?php echo $id_subcurso; ?>" method="post" enctype="multipart/form-data">
    <label for="id_actividad">Actividad:</label>
    <select id="id_actividad" name="id_actividad" required>
        <option value="">Seleccione una actividad</option>
        <?php
        // Obtener actividades del subcurso para llenar el select
        $sql_actividades = "SELECT id_actividad, titulo FROM Actividades WHERE id_subcurso = ?";
        $stmt_actividades = $conn->prepare($sql_actividades);
        $stmt_actividades->bind_param('i', $id_subcurso);
        $stmt_actividades->execute();
        $result_actividades = $stmt_actividades->get_result();
        while ($actividad = $result_actividades->fetch_assoc()):
        ?>
            <option value="<?php echo $actividad['id_actividad']; ?>">
                <?php echo htmlspecialchars($actividad['titulo']); ?>
            </option>
        <?php endwhile; ?>
    </select><br>

    <label for="archivo">Archivo:</label>
    <input type="file" id="archivo" name="archivo" required><br>

    <button type="submit">Subir Archivo</button>
</form>

<!-- Tabla de entregas -->
<h2>Lista de Entregas</h2>
<table border="1">
    <tr>
        <th>ID Entrega</th>
        <th>Actividad</th>
        <th>Alumno</th>
        <th>Nombre Archivo</th>
        <th>Ruta Archivo</th>
    </tr>
    <?php while ($entrega = $result_entregas->fetch_assoc()): ?>
    <tr>
        <td><?php echo $entrega['id_entrega']; ?></td>
        <td><?php echo htmlspecialchars($entrega['actividad_titulo']); ?></td>
        <td><?php echo htmlspecialchars($entrega['alumno_nombre']); ?></td>
        <td><?php echo htmlspecialchars($entrega['nombre_archivo']); ?></td>
        <td><?php echo htmlspecialchars($entrega['ruta_archivo']); ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
