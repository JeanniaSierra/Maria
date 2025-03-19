<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
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
                <div class="dropdown">
                    <button class="btn-custom-usuarios dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Proveedor
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#crearProveedorModal">Crear Proveedor</a></li>
                        <li><a class="dropdown-item" href="listar_proveedores.html" id="btnVerProveedor">Ver Proveedor</a></li>
                    </ul>
                </div>

                <div class="dropdown">
                    <button class="btn-custom-productos dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Categoria
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#crearCategoriaModal">Crear Categoria</a></li>
                        <li><a class="dropdown-item" href="listar_categorias.html">Ver Categoria</a></li>
                    </ul>
                </div>

                <div class="dropdown">
                    <button class="btn-custom-pedidos dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Producto
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#crearProductoModal">Crear Producto</a></li>
                        <li><a class="dropdown-item" href="listar_productos.php">Ver Producto</a></li>
                    </ul>
                </div>

                <!-- Aquí agregamos el botón "Mi Perfil" -->
                <div class="dropdown">
                    <button class="btn btn-custom-profile dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../imagenes/alejandrafotoperfil.png" class="profile-img" alt="Foto de perfil">
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
</body>
</html>