<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

$usuario_nombre = $_SESSION['usuario_nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario - Los Olivos</title>
    <link rel="stylesheet" href="stylepanel.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="logo">
    <div class="logo-icon">
        <img src="img/Logo.png" alt="Logo Los Olivos" width="100" height="80">
    </div>
    <div class="logo-text">
        <h1>LOS OLIVOS</h1>
        <p>restaurant | eventos</p>
    </div>
</div>

            <div class="user-info">
                <a href="mis_reservas.php" class="nav-link">Mis Reservas</a>
                <span>Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?></span>
                <a href="cerrar_sesion.php" class="logout-btn">Cerrar Sesión</a>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <div class="panel-container">
            <h1>Panel de Usuario</h1>
            
            <div class="panel-grid">
                <!-- Reservar Mesa -->
                <div class="panel-card">
                    <h2>Reservar Mesa</h2>
                    <form action="guardar_reserva.php" method="POST" class="panel-form">
                        <div class="form-group">
                            <label for="fecha_mesa">Fecha</label>
                            <input type="date" id="fecha_mesa" name="fecha" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="hora_mesa">Hora</label>
                            <input type="time" id="hora_mesa" name="hora" required>
                        </div>
                        <div class="form-group">
                            <label for="personas">Número de Personas</label>
                            <select id="personas" name="personas" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">1 persona</option>
                                <option value="2">2 personas</option>
                                <option value="3">3 personas</option>
                                <option value="4">4 personas</option>
                                <option value="5">5 personas</option>
                                <option value="6">6 personas</option>
                                <option value="7">7 personas</option>
                                <option value="8">8 personas</option>
                                <option value="9">9 personas</option>
                                <option value="10">10+ personas</option>
                            </select>
                        </div>
                        <button type="submit" class="submit-btn">Reservar Mesa</button>
                    </form>
                </div>

                <!-- Reservar Evento -->
                <div class="panel-card">
                    <h2>Reservar Evento</h2>
                    <form action="guardar_evento.php" method="POST" class="panel-form">
                        <div class="form-group">
                            <label for="nombre_evento">Nombre del Evento</label>
                            <input type="text" id="nombre_evento" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_evento">Fecha</label>
                            <input type="date" id="fecha_evento" name="fecha" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="hora_evento">Hora</label>
                            <input type="time" id="hora_evento" name="hora" required>
                        </div>
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" id="lugar" name="lugar" required placeholder="Salón principal, terraza, etc.">
                        </div>
                        <div class="form-group">
                            <label for="asistentes">Número de Asistentes</label>
                            <input type="number" id="asistentes" name="asistentes" required min="100" max="600">
                        </div>
                        <button type="submit" class="submit-btn">Reservar Evento</button>
                    </form>
                </div>

                <!-- Enviar Comentario -->
                <div class="panel-card">
                    <h2>Enviar Comentario</h2>
                    <form action="guardar_comentario.php" method="POST" class="panel-form">
                        <div class="form-group">
                            <label for="comentario">Tu Comentario</label>
                            <textarea id="comentario" name="comentario" required rows="4" placeholder="Comparte tu experiencia..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Calificación</label>
                            <div class="rating-stars">
                                <input type="radio" id="star5" name="calificacion" value="5" required>
                                <label for="star5">★</label>
                                <input type="radio" id="star4" name="calificacion" value="4">
                                <label for="star4">★</label>
                                <input type="radio" id="star3" name="calificacion" value="3">
                                <label for="star3">★</label>
                                <input type="radio" id="star2" name="calificacion" value="2">
                                <label for="star2">★</label>
                                <input type="radio" id="star1" name="calificacion" value="1">
                                <label for="star1">★</label>
                            </div>
                        </div>
                        <button type="submit" class="submit-btn">Enviar Comentario</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="scriptpanel.js"></script>
</body>
</html>
