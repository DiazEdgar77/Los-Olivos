// Mostrar formularios de reservas
document.getElementById('reservarMesaBtn').addEventListener('click', function() {
    document.getElementById('formReservaMesa').style.display = 'block';
    document.getElementById('formReservaEvento').style.display = 'none';
    document.getElementById('cancelarReservaBtn').style.display = 'block';
});

document.getElementById('reservarEventoBtn').addEventListener('click', function() {
    document.getElementById('formReservaEvento').style.display = 'block';
    document.getElementById('formReservaMesa').style.display = 'none';
    document.getElementById('cancelarReservaBtn').style.display = 'block';
    document.getElementById('cancelarEventoBtn').style.display = 'block'; // Mostrar el botón de cancelar evento
});

// Cancelar Reserva
document.getElementById('cancelarReservaBtn').addEventListener('click', function() {
    document.getElementById('formReservaMesa').style.display = 'none';
    document.getElementById('formReservaEvento').style.display = 'none';
    document.getElementById('cancelarReservaBtn').style.display = 'none';
    document.getElementById('cancelarEventoBtn').style.display = 'none'; // Ocultar también el botón de cancelar evento
});

// Cancelar Evento
document.getElementById('cancelarEventoBtn').addEventListener('click', function() {
    document.getElementById('formReservaEvento').style.display = 'none';
    document.getElementById('cancelarEventoBtn').style.display = 'none';
});

// Manejo de comentarios
document.getElementById('formComentario').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nombre = document.getElementById('comentarioNombre').value;
    const comentario = document.getElementById('comentarioTexto').value;
    
    if (nombre && comentario) {
        const nuevoComentario = document.createElement('div');
        nuevoComentario.innerHTML = `<strong>${nombre}:</strong> <p>${comentario}</p>`;
        document.getElementById('listaComentarios').appendChild(nuevoComentario);
        this.reset();
    }
});

// Manejo de reserva de evento
document.getElementById('formEvento').addEventListener('submit', function(e) {
    e.preventDefault();

    const nombre = document.getElementById('eventoNombre').value;
    const telefono = document.getElementById('eventoTelefono').value;
    const fecha = document.getElementById('eventoFecha').value;
    const hora = document.getElementById('eventoHora').value;
    const asistentes = document.getElementById('eventoAsistentes').value;
    const comida = document.getElementById('eventoComida').value;
    const notas = document.getElementById('eventoNotas').value;

    if (nombre && telefono && fecha && hora && asistentes && comida) {
        document.getElementById('mensajeEvento').textContent = "¡Tu evento ha sido reservado exitosamente!";
        this.reset();
        document.getElementById('formReservaEvento').style.display = 'none'; // Ocultar el formulario de evento después de la confirmación
        document.getElementById('cancelarEventoBtn').style.display = 'none'; // Ocultar el botón de cancelar evento
    } else {
        document.getElementById('mensajeEvento').textContent = "Por favor, completa todos los campos.";
    }
});
