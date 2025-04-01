<?php
include '../config/conexion.php'; // Asegúrate de que la conexión a la base de datos esté configurada correctamente
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log para depuración
error_log("Solicitud recibida en registro.php");
error_log("POST data: " . print_r($_POST, true));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $apellido_usuario = trim($_POST['apellido_usuario']);
    $email_usuario = trim($_POST['email_usuario']);
    $password_usuario = password_hash(trim($_POST['password_usuario']), PASSWORD_DEFAULT);
    $rol_usuario = trim($_POST['rol_usuario']);

    // Validar que los campos no estén vacíos
    if (empty($nombre_usuario) || empty($apellido_usuario) || empty($email_usuario) || empty($password_usuario) || empty($rol_usuario)) {
        echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    // Aquí deberías agregar la lógica para insertar los datos en la base de datos
    $stmt = $pdo->prepare("INSERT INTO Usuario (nombre_usuario, apellido_usuario, email_usuario, password_usuario, rol_usuario) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$nombre_usuario, $apellido_usuario, $email_usuario, $password_usuario, $rol_usuario])) {
        echo json_encode(["success" => true, "message" => "Usuario registrado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al registrar el usuario."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}
?>