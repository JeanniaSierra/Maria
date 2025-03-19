<!-- include -->
<?php include '../html/navbar.php'; ?>
<!-- Pagina de inicio con Cards de productos -->
<div class="container mt-4">
    <h1 class="mb-4 center">PRODUCTOS</h1>
</div>

<!--Seccion para listar productos -->
<div class="container mt-5" id="productos">
    <h2 class="mb-3">Lista de Productos</h2>
    <table class="table table-striped" id="tablaProductos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre del Producto</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Estado</th>
                <th>Fecha de Ingreso</th>
                <th>Categoria</th>
                <th>Proveedor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="listaProductos">
            <!-- Las filas de productos se agregarán aquí dinámicamente -->
        </tbody>
    </table>
</div>


