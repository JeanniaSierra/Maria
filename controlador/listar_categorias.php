<?php
header('Content-Type: application/json');
include '../config/conexion.php'; // Asegúrate de que este archivo esté correctamente configurado

try {
    $sql = "SELECT id_categoria, nombre_categoria, descripcion_categoria FROM Categoria"; // Incluye id_categoria
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $categorias]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}

$pdo = null; // Cierra la conexión
?>