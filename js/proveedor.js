// Función para cargar los proveedores
function cargarProveedores() {
    fetch('../php/listar_proveedores.php')
        .then(response => response.json())
        .then(data => {
            const listaProveedores = document.getElementById('listaProveedores');
            listaProveedores.innerHTML = ''; // Limpiar el contenedor

            if (!data.success) {
                listaProveedores.innerHTML = '<p>No se pudieron cargar los proveedores.</p>';
                return;
            }

            // Iterar sobre cada proveedor y crear una tarjeta para cada uno
            data.data.forEach(proveedor => {
                const card = document.createElement('div');
                card.className = 'col-md-4 mb-4';
                card.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">${proveedor.nombre_proveedor}</h5>
                            <p class="card-text">Dirección: ${proveedor.direccion_proveedor}</p>
                            <p class="card-text">Teléfono: ${proveedor.telefono_proveedor}</p>
                            <button class="btn btn-primary btn-sm" onclick="abrirModalEditar(${proveedor.id_proveedor}, '${proveedor.nombre_proveedor}', '${proveedor.direccion_proveedor}', '${proveedor.telefono_proveedor}')">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarProveedor(${proveedor.id_proveedor})">Eliminar</button>
                        </div>
                    </div>
                `;
                listaProveedores.appendChild(card);
            });
        })
        .catch(error => {
            console.error('Error al cargar proveedores:', error);
        });
}

// Función para abrir el modal en modo edición
function abrirModalEditar(id, nombre, direccion, telefono) {
    document.getElementById('nombreProveedor').value = nombre;
    document.getElementById('direccionProveedor').value = direccion;
    document.getElementById('telefonoProveedor').value = telefono;
    document.getElementById('btnGuardarProveedor').setAttribute('onclick', `guardarProveedor(${id})`); // Cambiar el onclick para guardar
    const modal = new bootstrap.Modal(document.getElementById('crearProveedorModal'));
    modal.show();
}

// Función para guardar cambios en el proveedor
function guardarProveedor(id = null) {
    const nombre = document.getElementById('nombreProveedor').value;
    const direccion = document.getElementById('direccionProveedor').value;
    const telefono = document.getElementById('telefonoProveedor').value;

    // Validar que los campos no estén vacíos
    if (!nombre || !direccion || !telefono) {
        alert('Todos los campos son obligatorios.');
        return;
    }

    const requestData = {
        action: id ? 'editarProveedor' : 'guardarProveedor',
        id: id,
        nombre: nombre,
        direccion: direccion,
        telefono: telefono
    };

    fetch('../php/proveedor.php', {
        method: 'POST',
        body: JSON.stringify(requestData),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message); // Mostrar el mensaje generado desde PHP
        if (data.success) {
            cargarProveedores(); // Recargar la lista de proveedores
            const modal = bootstrap.Modal.getInstance(document.getElementById('crearProveedorModal'));
            modal.hide(); // Cerrar el modal
        }
    })
    .catch(error => {
        console.error('Error al guardar el proveedor:', error);
        alert('Error al guardar el proveedor: ' + error.message);
    });
}

// Función para eliminar un proveedor
function eliminarProveedor(id) {
    if (confirm('¿Estás seguro que deseas eliminar este proveedor?')) {
        const requestData = {
            action: 'eliminarProveedor',
            id: id
        };

        fetch('../php/proveedor.php', {
            method: 'POST',
            body: JSON.stringify(requestData),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message); // Mostrar el mensaje generado desde PHP
            if (data.success) {
                cargarProveedores(); // Recargar la lista de proveedores
            }
        })
        .catch(error => {
            console.error('Error al eliminar el proveedor:', error);
            alert('Error al eliminar el proveedor: ' + error.message);
        });
    }
}