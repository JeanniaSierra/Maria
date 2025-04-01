<?php
include 'conexion.php'; // Asegúrate de que la conexión a la base de datos esté configurada correctamente
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permitir acceso desde cualquier origen
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $password_usuario = $_POST['password_usuario'];

    // Verificar que el nombre de usuario no esté vacío
    if (empty($nombre_usuario) || empty($password_usuario)) {
        echo json_encode(["success" => false, "message" => "Nombre de usuario y contraseña son obligatorios."]);
        exit;
    }

    // Consultar la base de datos para obtener el usuario
    $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE nombre_usuario = ?");
    $stmt->execute([$nombre_usuario]);
    $usuario = $stmt->fetch();

    // Verificar si el usuario existe y la contraseña es correcta
    if ($usuario && password_verify($password_usuario, $usuario['password_usuario'])) {
        // Iniciar sesión
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
        $_SESSION['apellido_usuario'] = $usuario['apellido_usuario'];
        $_SESSION['email_usuario'] = $usuario['email_usuario'];
        $_SESSION['rol_usuario'] = $usuario['rol_usuario'];
        $_SESSION['autenticado'] = true;

        // Log para depuración
        error_log("Sesión iniciada para usuario ID: " . $usuario['id_usuario']);
        error_log("Datos de sesión: " . print_r($_SESSION, true));

        echo json_encode([
            'success' => true,
            'message' => 'Inicio de sesión exitoso.',
            'redirect' => './bootstrap.html',
            'usuario' => [
                'nombre' => $usuario['nombre_usuario'],
                'rol' => $usuario['rol_usuario']
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Nombre de usuario o contraseña incorrectos."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}
?>