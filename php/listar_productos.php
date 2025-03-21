<?php
header('Content-Type: application/json');

try {
    // Incluir el archivo de conexión
    include 'conexion.php';

    // Consulta para obtener los productos con sus categorías y proveedores
    $query = $pdo->prepare('
    SELECT 
            p.id_producto,
            p.nombre_producto,
            p.descripcion_producto,
            p.precio_producto,
            p.cantidad_producto,
            p.estado_producto,
            p.fecha_ingreso,
            c.nombre_categoria AS categoria,
            pr.nombre_proveedor AS proveedor,
            p.imagen_producto
        FROM producto p
        LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
        LEFT JOIN proveedor pr ON p.id_proveedor = pr.id_proveedor
    ');
    $query->execute();
    $productos = $query->fetchAll(PDO::FETCH_ASSOC);

    // Convertir imágenes a base64
    foreach ($productos as &$producto) {
        if (!empty($producto['imagen_producto'])) {
            $producto['imagen_producto'] = base64_encode($producto['imagen_producto']);
        }
    }

    // Respuesta en formato JSON
    echo json_encode([
        'success' => true,
        'data' => $productos
    ]);
} catch (PDOException $e) {
    // Manejo de errores de conexión o consulta
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener los productos: ' . $e->getMessage()
    ]);
}
?>
