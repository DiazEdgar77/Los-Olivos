<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserva_id'], $_POST['accion'])) {
    $reserva_id = intval($_POST['reserva_id']);
    $accion = $_POST['accion'] === 'aprobar' ? 'aprobado' : 'rechazado';

    $stmt = $conexion->prepare("UPDATE reservas SET estado = ? WHERE id = ?");
    $stmt->execute([$accion, $reserva_id]);
}

header("Location: panel_admin.php");
exit();
