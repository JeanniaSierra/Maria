// Función para cargar categorías y proveedores
function cargarCategoriasYProveedores() {
    fetch('../controlador/producto.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            llenarSelectCategorias(data.categorias);
            llenarSelectProveedores(data.proveedores);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar categorías y proveedores');
        });
}

// Función para llenar el select de categorías
function llenarSelectCategorias(categorias) {
    const selectCategorias = document.getElementById('idCategoria');
    if (!selectCategorias || !categorias) return;
    
    // Limpiar opciones existentes
    selectCategorias.innerHTML = '<option value="">Seleccione una categoría</option>';
    
    // Agregar nuevas opciones
    categorias.forEach(categoria => {
        const option = document.createElement('option');
        option.value = categoria.id_categoria;
        option.textContent = categoria.nombre_categoria;
        selectCategorias.appendChild(option);
    });
}

// Función para llenar el select de proveedores
function llenarSelectProveedores(proveedores) {
    const selectProveedores = document.getElementById('idProveedor');
    if (!selectProveedores || !proveedores) return;
    
    // Limpiar opciones existentes
    selectProveedores.innerHTML = '<option value="">Seleccione un proveedor</option>';
    
    // Agregar nuevas opciones
    proveedores.forEach(proveedor => {
        const option = document.createElement('option');
        option.value = proveedor.id_proveedor;
        option.textContent = proveedor.nombre_proveedor;
        selectProveedores.appendChild(option);
    });
}

// Función para guardar un producto
function guardarProducto(event) {
    if (event) event.preventDefault();

    const form = document.getElementById('formCrearProducto');
    const formData = new FormData(form);

    // Validar campos obligatorios antes de enviar
    const camposObligatorios = [
        'nombreProducto',
        'descripcionProducto',
        'precioProducto',
        'cantidadProducto',
        'idCategoria',
        'idProveedor'
    ];

    for (const campo of camposObligatorios) {
        const valor = formData.get(campo);
        if (!valor || valor.trim() === '') {
            alert(`Por favor complete el campo obligatorio: ${campo}`);
            return;
        }
    }

    formData.append('action', 'registerProducto');

    fetch('../controlador/producto.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Respuesta del servidor:', data); // Depuración
        if (data.success) {
            alert('Producto guardado correctamente');
            form.reset();
            cargarProductos(); // Recargar la lista de productos
        } else {
            alert(`Error al guardar el producto: ${data.message}`);
        }
    })
    .catch(error => {
        console.error('Error en la solicitud:', error);
        alert('Error al comunicarse con el servidor. Por favor, intente nuevamente.');
    });
}

// Función para cargar productos
function cargarProductos() {
    fetch('../controlador/listar_productos.php')
        .then(response => {
            console.log('Estado HTTP:', response.status); // Depuración
            if (!response.ok) {
                throw new Error(`Error en la respuesta del servidor. Código de estado: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta del servidor:', data); // Depuración
            if (data.success) {
                const productos = data.data;
                const tbody = document.getElementById('listaProductos');

                if (!tbody) {
                    console.error('Elemento con ID "listaProductos" no encontrado en el DOM.');
                    return;
                }

                tbody.innerHTML = ''; // Limpiar la tabla antes de llenarla

                productos.forEach(producto => {
                    console.log('Ruta de la imagen:', producto.imagen_producto); // Depuración de la imagen
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${producto.id_producto}</td>
                        <td>
                            ${producto.imagen_producto 
                                ? `<img src="data:image/jpeg;base64,${producto.imagen_producto}" width="50" height="50" alt="Imagen">`
                                : 'Sin imagen'}
                        </td>
                        <td>${producto.nombre_producto}</td>
                        <td>${producto.descripcion_producto}</td>
                        <td>${producto.precio_producto}</td>
                        <td>${producto.cantidad_producto}</td>
                        <td>${producto.estado_producto}</td>
                        <td>${producto.fecha_ingreso}</td>
                        <td>${producto.categoria}</td>
                        <td>${producto.proveedor}</td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                console.error('Error al cargar productos:', data.message);
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
        });
}

// Llamar a la función de cargar productos cuando se carga la página
window.onload = function() {
    cargarCategoriasYProveedores();
    cargarProductos();
};

// Función para abrir el modal de edición
function abrirModalEditar(id) {
    const action = "obtenerProducto";
    fetch('../controlador/producto.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action, id })
    })
    .then(response => response.json())
    .then(producto => {
        document.getElementById('nombreProducto').value = producto.nombre_producto;
        document.getElementById('descripcionProducto').value = producto.descripcion_producto;
        document.getElementById('precioProducto').value = producto.precio_producto;
        document.getElementById('idCategoria').value = producto.id_categoria;
        document.getElementById('idProveedor').value = producto.id_proveedor;
        document.getElementById('btnGuardarProducto').setAttribute('onclick', `editarProducto(${id})`);
        $('#crearProductoModal').modal('show');
    });
}

// Función para editar un producto
function editarProducto(id) {
    const action = "editarProducto";
    const formData = new FormData(document.getElementById('formCrearProducto'));
    formData.append('action', action);
    formData.append('id', id);
    
    fetch('../controlador/producto.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.mensaje);
        $('#crearProductoModal').modal('hide');
        cargarProductos();
    });
}

// Función para eliminar un producto
function eliminarProducto(id) {
    if (!confirm("¿Seguro que deseas eliminar este producto?")) return;

    fetch('../controlador/producto.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: "eliminarProducto", id: id })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.mensaje);
        if (data.success) {
            cargarProductos(); // Recargar la lista después de eliminar
        }
    })
    .catch(error => {
        console.error("Error al eliminar el producto:", error);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('listaProductos'); // Asegúrate de que el ID sea correcto

    if (!tbody) {
        console.error('Elemento tbody no encontrado. Asegúrate de que el elemento con ID "tablaProductos" exista en el HTML.');
        return; // Salir si el elemento no existe
    }

    // ...existing code para manipular el tbody...
    // Ejemplo de manipulación:
    fetch('../controlador/listar_productos.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Error al cargar productos:', data.message);
                return;
            }

            tbody.innerHTML = ''; // Limpiar el contenido del tbody
            data.productos.forEach(producto => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${producto.id}</td>
                    <td>${producto.nombre}</td>
                    <td>${producto.precio}</td>
                    <td>${producto.cantidad}</td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error al cargar productos:', error);
        });
});