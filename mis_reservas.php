<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$usuario_nombre = $_SESSION['usuario_nombre'];

// Obtener reservas de mesa del usuario
$stmt_reservas = $conexion->prepare("
    SELECT * FROM reservas 
    WHERE usuario_id = ? 
    ORDER BY fecha_creacion DESC
");
$stmt_reservas->execute([$usuario_id]);
$reservas = $stmt_reservas->fetchAll(PDO::FETCH_ASSOC);

// Obtener eventos del usuario
$stmt_eventos = $conexion->prepare("
    SELECT * FROM eventos 
    WHERE usuario_id = ? 
    ORDER BY fecha_creacion DESC
");
$stmt_eventos->execute([$usuario_id]);
$eventos = $stmt_eventos->fetchAll(PDO::FETCH_ASSOC);

// Obtener comentarios del usuario
$stmt_comentarios = $conexion->prepare("
    SELECT * FROM comentarios 
    WHERE usuario_id = ? 
    ORDER BY fecha_creacion DESC
");
$stmt_comentarios->execute([$usuario_id]);
$comentarios = $stmt_comentarios->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas - Los Olivos</title>
    <link rel="stylesheet" href="stylepanel.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="logo">
                <div class="logo-icon">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                        <path d="M8 12L20 8L32 12L28 16L20 14L12 16L8 12Z" fill="white"/>
                        <path d="M10 18L20 16L30 18L28 22L20 20L12 22L10 18Z" fill="white"/>
                        <path d="M12 24L20 22L28 24L26 28L20 26L14 28L12 24Z" fill="white"/>
                    </svg>
                </div>
                <div class="logo-text">
                    <h1>LOS OLIVOS</h1>
                    <p>restaurant | eventos</p>
                </div>
            </div>
            <div class="user-info">
                <a href="panel_usuario.php" class="nav-link">Panel</a>
                <span>Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?></span>
                <a href="cerrar_sesion.php" class="logout-btn">Cerrar Sesión</a>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <div class="panel-container">
            <h1>Mis Reservas y Eventos</h1>
            
            <!-- Reservas de Mesa -->
            <div class="admin-section">
                <h2>Mis Reservas de Mesa</h2>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Personas</th>
                                <th>Estado</th>
                                <th>Reservado el</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservas as $reserva): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($reserva['fecha'])); ?></td>
                                <td><?php echo date('H:i', strtotime($reserva['hora'])); ?></td>
                                <td><?php echo $reserva['personas']; ?> personas</td>
                                <td>
                                    <?php 
                                    $fecha_reserva = strtotime($reserva['fecha']);
                                    $hoy = strtotime(date('Y-m-d'));
                                    if ($fecha_reserva < $hoy) {
                                        echo '<span class="status-pasado">Pasado</span>';
                                    } elseif ($fecha_reserva == $hoy) {
                                        echo '<span class="status-hoy">Hoy</span>';
                                    } else {
                                        echo '<span class="status-futuro">Próximo</span>';
                                    }
                                    ?>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($reserva['fecha_creacion'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($reservas)): ?>
                            <tr>
                                <td colspan="5" class="no-data">No tienes reservas de mesa</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Eventos -->
            <div class="admin-section">
                <h2>Mis Eventos</h2>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Evento</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Lugar</th>
                                <th>Asistentes</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($eventos as $evento): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($evento['nombre']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($evento['fecha'])); ?></td>
                                <td><?php echo date('H:i', strtotime($evento['hora'])); ?></td>
                                <td><?php echo htmlspecialchars($evento['lugar']); ?></td>
                                <td><?php echo $evento['asistentes']; ?></td>
                                <td>
                                    <?php 
                                    $fecha_evento = strtotime($evento['fecha']);
                                    $hoy = strtotime(date('Y-m-d'));
                                    if ($fecha_evento < $hoy) {
                                        echo '<span class="status-pasado">Pasado</span>';
                                    } elseif ($fecha_evento == $hoy) {
                                        echo '<span class="status-hoy">Hoy</span>';
                                    } else {
                                        echo '<span class="status-futuro">Próximo</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($eventos)): ?>
                            <tr>
                                <td colspan="6" class="no-data">No tienes eventos registrados</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Comentarios -->
            <div class="admin-section">
                <h2>Mis Comentarios</h2>
                <div class="comments-grid">
                    <?php foreach ($comentarios as $comentario): ?>
                    <div class="comment-card <?php echo $comentario['estado']; ?>">
                        <div class="comment-header">
                            <div class="comment-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?php echo $i <= $comentario['calificacion'] ? 'filled' : ''; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <span class="comment-status status-<?php echo $comentario['estado']; ?>">
                                <?php 
                                switch($comentario['estado']) {
                                    case 'pendiente': echo 'Pendiente'; break;
                                    case 'aprobado': echo 'Aprobado'; break;
                                    case 'rechazado': echo 'Rechazado'; break;
                                }
                                ?>
                            </span>
                        </div>
                        <p class="comment-text"><?php echo htmlspecialchars($comentario['comentario']); ?></p>
                        <div class="comment-date">
                            <?php echo date('d/m/Y H:i', strtotime($comentario['fecha_creacion'])); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($comentarios)): ?>
                    <div class="no-data">No has enviado comentarios aún</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <style>
        .nav-link {
            color: #3766d4ff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #1f24b8ff;
        }

        .status-pasado {
            background-color: rgba(108, 117, 125, 0.2);
            color: #6c757d;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-hoy {
            background-color: rgba(255, 193, 7, 0.2);
            color: #ffffffff;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-futuro {
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .admin-section {
            margin-bottom: 3rem;
        }

        .admin-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: #ffffffff;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #ffffffff;
            padding-bottom: 0.5rem;
        }

        .table-container {
            overflow-x: auto;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1rem;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            color: white;
        }

        .admin-table th,
        .admin-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-table th {
            background-color: rgba(77, 77, 77, 1);
            font-weight: 600;
            color: #ffffffff;
        }

        .admin-table tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .comments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .comment-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1.5rem;
            border-left: 4px solid #666;
        }

        .comment-card.aprobado {
            border-left-color: #28a745;
        }

        .comment-card.rechazado {
            border-left-color: #dc3545;
        }

        .comment-card.pendiente {
            border-left-color: #ffffffff;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .comment-rating .star {
            color: #666;
            font-size: 1.2rem;
        }

        .comment-rating .star.filled {
            color: #d4af37;
        }

        .comment-text {
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .comment-date {
            font-size: 0.9rem;
            color: #ccc;
            text-align: right;
        }

        .comment-status {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pendiente {
            background-color: rgba(184, 176, 152, 0.2);
            color: #ffc107;
        }

        .status-aprobado {
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .status-rechazado {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        .no-data {
            text-align: center;
            color: #999;
            font-style: italic;
            padding: 2rem;
        }
    </style>
</body>
</html>
