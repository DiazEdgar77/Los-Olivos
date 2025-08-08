<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['evento_id'], $_POST['accion'])) {
    $evento_id = intval($_POST['evento_id']);
    $accion = $_POST['accion'] === 'aprobar' ? 'aprobado' : 'rechazado';

    $stmt = $conexion->prepare("UPDATE eventos SET estado = ? WHERE id = ?");
    $stmt->execute([$accion, $evento_id]);
}

header("Location: panel_admin.php");
exit();
