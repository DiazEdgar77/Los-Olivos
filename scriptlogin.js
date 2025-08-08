document.getElementById('loginForm').addEventListener('submit', function(e) {
    const correo = document.getElementById('correo').value;
    const contraseña = document.getElementById('contraseña').value;
    
    if (!correo || !contraseña) {
        e.preventDefault();
        showError('Por favor, completa todos los campos');
        return;
    }
    
    if (!isValidEmail(correo)) {
        e.preventDefault();
        showError('Por favor, ingresa un correo válido');
        return;
    }
});

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showError(message) {
    // Remove existing error messages
    const existingError = document.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    // Create new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    
    // Insert before the form
    const form = document.getElementById('loginForm');
    form.parentNode.insertBefore(errorDiv, form);
    
    // Remove error after 5 seconds
    setTimeout(() => {
        errorDiv.remove();
    }, 5000);
}

// Check for error messages from PHP
window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    
    if (error === 'invalid') {
        showError('Correo o contraseña incorrectos');
    } else if (error === 'empty') {
        showError('Por favor, completa todos los campos');
    }
});
