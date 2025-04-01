<?php
header('Content-Type: application/json');
include '../config/conexion.php';

// Habilitar el registro de errores para depuración
ini_set('display_errors', 0); // No mostrar errores en la salida
ini_set('log_errors', 1); // Registrar errores en el archivo de registro
error_reporting(E_ALL); // Reportar todos los errores

// Añadir para depuración
error_log("Solicitud recibida: " . json_encode($_POST));

try {
    // Obtener y decodificar la entrada JSON
    $input = json_decode(file_get_contents("php://input"), true);
    $action = $_POST['action'] ?? $input['action'] ?? null;

    // Depuración: Verificar acción recibida
    error_log("Acción recibida: " . $action);

    if ($action == "registerProducto") {
        $nombre = htmlspecialchars($_POST['nombreProducto'] ?? '');
        $descripcion = htmlspecialchars($_POST['descripcionProducto'] ?? '');
        $precio = htmlspecialchars($_POST['precioProducto'] ?? '');
        $cantidad = htmlspecialchars($_POST['cantidadProducto'] ?? ''); // Validar cantidad
        $id_categoria = htmlspecialchars($_POST['idCategoria'] ?? '');
        $id_proveedor = htmlspecialchars($_POST['idProveedor'] ?? '');
        $estado_producto = htmlspecialchars($_POST['estadoProducto'] ?? 'activo'); // Valor predeterminado
        $imagen_producto = null;

        // Depuración: Verificar datos recibidos
        error_log("Datos recibidos para registrar producto:");
        error_log(print_r($_POST, true));
        error_log(print_r($_FILES, true));

        // Validar campos obligatorios
        if (empty($nombre) || empty($descripcion) || empty($precio) || empty($cantidad) || empty($id_categoria) || empty($id_proveedor)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
            exit;
        }

        // Manejar la carga de la imagen
        if (isset($_FILES['imagenProducto']) && $_FILES['imagenProducto']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../publico/image/"; // Cambiado a la carpeta "image"

            $nombre_imagen = basename($_FILES["imagenProducto"]["name"]);
            $ruta_imagen = $target_dir . $nombre_imagen;

            if (move_uploaded_file($_FILES["imagenProducto"]["tmp_name"], $ruta_imagen)) {
                $imagen_producto = $nombre_imagen; // Guardar solo el nombre del archivo en la base de datos
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al subir la imagen']);
                exit;
            }
        } else {
            $imagen_producto = null; // Si no se sube imagen
        }

        // Agregar la fecha de ingreso
        $fecha_ingreso = date('Y-m-d H:i:s'); // Fecha y hora actual

        // Insertar producto en la base de datos
        try {
            $stmt = $pdo->prepare("INSERT INTO producto (nombre_producto, descripcion_producto, precio_producto, cantidad_producto, id_categoria, id_proveedor, estado_producto, imagen_producto, fecha_ingreso) VALUES (:nombre, :descripcion, :precio, :cantidad, :id_categoria, :id_proveedor, :estado_producto, :imagen_producto, :fecha_ingreso)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':cantidad', $cantidad);
            $stmt->bindParam(':id_categoria', $id_categoria);
            $stmt->bindParam(':id_proveedor', $id_proveedor);
            $stmt->bindParam(':estado_producto', $estado_producto);
            $stmt->bindParam(':imagen_producto', $imagen_producto);
            $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
            $stmt->execute();

            error_log("Producto registrado correctamente");
            echo json_encode(['success' => true, 'message' => 'Producto guardado correctamente']);
        } catch (PDOException $e) {
            error_log("Error al guardar el producto: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error al guardar el producto: ' . $e->getMessage()]);
        }
    } elseif ($action == "cargarProductos") {
        try {
            // Obtener los datos del usuario desde la base de datos
            $stmt = $pdo->prepare("SELECT 
                                    producto.id_producto,
                                    producto.imagen_producto,
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
                                    proveedor ON producto.id_proveedor = proveedor.id_proveedor;");
            $stmt->execute();
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            error_log("Productos obtenidos: " . print_r($productos, true));
            echo json_encode(['success' => true, 'data' => $productos]);
        } catch (PDOException $e) {
            error_log("Error al obtener productos: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error al obtener productos: ' . $e->getMessage()]);
        }
    } elseif ($action == "obtenerProducto") {
        $id = $input['id'];
        try {
            $stmt = $pdo->prepare("SELECT * FROM producto WHERE id_producto = ?");
            $stmt->execute([$id]);
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Producto obtenido: " . print_r($producto, true));
            echo json_encode($producto);
        } catch (PDOException $e) {
            error_log("Error al obtener el producto: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error al obtener el producto']);
        }
    } elseif ($action == "editarProducto") {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $id_categoria = $_POST['id_categoria'];
        $id_proveedor = $_POST['id_proveedor'];

        try {
            $stmt = $pdo->prepare("UPDATE producto SET nombre_producto=?, descripcion_producto=?, precio_producto=?, id_categoria=?, id_proveedor=? WHERE id_producto=?");
            $stmt->execute([$nombre, $descripcion, $precio, $id_categoria, $id_proveedor, $id]);
            error_log("Producto actualizado correctamente");
            echo json_encode(["success" => true, "mensaje" => "Producto actualizado"]);
        } catch (PDOException $e) {
            error_log("Error al actualizar el producto: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el producto']);
        }
    } elseif ($action == "eliminarProducto") {
        $id = $input['id'];

        try {
            $stmt = $pdo->prepare("DELETE FROM producto WHERE id_producto = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            error_log("Producto eliminado correctamente");
            echo json_encode(['success' => true, 'message' => 'Producto eliminado correctamente']);
        } catch (PDOException $e) {
            error_log("Error al eliminar el producto: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            // Obtener categorías
            $stmtCategorias = $pdo->query("SELECT id_categoria, nombre_categoria FROM categoria");
            $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
            
            // Obtener proveedores
            $stmtProveedores = $pdo->query("SELECT id_proveedor, nombre_proveedor FROM proveedor");
            $proveedores = $stmtProveedores->fetchAll(PDO::FETCH_ASSOC);
            
            // Devolver los datos en formato JSON
            echo json_encode([
                'categorias' => $categorias,
                'proveedores' => $proveedores
            ]);
        } catch (PDOException $e) {
            error_log("Error al obtener categorías o proveedores: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error al obtener datos']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    }
} catch (PDOException $e) {
    // Captura cualquier error de la base de datos
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    // Captura cualquier otro error
    error_log("General error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error general: ' . $e->getMessage()]);
} finally {
    $pdo = null; // Cierra la conexión
}
?>