<?php
require_once 'conexion.php';

if ($_POST) {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contraseña = trim($_POST['contraseña']);
    $confirmar_contraseña = trim($_POST['confirmar_contraseña']);
    
    if (empty($nombre) || empty($correo) || empty($contraseña) || empty($confirmar_contraseña)) {
        header("Location: registro.html?error=empty");
        exit();
    }
    
    if ($contraseña !== $confirmar_contraseña) {
        header("Location: registro.html?error=passwords");
        exit();
    }
    
    try {
        // Verificar si el correo ya existe
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        
        if ($stmt->fetch()) {
            header("Location: registro.html?error=exists");
            exit();
        }
        
        // Crear nuevo usuario
        $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $correo, $contraseña_hash]);
        
        header("Location: registro.html?success=1");
        exit();
        
    } catch (PDOException $e) {
        header("Location: registro.html?error=empty");
        exit();
    }
} else {
    header("Location: registro.html");
    exit();
}
?>
