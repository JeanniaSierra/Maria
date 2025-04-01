// Función para cargar las categorías
function cargarCategorias() {
    fetch('../controlador/listar_categorias.php')
        .then(response => response.json())
        .then(data => {
            const listaCategorias = document.getElementById('listaCategorias');
            listaCategorias.innerHTML = ''; // Limpiar el contenedor

            if (!data.success) {
                listaCategorias.innerHTML = '<p>No se pudieron cargar las categorías.</p>';
                return;
            }

            // Iterar sobre cada categoría y crear una tarjeta para cada una
            data.data.forEach(categoria => {
                const card = document.createElement('div');
                card.className = 'col-md-4 mb-4';
                card.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">${categoria.nombre_categoria}</h5>
                            <p class="card-text">${categoria.descripcion_categoria}</p>
                            <button class="btn btn-primary btn-sm me-2" onclick="abrirModalEditar(${categoria.id_categoria}, '${categoria.nombre_categoria}', '${categoria.descripcion_categoria}')">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarCategoria(${categoria.id_categoria})">Eliminar</button>
                        </div>
                    </div>
                `;
                listaCategorias.appendChild(card);
            });
        })
        .catch(error => {
            console.error('Error al cargar categorías:', error);
            const listaCategorias = document.getElementById('listaCategorias');
            listaCategorias.innerHTML = `<p class="text-danger">Error al cargar las categorías: ${error.message}</p>`;
        });
}

// Función para abrir el modal en modo creación
function abrirModalCrear() {
    document.getElementById('categoriaId').value = '';
    document.getElementById('nombreCategoria').value = '';
    document.getElementById('descripcionCategoria').value = '';
    document.getElementById('crearCategoriaModalLabel').innerText = 'Crear Categoria';
    const modal = new bootstrap.Modal(document.getElementById('crearCategoriaModal'));
    modal.show();
}

// Función para abrir el modal en modo edición
function abrirModalEditar(id, nombre, descripcion) {
    document.getElementById('categoriaId').value = id; // Asignar el ID al campo oculto
    document.getElementById('nombreCategoria').value = nombre;
    document.getElementById('descripcionCategoria').value = descripcion;
    document.getElementById('crearCategoriaModalLabel').innerText = 'Editar Categoria';
    const modal = new bootstrap.Modal(document.getElementById('crearCategoriaModal'));
    modal.show();
}

// Función para guardar cambios en la categoría
function guardarCategoria() {
    const idCategoria = document.getElementById('categoriaId').value; // Obtener el valor del campo oculto
    const nombreCategoria = document.getElementById('nombreCategoria').value;
    const descripcionCategoria = document.getElementById('descripcionCategoria').value;

    // Validar que los campos requeridos no estén vacíos
    if (!nombreCategoria || !descripcionCategoria) {
        alert('Todos los campos son obligatorios.');
        return;
    }

    // Validar el ID de la categoría
    const id = idCategoria && !isNaN(idCategoria) ? parseInt(idCategoria) : null;

    const requestData = {
        action: id ? 'guardarCategoria' : 'crearCategoria', // Usar 'guardarCategoria' si hay ID
        id: id, // Enviar el ID como entero o null
        nombre_categoria: nombreCategoria,
        descripcion_categoria: descripcionCategoria,
    };

    console.log('Datos enviados:', requestData); // Registro para depuración

    fetch('../controlador/categoria.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(requestData),
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta recibida:', data); // Registro para depuración
        if (data.success) {
            alert(data.message);
            location.reload(); // Recargar la página para reflejar los cambios
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar la categoría');
    });
}

// Función para eliminar una categoría
function eliminarCategoria(id) {
    if (confirm(`¿Está seguro que desea eliminar esta categoría?`)) {
        const requestData = {
            action: 'eliminarCategoria',
            id: id
        };

        console.log('Datos enviados:', requestData); // Registro para depuración

        fetch('../controlador/categoria.php', {
            method: 'POST',
            body: JSON.stringify(requestData),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Datos recibidos:', data); // Registro para depuración
            alert(data.message); // Mostrar el mensaje generado desde PHP
            if (data.success) {
                cargarCategorias(); // Recargar las categorías después de eliminar
            }
        })
        .catch(error => {
            console.error('Error al eliminar la categoría:', error);
            alert('Error al eliminar la categoría: ' + error.message);
        });
    }
}