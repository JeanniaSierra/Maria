<?php
header('Content-Type: application/json');
include '../config/conexion.php';

// Habilitar el registro de errores
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

try {
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

    // Procesar las imágenes
    foreach ($productos as &$producto) {
        if (!empty($producto['imagen_producto'])) {
            $rutaImagen = "../publico/image/" . $producto['imagen_producto']; // Ruta completa de la imagen
            if (file_exists($rutaImagen)) {
                $producto['imagen_producto'] = base64_encode(file_get_contents($rutaImagen)); // Convertir a base64
            } else {
                error_log("Imagen no encontrada: " . $rutaImagen); // Registrar error si la imagen no existe
                $producto['imagen_producto'] = null; // Si no hay imagen
            }
        } else {
            $producto['imagen_producto'] = null; // Si no hay imagen
        }
    }

    // Respuesta en formato JSON
    echo json_encode([
        'success' => true,
        'data' => $productos
    ]);
} catch (PDOException $e) {
    error_log("Error al obtener los productos: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener los productos: ' . $e->getMessage()
    ]);
}
?>
