// Show success/error messages
function showMessage(message, type = 'success') {
    const existingMessage = document.querySelector('.success-message, .error-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    const messageDiv = document.createElement('div');
    messageDiv.className = type === 'success' ? 'success-message' : 'error-message';
    messageDiv.textContent = message;
    
    const container = document.querySelector('.panel-container');
    container.insertBefore(messageDiv, container.firstChild.nextSibling);
    
    setTimeout(() => {
        messageDiv.remove();
    }, 5000);
}

// Check for URL parameters
window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    const error = urlParams.get('error');
    
    if (success === 'reserva') {
        showMessage('Reserva de mesa guardada exitosamente');
    } else if (success === 'evento') {
        showMessage('Evento reservado exitosamente');
    } else if (success === 'comentario') {
        showMessage('Comentario enviado exitosamente. Será revisado por un administrador.');
    } else if (error === 'empty') {
        showMessage('Por favor, completa todos los campos', 'error');
    } else if (error === 'invalid') {
        showMessage('Error al procesar la solicitud', 'error');
    }
    
    // Clean URL
    if (success || error) {
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});

// Form validation
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.style.borderColor = '#dc3545';
            } else {
                input.style.borderColor = 'rgba(255, 255, 255, 0.2)';
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            showMessage('Por favor, completa todos los campos requeridos', 'error');
        }
    });
});

// Date validation - no past dates
document.querySelectorAll('input[type="date"]').forEach(dateInput => {
    dateInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            this.value = '';
            showMessage('No puedes seleccionar una fecha pasada', 'error');
        }
    });
});
