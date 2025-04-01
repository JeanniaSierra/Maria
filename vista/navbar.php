<!DOCTYPE html>
<html lang="en">
    <?php
session_start(); // Asegura que la sesión está iniciada
$rol_usuario = $_SESSION['rol_usuario'] ?? ''; // Obtiene el rol o vacío si no existe
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../publico/estilos/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="imagenes/imagen1.png" type="image/x-icon">
</head>

<body>
    <div id="overlay" class="overlay"></div>

    <nav class="navbar navbar-expand-lg bg-info">
        <div class="container-fluid" style="justify-content: unset;">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Eliminar enlaces innecesarios -->
                </ul>

                    
                <button id="modoOscuroBtn" class="btn btn-dark">🌙 Modo Oscuro</button>

                <div class="dropdown">
                    <button class="btn-custom-usuarios dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Proveedor
                    </button>
                    <ul class="dropdown-menu">
                    <?php if ($rol_usuario === 'Administrador' || $rol_usuario === 'Vendedor'): ?>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#crearProveedorModal">Crear Proveedor</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="listar_proveedores.html" id="btnVerProveedor">Ver Proveedor</a></li>
                    </ul>
                </div>

                <div class="dropdown">
                    <button class="btn-custom-productos dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Categoria
                    </button>
                    <ul class="dropdown-menu">
                    <?php if ($rol_usuario === 'Administrador' || $rol_usuario === 'Vendedor'): ?>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#crearCategoriaModal">Crear Categoria</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="listar_categorias.html">Ver Categoria</a></li>
                    </ul>
                </div>

                <div class="dropdown">
                    <button class="btn-custom-pedidos dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Producto
                    </button>
                    <ul class="dropdown-menu">
                    <?php if ($rol_usuario === 'Administrador' || $rol_usuario === 'Vendedor'): ?>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#crearProductoModal">Crear Producto</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="listar_productos.php">Ver Producto</a></li>
                    </ul>
                </div>

                <!-- Aquí agregamos el botón "Mi Perfil" -->
                <div class="dropdown">
                    <button class="btn btn-custom-profile dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../publico/imagenes/alejandrafotoperfil.png" class="profile-img" alt="Foto de perfil">
                        <span class="username">Usuario</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#verPerfilModal">Ver Perfil</a></li>
                        <li><a class="dropdown-item" href="#" id="cerrarSesion">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <script> 
    // Obtiene los elementos del body y el botón del modo oscuro
    const body = document.body;
    const btnModoOscuro = document.getElementById("modoOscuroBtn");

    // Función para activar el modo oscuro
    function aplicarModoOscuro() {
        body.classList.add("modo-oscuro");
        btnModoOscuro.classList.remove("btn-dark");
        btnModoOscuro.classList.add("btn-light");
        btnModoOscuro.innerHTML = "☀️ Modo Claro";
        localStorage.setItem("modo", "oscuro");
        console.log("Modo oscuro aplicado y guardado en localStorage.");
    }

    // Función para activar el modo claro
    function aplicarModoClaro() {
        body.classList.remove("modo-oscuro");
        btnModoOscuro.classList.remove("btn-light");
        btnModoOscuro.classList.add("btn-dark");
        btnModoOscuro.innerHTML = "🌙 Modo Oscuro";
        localStorage.setItem("modo", "claro");
        console.log("Modo claro aplicado y guardado en localStorage.");
    }

    // Comprobar si hay una preferencia guardada
    if (localStorage.getItem("modo") === "oscuro") {
        aplicarModoOscuro();
    } else {
        aplicarModoClaro();
    }

    // Evento para cambiar de modo cuando se hace clic en el botón
    btnModoOscuro.onclick = function () {
        if (body.classList.contains("modo-oscuro")) {
            aplicarModoClaro();
        } else {
            aplicarModoOscuro();
        }
    };

    </script>
</body>
</html>