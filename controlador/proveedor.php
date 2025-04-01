<?php
header('Content-Type: application/json');
include '../config/conexion.php'; // Asegúrate de que este archivo esté correctamente configurado

// Verifica si se recibió una solicitud POST para guardar un proveedor
$data = json_decode(file_get_contents("php://input"));

try {
    if ($data && isset($data->action)) { // Validar que $data no sea null y que tenga la propiedad 'action'
        $action = $data->action;

        if ($action == 'guardarProveedor') {
            // Guardar un nuevo proveedor
            $nombre = $data->nombre; 
            $direccion = $data->direccion;
            $telefono = $data->telefono;

            // Verificar que los campos no estén vacíos
            if (empty($nombre) || empty($direccion) || empty($telefono)) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
                exit;
            }

            $sql = "INSERT INTO Proveedor (nombre_proveedor, direccion_proveedor, telefono_proveedor) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$nombre, $direccion, $telefono])) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Proveedor guardado exitosamente.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al guardar el proveedor: ' . $stmt->errorInfo()[2]
                ]);
            }
        } elseif ($action == 'editarProveedor') {
            // Editar un proveedor existente
            $id = $data->id;
            $nombre = $data->nombre; 
            $direccion = $data->direccion;
            $telefono = $data->telefono;

            // Verificar que los campos no estén vacíos
            if (empty($nombre) || empty($direccion) || empty($telefono)) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
                exit;
            }

            $sql = "UPDATE Proveedor SET nombre_proveedor = ?, direccion_proveedor = ?, telefono_proveedor = ? WHERE id_proveedor = ?";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$nombre, $direccion, $telefono, $id])) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Proveedor actualizado exitosamente.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al actualizar el proveedor: ' . $stmt->errorInfo()[2]
                ]);
            }
        } elseif ($action == 'eliminarProveedor') {
            
            // Eliminar un proveedor existente
            $id = $data->id;

            $sql = "DELETE FROM Proveedor WHERE id_proveedor = ?";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$id])) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Proveedor eliminado exitosamente.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al eliminar el proveedor: ' . $stmt->errorInfo()[2]
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Acción no válida.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No se recibieron datos válidos.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}

$pdo = null; // Cierra la conexión
?>