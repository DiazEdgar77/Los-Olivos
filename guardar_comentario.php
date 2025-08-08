<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

if ($_POST) {
    $usuario_id = $_SESSION['usuario_id'];
    $comentario = trim($_POST['comentario']);
    $calificacion = trim($_POST['calificacion']);
    
    if (empty($comentario) || empty($calificacion)) {
        header("Location: panel_usuario.php?error=empty");
        exit();
    }
    
    // Validar calificación
    if ($calificacion < 1 || $calificacion > 5) {
        header("Location: panel_usuario.php?error=invalid");
        exit();
    }
    
    try {
        $stmt = $conexion->prepare("INSERT INTO comentarios (usuario_id, comentario, calificacion) VALUES (?, ?, ?)");
        $stmt->execute([$usuario_id, $comentario, $calificacion]);
        
        header("Location: panel_usuario.php?success=comentario");
        exit();
        
    } catch (PDOException $e) {
        header("Location: panel_usuario.php?error=invalid");
        exit();
    }
} else {
    header("Location: panel_usuario.php");
    exit();
}
?>
