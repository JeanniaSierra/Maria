<?php
header('Content-Type: application/json');
include 'conexion.php';

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
        $id_categoria = htmlspecialchars($_POST['idCategoria'] ?? '');
        $id_proveedor = htmlspecialchars($_POST['idProveedor'] ?? '');
        $estado_producto = htmlspecialchars($_POST['estadoProducto'] ?? 'activo'); // Valor predeterminado
        $imagen_producto = null;

        // Depuración: Verificar datos recibidos
        error_log("Datos recibidos para registrar producto:");
        error_log(print_r($_POST, true));
        error_log(print_r($_FILES, true));

        // Validar campos obligatorios
        if (empty($nombre) || empty($descripcion) || empty($precio) || empty($id_categoria) || empty($id_proveedor)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
            exit;
        }

        // Manejar la carga de la imagen
        $imagen_producto = null;
        if (isset($_FILES['imagenProducto']) && $_FILES['imagenProducto']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../image/"; // Cambiado a la carpeta "image"
            $imagen_producto = $target_dir . basename($_FILES["imagenProducto"]["name"]);
            if (!move_uploaded_file($_FILES["imagenProducto"]["tmp_name"], $imagen_producto)) {
                echo json_encode(['success' => false, 'message' => 'Error al subir la imagen']);
                exit;
            }
        }

        // Insertar producto en la base de datos
        try {
            $stmt = $pdo->prepare("INSERT INTO producto (nombre_producto, descripcion_producto, precio_producto, id_categoria, id_proveedor, estado_producto, imagen_producto) 
                                   VALUES (:nombre, :descripcion, :precio, :id_categoria, :id_proveedor, :estado_producto, :imagen_producto)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':id_categoria', $id_categoria);
            $stmt->bindParam(':id_proveedor', $id_proveedor);
            $stmt->bindParam(':estado_producto', $estado_producto);
            $stmt->bindParam(':imagen_producto', $imagen_producto);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Producto guardado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el producto: ' . $e->getMessage()]);
        }
    } elseif ($action == "cargarProductos") {
        try {
            // Obtener los datos del usuario desde la base de datos
            $stmt = $pdo->prepare("SELECT 
                                    producto.id_producto,
                                    producto.imagen,
                                    producto.nombre_producto,
                                    producto.descripcion_producto,
                                    producto.precio_producto,
                                    producto.cantidad,
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
            echo json_encode($productos);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error al obtener productos: ' . $e->getMessage()]);
        }
    } elseif ($action == "obtenerProducto") {
        $id = $input['id'];
        $stmt = $pdo->prepare("SELECT * FROM producto WHERE id_producto = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
        exit;
    } elseif ($action == "editarProducto") {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $id_categoria = $_POST['id_categoria'];
        $id_proveedor = $_POST['id_proveedor'];

        $stmt = $pdo->prepare("UPDATE producto SET nombre_producto=?, descripcion_producto=?, precio_producto=?, id_categoria=?, id_proveedor=? WHERE id_producto=?");
        $stmt->execute([$nombre, $descripcion, $precio, $id_categoria, $id_proveedor, $id]);
        echo json_encode(["success" => true, "mensaje" => "Producto actualizado"]);
        exit;
    } elseif ($action == "eliminarProducto") {
        $id = $input['id'];

        try {
            $stmt = $pdo->prepare("DELETE FROM producto WHERE id_producto = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Producto eliminado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
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