<!-- include -->
<?php include '../html/navbar.php'; ?>
<!-- Pagina de inicio con Cards de productos -->
<div class="container mt-4">
    <h1 class="mb-4 center">PRODUCTOS</h1>
    <script src="proveedo" defer></script>
</div>

<!--Seccion para listar productos -->
<div class="container mt-5" id="productos">
    <h2 class="mb-3">Lista de Productos</h2>
    <table class="table table-striped" id="listaProductos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre del Producto</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th >Cantidad</th>
                <th>Estado</th>
                <th>Fecha de Ingreso</th>
                <th>Categoria</th>
                <th>Proveedor</th>
            </tr>
        </thead>
        <tbody id="listaProductos">
            <!-- Las filas de productos se agregarán aquí dinámicamente -->
            <!-- Asegúrate de que el campo 'cantidad_producto' se envíe correctamente -->
        </tbody>
    </table>
</div>
<script src="../js/producto.js"></script> <!-- Asegúrate de que la ruta sea correcta -->
<script>
    // Verifica que el valor de 'cantidad_producto' se envíe al backend
    // Si no se proporciona, puedes establecer un valor predeterminado en el código JavaScript o en el backend.
</script>
</body>
</html>


