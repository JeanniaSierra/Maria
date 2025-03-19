<!-- include -->
<?php include '../html/navbar.php'; ?>

<!-- Modal para Ver Perfil -->
<div class="modal fade" id="verPerfilModal" tabindex="-1" aria-labelledby="verPerfilModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="verPerfilModalLabel">Mi Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img src="../imagenes/alejandrafotoperfil.png" class="rounded-circle" alt="Foto de perfil"  style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Nombre:</strong> <span id="perfilNombre" class="dato-perfil"></span></p>
                        <p><strong>Apellido:</strong> <span id="perfilApellido" class="dato-perfil"></span></p>
                        <p><strong>Email:</strong> <span id="perfilEmail" class="dato-perfil"></span></p>
                        <p><strong>Rol:</strong> <span id="perfilRol" class="dato-perfil"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnEditar" onclick="habilitarEdicion()">Editar</button>
                <button type="button" class="btn btn-success" id="btnGuardar" onclick="guardarCambios()" style="display: none;">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear Proveedor -->
<div class="modal fade" id="crearProveedorModal" tabindex="-1" aria-labelledby="crearProveedorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="crearProveedorModalLabel">Crear Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCrearProveedor">
                    <div class="mb-3">
                        <label for="nombreProveedor" class="form-label">Nombre del Proveedor</label>
                        <input type="text" class="form-control" id="nombreProveedor" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccionProveedor" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccionProveedor" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefonoProveedor" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefonoProveedor" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button id="btnGuardarProveedor" onclick="guardarProveedor()">Guardar Proveedor</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear Categoria -->
<div class="modal fade" id="crearCategoriaModal" tabindex="-1" aria-labelledby="crearCategoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="crearCategoriaModalLabel">Crear Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCrearCategoria">
                    <input type="hidden" id="categoriaId">
                    <div class="mb-3">
                        <label for="nombreCategoria" class="form-label">Nombre de la Categoria</label>
                        <input type="text" class="form-control" id="nombreCategoria" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcionCategoria" class="form-label">Descripción de la Categoria</label>
                        <input type="text" class="form-control" id="descripcionCategoria" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarCategoria" onclick="guardarCategoria()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear Producto -->
<div class="modal fade" id="crearProductoModal" tabindex="-1" aria-labelledby="crearProductoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="crearProductoModalLabel">Crear Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCrearProducto" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="idCategoria" class="form-label">ID de Categoria</label>
                        <select class="form-control" id="idCategoria" name="idCategoria" required>
                            <option value="">Seleccione una categoría</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="idProveedor" class="form-label">ID de Proveedor</label>
                        <select class="form-control" id="idProveedor" name="idProveedor" required>
                            <option value="">Seleccione un proveedor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="imagenProducto" class="form-label">Imagen del Producto</label>
                        <input type="file" class="form-control" id="imagenProducto" name="imagenProducto" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombreProducto" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcionProducto" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcionProducto" name="descripcionProducto"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="precioProducto" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="precioProducto" name="precioProducto" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="cantidadProducto" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="cantidadProducto" name="cantidadProducto" required>
                    </div>
                    <div class="mb-3">
                        <label for="estadoProducto" class="form-label">Estado</label>
                        <select class="form-control" id="estadoProducto" name="estadoProducto" required>
                            <option value="">Seleccione un estado</option>
                            <option value="Disponible">Disponible</option>
                            <option value="No Disponible">No Disponible</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fechaIngreso" class="form-label">Fecha de Ingreso</label>
                        <input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarProducto" onclick="guardarProducto()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- Carrusel de Productos -->
<div id="productCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="../imagenes/imagen1.png" class="d-block w-100" alt="Alejandra Pizarnik">
        </div>
        <div class="carousel-item">
            <img src="../imagenes/imagen2.png" class="d-block w-100" alt="Poemas de Alejandra Pizarnik">
        </div>
        <div class="carousel-item">
            <img src="../imagenes/imagen3.png" class="d-block w-100" alt="Poemas de Alejandra Pizarnik">
        </div>
        <div class="carousel-item">
            <img src="../imagenes/imagen4.png" class="d-block w-100" alt="Poemas de Alejandra Pizarnik">
        </div>
        <div class="carousel-item">
            <img src="../imagenes/imagen5.png" class="d-block w-100" alt="Poemas de Alejandra Pizarnik">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>

<section id="alejandra-pizarnik" class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-pink shadow">
                    <img src="../imagenes/imagengatito.jpg" class="card-img-top" alt="Alejandra Pizarnik">
                    <div class="card-body">
                        <h5 class="card-title text-pink">Alejandra Pizarnik</h5>
                        <p class="card-text text-muted">Poeta y traductora argentina. Su obra, marcada por la intensidad y la introspección, la posiciona como una de las voces más relevantes de la poesía latinoamericana del siglo XX.</p>
                        <a href="#" class="btn btn-pink">Bienvenido a la aventura</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="vision-mision" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="../imagenes/imagenvision" class="d-block w-100" alt="Nuestra visión"> <!-- Agrega la extensión correcta -->
                <p>Nuestra visión:...</p>
            </div>
            <div class="col-md-6">
                <img src="../imagenes/imagenmision.png" class="d-block w-100" alt="Nuestra misión">
                <p>Nuestra misión:...</p>
            </div>
        </div>
    </div>
</section>

<!--Componentes de la tarjeta-->
<section id="componentes">
    <div class="contenedor">
        <h2>Componente - Tarjeta</h2>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <img src="https://cloudfront-eu-central-1.images.arcpublishing.com/prisa/7HQHOZJRR5ARNDBQIPMPS3YHOA.jpg" class="card-img-top" alt="Ejemplo de tarjeta">
                <div class="card-body">
                    <h5>Mi tarjeta</h5>
                    <p class="card-text">Las tarjetas son contenedores flexibles que puedes personalizar a tu gusto</p>
                    <a href="#" class="btn btn-primary">Ver mas</a>
                </div>
            </div>
        </div>

        <!-- JS de Bootstrap -->
        <script src="../js/loginscrip.js"></script>
        <script src="../js/proveedor.js"></script>
        <script src="../js/categoria.js"></script>
        <script src="../js/producto.js"></script>
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz' crossorigin='anonymous'></script> 
        <script>
            document.getElementById('verPerfilModal').addEventListener('show.bs.modal', function (event) {
                fetch('../php/verperfil.php', {
                    method: 'GET',
                    credentials: 'include', // Importante: esto permite enviar las cookies de sesión
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('perfilNombre').textContent = data.data.nombre_usuario;
                        document.getElementById('perfilApellido').textContent = data.data.apellido_usuario;
                        document.getElementById('perfilEmail').textContent = data.data.email_usuario;
                        document.getElementById('perfilRol').textContent = data.data.rol_usuario;
                    } else {
                        console.error('Error:', data.message); // Para ver el error en la consola
                        alert('Error al cargar los datos del perfil: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error); // Para ver el error en la consola
                    alert('Error al cargar los datos del perfil');
                });
            });
            
            document.getElementById('cerrarSesion').addEventListener('click', function(e) {
                e.preventDefault();
                if(confirm('¿Estás seguro que deseas cerrar sesión?')) {
                    window.location.href = 'login.html';
                }
            });
        </script>
    </body>
</html>