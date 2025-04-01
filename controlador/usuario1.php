<?php
header('Content-Type: application/json');
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log para depuración
error_log("Solicitud recibida en usuario1.php");
error_log("POST data: " . print_r($_POST, true));

// Incluir archivo de conexión
require_once '../config/conexion.php';

// Verificar si la conexión está establecida
if (!isset($pdo)) {
    error_log("No hay conexión a la base de datos");
    die(json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos'
    ]));
}

function registrarUsuario($pdo, $datos) {
    try {
        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT idUsuario FROM usuarios WHERE correo = ? OR documento = ?");
        $stmt->execute([$datos['correo'], $datos['documento']]);
        
        if($stmt->rowCount() > 0) {
            return [
                'success' => false,
                'message' => 'El correo o documento ya están registrados'
            ];
        }

        // Consulta SQL ajustada a tu estructura exacta de la base de datos
        $sql = "INSERT INTO usuarios (nombre_completo, correo, documento, password) VALUES (?, ?, ?, ?)";

        // Hash de la contraseña
        $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);

        // Ejecutar la consulta
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $datos['nombre_completo'],
            $datos['correo'],
            $datos['documento'],
            $passwordHash
        ]);

        return [
            'success' => true,
            'message' => 'Usuario registrado exitosamente'
        ];

    } catch (PDOException $e) {
        error_log("Error en la base de datos: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Error al procesar el registro: ' . $e->getMessage()
        ];
    }
}

// Procesar la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que todos los campos necesarios estén presentes
    $camposRequeridos = [
        'documento',
        'nombre_completo',
        'correo',
        'password'
    ];

    $datosCompletos = true;
    $datos = [];

    foreach ($camposRequeridos as $campo) {
        if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
            $datosCompletos = false;
            error_log("Campo faltante: " . $campo);
            break;
        }
        $datos[$campo] = trim($_POST[$campo]);
    }

    if (!$datosCompletos) {
        echo json_encode([
            'success' => false,
            'message' => 'Todos los campos son obligatorios'
        ]);
        exit;
    }

    // Registrar usuario
    $resultado = registrarUsuario($pdo, $datos);
    echo json_encode($resultado);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
