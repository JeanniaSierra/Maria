<?php
session_start();
header('Content-Type: application/json');

require_once('../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['usuario_id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    
    try {
        $query = "UPDATE Usuario SET nombre_usuario = ?, apellido_usuario = ?, email_usuario = ? WHERE id_usuario = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nombre, $apellido, $email, $id_usuario]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Perfil actualizado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar el perfil: ' . $e->getMessage()
        ]);
    }
}
?>