<?php
session_start();
require_once 'conexion.php';

if ($_POST) {
    $correo = trim($_POST['correo']);
    $contraseña = trim($_POST['contraseña']);
    
    if (empty($correo) || empty($contraseña)) {
        header("Location: login.html?error=empty");
        exit();
    }
    
    try {
        $stmt = $conexion->prepare("SELECT id, nombre, contraseña, rol FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            
            if ($usuario['rol'] === 'admin') {
                header("Location: panel_admin.php");
            } else {
                header("Location: panel_usuario.php");
            }
            exit();
        } else {
            header("Location: login.html?error=invalid");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: login.html?error=invalid");
        exit();
    }
} else {
    header("Location: login.html");
    exit();
}
?>
