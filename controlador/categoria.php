<?php
header('Content-Type: application/json');
include 'conexion.php'; // Asegúrate de que la conexión se establezca correctamente

// Leer el cuerpo de la solicitud como JSON
$data = json_decode(file_get_contents('php://input'));

// Agregar registro para depuración
error_log("Datos recibidos: " . json_encode($data));

try {
    if ($data && isset($data->action)) { // Validar que $data no sea null y que tenga la propiedad 'action'
        error_log("Acción recibida: " . $data->action); // Registro para depuración

        $action = $data->action;

        if ($action == 'guardarCategoria') { // Manejar la edición de categorías
            $id = isset($data->id) && is_numeric($data->id) ? intval($data->id) : null; // Asegurarse de que $id sea un número válido
            $nombre = $data->nombre_categoria;
            $descripcion = $data->descripcion_categoria;

            error_log("Datos procesados: ID=$id, Nombre=$nombre, Descripción=$descripcion"); // Registro para depuración

            // Verificar que los campos no estén vacíos
            if (empty($nombre) || empty($descripcion)) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
                exit;
            }

            if ($id) {
                // Actualizar categoría existente
                $sql = "UPDATE Categoria SET nombre_categoria = :nombre, descripcion_categoria = :descripcion WHERE id_categoria = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':descripcion', $descripcion);

                if ($stmt->execute()) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Categoría actualizada exitosamente.'
                    ]);
                } else {
                    error_log("Error en la operación: " . json_encode($stmt->errorInfo())); // Registro para depuración
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error en la operación: ' . $stmt->errorInfo()[2]
                    ]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'ID de categoría no válido.']);
            }
        } elseif ($action == 'crearCategoria') { // Manejar la creación de nuevas categorías
            $nombre = $data->nombre_categoria;
            $descripcion = $data->descripcion_categoria;

            error_log("Datos procesados para creación: Nombre=$nombre, Descripción=$descripcion"); // Registro para depuración

            // Verificar que los campos no estén vacíos
            if (empty($nombre) || empty($descripcion)) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
                exit;
            }

            // Crear nueva categoría
            $sql = "INSERT INTO Categoria (nombre_categoria, descripcion_categoria) VALUES (:nombre, :descripcion)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);

            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Categoría creada exitosamente.'
                ]);
            } else {
                error_log("Error en la operación: " . json_encode($stmt->errorInfo())); // Registro para depuración
                echo json_encode([
                    'success' => false,
                    'message' => 'Error en la operación: ' . $stmt->errorInfo()[2]
                ]);
            }
        } elseif ($action == 'eliminarCategoria') {
            // Eliminar una categoría por ID
            $id = isset($data->id) && is_numeric($data->id) ? intval($data->id) : null;

            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'ID de categoría no válido.']);
                exit;
            }

            $sql = "DELETE FROM Categoria WHERE id_categoria = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Categoría eliminada exitosamente.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al eliminar la categoría: ' . $stmt->errorInfo()[2]
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Acción no válida.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No se recibieron datos válidos.']);
    }
} catch (PDOException $e) {
    error_log("Error en la base de datos: " . $e->getMessage()); // Registro para depuración
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}

$pdo = null; // Cierra la conexión
?>