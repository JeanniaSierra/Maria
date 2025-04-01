<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'No estás autenticado.']);
    exit;
}

// Aquí deberías incluir la conexión a la base de datos
include 'conexion.php';

$id_usuario = $_SESSION['usuario_id'];
$stmt = $pdo->prepare("SELECT nombre_usuario, apellido_usuario, email_usuario, rol_usuario FROM Usuario WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch();

if ($usuario) {
    echo json_encode(['success' => true, 'data' => $usuario]);
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
}
?>