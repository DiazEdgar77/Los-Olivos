<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

if ($_POST) {
    $usuario_id = $_SESSION['usuario_id'];
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $fecha = trim($_POST['fecha']);
    $hora = trim($_POST['hora']);
    $lugar = trim($_POST['lugar']);
    $asistentes = trim($_POST['asistentes']);
    
    if (empty($nombre) || empty($telefono) || empty($fecha) || empty($hora) || empty($lugar) || empty($asistentes)) {
        header("Location: panel_usuario.php?error=empty");
        exit();
    }
    
    // Validar que la fecha no sea pasada
    if (strtotime($fecha) < strtotime(date('Y-m-d'))) {
        header("Location: panel_usuario.php?error=invalid");
        exit();
    }
    
    try {
        $stmt = $conexion->prepare("INSERT INTO eventos (usuario_id, nombre, telefono, fecha, hora, lugar, asistentes) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$usuario_id, $nombre, $telefono, $fecha, $hora, $lugar, $asistentes]);
        
        header("Location: panel_usuario.php?success=evento");
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
