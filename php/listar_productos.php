<?php
header('Content-Type: application/json');
include 'conexion.php'; // Asegúrate de que este archivo esté correctamente configurado

try {
    $sql = "SELECT 
                producto.id_producto,
                producto.imagen_producto, -- Asegúrate de que este nombre coincida con la base de datos
                producto.nombre_producto,
                producto.descripcion_producto,
                producto.precio_producto,
                producto.cantidad_producto,
                producto.estado_producto,
                producto.fecha_ingreso,
                categoria.nombre_categoria AS categoria,
                proveedor.nombre_proveedor AS proveedor
            FROM 
                producto
            JOIN 
                categoria ON producto.id_categoria = categoria.id_categoria
            JOIN 
                proveedor ON producto.id_proveedor = proveedor.id_proveedor";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $productos]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}

$pdo = null; // Cierra la conexión
?>
