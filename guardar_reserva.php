<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

if ($_POST) {
    $usuario_id = $_SESSION['usuario_id'];
    $fecha = trim($_POST['fecha']);
    $hora = trim($_POST['hora']);
    $personas = trim($_POST['personas']);
    
    if (empty($fecha) || empty($hora) || empty($personas)) {
        header("Location: panel_usuario.php?error=empty");
        exit();
    }
    
    // Validar que la fecha no sea pasada
    if (strtotime($fecha) < strtotime(date('Y-m-d'))) {
        header("Location: panel_usuario.php?error=invalid");
        exit();
    }
    
    try {
        $stmt = $conexion->prepare("INSERT INTO reservas (usuario_id, fecha, hora, personas) VALUES (?, ?, ?, ?)");
        $stmt->execute([$usuario_id, $fecha, $hora, $personas]);
        
        header("Location: panel_usuario.php?success=reserva");
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
