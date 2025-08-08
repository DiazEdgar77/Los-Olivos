<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Obtener eventos
$stmt_eventos = $conexion->prepare("
    SELECT e.*, u.nombre as usuario_nombre 
    FROM eventos e 
    JOIN usuarios u ON e.usuario_id = u.id 
    ORDER BY e.fecha_creacion DESC
");


$stmt_eventos->execute();
$eventos = $stmt_eventos->fetchAll(PDO::FETCH_ASSOC);

$stmt_reservas = $conexion->prepare("
    SELECT r.*, u.nombre AS usuario_nombre
    FROM reservas r
    JOIN usuarios u ON r.usuario_id = u.id
    ORDER BY r.fecha_creacion DESC
");

$stmt_reservas->execute();
$reservas = $stmt_reservas->fetchAll(PDO::FETCH_ASSOC);

// Obtener comentarios
$stmt_comentarios = $conexion->prepare("
    SELECT c.*, u.nombre as usuario_nombre 
    FROM comentarios c 
    JOIN usuarios u ON c.usuario_id = u.id 
    ORDER BY c.fecha_creacion DESC
");
$stmt_comentarios->execute();
$comentarios = $stmt_comentarios->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador - Los Olivos</title>
    <link rel="stylesheet" href="stylepanel.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="logo-icon">
                     <img src="img/Logo.png" alt="Logo Los Olivos" width="100" height="80">
                </div>
                </div>
                <div class="logo-text">
                    <h1>LOS OLIVOS</h1>
                    <p>restaurant | eventos</p>
                </div>
            </div>
            <div class="user-info">
                <span>Administrador</span>
                <a href="cerrar_sesion.php" class="logout-btn">Cerrar Sesión</a>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <div class="panel-container">
            <h1>Panel de Administrador</h1>
            
            <!-- Eventos -->
<div class="admin-section">
    <h2>Eventos Registrados</h2>
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Evento</th>
                    <th>Teléfono</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Lugar</th>
                    <th>Asistentes</th>
                    <th>Registrado</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($eventos as $evento): ?>
                <tr>
                    <td><?= htmlspecialchars($evento['usuario_nombre']) ?></td>
                    <td><?= htmlspecialchars($evento['nombre']) ?></td>
                    <td><?= htmlspecialchars($evento['telefono']) ?></td>
                    <td><?= date('d/m/Y', strtotime($evento['fecha'])) ?></td>
                    <td><?= date('H:i', strtotime($evento['hora'])) ?></td>
                    <td><?= htmlspecialchars($evento['lugar']) ?></td>
                    <td><?= $evento['asistentes'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($evento['fecha_creacion'])) ?></td>
                    <td>
                        <?php if ($evento['estado'] === 'pendiente'): ?>
                            <form action="moderar_evento.php" method="POST" style="display:inline;">
                                <input type="hidden" name="evento_id" value="<?= $evento['id'] ?>">
                                <input type="hidden" name="accion" value="aprobar">
                                <button type="submit" class="approve-btn">Aprobar</button>
                            </form>
                            <form action="moderar_evento.php" method="POST" style="display:inline;">
                                <input type="hidden" name="evento_id" value="<?= $evento['id'] ?>">
                                <input type="hidden" name="accion" value="rechazar">
                                <button type="submit" class="reject-btn">Rechazar</button>
                            </form>
                        <?php else: ?>
                            <span class="comment-status status-<?= $evento['estado'] ?>">
                                <?= ucfirst($evento['estado']) ?>
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($eventos)): ?>
                <tr>
                    <td colspan="9" class="no-data">No hay eventos registrados</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

                    <h2>Reservas de Mesas</h2>
<div class="table-container">
<table class="admin-table">
  <thead>
    <tr>
      <th>Usuario</th>
      <th>Fecha</th>
      <th>Hora</th>
      <th>Personas</th>
      <th>Estado</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($reservas as $reserva): ?>
    <tr>
      <td><?= htmlspecialchars($reserva['usuario_nombre']) ?></td>
      <td><?= htmlspecialchars($reserva['fecha']) ?></td>
      <td><?= htmlspecialchars($reserva['hora']) ?></td>
      <td><?= htmlspecialchars($reserva['personas']) ?></td>
      <td>
        <?php if ($reserva['estado'] === 'pendiente'): ?>
          <form action="moderar_reserva.php" method="POST" style="display:inline;">
              <input type="hidden" name="reserva_id" value="<?= $reserva['id'] ?>">
              <input type="hidden" name="accion" value="aprobar">
              <button type="submit" class="approve-btn">Aprobar</button>
          </form>
          <form action="moderar_reserva.php" method="POST" style="display:inline;">
              <input type="hidden" name="reserva_id" value="<?= $reserva['id'] ?>">
              <input type="hidden" name="accion" value="rechazar">
              <button type="submit" class="reject-btn">Rechazar</button>
          </form>
        <?php else: ?>
          <span class="comment-status status-<?= $reserva['estado'] ?>">
              <?= ucfirst($reserva['estado']) ?>
          </span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($reservas)): ?>
    <tr>
      <td colspan="5" class="no-data">No hay reservas registradas</td>
    </tr>
    <?php endif; ?>
  </tbody>
</table>
</div>


            <!-- Comentarios -->
            <div class="admin-section">
                <h2>Comentarios</h2>
                <div class="comments-grid">
                    <?php foreach ($comentarios as $comentario): ?>
                    <div class="comment-card <?php echo $comentario['estado']; ?>">
                        <div class="comment-header">
                            <h3><?php echo htmlspecialchars($comentario['usuario_nombre']); ?></h3>
                            <div class="comment-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?php echo $i <= $comentario['calificacion'] ? 'filled' : ''; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="comment-text"><?php echo htmlspecialchars($comentario['comentario']); ?></p>
                        <div class="comment-footer">
                            <span class="comment-date"><?php echo date('d/m/Y H:i', strtotime($comentario['fecha_creacion'])); ?></span>
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
                        <?php if ($comentario['estado'] === 'pendiente'): ?>
                        <div class="comment-actions">
                            <form action="moderar_comentario.php" method="POST" style="display: inline;">
                                <input type="hidden" name="comentario_id" value="<?php echo $comentario['id']; ?>">
                                <input type="hidden" name="accion" value="aprobar">
                                <button type="submit" class="approve-btn">Aprobar</button>
                            </form>
                            <form action="moderar_comentario.php" method="POST" style="display: inline;">
                                <input type="hidden" name="comentario_id" value="<?php echo $comentario['id']; ?>">
                                <input type="hidden" name="accion" value="rechazar">
                                <button type="submit" class="reject-btn">Rechazar</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($comentarios)): ?>
                    <div class="no-data">No hay comentarios registrados</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <style>
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
            background-color: rgba(71, 71, 71, 0.97);
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
            border-left-color: #1c9f41ff;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .comment-header h3 {
            color: #4e37d4ff;
            font-size: 1.1rem;
        }

        .comment-rating .star {
            color: #666;
            font-size: 1.2rem;
        }

        .comment-rating .star.filled {
            color: #ede662ff;
        }

        .comment-text {
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .comment-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            color: #ccc;
        }

        .comment-status {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pendiente {
            background-color: rgba(255, 193, 7, 0.2);
            color: #000000ff;
        }

        .status-aprobado {
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .status-rechazado {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        .comment-actions {
            margin-top: 1rem;
            display: flex;
            gap: 0.5rem;
        }

        .approve-btn,
        .reject-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .approve-btn {
            background-color: #28a745;
            color: white;
        }

        .approve-btn:hover {
            background-color: #218838;
        }

        .reject-btn {
            background-color: #dc3545;
            color: white;
        }

        .reject-btn:hover {
            background-color: #c82333;
        }

        .no-data {
            text-align: center;
            color: #999;
            font-style: italic;
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .comments-grid {
                grid-template-columns: 1fr;
            }
            
            .admin-table {
                font-size: 0.9rem;
            }
            
            .admin-table th,
            .admin-table td {
                padding: 0.5rem;
            }
        }
    </style>
</body>
</html>
