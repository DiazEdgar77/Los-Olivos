<?php
require_once 'conexion.php';

// Obtener comentarios aprobados
$stmt = $conexion->prepare("
    SELECT c.comentario, c.calificacion, c.fecha_creacion, u.nombre as usuario_nombre 
    FROM comentarios c 
    JOIN usuarios u ON c.usuario_id = u.id 
    WHERE c.estado = 'aprobado' 
    ORDER BY c.fecha_creacion DESC
");
$stmt->execute();
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentarios - Los Olivos</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header"> 
        <nav class="navbar">
            <div class="logo">
                <div class="logo-icon">
                     <img src="img/Logo.png" alt="Logo Los Olivos" width="100" height="80">
                </div>
                </div>
                <div class="logo-text">
                    <h1>LOS OLIVOS</h1>
                    <p>restaurant | eventos</p>
                </div>
            </div>
            <ul class="nav-menu">
                <li><a href="index.html">Inicio</a></li>
                <li><a href="index.html#fotos">Fotos</a></li>
                <li><a href="index.html#reservar-mesa">Reservar mesa</a></li>
                <li><a href="index.html#reservar-evento">Reservar Evento</a></li>
                <li><a href="comentarios_publicos.php" class="active">Comentarios</a></li>
                <li><a href="index.html#contactos">Contactos</a></li>
            </ul>
            <button class="login-btn" onclick="window.location.href='login.html'">Iniciar Sesión</button>
        </nav>
    </header>

    <main style="margin-top: 80px; padding: 2rem;">
        <div class="comentarios-container">
            <h1>Comentarios de Nuestros Clientes</h1>
            
            <div class="comentarios-grid">
                <?php foreach ($comentarios as $comentario): ?>
                <div class="comentario-card">
                    <div class="comentario-header">
                        <h3><?php echo htmlspecialchars($comentario['usuario_nombre']); ?></h3>
                        <div class="comentario-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star <?php echo $i <= $comentario['calificacion'] ? 'filled' : ''; ?>">★</span>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <p class="comentario-text"><?php echo htmlspecialchars($comentario['comentario']); ?></p>
                    <div class="comentario-date">
                        <?php echo date('d/m/Y', strtotime($comentario['fecha_creacion'])); ?>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (empty($comentarios)): ?>
                <div class="no-comentarios">
                    <p>Aún no hay comentarios públicos. ¡Sé el primero en compartir tu experiencia!</p>
                    <button class="login-btn" onclick="window.location.href='login.html'">Dejar un Comentario</button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <style>
        .comentarios-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .comentarios-container h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 3rem;
            color: #ffffffff;//////
        }

        .comentarios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }

        .comentario-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease;
        }

        .comentario-card:hover {
            transform: translateY(-5px);
        }

        .comentario-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .comentario-header h3 {
            color: #ffffffff;/////
            font-size: 1.2rem;
            font-weight: 600;
        }

        .comentario-rating .star {
            color: #666;
            font-size: 1.3rem;
            margin-left: 2px;
        }

        .comentario-rating .star.filled {
            color: #bda416ff;///
        }

        .comentario-text {
            line-height: 1.6;
            margin-bottom: 1rem;
            color: #f0f0f0;
        }

        .comentario-date {
            font-size: 0.9rem;
            color: #999;
            text-align: right;
        }

        .no-comentarios {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            color: #ccc;
        }

        .no-comentarios p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .nav-menu .active {
            color: #ffffffff;/////
        }

        .nav-menu .active::after {
            width: 100%;
        }

        @media (max-width: 768px) {
            .comentarios-container h1 {
                font-size: 2rem;
            }
            
            .comentarios-grid {
                grid-template-columns: 1fr;
            }
            
            .comentario-card {
                padding: 1.5rem;
            }
        }
    </style>
</body>
</html>
