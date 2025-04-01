<?php
header('Content-Type: application/json');
include '../config/conexion.php'; // Asegúrate de que este archivo esté correctamente configurado

try {
    $sql = "SELECT id_proveedor, nombre_proveedor, direccion_proveedor, telefono_proveedor FROM Proveedor";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $proveedores]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}

$pdo = null; // Cierra la conexión
?>