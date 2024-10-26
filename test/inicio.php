<?php
session_start();

// Verificar si hay una sesión activa
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html"); // Redirigir al login si no hay sesión activa
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aula Virtual</title>
    <link rel="stylesheet" href="css/inicio.css">
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
        <section class="virtual-classroom">
            <div class="container">
                <h2>Aula Virtual</h2>
                <p>Nuestra aula virtual está diseñada para proporcionar una experiencia de aprendizaje enriquecedora y flexible. A continuación, se presentan algunas de sus características clave:</p>
                <div class="features-grid">
                    <div class="feature">
                        <h3>Interactividad</h3>
                        <p>Participa en discusiones en tiempo real y colabora con compañeros y profesores a través de foros y chats.</p>
                    </div>
                    <div class="feature">
                        <h3>Material Multimedia</h3>
                        <p>Accede a videos, presentaciones y recursos interactivos que facilitan el aprendizaje.</p>
                    </div>
                    <div class="feature">
                        <h3>Evaluaciones y Retroalimentación</h3>
                        <p>Realiza evaluaciones en línea y recibe retroalimentación inmediata para mejorar tu rendimiento.</p>
                    </div>
                    <div class="feature">
                        <h3>Acceso a Recursos</h3>
                        <p>Disponibilidad de materiales de estudio, libros y artículos en una biblioteca digital accesible las 24 horas.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>        
    <footer>
        <p>&copy; 2024 Aula Virtual. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

