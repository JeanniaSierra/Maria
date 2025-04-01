// Elementos del DOM para la transición
const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

// Eventos para la transición entre formularios
registerBtn.addEventListener('click', (e) => {
    e.preventDefault();
    container.classList.add('right-panel-active');
});

loginBtn.addEventListener('click', (e) => {
    e.preventDefault();
    container.classList.remove('right-panel-active');
    // Limpiar los campos del formulario de registro
    document.getElementById('register-form').reset();
});

// Función para validar el formulario de registro
function validarFormularioRegistro(formData) {
    const nombre = formData.get('nombre_usuario');
    const password = formData.get('password_usuario');
    
    // Validar que el nombre no esté vacío y tenga al menos 3 caracteres
    if (!nombre || nombre.length < 3) {
        return "El nombre debe tener al menos 3 caracteres";
    }

    // Validar que el nombre solo contenga letras y espacios
    const nombreRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
    if (!nombreRegex.test(nombre)) {
        return "El nombre solo puede contener letras y espacios";
    }

    // Validar contraseña (mínimo 6 caracteres)
    if (password.length < 6) {
        return "La contraseña debe tener al menos 6 caracteres";
    }

    return null; // null significa que no hay errores
}

document.addEventListener("DOMContentLoaded", function() {
    
    // Manejador del formulario de registro
    document.getElementById("register-form").addEventListener("submit", async function(e) {
        e.preventDefault(); // Evita que el formulario se envíe de la manera tradicional
        const formData = new FormData(this); // Obtiene los datos del formulario
        const signupMessage = document.getElementById("signupMessage"); // Elemento para mostrar mensajes

        // Validar el formulario antes de enviar (puedes agregar tu lógica de validación aquí)

        try {
            console.log('Iniciando registro...'); // Debug
            console.log('Datos del formulario:', Object.fromEntries(formData)); // Debug

            const response = await fetch('../controlador/registro.php', { // Asegúrate de que la ruta sea correcta
                method: 'POST', // Método de la solicitud
                body: formData, // Datos del formulario
                headers: {
                    'Accept': 'application/json' // Aceptar respuesta en formato JSON
                }
            });

            console.log('Respuesta recibida del servidor'); // Debug
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`); // Manejo de errores
            }

            const result = await response.json(); // Procesa la respuesta JSON
            console.log('Resultado del registro:', result); // Debug

            if (result.success) {
                signupMessage.style.color = "green"; // Mensaje de éxito
                signupMessage.textContent = "¡Registro exitoso! Ya puedes iniciar sesión";
                // Limpiar el formulario y redirigir al login después de 2 segundos
                setTimeout(() => {
                    container.classList.remove('right-panel-active')

                    // this.reset();
                    // signupMessage.textContent = "";
                    
                    

                }, 2000);
            } else {
                signupMessage.style.color = "red"; // Mensaje de error
                signupMessage.textContent = result.message || "Error en el registro";
            }
        } catch (error) {
            console.error('Error detallado:', error); // Debug
            signupMessage.style.color = "red"; // Mensaje de error
            signupMessage.textContent = "Error al procesar el registro. Por favor, intenta nuevamente.";
        }
    });

    // Manejador del formulario de login
    document.getElementById("login-form").addEventListener("submit", async function(e) {
        e.preventDefault(); // Evita que el formulario se envíe de la manera tradicional
        const formData = new FormData(this); // Obtiene los datos del formulario
        const loginMessage = document.getElementById("loginMessage"); // Elemento para mostrar mensajes

        try {
            loginMessage.textContent = "Iniciando sesión..."; // Mensaje de carga
            loginMessage.style.color = "blue"; // Cambia el color del mensaje

            const response = await fetch('../controlador/login.php', { // Asegúrate de que la ruta sea correcta
                method: 'POST', // Método de la solicitud
                body: formData, // Datos del formulario
                credentials: 'include', // Añadir esta línea para manejar sesiones
                headers: {
                    'Accept': 'application/json' // Aceptar respuesta en formato JSON
                }
            });

            console.log('Respuesta del servidor:', response); // Para depuración

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`); // Manejo de errores
            }

            const result = await response.json(); // Procesa la respuesta JSON
            console.log('Datos recibidos:', result); // Para depuración

            if (result.success) {
                loginMessage.style.color = "green"; // Mensaje de éxito
                loginMessage.textContent = "¡Inicio de sesión exitoso!";
                // Redirigir a la página principal o a otra página después de iniciar sesión
                window.location.href = '../vista/bootstrap.php'; // Cambia esto a la ruta que desees
            } else {
                loginMessage.style.color = "red"; // Mensaje de error
                loginMessage.textContent = result.message || "Usuario o contraseña incorrectos";
            }
        } catch (error) {
            console.error('Error completo:', error); // Para depuración
            loginMessage.style.color = "red"; // Mensaje de error
            loginMessage.textContent = "Error de conexión con el servidor. Por favor, intenta nuevamente.";
        }
    });

    const cerrarSesionBtn = document.getElementById('cerrarSesion');
    if (cerrarSesionBtn) {
        cerrarSesionBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('¿Estás seguro que deseas cerrar sesión?')) {
                window.location.href = '../vista/login.html';
            }
        });
    }
});


// Limpiar mensajes cuando se cambia entre formularios
registerBtn.addEventListener('click', () => {
    document.getElementById("loginMessage").textContent = "";
});

loginBtn.addEventListener('click', () => {
    document.getElementById("signupMessage").textContent = "";
});

// Cerrar el perfil de datos
function cerrarPerfil() {
    document.getElementById("perfilDatos").style.display = "none";
    document.getElementById("perfilNombre").style.display = "true";
    document.getElementById("perfilApellido").disabled = true;
    document.getElementById("perfilEmail").disabled = true;
    document.getElementById("perfilRol").disabled = true;
    document.getElementById("overlay").style.display = "none";
}

// Función para habilitar edición
function habilitarEdicion() {
    const elementos = document.querySelectorAll('.dato-perfil');
    
    elementos.forEach(elemento => {
        const valor = elemento.textContent;
        const input = document.createElement('input');
        input.type = 'text';
        input.value = valor;
        input.className = 'form-control';
        elemento.parentNode.replaceChild(input, elemento);
    });

    document.getElementById('btnEditar').style.display = 'none';
    document.getElementById('btnGuardar').style.display = 'inline-block';
}

// Función para modificar el perfil
function modificarPerfil() {
    const datos = {
        action: "modificar",
        nombre: document.getElementById("perfilNombre").value,
        apellido: document.getElementById("perfilApellido").value,
        email: document.getElementById("perfilEmail").value,
        rol: document.getElementById("perfilRol").value
    };

    fetch("../controlador/actualizarperfil.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        credentials: 'include', // Para enviar las cookies de sesión
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Perfil actualizado correctamente');
            // Actualizar los campos en el modal
            document.getElementById("perfilNombre").textContent = datos.nombre;
            document.getElementById("perfilApellido").textContent = datos.apellido;
            document.getElementById("perfilEmail").textContent = datos.email;
            document.getElementById("perfilRol").textContent = datos.rol;
        } else {
            alert('Error al actualizar el perfil: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el perfil');
    });
}

// Función para guardar cambios
function guardarCambios() {
    const inputs = document.querySelectorAll('.col-md-12 input');
    const datos = {
        nombre: inputs[0].value,
        apellido: inputs[1].value,
        email: inputs[2].value
    };

    fetch('../controlador/actualizarperfil.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(datos),
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Convertir inputs de vuelta a spans
            inputs.forEach((input, index) => {
                const span = document.createElement('span');
                span.className = 'dato-perfil';
                span.textContent = input.value;
                const ids = ['perfilNombre', 'perfilApellido', 'perfilEmail', 'perfilRol'];
                span.id = ids[index];
                input.parentNode.replaceChild(span, input);
            });

            alert('Perfil actualizado correctamente');
        } else {
            alert('Error al actualizar el perfil: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el perfil');
    });

    // Mostrar botón de editar y ocultar botón de guardar
    document.getElementById('btnEditar').style.display = 'inline-block';
    document.getElementById('btnGuardar').style.display = 'none';
}

