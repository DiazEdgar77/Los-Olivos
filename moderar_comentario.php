<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}

if ($_POST) {
    $comentario_id = trim($_POST['comentario_id']);
    $accion = trim($_POST['accion']);
    
    if (empty($comentario_id) || empty($accion)) {
        header("Location: panel_admin.php?error=empty");
        exit();
    }
    
    $nuevo_estado = '';
    if ($accion === 'aprobar') {
        $nuevo_estado = 'aprobado';
    } elseif ($accion === 'rechazar') {
        $nuevo_estado = 'rechazado';
    } else {
        header("Location: panel_admin.php?error=invalid");
        exit();
    }
    
    try {
        $stmt = $conexion->prepare("UPDATE comentarios SET estado = ? WHERE id = ?");
        $stmt->execute([$nuevo_estado, $comentario_id]);
        
        header("Location: panel_admin.php?success=moderacion");
        exit();
        
    } catch (PDOException $e) {
        header("Location: panel_admin.php?error=invalid");
        exit();
    }
} else {
    header("Location: panel_admin.php");
    exit();
}
?>
